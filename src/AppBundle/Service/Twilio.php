<?php

namespace AppBundle\Service;

use Psr\Log\LoggerInterface;

class Twilio
{
    private $logger;
    private $client;
    private $twilioFrom;
    private $twilioTo;

    public function __construct(LoggerInterface $logger, \Services_Twilio $client, $twilioFrom, $twilioTo)
    {
        $this->logger = $logger;
        $this->client = $client;
        $this->twilioFrom = $twilioFrom;
        $this->twilioTo = $twilioTo;
    }

    public function notifyOwner($message)
    {
        try {
            $this->client->account->messages->sendMessage(
                $this->twilioFrom,
                $this->twilioTo,
                $message
            );
        } catch (\Exception $e) {
            $this->logger->error('Unable to send notification SMS : '.$e->getMessage());
        }
    }
}
