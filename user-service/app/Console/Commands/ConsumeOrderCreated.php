<?php
namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

#[Signature('app:consume-order-created')]
#[Description('Command description')]
class ConsumeOrderCreated extends Command {
    /**
     * Execute the console command.
     */
    protected $signature = 'rabbitmq:consume-order-created';

    protected $description = 'Consume order.created messages';

    public function handle(): int {
        $connection = new AMQPStreamConnection(
            config('rabbitmq.host'),
            config('rabbitmq.port'),
            config('rabbitmq.user'),
            config('rabbitmq.password'),
            config('rabbitmq.vhost')
        );

        $channel = $connection->channel();

        $queue = 'order.created';

        $channel->basic_qos(
            null,
            1,
            null
        );

        $channel->basic_consume(
            $queue,
            '',
            false,
            false,
            false,
            false,
            function ($message) use ($channel) {

                $data = json_decode(
                    $message->getBody(),
                    true
                );

                $this->info(
                    'Order received: ' . $data['order_id']
                );

                // Processing

                $channel->basic_ack(
                    $message->get('delivery_tag')
                );
            }
        );

        $this->info('Waiting for messages...');

        while (true) {
            $channel->wait();
        }
    }
}
