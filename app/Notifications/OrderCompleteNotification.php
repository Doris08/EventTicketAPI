<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCompleteNotification extends Notification
{
    use Queueable;
    public $confirmationMessage;

    /**
     * Create a new notification instance.
     */
    public function __construct($confirmationMessage)
    {
        $this->confirmationMessage = $confirmationMessage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->replyTo('areli.bonilla.ramos.abr@gmail.com', 'Areli Bonilla')
                    ->line("Hi Areli Bonilla")
                    ->line("your order was successfully complete");

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
