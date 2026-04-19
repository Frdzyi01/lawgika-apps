<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    private $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Status Pesanan Anda Diperbarui: ' . $this->order->order_number)
                    ->greeting('Halo ' . $this->order->user->name . ',')
                    ->line('Status pesanan Anda untuk jasa "' . $this->order->service->name . '" telah diperbarui menjadi: **' . strtoupper($this->order->status) . '**')
                    ->action('Lihat Pesanan', url('/dashboard/orders/' . $this->order->id))
                    ->line('Terima kasih telah menggunakan layanan kami!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
