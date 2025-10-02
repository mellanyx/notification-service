<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\Models\Notification;

class ConsumeNotifications extends Command
{
    protected $signature = 'notification:consume';
    protected $description = 'Consume notifications from RabbitMQ';

    /**
     * @throws Exception
     */
    public function handle(): void {
        $connection = new AMQPStreamConnection('notification-service-rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('notifications', false, true, false, false);

        $callback = function ($msg) {
            $data = json_decode($msg->body, true);

            if (isset($data['sender_email'], $data['recipient_email'], $data['message'])) {
                Notification::create($data);
                $this->info("Notification saved: {$data['recipient_email']}");
            } else {
                $this->error("Invalid message received");
            }
        };

        $channel->basic_consume('notifications', '', false, true, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }
}
