<?php

class StatsController extends \BaseController
{

    protected $layout = 'layouts.main';

    /**
     * @var \BB\Repo\UserRepository
     */
    private $userRepository;


    function __construct(\BB\Repo\UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users         = $this->userRepository->getActive();
        $paymentMethodsNumbers = [
            'gocardless'     => 0,
            'paypal'         => 0,
            'standing-order' => 0
        ];
        foreach ($users as $user) {
            if (isset($paymentMethodsNumbers[$user->payment_method])) {
                $paymentMethodsNumbers[$user->payment_method]++;
            }
        }
        $paymentMethods = [
            [
                'Payment Method', 'Number'
            ],
            [
                'Direct Debit', $paymentMethodsNumbers['gocardless']
            ],
            [
                'PayPal', $paymentMethodsNumbers['paypal']
            ],
            [
                'Standing Order', $paymentMethodsNumbers['standing-order']
            ]
        ];

        $monthlyAmounts = array_fill_keys(range(5, 50), 0);
        foreach ($users as $user) {
            $monthlyAmounts[$user->monthly_subscription]++;
        }
        $monthlyAmountsData = [];
        $monthlyAmountsData[] = ['Amount', 'Number of Members'];
        foreach ($monthlyAmounts as $amount => $numUsers) {
            $monthlyAmountsData[] = [$amount, $numUsers];
        }


        JavaScript::put([
                'paymentMethods' => $paymentMethods,
                'monthlyAmounts' => $monthlyAmountsData
        ]);

        $this->layout->content = View::make('stats.index');
    }


}
