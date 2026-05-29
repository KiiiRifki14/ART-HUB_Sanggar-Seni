<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FullPaymentProofUploaded extends Notification
{
    use Queueable;

    public $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
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
            'title' => 'Bukti Pelunasan Masuk',
            'message' => "Klien {$this->booking->client_name} mengunggah bukti pelunasan untuk: {$this->booking->event_type}!",
            'type' => 'full_proof_uploaded',
            'url' => route('admin.payments.index', [], false),
        ];
    }
}
