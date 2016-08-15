<?php

namespace AppBundle\Service\Dev;

use AppBundle\Service\Twilio as BaseTwilio;
use Psr\Log\LoggerInterface;

class Twilio extends BaseTwilio
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function notifyOwner($message)
    {
        $this->logger->info('SMS - Notified owner with content: '.$message);
    }
}
