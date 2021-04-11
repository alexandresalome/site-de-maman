<?php

namespace App\Service;

use App\Entity\Order;
use App\Event\OrderCreatedEvent;
use App\Service\Sms\SmsInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Notification implements EventSubscriberInterface
{
    private MailerInterface $mailer;
    private SmsInterface $sms;
    private string $ownerEmail;

    public function __construct(MailerInterface $mailer, SmsInterface $sms, string $ownerEmail)
    {
        $this->mailer = $mailer;
        $this->sms = $sms;
        $this->ownerEmail = $ownerEmail;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderCreatedEvent::class => 'onOrderCreatedEvent'
        ];
    }

    public function onOrderCreatedEvent(OrderCreatedEvent $event): void
    {
        $order = $event->getOrder();

        $this->notifyOwnerByMail($order);
        $this->notifyOwnerBySms($order);
        $this->notifyCustomerByMail($order);
    }

    private function notifyCustomerByMail(Order $order): void
    {
        $email = (new TemplatedEmail())
            ->subject('Réception de votre commande')
            ->to(new Address(
                $order->getEmail(),
                $order->getFullname()
            ))
            ->htmlTemplate('_mail/customer_notification.html.twig')
            ->textTemplate('_mail/customer_notification.text.twig')
            ->context(['order' => $order])
        ;

        $this->mailer->send($email);
    }

    private function notifyOwnerByMail(Order $order): void
    {
        $email = (new TemplatedEmail())
            ->subject('Nouvelle commande de '.$order->getFullname().' pour le '.$order->getDate())
            ->to($this->ownerEmail)
            ->htmlTemplate('_mail/owner_notification.html.twig')
            ->textTemplate('_mail/owner_notification.text.twig')
            ->context(['order' => $order])
        ;

        $this->mailer->send($email);
    }

    private function notifyOwnerBySms(Order $order): void
    {
        $price = $order->getOrder()['total_price'];
        $this->sms->notifyOwner(
            'Nouvelle commande de '.
            $order->getFullname().
            ' pour le '.
            $order->getDate().
            ' pour un montant total de '.
            $price[0].' '.$price[1]
        );
    }
}
