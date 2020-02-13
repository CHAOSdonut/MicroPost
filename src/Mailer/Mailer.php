<?php

namespace App\Mailer;

use App\Entity\User;
use App\Event\UserRegisterEvent;

class Mailer
{
    /**
     * @var \Twig\Environment
     */
    private $twig;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var string
     */
    private $mailFrom;

    public function __construct(\Twig\Environment $twig, \Swift_Mailer $mailer, string $mailFrom)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->mailFrom = $mailFrom;
    }

    public function sendConfirmationEmail(User $user, UserRegisterEvent $event, string $mailFrom)
    {
        $body = $this->twig->render('email/registration.html.twig', [
            'user' => $user,
        ]);

        $message = (new \Swift_Message())
            ->setSubject('Welkom to the micro-post app!')
            ->setFrom($this->mailFrom)
            ->setTo($user->getEmail())
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }
}