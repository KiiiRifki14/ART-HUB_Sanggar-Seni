<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPersonnelRegistration extends Notification
{
    use Queueable;

    public $userName;

    /**
     * Create a new notification instance.
     */
    public function __construct($userName)
    {
        $this->userName = $userName;
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
            'title' => 'Pendaftaran Personel Baru',
            'message' => "Ada Pendaftaran Personel Menunggu Persetujuan dari: {$this->userName}!",
            'type' => 'new_personnel',
            'url' => route('admin.personnel.index', [], false) . '#pending-section',
        ];
    }
}
