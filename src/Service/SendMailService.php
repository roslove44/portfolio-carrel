<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendMailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(string $from, string $to, string $subject, string $template, array $context, string $replyTo = null): void
    {
        // On crée le mail 
        if ($replyTo) {
            $email = (new TemplatedEmail())
                ->from($from)->to($to)
                ->subject($subject)
                ->htmlTemplate("email/$template.html.twig")
                ->replyTo($replyTo)
                ->context($context);
        } else {
            $email = (new TemplatedEmail())
                ->from($from)->to($to)
                ->subject($subject)
                ->htmlTemplate("email/$template.html.twig")
                ->context($context);
        }


        // On envoie le mail 
        $this->mailer->send($email);
    }
}
