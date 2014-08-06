<?php

use Carbon\Carbon;

class SubscriptionController extends \BaseController {


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
        $payment_details = array(
            'amount'            => $user->monthly_subscription,
            'interval_length'   => 1,
            'interval_unit'     => 'month',
            'name'              => 'BBSUB'.$user->id,
            'description'       => 'Build Brighton Monthly Subscription',
            'redirect_uri'      => route('account.subscription.store', $user->id),
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

        return Redirect::to($this->goCardless->newSubUrl($payment_details));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
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
            return Redirect::route('account.show', $user->id)->withErrors($e->getMessage()['error']);
        }

        if (strtolower($confirmed_resource->status) =='active')
        {
            $user->payment_method = 'gocardless';
            $user->payment_day = Carbon::parse($confirmed_resource->next_interval_start)->day;
            $user->status = 'active';
            $user->active = true;
            $user->subscription_id = $confirmed_resource->id;
            //$user->last_subscription_payment = Carbon::now(); //this is updated by the webhook controller
            $user->save();

            return Redirect::route('account.show', $user->id)->withSuccess("Your subscription has been setup");
        }

        //print_r($confirmed_resource);

        return Redirect::route('account.show', $user->id)->withErrors("Something went wrong, you can try again or get in contact");
    }


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($userId, $id=null)
	{
        $user = User::findWithPermission($userId);
        if ($user->payment_method == 'gocardless')
        {
            $subscription = $this->goCardless->cancelSubscription($user->subscription_id);
            if ($subscription->status == 'cancelled')
            {
                $user->cancelSubscription();
                return Redirect::back()->withSuccess("Your subscription has been cancelled");
            }
        }
        return Redirect::back()->withError("Unable to cancel");
	}


}
