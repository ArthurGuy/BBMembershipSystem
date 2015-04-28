<?php namespace BB\Repo;

use BB\Entities\SubscriptionCharge;
use BB\Exceptions\InvalidDataException;
use BB\Helpers\GoCardlessHelper;
use Carbon\Carbon;

class SubscriptionChargeRepository extends DBRepository
{


    /**
     * @var SubscriptionCharge
     */
    protected $model;
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;
    /**
     * @var GoCardlessHelper
     */
    private $goCardless;

    function __construct(SubscriptionCharge $model, PaymentRepository $paymentRepository, GoCardlessHelper $goCardless)
    {
        $this->model = $model;
        $this->paymentRepository = $paymentRepository;
        $this->goCardless = $goCardless;
    }

    /**
     * @param integer   $userId
     * @param \DateTime $date
     * @param integer   $amount
     * @param string    $status
     * @return SubscriptionCharge
     */
    public function createCharge($userId, \DateTime $date, $amount=0, $status='pending')
    {
        return $this->model->create(['charge_date' => $date, 'user_id' => $userId, 'amount' => $amount, 'status'=>$status]);
    }

    /**
     * Create a charge and immediately bill it through the direct debit process
     *
     * @param integer   $userId
     * @param \DateTime $date
     * @param integer   $amount
     * @param string    $status
     * @param string    $DDAuthId
     * @return SubscriptionCharge
     */
    public function createChargeAndBillDD($userId, $date, $amount, $status, $DDAuthId)
    {
        $charge = $this->createCharge($userId, $date, $amount, $status);

        $bill = $this->goCardless->newBill($DDAuthId, $amount);
        if ($bill) {
            $this->paymentRepository->recordSubscriptionPayment($userId, 'gocardless-variable', $bill->id,
                $bill->amount, $bill->status, $bill->gocardless_fees, $charge->id);
        }
    }

    /**
     * Does a charge already exist for the user and date
     * @param $userId
     * @param $date
     * @return bool
     */
    public function chargeExists($userId, $date)
    {
        if ($this->model->where('user_id', $userId)->where('charge_date', $date)->count() !== 0) {
            return true;
        }
        return false;
    }

    /**
     * Locate the next payment the user has to pay off
     *
     * @param $userId
     * @param Carbon $paymentDate
     * @return mixed
     */
    public function findCharge($userId, $paymentDate=null)
    {
        //find any existing payment that hasn't been paid
        //Subscription payments will always be used to pay of bills

        return $this->model->where('user_id', $userId)->whereIn('status', ['pending', 'due'])->orderBy('charge_date', 'ASC')->first();
    }

    /**
     * @param $chargeId
     * @param $paymentDate
     */
    public function markChargeAsPaid($chargeId, $paymentDate=null)
    {
        if (is_null($paymentDate)) {
            $paymentDate = new Carbon();
        }
        $subCharge = $this->getById($chargeId);
        $subCharge->payment_date = $paymentDate;
        $subCharge->status = 'paid';
        $subCharge->save();

        \Event::fire('sub-charge.paid', array($chargeId, $subCharge->user_id, $subCharge->charge_date, $subCharge->amount));
    }

    /**
     * @param $chargeId
     */
    public function markChargeAsProcessing($chargeId)
    {
        $subCharge = $this->getById($chargeId);
        $subCharge->status = 'processing';
        $subCharge->save();

        \Event::fire('sub-charge.processing', array($chargeId, $subCharge->user_id, $subCharge->charge_date, $subCharge->amount));
    }

    /**
     * If a payment has failed update the sub charge to reflect this
     *
     * @param $chargeId
     */
    public function paymentFailed($chargeId)
    {
        $subCharge = $this->getById($chargeId);
        //If the charge has already been cancelled dont touch it
        if ($subCharge->status != 'cancelled') {
            $subCharge->payment_date = null;
            $subCharge->status       = 'due';
            $subCharge->amount       = 0;
            $subCharge->save();
        } else {
            \Log::debug("Sub charge not updated after payment failure, already cancelled. Charge ID: ".$chargeId);
        }

        \Event::fire('sub-charge.payment-failed', array($chargeId, $subCharge->user_id, $subCharge->charge_date, $subCharge->amount));
    }

    /**
     * Return a paginated list of member payments
     *
     * @param integer $userId
     * @return mixed
     */
    public function getMemberChargesPaginated($userId)
    {
        return $this->model->where('user_id', $userId)->orderBy('charge_date', 'DESC')->paginate();
    }

    /**
     * Return a paginated list of member payments
     *
     * @param integer $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMemberCharges($userId)
    {
        return $this->model->where('user_id', $userId)->orderBy('charge_date', 'DESC')->get();
    }

    /**
     * Return a paginated list of member payments
     *
     * @return mixed
     */
    public function getChargesPaginated()
    {
        return $this->model->orderBy('charge_date', 'DESC')->paginate();
    }

    /**
     * Get all the charges which are due payment
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDue()
    {
        return $this->model->where('status', 'due')->get();
    }

    /**
     * Get charges that are newly created and pending
     *
     * @return mixed
     */
    public function getPending()
    {
        return $this->model->where('status', 'pending')->get();
    }

    /**
     * Update a charge and mark it as due
     *
     * @param $chargeId
     */
    public function setDue($chargeId)
    {
        $subCharge = $this->getById($chargeId);
        $subCharge->status = 'due';
        $subCharge->save();
    }

    /**
     * Cancel all outstanding (due and pending) charges for a user
     * Used when leaving
     *
     * @param $userId
     */
    public function cancelOutstandingCharges($userId)
    {
        $this->model->where('user_id', $userId)->whereIn('status', ['pending', 'due'])->update(['status'=>'cancelled']);
    }

    /**
     * Does the user have any active or outstanding charges
     *
     * @param $userId
     * @return bool
     */
    public function hasOutstandingCharges($userId)
    {
        return ($this->model->where('user_id', $userId)->whereIn('status', ['pending', 'due', 'processing'])->count() > 0);
    }

    public function updateAmount($chargeId, $newAmount)
    {
        $subCharge = $this->getById($chargeId);
        $subCharge->amount = $newAmount;
        $subCharge->save();
    }

}