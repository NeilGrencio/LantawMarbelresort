<?php

namespace App\Notifications\Channels;

use App\Notifications\BookingUpdateNotification;
use Illuminate\Support\Facades\Log;

class FcmChannel
{
    public function send($notifiable, BookingUpdateNotification $notification)
    {
        // Check if the notification has toFcm method
        if (!method_exists($notification, 'toFcm')) {
            Log::warning('Notification missing toFcm method', [
                'notification' => get_class($notification),
            ]);
            return;
        }

        try {
            $fcmMessage = $notification->toFcm($notifiable);

            // Optional: log the payload
            Log::info('Sending FCM notification', [
                'notifiable' => $notifiable->id ?? null,
                'payload' => $fcmMessage,
            ]);

            return $fcmMessage;

        } catch (\Exception $e) {
            Log::error('FCM notification failed', [
                'exception' => $e->getMessage(),
                'notification' => get_class($notification),
                'notifiable' => $notifiable->id ?? null,
            ]);
        }
    }
}
