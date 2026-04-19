<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification implements ShouldQueue
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
                    ->subject('Pesanan Baru: ' . $this->order->order_number)
                    ->greeting('Halo Admin,')
                    ->line('Ada pesanan baru dari pelanggan ' . $this->order->user->name)
                    ->line('Jasa: ' . $this->order->service->name)
                    ->action('Lihat Detail', url('/admin/orders/' . $this->order->id))
                    ->line('Harap segera diproses.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
