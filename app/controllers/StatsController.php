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
            'gocardless'     => 10,
            'paypal'         => 20,
            'standing-order' => 5
        ];
        foreach ($users as $user) {
            if (isset($paymentMethodsNumbers[$user->payment_method])) {
                $paymentMethodsNumbers[$user->payment_method]++;
            }
        }
        $paymentMethods = [];
        $paymentMethods[] = (object)[
            'value' => $paymentMethodsNumbers['gocardless'],
            'label' => 'Direct Debit',
            'colour' => '#F7464A',
            'highlight' => '#FF5A5E'
        ];
        $paymentMethods[] = (object)[
            'value' => $paymentMethodsNumbers['paypal'],
            'label' => 'PayPal',
            'colour' => '#46BFBD',
            'highlight' => '#5AD3D1'
        ];
        $paymentMethods[] = (object)[
            'value' => $paymentMethodsNumbers['standing-order'],
            'label' => 'Standing Order',
            'colour' => '#FDB45C',
            'highlight' => '#FFC870'
        ];


        JavaScript::put([
                'paymentMethods' => $paymentMethods
        ]);

        $this->layout->content = View::make('stats.index');
    }


}
