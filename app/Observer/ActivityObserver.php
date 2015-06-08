<?php namespace BB\Observer;

use BB\Entities\User;
use BB\Helpers\UserImage;
use Illuminate\Support\Facades\Log;

class ActivityObserver
{

    /**
     * Look at the user record each time its saved and fire events
     * @param $accessLog
     * @internal param $user
     */
    public function saved($accessLog)
    {
        //If the record was delayed then we don't want a real time event
        if ($accessLog->delayed) {
            return;
        }
        try {
            $userName     = null;
            $userImageUrl = UserImage::anonymous();
            $user         = User::find($accessLog->user_id);
            if ($user) {
                $userName = $user->name;
                if ($user->profile->profile_photo) {
                    $userImageUrl = UserImage::imageUrl($user->hash);
                }
            }

            if (\App::environment('production')) {
                \Slack::send($userName . ' is in the space');
            }

        } catch (\Exception $e) {
            Log::error($e);
        }
    }
} 