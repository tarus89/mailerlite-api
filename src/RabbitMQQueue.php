<?php
require 'env_loader.php';

class RabbitMQQueue implements QueueInterface
{
    private $connection;
    private $channel;

    public function __construct()
    {
        $host = $_ENV['RABBITMQ_HOST'];
        $port = $_ENV['RABBITMQ_PORT'];
        $user = $_ENV['RABBITMQ_USER'];
        $password = $_ENV['RABBITMQ_PASSWORD'];

        $this->connection = new \PhpAmqpLib\Connection\AMQPStreamConnection($host, $port, $user, $password);
        $this->channel = $this->connection->channel();
    }

    public function publish($queue, $messageBody)
    {
        $this->channel->queue_declare($queue, false, false, false, false);

        $message = new \PhpAmqpLib\Message\AMQPMessage($messageBody);
        $this->channel->basic_publish($message, '', $queue);
    }

    public function close()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
