<?php
namespace App\Console\Commands;

use App\Services\RabbitMQService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:setup-rabbit-m-q')]
#[Description('Command description')]
class SetupRabbitMQ extends Command {
    /**
     * composer require php-amqplib/php-amqplib:3.7.4
     * Execute the console command.
     */
    public function handle(RabbitMQService $rabbitMQ): int {
        $rabbitMQ->setup(
            'orders',        // exchange name
            'order.created', // queue name
            'order.created'  // routing key
        );

        $this->info('RabbitMQ setup completed successfully.');

        return self::SUCCESS;
    }
}
