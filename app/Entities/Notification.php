<?php

namespace BB\Entities;

use BB\Events\NewMemberNotification;
use Illuminate\Database\Eloquent\Model;

/**
 * @property User     user
 * @property integer  user_id
 * @property string   message
 * @property string   type
 * @property string   hash
 * @property boolean  unread
 */
class Notification extends Model
{
    protected $fillable = ['user_id', 'message', 'type', 'hash', 'unread', 'notified_method', 'notified_at'];

    public function getDates()
    {
        return array('created_at', 'updated_at', 'notified_at');
    }

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

        $newNotification = parent::create([
            'user_id' => $userId,
            'message' => $message,
            'type'    => $type,
            'hash'    => $hash
        ]);

        event(new NewMemberNotification($newNotification));

        return $newNotification;
    }

    public function user()
    {
        return $this->belongsTo(User::class)->first();
    }

    /**
     * Scope a query to only include unread notifications
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread($query)
    {
        return $query->where('unread', true);
    }
}
