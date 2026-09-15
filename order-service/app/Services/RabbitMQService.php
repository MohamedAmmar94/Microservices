<?php
namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService {
    /**
     * Create a new class instance.
     */
    public function __construct() {
        //
    }
    public function publish(
        string $exchange,
        string $queue,
        string $routingKey,
        array $data
    ): void {
        $connection = new AMQPStreamConnection(
            config('rabbitmq.host'),
            config('rabbitmq.port'),
            config('rabbitmq.user'),
            config('rabbitmq.password'),
            config('rabbitmq.vhost')
        );

        $channel = $connection->channel();

        $message = new AMQPMessage(
            json_encode($data),
            [
                'content_type'  => 'application/json',
                'delivery_mode' => 2, // make message persistent not lost in case of server restart, is stored on disk until consumed
            ]
        );
// 1. Create Exchange
        $channel->exchange_declare(
            $exchange,
            'direct',
            false, // passive
            true,  // durable
            false  // auto_delete
        );

// 2. Create Queue
        $channel->queue_declare(
            $queue,
            false, // passive
            true,  // durable
            false, // exclusive
            false  // auto_delete
        );

// 3. Connect Exchange → Queue
        $channel->queue_bind($queue, $exchange, $routingKey);

// 4. Publish Message
        $channel->basic_publish(
            $message,
            $exchange,
            $routingKey
        );
        // $channel->exchange_declare(
        //     $exchange,
        //     'direct',
        //     false,
        //     true,
        //     false
        // );

        // $channel->basic_publish(
        //     $message,
        //     $exchange,
        //     $routingKey
        // );

        $channel->close();
        $connection->close();
    }
    public function setup(
        string $exchange,
        string $queue,
        string $routingKey
    ): void {
        $connection = new AMQPStreamConnection(
            config('rabbitmq.host'),
            config('rabbitmq.port'),
            config('rabbitmq.user'),
            config('rabbitmq.password'),
            config('rabbitmq.vhost')
        );

        $channel = $connection->channel();

        // Exchange
        $channel->exchange_declare(
            $exchange,
            'direct',
            false,
            true,
            false
        );

        // Queue
        $channel->queue_declare(
            $queue,
            false,
            true,
            false,
            false
        );

        // Binding
        $channel->queue_bind(
            $queue,
            $exchange,
            $routingKey
        );

        $channel->close();
        $connection->close();
    }
}
