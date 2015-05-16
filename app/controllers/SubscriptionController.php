<?php

use BB\Entities\User;
use BB\Entities\Payment;
use BB\Repo\SubscriptionChargeRepository;
use Carbon\Carbon;

class SubscriptionController extends \BaseController
{


    /**
     * @var SubscriptionChargeRepository
     */
    private $subscriptionChargeRepository;
    /**
     * @var \BB\Repo\UserRepository
     */
    private $userRepository;

    function __construct(\BB\Helpers\GoCardlessHelper $goCardless, SubscriptionChargeRepository $subscriptionChargeRepository, \BB\Repo\UserRepository $userRepository)
    {
        $this->goCardless = $goCardless;
        $this->subscriptionChargeRepository = $subscriptionChargeRepository;
        $this->userRepository = $userRepository;

        $this->beforeFilter('role:member', array('only' => ['create', 'destroy']));
    }

    /**
     * Setup a new pre auth
     *
     * @return Response
     */
    public function create($userId)
    {
        $user = User::findWithPermission($userId);
        $payment_details = array(
            'redirect_uri'      => route('account.subscription.store', $user->id),
            'user'              => [
                'first_name'        =>  $user->given_name,
                'last_name'         =>  $user->family_name,
                'billing_address1'  =>  $user->address->line_1,
                'billing_address2'  =>  $user->address->line_2,
                'billing_town'      =>  $user->address->line_3,
                'billing_postcode'  =>  $user->address->postcode,
                'country_code'      => 'GB'
            ]
        );

        return Redirect::to($this->goCardless->newPreAuthUrl($payment_details));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($userId)
    {
        $confirm_params = array(
            'resource_id'    => Request::get('resource_id'),
            'resource_type'  => Request::get('resource_type'),
            'resource_uri'   => Request::get('resource_uri'),
            'signature'      => Request::get('signature'),
        );

        // State is optional
        if (Request::get('state')) {
            $confirm_params['state'] = Request::get('state');
        }

        $user = User::findWithPermission($userId);

        try {
            $confirmed_resource = $this->goCardless->confirmResource($confirm_params);
        } catch (\Exception $e) {
            Notification::error($e->getMessage());
            return Redirect::route('account.show', $user->id);
        }

        if (strtolower($confirmed_resource->status) != 'active') {
            Notification::error('Something went wrong, you can try again or get in contact');
            return Redirect::route('account.show', $user->id);
        }

        $this->userRepository->recordGoCardlessVariableDetails($user->id, $confirmed_resource->id);

        //all we need for a valid member is an active dd so make sure the user account is active
        $this->userRepository->ensureMembershipActive($user->id);

        return Redirect::route('account.show', [$user->id]);
    }


    /**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Response
	 */
	public function destroy($userId, $id=null)
	{

        /**
         * TODO: Check for and cancel pending sub charges
         */
        $user = User::findWithPermission($userId);
        if ($user->payment_method == 'gocardless') {
            try {
                $subscription = $this->goCardless->cancelSubscription($user->subscription_id);
                if ($subscription->status == 'cancelled') {
                    $user->cancelSubscription();
                    Notification::success('Your subscription has been cancelled');
                    return Redirect::back();
                }
            } catch (\GoCardless_ApiException $e) {
                if ($e->getCode() == 404) {
                    $user->cancelSubscription();
                    Notification::success('Your subscription has been cancelled');
                    return Redirect::back();
                }
            }
        } elseif ($user->payment_method == 'gocardless-variable') {
            $status = $this->goCardless->cancelPreAuth($user->subscription_id);
            if ($status) {
                $user->subscription_id = null;
                $user->payment_method = '';
                $user->save();

                $user->setLeaving();

                $this->subscriptionChargeRepository->cancelOutstandingCharges($userId);

                Notification::success('Your direct debit has been cancelled');
                return Redirect::back();
            }
        }
        Notification::error('Sorry, we were unable to cancel your subscription, please get in contact');
        return Redirect::back();
	}


    public function listCharges()
    {
        $charges = $this->subscriptionChargeRepository->getChargesPaginated();
        return View::make('payments.sub-charges')->with('charges', $charges);
    }

}
