<?php

namespace AppBundle\Service;

use AppBundle\Event\OrderEvent;
use AppBundle\Event\OrderEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Notification implements EventSubscriberInterface
{
    private $twig;
    private $mailer;
    private $ownerRecipient;

    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer, $ownerRecipient)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->ownerRecipient = $ownerRecipient;
    }

    public function onOrderCreatedEvent(OrderEvent $event)
    {
        $order = $event->getOrder();

        // notification for owner
        $this->sendMail(
            $this->ownerRecipient,
            '_mail/owner_notification.html.twig',
            array('order' => $order)
        );

        // notification for customer
        $this->sendMail(
            array($order->getEmail() => $order->getFullname()),
            '_mail/customer_notification.html.twig',
            array('order' => $order)
        );
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
