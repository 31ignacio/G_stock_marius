<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StockNotification extends Notification
{
    use Queueable;
    public $stock;

    /**
     * Create a new notification instance.
     */
    public function __construct($stock)
    {
        $this->stock=$stock;
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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
     public function toArray(object $notifiable): array
    {
        $date = $this->stock->date->format('d/m/Y');
        return [
            'message' => "Un nouveau stock de produits est en attente de validation depuis le $date. Veuillez le vérifier avant de procéder à la validation.",
        ];
    }

}
