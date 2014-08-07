<?php

class PaymentController extends \BaseController {


    function __construct(\BB\Helpers\GoCardlessHelper $goCardless)
    {
        $this->goCardless = $goCardless;

        $this->beforeFilter('auth', array('only' => ['create', 'destroy']));
    }


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($userId)
	{
        $user = User::findWithPermission($userId);
		if (Input::get('source') == 'gocardless')
        {
            $reason = Input::get('reason');
            if ($reason == 'subscription')
            {
                $name = strtoupper("BBSUB".$user->id.":MANUAL");
                $description = "Monthly Subscription Fee - Manual";
                $amount = $user->monthly_subscription;
                $ref = null;
            }
            elseif ($reason == 'induction')
            {
                $name           = strtoupper("BBINDUCTION".$user->id.":".Input::get('induction_key'));
                $description    = strtoupper(Input::get('induction_key')) . " Induction Fee";
                $ref            = Input::get('induction_key');
                ($item = Induction::inductionList($ref)) || App::abort(404);
                $amount         = $item->cost;
            }
            else
            {
                throw new \BB\Exceptions\NotImplementedException();
            }
            $payment_details = array(
                'amount'        => $amount,
                'name'          => $name,
                'description'   => $description,
                'redirect_uri'      => route('account.payment.confirm-payment', [$user->id]),
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
        else
        {
            //Unsupported for now
            // perhaps manual payments or maybe they should go straight to store
            throw new \BB\Exceptions\NotImplementedException();
        }
	}


    public function confirmPayment($userId)
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
            return Redirect::route('account.show', $user->id)->withErrors($errors);
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
        else
        {
            throw new \BB\Exceptions\NotImplementedException();
        }

        return Redirect::route('account.show', $user->id)->withSuccess("Your payment has been made");
    }


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($userId)
	{
        $user = User::findWithPermission($userId);
        $reason = Input::get('reason');

        if ($reason == 'subscription')
        {
            $payment = new Payment([
                'reason'            => $reason,
                'source'            => Input::get('source'),
                'source_id'         => '',
                'amount'            => $user->monthly_subscription,
                'amount_minus_fee'  => $user->monthly_subscription,
                'status'            => 'paid'
            ]);
            $user->payments()->save($payment);

            $user->extendMembership(Input::get('source'), \Carbon\Carbon::now()->addMonth());
            /*
            $user->status = 'active';
            $user->active = true;
            $user->payment_method = Input::get('source');
            $user->subscription_expires = \Carbon\Carbon::now()->addMonth();
            $user->save();
            */
        }
        elseif ($reason == 'induction')
        {
            if (Input::get('source') == 'manual')
            {
                $ref = Input::get('induction_key');
                ($item = Induction::inductionList($ref)) || App::abort(404);
                $payment = new Payment([
                    'reason'            => $reason,
                    'source'            => 'manual',
                    'source_id'         => '',
                    'amount'            => $item->cost,
                    'amount_minus_fee'  => $item->cost,
                    'status'            => 'paid'
                ]);
                $payment = $user->payments()->save($payment);
                Induction::create([
                    'user_id' => $user->id,
                    'key' => $ref,
                    'paid' => true,
                    'payment_id' => $payment->id
                ]);
            }
            else
            {
                throw new \BB\Exceptions\NotImplementedException();
            }
        }
        else
        {
            throw new \BB\Exceptions\NotImplementedException();
        }
        return Redirect::route('account.show', $user->id)->withSuccess("Your payment has been recorded");
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
