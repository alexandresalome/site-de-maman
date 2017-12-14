<?php

namespace AppBundle\Service\Dev;

use AppBundle\Service\Sms as BaseSms;
use Psr\Log\LoggerInterface;

class Sms extends BaseSms
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
