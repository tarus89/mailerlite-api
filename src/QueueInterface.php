<?php

namespace MailerLite;
interface QueueInterface
{
    public function publish($queue, $messageBody);

    public function close();

}
