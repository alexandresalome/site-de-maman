<?php

namespace AppBundle\Service\Dev;

use Psr\Log\LoggerInterface;

class Twilio extends \Services_Twilio
{
    private $logger;
    public $account;
    public $messages;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->account = $this;
        $this->messages = $this;
    }

    public function sendMessage($from, $to, $message)
    {
        $this->logger->info('Message sent to '.$to.' with content: '.$message);
    }
}
