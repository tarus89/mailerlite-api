<?php

namespace MailerLite;
class SubscriberService
{
    private $repository;

    public function __construct(SubscriberRepository $repository)
    {
        $this->repository = $repository;
    }

    public function writeSubscriber($email, $name, $lastName, $status)
    {
        // validations etc
        $this->repository->writeSubscriber($email, $name, $lastName, $status);
    }

    public function retrieveSubscriber($email)
    {
        // validations etc
        return $this->repository->retrieveSubscriber($email);
    }
}