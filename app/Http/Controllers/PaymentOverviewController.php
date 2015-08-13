<?php namespace BB\Http\Controllers;

class PaymentOverviewController extends Controller
{

    /**
     * @var \BB\Repo\PaymentRepository
     */
    private $paymentRepository;

    function __construct(\BB\Repo\PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function index()
    {
        $this->paymentRepository->reasonFilter('balance');
        $balancePaidIn = $this->paymentRepository->getTotalAmount();

        $this->paymentRepository->resetFilters();

        $this->paymentRepository->sourceFilter('balance');
        $balancePaidOut = $this->paymentRepository->getTotalAmount();

        $balanceLiability = $balancePaidIn - $balancePaidOut;


        $this->paymentRepository->resetFilters();
        $this->paymentRepository->reasonFilter('storage-box');
        $storageBoxLiability = $this->paymentRepository->getTotalAmount();


        $this->paymentRepository->resetFilters();
        $this->paymentRepository->reasonFilter('door-key');
        $doorKeyLiability = $this->paymentRepository->getTotalAmount();
        

        $laserCutterInvestment = $this->paymentRepository->getPaymentsByReference('laser-cutter')->sum('amount');
        $laserCutterMoneySpent = $this->paymentRepository->getEquipmentFeePayments('laser')->sum('amount');

        return \View::make('payment_overview.index')->with(compact('balancePaidIn', 'balancePaidOut', 'balanceLiability', 'storageBoxLiability', 'doorKeyLiability', 'laserCutterInvestment', 'laserCutterMoneySpent'));
    }
}