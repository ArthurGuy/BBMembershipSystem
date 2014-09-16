<?php namespace BB\Observer;

use Artdarek\Pusherer\Facades\Pusherer;
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
            Pusherer::trigger(
                'activity',
                $accessLog->service,
                array(
                    'user_id'    => $accessLog->user_id,
                    'response'   => $accessLog->response,
                    'key_fob_id' => $accessLog->key_fob_id
                )
            );
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
} 