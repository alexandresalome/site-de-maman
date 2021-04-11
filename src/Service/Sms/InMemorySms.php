<?php

namespace App\Service\Sms;

class InMemorySms implements SmsInterface
{
    /** @var string[] */
    private array $messages = [];

    public function notifyOwner(string $message): void
    {
        $this->messages[] = $message;
    }

    /**
     * @return string[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
