<?php

namespace AppBundle\Service;

use AppBundle\Event\OrderEvent;
use AppBundle\Event\OrderEvents;
use AppBundle\Service\Sms;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Notification implements EventSubscriberInterface
{
    private $twig;
    private $mailer;
    private $twilio;
    private $ownerRecipient;
    private $sender;

    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer, Sms $twilio, $ownerRecipient, $sender)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->twilio = $twilio;
        $this->ownerRecipient = $ownerRecipient;
        $this->sender = $sender;
    }

    public function onOrderCreatedEvent(OrderEvent $event)
    {
        $order = $event->getOrder();

        // email for owner
        $this->sendMail(
            $this->ownerRecipient,
            '_mail/owner_notification.html.twig',
            array('order' => $order)
        );

        // email for customer
        $this->sendMail(
            array($order->getEmail() => $order->getFullname()),
            '_mail/customer_notification.html.twig',
            array('order' => $order)
        );

        // sms for owner
        $this->twilio->notifyOwner($this->twig->render(
            '_sms/owner_notification.html.twig',
            array('order' => $order)
        ));
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            OrderEvents::ORDER_CREATED => 'onOrderCreatedEvent'
        );
    }

    private function sendMail($recipient, $template, array $parameters = array())
    {
        $message = $this->getMessage($template, $parameters);
        $message->setFrom($this->sender);
        $message->setTo($recipient);
        $this->mailer->send($message);
    }

    public function getMessage($template, $parameters = array())
    {
        $template = $this->twig->loadTemplate($template);

        $parameters = array_merge($this->twig->getGlobals(), $parameters);

        $subject  = $template->renderBlock('subject',   $parameters);
        $bodyHtml = $template->renderBlock('body_html', $parameters);
        $bodyText = $template->renderBlock('body_text', $parameters);

        return \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setBody($bodyText, 'text/plain')
            ->addPart($bodyHtml, 'text/html')
        ;
    }
}
