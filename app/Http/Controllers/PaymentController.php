<?php namespace BB\Http\Controllers;

use BB\Entities\Induction;
use BB\Entities\Payment;
use BB\Entities\User;

class PaymentController extends Controller
{


    /**
     *
     * @TODO: Workout exactly what this is used for - I think most of the functionality has been moved elsewhere
     *
     */


    /**
     * @var \BB\Repo\EquipmentRepository
     */
    private $equipmentRepository;
    /**
     * @var \BB\Repo\PaymentRepository
     */
    private $paymentRepository;
    /**
     * @var \BB\Repo\UserRepository
     */
    private $userRepository;
    /**
     * @var \BB\Repo\SubscriptionChargeRepository
     */
    private $subscriptionChargeRepository;

    function __construct(
        \BB\Helpers\GoCardlessHelper $goCardless,
        \BB\Repo\EquipmentRepository $equipmentRepository,
        \BB\Repo\PaymentRepository $paymentRepository,
        \BB\Repo\UserRepository $userRepository,
        \BB\Repo\SubscriptionChargeRepository $subscriptionChargeRepository
    ) {
        $this->goCardless                   = $goCardless;
        $this->equipmentRepository          = $equipmentRepository;
        $this->paymentRepository            = $paymentRepository;
        $this->userRepository               = $userRepository;
        $this->subscriptionChargeRepository = $subscriptionChargeRepository;

        $this->middleware('role:member', array('only' => ['create', 'destroy']));

    }


    public function index()
    {
        $sortBy       = \Request::get('sortBy', 'created_at');
        $direction    = \Request::get('direction', 'desc');
        $dateFilter   = \Request::get('date_filter', '');
        $memberFilter = \Request::get('member_filter', '');
        $reasonFilter = \Request::get('reason_filter', '');
        $this->paymentRepository->setPerPage(50);

        if ($dateFilter) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', $dateFilter)->setTime(0, 0, 0);
            $this->paymentRepository->dateFilter($startDate, $startDate->copy()->addMonth());
        }

        if ($memberFilter) {
            $this->paymentRepository->memberFilter($memberFilter);
        }

        if ($reasonFilter) {
            $this->paymentRepository->reasonFilter($reasonFilter);
        }

        $payments = $this->paymentRepository->getPaginated(compact('sortBy', 'direction'));

        $paymentTotal = $this->paymentRepository->getTotalAmount();

        $dateRangeEarliest = \Carbon\Carbon::create(2009, 07, 01);
        $dateRangeStart    = \Carbon\Carbon::now();
        $dateRange         = [];
        while ($dateRangeStart->gt($dateRangeEarliest)) {
            $dateRange[$dateRangeStart->toDateString()] = $dateRangeStart->format('F Y');
            $dateRangeStart->subMonth();
        }

        $memberList = $this->userRepository->getAllAsDropdown();

        $reasonList = [
            'subscription'  => 'Subscription',
            'induction'     => 'Equipment Access Fee',
            'balance'       => 'Balance',
            'door-key'      => 'Key Deposit',
            'storage-box'   => 'Storage Box Deposit',
            'equipment-fee' => 'Equipment Costs'
        ];

