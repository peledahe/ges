<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\WorkOrder;

class WorkOrderUpdated extends Notification implements ShouldQueue
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
        $status = ucfirst(str_replace('_', ' ', $this->order->status));
        $vehicle = $this->order->vehicle->brand . ' ' . $this->order->vehicle->model . ' (' . $this->order->vehicle->plate . ')';

        return (new MailMessage)
                    ->subject("Actualización de Estado - Orden #{$this->order->id}")
                    ->greeting("Hola {$this->order->vehicle->owner_name},")
                    ->line("Le informamos que el estado de su vehículo {$vehicle} ha cambiado.")
                    ->line("Nuevo Estado: **{$status}**")
                    ->line("Ubicación Actual: " . ($this->order->area->name ?? 'Taller'))
                    ->action('Ver Detalle y Rastreo', route('tracking', ['plate' => $this->order->vehicle->plate]))
                    ->line('Gracias por confiar en nosotros.');
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
