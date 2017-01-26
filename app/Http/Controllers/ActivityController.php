<?php namespace BB\Http\Controllers;

use Auth;
use BB\Entities\Settings;
use BB\Events\MemberActivity;
use Carbon\Carbon;

class ActivityController extends Controller
{

    /**
     * @var \BB\Repo\ActivityRepository
     */
    private $activityRepository;

    function __construct(\BB\Repo\ActivityRepository $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $date = \Input::get('date', \Carbon\Carbon::now()->format('Y-m-d'));
        $date = \Carbon\Carbon::createFromFormat('Y-m-d', $date)->setTime(0, 0, 0);
        $today = \Carbon\Carbon::now()->setTime(0, 0, 0);
        $doorPin = \Input::get('doorPin');

        $logEntries = $this->activityRepository->getForDate($date);

        $nextDate = null;
        if ($date->lt($today)) {
            $nextDate = $date->copy()->addDay();
        }
        $previousDate = $date->copy()->subDay();



        return \View::make('activity.index')
            ->with('logEntries', $logEntries)
            ->with('date', $date)
            ->with('nextDate', $nextDate)
            ->with('previousDate', $previousDate)
            ->with('doorPin', $doorPin);
    }

    public function create()
    {
        $keyFob = Auth::user()->keyFob();
        event(new MemberActivity($keyFob, 'main-door', Carbon::now(), true));

        $doorPin = Settings::get('emergency_door_key_storage_pin');

        return redirect(route('activity.index', ['doorPin' => $doorPin]));
    }


    /**
     * @return Response
     */
    public function realtime()
    {
        return \View::make('activity.realtime');
    }



}