        return \View::make('payments.index')->with('payments', $payments)->with('dateRange', $dateRange)
            ->with('memberList', $memberList)->with('reasonList', $reasonList)->with('paymentTotal', $paymentTotal);
    }


    /**
     * Start the creation of a new gocardless payment
     *   Details get posted into this method and the redirected to gocardless
     *
     * @depreciated
     * @param $userId
     * @throws \BB\Exceptions\AuthenticationException
     * @throws \BB\Exceptions\FormValidationException
     * @throws \BB\Exceptions\NotImplementedException
     */
    public function create($userId)
    {
        $user = User::findWithPermission($userId);
        if (\Input::get('source') == 'gocardless') {
            $reason = \Input::get('reason');
            if ($reason == 'subscription') {
                //must go via the gocardless payment controller
                throw new \BB\Exceptions\NotImplementedException('Attempted GoCardless subscription payment');
            } elseif ($reason == 'induction') {
                //Payments must go via the balance
                throw new \BB\Exceptions\NotImplementedException('Attempted GoCardless induction payment');
            } elseif ($reason == 'door-key') {
                //Payments must go via the balance
                throw new \BB\Exceptions\NotImplementedException('Attempted GoCardless door payment');
            } elseif ($reason == 'storage-box') {
                //Payments must go via the balance
                throw new \BB\Exceptions\NotImplementedException('Attempted GoCardless storage box payment');
            } elseif ($reason == 'balance') {
                $amount = \Input::get('amount') * 1; //convert the users amount into a number
                if ( ! is_numeric($amount)) {
                    $exceptionErrors = new \Illuminate\Support\MessageBag(['amount' => 'Invalid amount']);
                    throw new \BB\Exceptions\FormValidationException('Not a valid amount', $exceptionErrors);
                }
                $name        = strtoupper('BBBALANCE' . $user->id);
                $description = 'BB Credit Payment';
                $ref         = null;
            } else {
                throw new \BB\Exceptions\NotImplementedException();
            }
            $payment_details = array(
                'amount'       => $amount,
                'name'         => $name,
                'description'  => $description,
                'redirect_uri' => route('account.payment.confirm-payment', [$user->id]),
                'user'         => [
                    'first_name'       => $user->given_name,
                    'last_name'        => $user->family_name,
                    'billing_address1' => $user->address_line_1,
                    'billing_address2' => $user->address_line_2,
                    'billing_town'     => $user->address_line_3,
                    'billing_postcode' => $user->address_postcode,
                    'country_code'     => 'GB'
                ],
                'state'        => $reason . ':' . $ref
            );

            return \Redirect::to($this->goCardless->newBillUrl($payment_details));
        } else {
            //Unsupported for now
            // perhaps manual payments or maybe they should go straight to store
            throw new \BB\Exceptions\NotImplementedException();
        }
    }


    /**
     * Confirm a gocardless payment and create a payment record
     *
     * @depreciated
     * @param $userId
     * @return Illuminate\Http\RedirectResponse
     * @throws \BB\Exceptions\AuthenticationException
     * @throws \BB\Exceptions\NotImplementedException
     */
    public function confirmPayment($userId)
    {
        $confirm_params = array(
            'resource_id'   => $_GET['resource_id'],
            'resource_type' => $_GET['resource_type'],
            'resource_uri'  => $_GET['resource_uri'],
            'signature'     => $_GET['signature']
        );

        // State is optional
        if (isset($_GET['state'])) {
            $confirm_params['state'] = $_GET['state'];
        }

        $user = User::findWithPermission($userId);

        try {
            $confirmed_resource = $this->goCardless->confirmResource($confirm_params);
        } catch (\Exception $e) {
            $errors = $e->getMessage();
            \Notification::error($errors);

            return \Redirect::route('account.show', [$user->id]);
        }

        $details = explode(':', \Input::get('state'));
        $reason  = $details[0];
        $ref     = $details[1];

        \Log::debug('Old PaymentController@confirmPayment method used. Reason: ' . $reason);

        $payment = new Payment([
            'reason'           => $reason,
            'source'           => 'gocardless',
            'source_id'        => $confirmed_resource->id,
            'amount'           => $confirmed_resource->amount,
            'fee'              => ($confirmed_resource->amount - $confirmed_resource->amount_minus_fees),
            'amount_minus_fee' => $confirmed_resource->amount_minus_fees,
            'status'           => $confirmed_resource->status
        ]);
        $user->payments()->save($payment);

        if ($reason == 'subscription') {
            $user->status = 'active';
            $user->active = true;
            $user->save();
        } elseif ($reason == 'induction') {
            //Payments must go via the balance
            throw new \BB\Exceptions\NotImplementedException('Attempted GoCardless induction payment');
        } elseif ($reason == 'door-key') {
            //Payments must go via the balance
            throw new \BB\Exceptions\NotImplementedException('Attempted GoCardless dor key payment');
        } elseif ($reason == 'storage-box') {
            //Payments must go via the balance
            throw new \BB\Exceptions\NotImplementedException('Attempted GoCardless storage box payment');
        } elseif ($reason == 'balance') {
            $memberCreditService = \App::make('\BB\Services\Credit');
            $memberCreditService->setUserId($user->id);
            $memberCreditService->recalculate();

            //This needs to be improved
            \Notification::success('Payment recorded');

            return \Redirect::route('account.bbcredit.index', $user->id);
        } else {
            throw new \BB\Exceptions\NotImplementedException();
        }

        \Notification::success('Payment made');

        return \Redirect::route('account.show', [$user->id]);
    }


    /**
     * Store a manual payment
     *
     * @param $userId
     * @throws \BB\Exceptions\AuthenticationException
     * @throws \BB\Exceptions\FormValidationException
     * @throws \BB\Exceptions\NotImplementedException
     * @return Illuminate\Http\RedirectResponse
     * @deprecated
     */
    public function store($userId)
    {
        $user = User::findWithPermission($userId);

        if ( ! \Auth::user()->hasRole('admin')) {
            throw new \BB\Exceptions\AuthenticationException;
        }

        \Log::debug('Manual payment endpoint getting hit. account/{id}/payment. paymentController@store '.json_encode(\Input::all()));

        $reason = \Input::get('reason');

        if ($reason == 'subscription') {
            $payment = new Payment([
                'reason'           => $reason,
                'source'           => \Input::get('source'),
                'source_id'        => '',
                'amount'           => $user->monthly_subscription,
                'amount_minus_fee' => $user->monthly_subscription,
                'status'           => 'paid'
            ]);
            $user->payments()->save($payment);

            $user->extendMembership(\Input::get('source'), \Carbon\Carbon::now()->addMonth());

        } elseif ($reason == 'induction') {
            if (\Input::get('source') == 'manual') {
                $ref = \Input::get('induction_key');
                ($item = $this->equipmentRepository->findByKey($ref)) || App::abort(404);
                $payment = new Payment([
                    'reason'           => $reason,
                    'source'           => 'manual',
                    'source_id'        => '',
                    'amount'           => $item->cost,
                    'amount_minus_fee' => $item->cost,
                    'status'           => 'paid'
                ]);
                $payment = $user->payments()->save($payment);
                Induction::create([
                    'user_id'    => $user->id,
                    'key'        => $ref,
                    'paid'       => true,
                    'payment_id' => $payment->id
                ]);
            } else {
                throw new \BB\Exceptions\NotImplementedException();
            }
        } elseif ($reason == 'door-key') {
            $payment = new Payment([
                'reason'           => $reason,
                'source'           => \Input::get('source'),
                'source_id'        => '',
                'amount'           => 10,
                'amount_minus_fee' => 10,
                'status'           => 'paid'
            ]);
            $user->payments()->save($payment);

            $user->key_deposit_payment_id = $payment->id;
            $user->save();

        } elseif ($reason == 'storage-box') {
            $payment = new Payment([
                'reason'           => $reason,
                'source'           => \Input::get('source'),
                'source_id'        => '',
                'amount'           => 5,
                'amount_minus_fee' => 5,
                'status'           => 'paid'
            ]);
            $user->payments()->save($payment);

            $user->storage_box_payment_id = $payment->id;
            $user->save();
        } elseif ($reason == 'balance') {
            $amount = \Input::get('amount') * 1; //convert the users amount into a number
            if ( ! is_numeric($amount)) {
                $exceptionErrors = new \Illuminate\Support\MessageBag(['amount' => 'Invalid amount']);
                throw new \BB\Exceptions\FormValidationException('Not a valid amount', $exceptionErrors);
            }
            $payment = new Payment([
                'reason'           => 'balance',
                'source'           => \Input::get('source'),
                'source_id'        => '',
                'amount'           => $amount,
                'amount_minus_fee' => $amount,
                'status'           => 'paid'
            ]);
            $user->payments()->save($payment);

            $memberCreditService = \App::make('\BB\Services\Credit');
            $memberCreditService->setUserId($user->id);
            $memberCreditService->recalculate();

            //This needs to be improved
            \Notification::success('Payment recorded');

            return \Redirect::route('account.bbcredit.index', $user->id);
        } else {
            throw new \BB\Exceptions\NotImplementedException();
        }
        \Notification::success('Payment recorded');

        return \Redirect::route('account.show', [$user->id]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update a payment
     * Change where the money goes by altering the original record or creating a secondary payment
     *
     * @param  int $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        $this->paymentRepository->getById($id);

        \Notification::success('Not yet implemented');

        return \Redirect::back();
    }


    /**
     * Remove the specified payment
     *
     * @param  int $id
     * @return Illuminate\Http\RedirectResponse
     * @throws \BB\Exceptions\ValidationException
     */
    public function destroy($id)
    {
        $payment = $this->paymentRepository->getById($id);

        //we can only allow some records to get deleted, only cash payments can be removed, everything else must be refunded off
        if ($payment->source != 'cash') {
            throw new \BB\Exceptions\ValidationException('Only cash payments can be deleted');
        }
        if ($payment->reason != 'balance') {
            throw new \BB\Exceptions\ValidationException('Currently only payments to the members balance can be deleted');
        }

        //The delete event will broadcast an event and allow related actions to occur
        $this->paymentRepository->delete($id);

        \Notification::success('Payment deleted');

        return \Redirect::back();
    }


    /**
     * This is a method for migrating user to the variable gocardless subscription
     * It will cancel the existing direct debit and direct the user to setup a pre auth
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function migrateDD()
    {
        $user = \Auth::user();

        //cancel the existing dd
        try {
            $subscription = $this->goCardless->cancelSubscription($user->subscription_id);
            if ($subscription->status != 'cancelled') {
                \Notification::error('Could not cancel the existing subscription');

                return \Redirect::back();
            }
        } catch (\GoCardless_ApiException $e) {

        }

        $user->payment_method  = '';
        $user->subscription_id = '';
        $user->save();

        $payment_details = array(
            'redirect_uri' => route('account.subscription.store', $user->id),
            'user'         => [
                'first_name'       => $user->given_name,
                'last_name'        => $user->family_name,
                'billing_address1' => $user->address->line_1,
                'billing_address2' => $user->address->line_2,
                'billing_town'     => $user->address->line_3,
                'billing_postcode' => $user->address->postcode,
                'country_code'     => 'GB'
            ]
        );

        return \Redirect::to($this->goCardless->newPreAuthUrl($payment_details));
    }

}
