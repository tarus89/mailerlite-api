<?php

require 'DatabaseConnection.php';
require 'RedisCache.php';
require 'SubscriberRepository.php';
require 'SubscriberService.php';
require 'SubscriberController.php';

// Handle retrieve subscriber request

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

if (!isset($_GET['email'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Email parameter is required']);
    exit;
}

// Database Connection
$database = new DatabaseConnection();

// Cache
$cache = new RedisCache();
// Placeholder for RabbitMQ queue
$queue = new RabbitMQQueue(); // Replace with the actual RabbitMQ queue if needed
// Repository, Service, and Controller
$repository = new SubscriberRepository($database, $cache, $queue);
$service = new SubscriberService($repository);
$controller = new SubscriberController($service);

// Retrieve subscriber by email
$email = $_GET['email'];

try {
    $result = $controller->handleRetrieveSubscriberRequest($email);

    if ($result) {
        // Return the result as JSON
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        // Subscriber not found
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'Subscriber not found']);
    }
} catch (Exception $e) {
    // Internal Server Error
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Internal Server Error']);
}
