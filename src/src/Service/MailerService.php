<?php

namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class MailerService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function testSmtpConnection(string $smtpHost, int $smtpPort, string $smtpUsername, string $smtpPassword, string $email): bool
    {
        // Créez le transport SMTP avec le nom d'utilisateur et le mot de passe inclus dans l'URL
        $transport = Transport::fromDsn('smtp://'.$smtpUsername.':'.$smtpPassword.'@'.$smtpHost.':'.$smtpPort.'?encryption=tls');

        // Créez le service Mailer avec le transport SMTP configuré
        $mailer = new Mailer($transport);

        // Créez un objet Email
        $emailObject = (new Email())
            ->from($email)
            ->to($email)
            ->subject('SMTP Testing')
            ->text('SMTP is valid !')
            ->html('<p>return checking the next steps of restoflash installation</p>');

        try {
            // Envoyez l'e-mail
            $mailer->send($emailObject);

            // Si le code atteint ce point, la connexion SMTP a réussi
            return true;
        } catch (TransportExceptionInterface $e) {
            // En cas d'erreur, affichez le message d'erreur
            return false;
        }
    }
}
