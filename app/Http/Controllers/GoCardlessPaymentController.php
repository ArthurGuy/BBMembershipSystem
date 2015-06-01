<?php namespace BB\Http\Controllers;

use BB\Entities\User;

class GoCardlessPaymentController extends Controller
{
    /**
     * @var \BB\Repo\PaymentRepository
     */
    private $paymentRepository;
    /**
     * @var \BB\Helpers\GoCardlessHelper
     */
    private $goCardless;

    function __construct(\BB\Repo\PaymentRepository $paymentRepository, \BB\Helpers\GoCardlessHelper $goCardless)
    {
        $this->paymentRepository = $paymentRepository;

        $this->middleware('role:member', array('only' => ['create', 'store']));
        $this->goCardless = $goCardless;
    }

    /**
     * Main entry point for all gocardless payments - not subscriptions
     * @param $userId
     * @return mixed
     * @throws \BB\Exceptions\AuthenticationException
     */
    public function create($userId)
    {
        $user = User::findWithPermission($userId);

        $requestData = \Request::only(['reason', 'amount', 'return_path']);

        $reason = $requestData['reason'];
        $amount = ($requestData['amount'] * 1) / 100;
        $returnPath = $requestData['return_path'];
        $ref = $this->getReference($reason);


        if ($user->payment_method == 'gocardless-variable') {

            return $this->handleBill($amount, $reason, $user, $ref, $returnPath);

        } elseif ($user->payment_method == 'gocardless') {

            return $this->ddMigratePrompt($returnPath);

        } else {

            return $this->handleManualBill($amount, $reason, $user, $ref, $returnPath);

        }
    }

    /**
     * Processes the return for old gocardless payments
     *
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \BB\Exceptions\AuthenticationException
     */
    public function handleManualReturn($userId)
    {
        $user = User::findWithPermission($userId);

        $confirm_params = array(
            'resource_id'    => $_GET['resource_id'],
            'resource_type'  => $_GET['resource_type'],
            'resource_uri'   => $_GET['resource_uri'],
            'signature'      => $_GET['signature']
        );

        // State is optional
        if (isset($_GET['state'])) {
            $confirm_params['state'] = $_GET['state'];
        }

        //Get the details, reason, reference and return url
        $details = explode(':', \Input::get('state'));
        $reason  = 'unknown';
        $ref     = null;
        $returnPath = route('account.show', [$user->id], false);
        if (is_array($details)) {
            if (isset($details[0])) {
                $reason = $details[0];
            }
            if (isset($details[1])) {
                $ref = $details[1];
            }
            if (isset($details[2])) {
                $returnPath = $details[2];
            }
        }


        //Confirm the resource
        try {
            $confirmed_resource = $this->goCardless->confirmResource($confirm_params);
        } catch (\Exception $e) {
            \Notification::error($e->getMessage());
            return \Redirect::to($returnPath);
        }


        //Store the payment
        $fee = ($confirmed_resource->amount - $confirmed_resource->amount_minus_fees);
        $paymentSourceId = $confirmed_resource->id;
        $amount = $confirmed_resource->amount;
        $status = $confirmed_resource->status;

        //The record payment process will make the necessary record updates
        $this->paymentRepository->recordPayment($reason, $userId, 'gocardless', $paymentSourceId, $amount, $status, $fee, $ref);

        \Notification::success("Payment made");
        return \Redirect::to($returnPath);
    }

