<?php

namespace MailerLite;

class SubscriberController
{
    private $service;

    public function __construct(SubscriberService $service)
    {
        $this->service = $service;
    }

    public function handleWriteSubscriberRequest($email, $name, $lastName, $status)
    {
        try {
            $this->service->writeSubscriber($email, $name, $lastName, $status);
            // Return appropriate success response, e.g., HTTP 200 OK, JSON success response, etc.
            return ['success' => true, 'message' => 'Subscriber successfully written'];
        } catch (Exception $e) {
            // Return appropriate error response, e.g., HTTP 500 Internal Server Error, JSON error response, etc.
            return ['success' => false, 'error' => 'Failed to write subscriber', 'message' => $e->getMessage()];
        }
    }

    public function handleRetrieveSubscriberRequest($email)
    {
        try {
            $subscriber = $this->service->retrieveSubscriber($email);

            if ($subscriber) {
                // Return the retrieved subscriber as an associative array
                return $subscriber;
            } else {
                // Return appropriate error response, e.g., HTTP 404 Not Found, JSON error response, etc.
                return ['error' => 'Subscriber not found'];
            }
        } catch (Exception $e) {
            // Return appropriate error response, e.g., HTTP 500 Internal Server Error, JSON error response, etc.
            return ['error' => 'Failed to retrieve subscriber', 'message' => $e->getMessage()];
        }
    }
}
