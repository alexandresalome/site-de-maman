<?php

namespace App\Service\Sms;

interface SmsInterface
{
    public function notifyOwner(string $message): void;
}
