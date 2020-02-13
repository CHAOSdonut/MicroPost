<?php

namespace App\Test\Mailer;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Mailer\Mailer;

class MailerTest extends TestCase
{
    public function testConfirmationEmail()
    {
        $user = new User();
        $user->setEmail('john@doe.com');

        $swiftMailer = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $swiftMailer->expects($this->once())->method('send')
            ->with($this->callback(function ($subject){
                $messageStr = (string)$subject;
                dump($messageStr);
                return strpos($messageStr, "From: me@domain.com") !== false
                    && strpos($messageStr, "Content-Type: text/html; charset=utf-8") !== false
                    && strpos($messageStr, "Subject: Welkom to the micro-post app!") !== false
                    && strpos($messageStr, "To: john@doe.com") !== false
                    && strpos($messageStr, 'This is a message body') !== false;
            }));

        $twigMock = $this->getMockBuilder(\Twig\Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
        $twigMock->expects($this->once())->method('render')
            ->with('email/registration.html.twig',
                ['user' => $user])->willReturn('This is a message body');

        $mailer = new Mailer($twigMock, $swiftMailer, 'me@domain.com');
        $mailer->sendConfirmationEmail($user);
    }
}