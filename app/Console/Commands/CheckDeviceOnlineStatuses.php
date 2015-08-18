<?php

namespace BB\Console\Commands;

use BB\Entities\Notification;
use BB\Entities\Role;
use BB\Repo\DeviceRepository;
use Illuminate\Console\Command;

class CheckDeviceOnlineStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'device:check-online';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the devices log and ensure devices are checking in correctly';

    /**
     * @var DeviceRepository
     */
    private $deviceRepository;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->deviceRepository = \App::make('\BB\Repo\DeviceRepository');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->deviceRepository->getAll() as $device) {
            /** @var $device \BB\Entities\Device */
            if ($device->heartbeatWarning()) {

                //There is a warning with the device, see if people have been notified
                $notificationHash = $device->device_id . md5($device->last_heartbeat->timestamp);

                $message = 'Nothing has been heard from device ' . $device->name . ' in a while. ';
                $message .= 'The last update was ' . \Carbon\Carbon::now()->diffForHumans($device->last_heartbeat, true) . ' ago.';

                $role = Role::findByName('infra');
                foreach ($role->users()->get() as $user) {
                    $this->info($user->name);

                    //If the user already has this notification dont create another
                    $existingNotifications = Notification::where('user_id', $user->id)->where('hash', $notificationHash)->count();
                    if ($existingNotifications) {
                        continue;
                    }

                    Notification::create([
                        'user_id' => $user->id,
                        'message' => $message,
                        'type'    => 'device_contact',
                        'hash'    => $notificationHash
                    ]);

                }

            }
        }
    }
}
