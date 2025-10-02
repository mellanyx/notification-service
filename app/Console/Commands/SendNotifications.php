<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class SendNotifications extends Command
{
    protected $signature = 'notification:send {msg} {email?}';
    protected $description = 'Send notifications into RabbitMQ';

    /**
     * @throws Exception
     */
    public function handle(): void {
        $data = [
            'sender_email' => config('mail.from.address'),
            'recipient_email' => $this->argument('email'),
            'message' => $this->argument('msg')
        ];

        $connection = new AMQPStreamConnection('notification-service-rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('notifications', false, true, false, false);

        $msg = new AMQPMessage(json_encode($data), [
            'content_type' => 'application/json'
        ]);

        $channel->basic_publish($msg, '', 'notifications');

        $channel->close();
        $connection->close();
    }
}
