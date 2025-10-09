<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;
use Kreait\Firebase\Messaging;

class BookingUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $messageData;

    /**
     * @param array $messageData
     * [
     *   'title' => 'Notification title',
     *   'body' => 'Notification body',
     *   'extra' => ['key' => 'value'] // optional
     * ]
     */
    public function __construct(array $messageData)
    {
        $this->messageData = $messageData;
    }

    /**
     * Define delivery channels
     */
    public function via($notifiable)
    {
        return ['database', \App\Notifications\Channels\FcmChannel::class];
    }

    /**
     * Data saved into `notifications` table
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->messageData['title'] ?? 'Notification',
            'body'  => $this->messageData['body'] ?? '',
            'extra' => $this->messageData['extra'] ?? [],
        ];
    }

    /**
     * FCM push notification
     */
    public function toFcm($notifiable)
    {
        $messaging = app(Messaging::class);

        if (! $notifiable->fcm_token) {
            Log::warning("User {$notifiable->id} has no FCM token");
            return;
        }

        $notification = FcmNotification::create(
            $this->messageData['title'] ?? 'Notification',
            $this->messageData['body'] ?? ''
        );

        $message = CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification($notification)
            ->withData($this->messageData['extra'] ?? []);

        $messaging->send($message);
    }
}
