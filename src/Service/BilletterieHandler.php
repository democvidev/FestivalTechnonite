<?php

namespace App\Service;

class BilletterieHandler
{
    public function handle($emailTitle, $emailTo, $emailBody, $userEmail): \Swift_Message
    {
        $message = new \Swift_Message($emailTitle);
        return $message->setFrom($userEmail)
            ->setTo($emailTo)
            ->setBody(
                $emailBody,
                'text/html'
            );
    }
}