    /**
     * Process a manual gocardless payment
     *
     * @param $amount
     * @param $reason
     * @param User $user
     * @param $ref
     * @param $returnPath
     * @return mixed
     * @throws \BB\Exceptions\NotImplementedException
     */
    private function handleManualBill($amount, $reason, $user, $ref, $returnPath)
    {
        $payment_details = array(
            'amount'       => $amount,
            'name'         => $this->getName($reason, $user->id),
            'description'  => $this->getDescription($reason),
            'redirect_uri' => route('account.payment.gocardless.manual-return', [$user->id]),
            'user'         => [
                'first_name'       => $user->given_name,
                'last_name'        => $user->family_name,
                'billing_address1' => $user->address_line_1,
                'billing_address2' => $user->address_line_2,
                'billing_town'     => $user->address_line_3,
                'billing_postcode' => $user->address_postcode,
                'country_code'     => 'GB'
            ],
            'state'        => $reason . ':' . $ref . ':' . $returnPath
        );
        $redirectUrl = $this->goCardless->newBillUrl($payment_details);

        if (\Request::wantsJson()) {
            return \Response::json(['url' => $redirectUrl], 303);
        }

        return \Redirect::to($redirectUrl);
    }


    private function ddMigratePrompt($returnPath)
    {
        if (\Request::wantsJson()) {
            return \Response::json(['error' => 'Please visit the "Your Membership" page and migrate your Direct Debit first, then return and make the payment'], 400);
        }
        \Notification::error("Please visit the \"Your Membership\" page and migrate your Direct Debit first, then return and make the payment");
        return \Redirect::to($returnPath);
    }

    /**
     * Process a direct debit payment when we have a preauth
     *
     * @param $amount
     * @param $reason
     * @param User $user
     * @param $ref
     * @param $returnPath
     * @return mixed
     */
    private function handleBill($amount, $reason, $user, $ref, $returnPath)
    {
        $bill = $this->goCardless->newBill($user->subscription_id, $amount, $this->goCardless->getNameFromReason($reason));

        if ($bill) {
            //Store the payment
            $fee = ($bill->amount - $bill->amount_minus_fees);
            $paymentSourceId = $bill->id;
            $amount = $bill->amount;
            $status = $bill->status;

            //The record payment process will make the necessary record updates
            $this->paymentRepository->recordPayment($reason, $user->id, 'gocardless-variable', $paymentSourceId, $amount, $status, $fee, $ref);

            if (\Request::wantsJson()) {
                return \Response::json(['message' => 'The payment was submitted successfully']);
            }

            \Notification::success("The payment was submitted successfully");
        } else {
            //something went wrong - we still have the pre auth though

            if (\Request::wantsJson()) {
                return \Response::json(['error' => 'There was a problem charging your account'], 400);
            }

            \Notification::error("There was a problem charging your account");
        }

        return \Redirect::to($returnPath);
    }



    private function getDescription($reason)
    {
        if ($reason == 'subscription') {
            return "Monthly Subscription Fee - Manual";
        } elseif ($reason == 'induction') {
            return strtoupper(\Input::get('induction_key')) . " Induction Fee";
        } elseif ($reason == 'door-key') {
            return "Door Key Deposit";
        } elseif ($reason == 'storage-box') {
            return "Storage Box Deposit";
        } elseif ($reason == 'balance') {
            return "BB Credit Payment";
        } else {
            throw new \BB\Exceptions\NotImplementedException();
        }
    }

    private function getName($reason, $userId)
    {
        if ($reason == 'subscription') {
            return strtoupper("BBSUB" . $userId . ":MANUAL");
        } elseif ($reason == 'induction') {
            return strtoupper("BBINDUCTION" . $userId . ":" . \Request::get('induction_key'));
        } elseif ($reason == 'door-key') {
            return strtoupper("BBDOORKEY" . $userId);
        } elseif ($reason == 'storage-box') {
            return strtoupper("BBSTORAGEBOX" . $userId);
        } elseif ($reason == 'balance') {
            return strtoupper("BBBALANCE" . $userId);
        } else {
            throw new \BB\Exceptions\NotImplementedException();
        }
    }

    private function getReference($reason)
    {
        if ($reason == 'induction') {
            return \Request::get('induction_key');
        } elseif ($reason == 'balance') {
            return \Request::get('reference');
        }
        return false;
    }
}
