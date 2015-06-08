<?php namespace BB\Http\Controllers;

use BB\Helpers\StatsHelper;

class StatsController extends Controller
{

    protected $layout = 'layouts.main';

    /**
     * @var \BB\Repo\UserRepository
     */
    private $userRepository;
    /**
     * @var \BB\Repo\ActivityRepository
     */
    private $activityRepository;


    function __construct(\BB\Repo\UserRepository $userRepository, \BB\Repo\ActivityRepository $activityRepository)
    {
        $this->userRepository = $userRepository;
        $this->activityRepository = $activityRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = $this->userRepository->getActive();
        $paymentMethodsNumbers = [
            'gocardless'            => 0,
            'gocardless-variable'   => 0,
            'paypal'                => 0,
            'standing-order'        => 0
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
                'Direct Debit', $paymentMethodsNumbers['gocardless'] + $paymentMethodsNumbers['gocardless-variable']
            ],
            [
                'PayPal', $paymentMethodsNumbers['paypal']
            ],
            [
                'Standing Order', $paymentMethodsNumbers['standing-order']
            ]
        ];

        //Fetch the users amounts and bucket them
        $averageMonthlyAmount = 0;
        $numPayingUsers = 0;
        $monthlyAmounts = array_fill_keys(range(5, 50, 5), 0);
        foreach ($users as $user) {
            if (isset($monthlyAmounts[(int)StatsHelper::roundToNearest($user->monthly_subscription)])) {
                $monthlyAmounts[(int)StatsHelper::roundToNearest($user->monthly_subscription)]++;
            }
            if ($user->monthly_subscription > 0) {
                $averageMonthlyAmount = $averageMonthlyAmount + $user->monthly_subscription;
                $numPayingUsers++;
            }
        }

        $averageMonthlyAmount = $averageMonthlyAmount / $numPayingUsers;

        //Remove the higher empty amounts
        $i = 50;
        while ($i >= 0) {
            if (isset($monthlyAmounts[$i]) && empty($monthlyAmounts[$i])) {
                unset($monthlyAmounts[$i]);
            } else {
                break;
            }
            $i = $i - 5;
        }

        //Format the data into the chart format
        $monthlyAmountsData = [];
        $monthlyAmountsData[] = ['Amount', 'Number of Members', (object)['role'=> 'annotation']];
        foreach ($monthlyAmounts as $amount => $numUsers) {
            $monthlyAmountsData[] = ['£' . $amount, $numUsers, $numUsers];
        }


        //Number of Users
        $numMembers = count($users);


        //door access logs
        $logEntrys = $this->activityRepository->activeUsersForPeriod(\Carbon\Carbon::now()->subMonth(), \Carbon\Carbon::now());
        $userArray = [];
        foreach ($logEntrys as $entry) {
            $userArray[] = $entry->user_id;
        }
        $numActiveUsers = count(array_unique($userArray));

        $logEntrys = $this->activityRepository->activeUsersForPeriod(\Carbon\Carbon::now()->subMonths(3), \Carbon\Carbon::now());
        $userArray = [];
        foreach ($logEntrys as $entry) {
            $userArray[] = $entry->user_id;
        }
        $numActiveUsersQuarter = count(array_unique($userArray));

        return \View::make('stats.index')
            ->with('averageMonthlyAmount', round($averageMonthlyAmount))
            ->with('numMembers', $numMembers)
            ->with('numActiveUsers', $numActiveUsers)
            ->with('numActiveUsersQuarter', $numActiveUsersQuarter)
            ->with('paymentMethods', $paymentMethods)
            ->with('monthlyAmountsData', $monthlyAmountsData);
    }

    public function ddSwitch()
    {
        $users = $this->userRepository->getActive();
        $paymentMethodsNumbers = [
            'gocardless'            => 0,
            'gocardless-variable'   => 0,
        ];
        foreach ($users as $user) {
            if (isset($paymentMethodsNumbers[$user->payment_method])) {
                $paymentMethodsNumbers[$user->payment_method]++;
            }
        }
        $paymentMethods = [
            [
                'GoCardless Type', 'Number'
            ],
            [
                'Fixed', $paymentMethodsNumbers['gocardless']
            ],
            [
                'Variable', $paymentMethodsNumbers['gocardless-variable']
            ]
        ];

        return \View::make('stats.dd-switch')
            ->with('paymentMethods', $paymentMethods);
    }

}
