<?php
namespace App\Listeners;

use App\Events\OrderCreated;
use App\Services\RabbitMQService;
//use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class PublishOrderCreated//implements ShouldQueue

{
    /**
     * Create the event listener.
     */
    public function __construct(
        private RabbitMQService $rabbitMQ

    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void {
        Log::info('Publishing order.created event to RabbitMQ', [
            'order_id' => $event->order->id,
            'user_id'  => $event->order->user_id,
            'product'  => $event->order->product,
            'quantity' => $event->order->quantity,
            'total'    => $event->order->total,
        ]);
        $this->rabbitMQ->publish(
            'orders',
            'order.created',
            'order.created',
            [
                'order_id' => $event->order->id,
                'user_id'  => $event->order->user_id,
                'product'  => $event->order->product,
                'quantity' => $event->order->quantity,
                'total'    => $event->order->total,
            ]
        );
    }
}
