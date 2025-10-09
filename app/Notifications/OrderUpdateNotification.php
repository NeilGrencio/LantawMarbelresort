<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log; // ✅ add this
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;
use Kreait\Firebase\Messaging;
class OrderUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
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
            'title' => 'Order Update',
            'body'  => "Your order #{$this->order->id} has been updated!",
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
            'Order Update',
            "Your order #{$this->order->id} has been updated!"
        );

        $message = CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification($notification);

        $messaging->send($message);
    }
}
