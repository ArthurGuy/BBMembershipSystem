<?php

namespace BB\Console\Commands;

use BB\Entities\Notification;
use BB\Entities\Role;
use BB\Repo\ACSNodeRepository;
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
     * @var ACSNodeRepository
     */
    private $acsNodeRepository;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->acsNodeRepository = \App::make('\BB\Repo\ACSNodeRepository');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->acsNodeRepository->getAll() as $device) {

            $this->info('Checking device ' . $device->name);
            /** @var $device \BB\Entities\ACSNode */
            if ($device->heartbeatWarning()) {

                $this->warn('Heartbeat warning');

                //There is a warning with the device, see if people have been notified
                $notificationHash = $device->device_id . md5($device->last_heartbeat->timestamp);

                $message = 'Nothing has been heard from device "' . $device->name . '"" in a while. ';
                $message .= 'The last update was ' . \Carbon\Carbon::now()->diffForHumans($device->last_heartbeat, true) . ' ago.';

                $role = Role::findByName('acs');
                foreach ($role->users()->get() as $user) {
                    $this->info('  Notifying ' . $user->name);

                    Notification::logNew($user->id, $message, 'device_contact', $notificationHash);

                }

            }
        }
    }
}
