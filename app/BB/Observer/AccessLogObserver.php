<?php namespace BB\Observer;

use Artdarek\Pusherer\Facades\Pusherer;
use BB\Helpers\UserImage;
use Illuminate\Support\Facades\Log;

class AccessLogObserver
{

    /**
     * Look at the user record each time its saved and fire events
     * @param $accessLog
     * @internal param $user
     */
    public function saved($accessLog)
    {
        try {
            $userName     = null;
            $userImageUrl = UserImage::anonymous();
            $user         = \User::find($accessLog->user_id);
            if ($user) {
                $userName = $user->name;
                if ($user->profile->profile_photo) {
                    $userImageUrl = UserImage::imageUrl($user->hash);
                }
            }

            Pusherer::trigger(
                'activity',
                $accessLog->service,
                array(
                    'user_id'    => $accessLog->user_id,
                    'response'   => $accessLog->response,
                    'key_fob_id' => $accessLog->key_fob_id,
                    'user_name'  => $userName,
                    'user_image' => $userImageUrl,
                    'time'       => $accessLog->created_at->toTimeString()
                )
            );
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
} 