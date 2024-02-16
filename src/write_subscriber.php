<?php

require 'DatabaseConnection.php';
require 'RedisCache.php';
require 'RabbitMQQueue.php';
require 'SubscriberRepository.php';
require 'SubscriberService.php';
require 'SubscriberController.php';

// Database Connection
$database = new DatabaseConnection();

// Cache and Queue
$cache = new RedisCache();
$queue = new RabbitMQQueue();

// Repository, Service, and Controller
$repository = new SubscriberRepository($database, $cache, $queue);
$service = new SubscriberService($repository);
$controller = new SubscriberController($service);

// Response array to be encoded as JSON
$response = [];

// Get JSON data from the request body
$jsonData = file_get_contents('php://input');

// Validate if JSON data is present
if (empty($jsonData)) {
    $response['success'] = false;
    $response['error'] = 'No data received';
} else {
    // Decode the JSON data
    $data = json_decode($jsonData, true);

    // Validate required fields
    if (!isset($data['email']) || !isset($data['name']) || !isset($data['lastName']) || !isset($data['status'])) {
        $response['success'] = false;
        $response['error'] = 'Required fields are missing';
    } else {
        // Assume parameters from JSON data
        $email = $data['email'];
        $name = $data['name'];
        $lastName = $data['lastName'];
        $status = $data['status'];

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['success'] = false;
            $response['error'] = 'Invalid email format';
        } else {
            // Handle write subscriber request
            try {
                $controller->handleWriteSubscriberRequest($email, $name, $lastName, $status);
                $response['success'] = true;
                $response['message'] = 'Subscriber successfully written';
            } catch (Exception $e) {
                $response['success'] = false;
                $response['error'] = 'Failed to write subscriber';
                $response['message'] = $e->getMessage();
            }
        }
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);

// Close RabbitMQ connection
$queue->close();
