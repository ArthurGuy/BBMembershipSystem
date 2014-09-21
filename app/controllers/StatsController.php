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


        JavaScript::put([
                'paymentMethods' => $paymentMethods
        ]);

        $this->layout->content = View::make('stats.index');
    }


}
