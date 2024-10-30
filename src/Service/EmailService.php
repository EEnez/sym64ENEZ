<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig,
        private UrlGeneratorInterface $urlGenerator,
        private string $mailFrom
    ) {}

    public function sendVerificationEmail(string $to, string $token): void
    {
        $url = $this->urlGenerator->generate('app_verify_email', [
            'token' => $token
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $email = (new Email())
            ->from($this->mailFrom)
            ->to($to)
            ->subject('Vérification de votre compte')
            ->html($this->twig->render('emails/verification.html.twig', [
                'verificationUrl' => $url
            ]));

        $this->mailer->send($email);
    }
} 