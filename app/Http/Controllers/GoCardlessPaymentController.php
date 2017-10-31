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

            abort(500, 'Not supported');

        }
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
        if (is_null($ref)) {
            $ref = '';
        }
        $bill = $this->goCardless->newBill($user->subscription_id, $amount * 100, $this->goCardless->getNameFromReason($reason));

        if ($bill) {
            //Store the payment
            $fee = 0;
            $paymentSourceId = $bill->id;
            $amount = $bill->amount / 100;
            $status = $bill->status;
            if ($status == 'pending_submission') {
                $status = 'pending';
            }

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
            return \Request::get('ref');
        } elseif ($reason == 'balance') {
            return \Request::get('reference');
        }
        return false;
    }
}
