<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailController extends AbstractController
{
    public function sendEmail(MailerInterface $mailer): Response
    {
        // Créer un nouvel email
        $email = (new Email())
            ->from('no-reply@example.com')
            ->to('t.diaz@it-students.fr')
            ->subject('Notification : Nouvel Ajout de Prestataire')
            ->text('Un nouveau prestataire a été ajouté.')
            ->html('<p>Un nouveau prestataire a été ajouté à la base de données.</p>');

        // Envoyer l'email
        $mailer->send($email);

        return new Response('Email envoyé avec succès.');
    }
}
