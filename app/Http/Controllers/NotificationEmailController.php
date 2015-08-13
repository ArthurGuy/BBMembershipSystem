<?php namespace BB\Http\Controllers;

use BB\Exceptions\AuthenticationException;
use BB\Exceptions\NotImplementedException;
use BB\Mailer\UserMailer;
use BB\Repo\InductionRepository;
use BB\Repo\UserRepository;
use BB\Validators\EmailNotificationValidator;

class NotificationEmailController extends Controller
{


    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EmailNotificationValidator
     */
    private $emailNotificationValidator;
    /**
     * @var InductionRepository
     */
    private $inductionRepository;

    /**
     * @param UserRepository               $userRepository
     * @param EmailNotificationValidator   $emailNotificationValidator
     * @param InductionRepository $inductionRepository
     * @throws AuthenticationException
     */
    public function __construct(
        UserRepository $userRepository,
        EmailNotificationValidator $emailNotificationValidator,
        InductionRepository $inductionRepository
    ) {
        $this->userRepository             = $userRepository;
        $this->emailNotificationValidator = $emailNotificationValidator;
        $this->inductionRepository        = $inductionRepository;
    }

    public function create()
    {
        if ( ! \Auth::user()->isAdmin() && \Auth::user()->roles()->get()->count() <= 0) {
            throw new AuthenticationException("You don't have permission to be here");
        }

        $recipients = ['all' => 'All Members', 'laser_induction_members' => 'Laser Induction Members'];
        return \View::make('notification_email.create')->with('recipients', $recipients);
    }

    public function store()
    {
        $input = \Input::only('subject', 'message', 'send_to_all', 'recipient');

        $this->emailNotificationValidator->validate($input);

        //This is for admins only unless they are part of a group, then they have access to specific lists
        if ( ! \Auth::user()->isAdmin() && ! \Auth::user()->hasRole('laser')) {

        }


        if ($input['send_to_all']) {

            if ($input['recipient'] == 'all') {
                if ( ! \Auth::user()->isAdmin()) {
                    throw new AuthenticationException("You don't have permission to send to this group");
                }
                $users = $this->userRepository->getActive();
            } else {
                if ($input['recipient'] == 'laser_induction_members') {

                    if ( ! \Auth::user()->hasRole('laser')) {
                        throw new AuthenticationException("You don't have permission to send to this group");
                    }

                    $users = $this->inductionRepository->getUsersForEquipment('laser');
                } else {
                    throw new NotImplementedException("Recipient not supported");
                }
            }
            foreach ($users as $user) {
                $notification = new UserMailer($user);
                $notification->sendNotificationEmail($input['subject'], nl2br($input['message']));
            }

        } else {

            //Just send to the current user
            $notification = new UserMailer(\Auth::user());
            $notification->sendNotificationEmail($input['subject'], nl2br($input['message']));

        }


        \Notification::success('Email Queued to Send');
        return \Redirect::route('notificationemail.create');
    }
} 