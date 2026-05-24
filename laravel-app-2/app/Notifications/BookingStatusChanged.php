<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class BookingStatusChanged extends Notification
{
    use Queueable;

    public $booking;
    public $statusMessage;

    /**
     * Create a new notification instance.
     */
    public function __construct($booking, $statusMessage)
    {
        $this->booking = $booking;
        $this->statusMessage = $statusMessage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Update Status Pesanan',
            // FIX B-06: Sanitasi data yang masuk ke notifikasi untuk mencegah XSS
            'message' => 'Pesanan Anda (' . strip_tags($this->booking->booking_code) . ') ' . strip_tags($this->statusMessage),
            'booking_id' => $this->booking->id
        ];
    }
}
