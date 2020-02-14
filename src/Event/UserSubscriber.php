<?php

namespace App\Event;

use App\Entity\UserPreferences;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Mailer\Mailer;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @var Mailer2
     */
    private $mailer2;

    public function __construct(\Swift_Mailer $mailer, EntityManagerInterface $entityManager, string $defaultLocale, Mailer $mailer2)
    {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->defaultLocale = $defaultLocale;
        $this->mailer2 = $mailer2;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisterEvent::NAME => 'onUserRegister'
        ];
    }

    public function onUserRegister(UserRegisterEvent $event)
    {
        $preferences = new UserPreferences();
        $preferences->setLocale($this->defaultLocale);

        $user = $event->getRegisteredUser();
        $user->setPreferences($preferences);

        $this->entityManager->flush();
        $this->mailer2->sendConfirmationEmail($event->getRegisteredUser());
    }
}