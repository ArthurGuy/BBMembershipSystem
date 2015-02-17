<?php

use BB\Notifications\Notification;
use BB\Repo\UserRepository;
use BB\Validators\EmailNotificationValidator;

class NotificationEmailController extends \BaseController
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
     * @var Notification
     */
    private $notifications;
    /**
     * @var \BB\Repo\InductionRepository
     */
    private $inductionRepository;

    /**
     * @param UserRepository               $userRepository
     * @param EmailNotificationValidator   $emailNotificationValidator
     * @param Notification                 $notifications
     * @param \BB\Repo\InductionRepository $inductionRepository
     * @throws \BB\Exceptions\AuthenticationException
     */
    public function __construct(
        UserRepository $userRepository,
        EmailNotificationValidator $emailNotificationValidator,
        Notification $notifications,
        \BB\Repo\InductionRepository $inductionRepository
    ) {
        $this->userRepository             = $userRepository;
        $this->emailNotificationValidator = $emailNotificationValidator;
        $this->notifications              = $notifications;
        $this->inductionRepository        = $inductionRepository;

        if (!Auth::user()->isAdmin() && Auth::user()->roles()->get()->count() <= 0) {
            throw new \BB\Exceptions\AuthenticationException("You don't have permission to be here");
        }
    }

    public function create()
    {
        $recipients = ['all' => 'All Members', 'laser_induction_members' => 'Laser Induction Members'];
        return View::make('notification_email.create')->with('recipients', $recipients);
    }

    public function store()
    {
        $input = Input::only('subject', 'message', 'send_to_all', 'recipient');

        $this->emailNotificationValidator->validate($input);

        //This is for admins only unless they are part of a group, then they have access to specific lists
        if (!Auth::user()->isAdmin() && !Auth::user()->hasRole('laser')) {

        }


        if ($input['send_to_all']) {

            if ($input['recipient'] == 'all') {
                if (!Auth::user()->isAdmin()) {
                    throw new \BB\Exceptions\AuthenticationException("You don't have permission to send to this group");
                }
                $users = $this->userRepository->getActive();
            } else {
                if ($input['recipient'] == 'laser_induction_members') {

                    if (!Auth::user()->hasRole('laser')) {
                        throw new \BB\Exceptions\AuthenticationException("You don't have permission to send to this group");
                    }

                    $users = $this->inductionRepository->getUsersForEquipment('laser');
                } else {
                    throw new \Aws\S3\Exception\NotImplementedException("Recipient not supported");
                }
            }
            foreach ($users as $user) {
                $notification = new \BB\Mailer\UserMailer($user);
                $notification->sendNotificationEmail($input['subject'], nl2br($input['message']));
            }

        } else {

            //Just send to the current user
            $notification = new \BB\Mailer\UserMailer(Auth::user());
            $notification->sendNotificationEmail($input['subject'], nl2br($input['message']));

        }


        $this->notifications->success("Email Queued to Send");
        return Redirect::route('notificationemail.create');
    }
} 