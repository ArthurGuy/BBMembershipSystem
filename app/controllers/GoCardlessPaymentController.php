<?php

class GoCardlessPaymentController extends \BaseController {


    /**
     * @var \BB\Repo\EquipmentRepository
     */
    private $equipmentRepository;
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

        $this->beforeFilter('role:member', array('only' => ['create', 'store']));
        $this->goCardless = $goCardless;
    }


    public function create($userId)
    {
        $user = User::findWithPermission($userId);

        $reason = Request::get('reason');
        $amount = Request::get('amount');
        $ref = $this->getReference($reason);
        $payment_details = array(
            'amount'        => $amount,
            'name'          => $this->getName($reason, $user->id),
            'description'   => $this->getDescription($reason),
            'redirect_uri'      => route('account.payment.gocardless.store', [$user->id]),
            'user'              => [
                'first_name'        =>  $user->given_name,
                'last_name'         =>  $user->family_name,
                'billing_address1'  =>  $user->address_line_1,
                'billing_address2'  =>  $user->address_line_2,
                'billing_town'      =>  $user->address_line_3,
                'billing_postcode'  =>  $user->address_postcode,
                'country_code'      => 'GB'
            ],
            'state'         => $reason.':'.$ref
        );
        return Redirect::to($this->goCardless->newBillUrl($payment_details));
    }

    public function store($userId)
    {
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

        $user = User::findWithPermission($userId);

        try
        {
            $confirmed_resource = $this->goCardless->confirmResource($confirm_params);
        }
        catch (\Exception $e)
        {
            $errors = $e->getMessage();
            Notification::error($errors);
            return Redirect::route('account.show', $user->id);
        }

        $details = explode(':',Input::get('state'));
        $reason = $details[0];
        $ref = $details[1];

        $payment = new Payment([
                'reason'            => $reason,
                'source'            => 'gocardless',
                'source_id'         => $confirmed_resource->id,
                'amount'            => $confirmed_resource->amount,
                'fee'               => ($confirmed_resource->amount - $confirmed_resource->amount_minus_fees),
                'amount_minus_fee'  => $confirmed_resource->amount_minus_fees,
                'status'            => $confirmed_resource->status
            ]);
        $payment = $user->payments()->save($payment);

        if ($reason == 'subscription')
        {
            $user->status = 'active';
            $user->active = true;
            $user->save();
        }
        elseif ($reason == 'induction')
        {
            Induction::create([
                    'user_id' => $user->id,
                    'key' => $ref,
                    'paid' => true,
                    'payment_id' => $payment->id
                ]);
        }
        elseif ($reason == 'door-key')
        {
            $user->key_deposit_payment_id = $payment->id;
            $user->save();
        }
        elseif ($reason == 'storage-box')
        {
            $user->storage_box_payment_id = $payment->id;
            $user->save();
        }
        elseif ($reason == 'balance')
        {
            $memberCreditService = \App::make('\BB\Services\Credit');
            $memberCreditService->setUserId($user->id);
            $memberCreditService->recalculate();

            //This needs to be improved
            Notification::success("Payment recorded");
            return Redirect::route('account.bbcredit.index', $user->id);
        }
        else
        {
            throw new \BB\Exceptions\NotImplementedException();
        }

        Notification::success("Payment made");
        return Redirect::route('account.show', $user->id);
    }


    private function getDescription($reason)
    {
        if ($reason == 'subscription')
        {
            return "Monthly Subscription Fee - Manual";
        }
        elseif ($reason == 'induction')
        {
            return strtoupper(Input::get('induction_key')) . " Induction Fee";
        }
        elseif ($reason == 'door-key')
        {
            return "Door Key Deposit";
        }
        elseif ($reason == 'storage-box')
        {
            return "Storage Box Deposit";
        }
        elseif ($reason == 'balance')
        {
            return "BB Credit Payment";
        }
        else
        {
            throw new \BB\Exceptions\NotImplementedException();
        }
    }

    private function getName($reason, $userId)
    {
        if ($reason == 'subscription')
        {
            return strtoupper("BBSUB".$userId.":MANUAL");
        }
        elseif ($reason == 'induction')
        {
            return strtoupper("BBINDUCTION".$userId.":".Request::get('induction_key'));
        }
        elseif ($reason == 'door-key')
        {
            return strtoupper("BBDOORKEY".$userId);
        }
        elseif ($reason == 'storage-box')
        {
            return strtoupper("BBSTORAGEBOX".$userId);
        }
        elseif ($reason == 'balance')
        {
            return strtoupper("BBBALANCE".$userId);
        }
        else
        {
            throw new \BB\Exceptions\NotImplementedException();
        }
    }

    private function getReference($reason)
    {
        if ($reason == 'induction')
        {
            return Request::get('induction_key');
        }
        return false;
    }

}
