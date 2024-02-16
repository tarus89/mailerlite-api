<?php
require_once 'vendor/autoload.php';
require_once 'src/DatabaseConnection.php';
require_once 'src/RedisCache.php';
require_once 'src/RabbitMQQueue.php';
require_once 'src/SubscriberRepository.php';
require_once 'src/SubscriberService.php';
require_once 'src/SubscriberController.php';


use PHPUnit\Framework\TestCase;

class SubscriberTest extends TestCase
{

    // Test writing a subscriber
    public function testWriteSubscriber()
    {
        $databaseMock = $this->getMockBuilder(DatabaseConnection::class)->disableOriginalConstructor()->getMock();
        $cacheMock = $this->getMockBuilder(RedisCache::class)->getMock();
        $queueMock = $this->getMockBuilder(RabbitMQQueue::class)->getMock();

        // Create a mock for SubscriberRepository without constructor arguments
        $repositoryMock = $this->getMockBuilder(SubscriberRepository::class)->getMock();

        $service = new SubscriberService($repositoryMock);
        $controller = new SubscriberController($service);

        $email = 'test11@example.com';
        $name = 'John';
        $lastName = 'Doe';
        $status = 'active';

        $result = $controller->handleWriteSubscriberRequest($email, $name, $lastName, $status);

        $this->assertTrue($result['success']);
    }

    // Test retrieving a subscriber
    public function testRetrieveSubscriber()
    {
        $databaseMock = $this->getMockBuilder(DatabaseConnection::class)->disableOriginalConstructor()->getMock();
        $cacheMock = $this->getMockBuilder(RedisCache::class)->getMock();
        $queueMock = $this->getMockBuilder(RabbitMQQueue::class)->getMock();

        // Create a mock for SubscriberRepository without constructor arguments
        $repositoryMock = $this->getMockBuilder(SubscriberRepository::class)->getMock();

        // Use actual or test data for the retrieved subscriber
        $retrievedSubscriber = ['email' => 'test@example.com', 'name' => 'John', 'last_name' => 'Doe', 'status' => 'active'];

        $repositoryMock->method('retrieveSubscriber')->willReturn($retrievedSubscriber);

        $service = new SubscriberService($repositoryMock);
        $controller = new SubscriberController($service);

        $email = 'test11@gmail.com';

        $result = $controller->handleRetrieveSubscriberRequest($email);

        $this->assertEquals($retrievedSubscriber, $result);
    }
}
