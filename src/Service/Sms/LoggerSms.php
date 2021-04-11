<?php

namespace App\Service\Sms;

use Psr\Log\LoggerInterface;

class LoggerSms implements SmsInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function notifyOwner(string $message): void
    {
        $this->logger->notice('Sending SMS: "{message}"', ['message' => $message]);
    }
}
