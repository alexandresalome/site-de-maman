<?php

namespace App\Service\Sms;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class FreeSms implements SmsInterface
{
    private string $user;
    private string $password;
    private LoggerInterface $logger;

    public function __construct(string $user, string $password, ?LoggerInterface $logger = null)
    {
        $this->user = $user;
        $this->password = $password;
        $this->logger = $logger ?? new NullLogger();
    }

    public function notifyOwner($message): void
    {
        $url = 'https://smsapi.free-mobile.fr/sendmsg?user='
            .urlencode($this->user)
            .'&pass='.urlencode($this->password)
            .'&msg='.urlencode($message)
        ;

        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
        $content = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        if ($httpCode !== 200) {
            $this->logger->error(sprintf(
                "Error while sending SMS, API returned code %s with the following content:\n%s",
                $httpCode,
                $content
            ));
        }
    }
}
