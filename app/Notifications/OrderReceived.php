<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\WorkOrder;

class OrderReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(WorkOrder $order)
    {
        $this->order = $order;
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
                    ->subject("Recepción de Vehículo - Orden #{$this->order->id}")
                    ->greeting("Hola {$this->order->vehicle->owner_name},")
                    ->line("Hemos recibido su vehículo {$this->order->vehicle->brand} {$this->order->vehicle->model} con placas **{$this->order->vehicle->plate}**.")
                    ->line("Número de Orden: **#{$this->order->id}**")
                    ->line("Fecha de Recepción: " . $this->order->created_at->format('d/m/Y H:i'))
                    ->line("Puede dar seguimiento al estado de su vehículo en el siguiente enlace:")
                    ->action('Rastrear mi Vehículo', route('tracking', ['plate' => $this->order->vehicle->plate]))
                    ->line('Nos pondremos en contacto con usted pronto para informarle sobre el presupuesto o reparaciones.')
                    ->salutation('Atentamente, El Equipo de ' . config('app.name'));
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
