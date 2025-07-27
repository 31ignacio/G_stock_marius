<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ValiderNotification extends Notification
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
        $date = now()->setTimezone('Africa/Lagos')->format('d/m/Y à H:i');
        $libelle = strtoupper($this->stock->libelle);
        $quantite = $this->stock->quantite;

        return [
            'message' => "Produit : {$libelle} | Quantité : {$quantite} | Validé le : {$date}. Vérifiez votre stock.",
        ];
    }



}
