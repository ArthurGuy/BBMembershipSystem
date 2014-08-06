<?php

class InductionController extends \BaseController {


    function __construct(\BB\Helpers\GoCardlessHelper $goCardless)
    {
        $this->goCardless = $goCardless;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($userId)
	{
        $inductionKey = Input::get('induction_key');
        $user = User::findWithPermission($userId);

        $item = Induction::inductionList($inductionKey);
        if (!$item)
        {
            App::abort(404);
        }

        //Locate an existing induction record if there is one
        $induction = Induction::findExisting($user->id, $inductionKey);
        if (!$induction)
        {
            //Create a new one if one wasnt found
            $induction = Induction::create([
                'user_id' => $user->id,
                'key' => $inductionKey,
            ]);
        }


        $payment_details = array(
            'amount'        => $item->cost,
            'name'          => strtoupper("BBINDUCTION".$user->id.":".$inductionKey),
            'description'   => $item->name . " Induction Fee",
            'redirect_uri'      => route('account.induction.confirm-payment', [$user->id, $induction->id]),
            'user'              => [
                'first_name'        =>  $user->given_name,
                'last_name'         =>  $user->family_name,
                'billing_address1'  =>  $user->address_line_1,
                'billing_address2'  =>  $user->address_line_2,
                'billing_town'      =>  $user->address_line_3,
                'billing_postcode'  =>  $user->address_postcode,
                'country_code'      => 'GB'
            ]
        );
        return Redirect::to($this->goCardless->newBillUrl($payment_details));
	}


    public function confirmPayment($userId, $id)
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
        $induction = Induction::findOrFail($id);

        try
        {
            $confirmed_resource = $this->goCardless->confirmResource($confirm_params);
        }
        catch (\Exception $e)
        {
            $errors = $e->getMessage();
            return Redirect::route('account.show', $user->id)->withErrors($errors);
        }

        $payment = new Payment([
            'reason'            => 'induction',
            'source'            => 'gocardless',
            'source_id'         => $confirmed_resource->id,
            'amount'            => $confirmed_resource->amount,
            'fee'               => ($confirmed_resource->amount - $confirmed_resource->amount_minus_fees),
            'amount_minus_fee'  => $confirmed_resource->amount_minus_fees,
            'status'            => $confirmed_resource->status
        ]);
        $payment = $user->payments()->save($payment);

        $induction->payment_id = $payment->id;
        $induction->paid = true;
        $induction->save();

        return Redirect::route('account.show', $user->id)->withSuccess("Your payment has been made");
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
	public function update($userId, $inductionKey)
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
