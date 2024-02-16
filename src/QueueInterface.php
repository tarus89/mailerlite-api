<?php

interface QueueInterface
{
    public function publish($queue, $messageBody);

    public function close();

}
