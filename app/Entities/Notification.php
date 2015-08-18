<?php

namespace BB\Entities;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'message', 'type', 'hash'];

    /**
     * Record a notification for the user but make sure there are no duplicates first
     *
     * @param int    $userId
     * @param string $message
     * @param string $type
     * @param string $hash
     * @return Notification
     */
    public static function logNew($userId, $message, $type, $hash) {
        $existingNotifications = Notification::where('user_id', $userId)->where('hash', $hash)->first();
        if ($existingNotifications) {
            return $existingNotifications;
        }

        return parent::create([
            'user_id' => $userId,
            'message' => $message,
            'type'    => $type,
            'hash'    => $hash
        ]);
    }
}
