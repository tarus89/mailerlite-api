<?php

class SubscriberRepository
{
    private $db;
    private $cache;
    private $queue;

    public function __construct(DatabaseConnection $db, CacheInterface $cache, QueueInterface $queue)
    {
        $this->db = $db->getConnection();
        $this->cache = $cache;
        $this->queue = $queue;
    }

    public function writeSubscriber($email, $name, $lastName, $status)
    {
        $query = "SELECT * FROM subscribers WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $this->updateSubscriberStatus($email, $status);
        } else {
            $this->insertSubscriber($email, $name, $lastName, $status);
        }

        $stmt->close();

        // Invalidate Redis cache for this email
        $this->cache->del("subscriber:$email");

        // Publish a message to RabbitMQ queue for further processing
        $messageBody = json_encode(['email' => $email, 'action' => 'write']);
        $this->queue->publish('write_queue', $messageBody);
    }

    private function updateSubscriberStatus($email, $status)
    {
        $updateQuery = "UPDATE subscribers SET status = ? WHERE email = ?";
        $updateStmt = $this->db->prepare($updateQuery);
        $updateStmt->bind_param("ss", $status, $email);
        $updateStmt->execute();
        $updateStmt->close();
    }

    private function insertSubscriber($email, $name, $lastName, $status)
    {
        $insertQuery = "INSERT INTO subscribers (email, name, last_name, status) VALUES (?, ?, ?, ?)";
        $insertStmt = $this->db->prepare($insertQuery);
        $insertStmt->bind_param("ssss", $email, $name, $lastName, $status);
        $insertStmt->execute();
        $insertStmt->close();
    }

    public function retrieveSubscriber($email)
    {
        // Implementation for retrieving subscriber from the database
        $stmt = $this->db->prepare("SELECT * FROM subscribers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }
}

