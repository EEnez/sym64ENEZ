<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        EmailService $emailService
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $token = bin2hex(random_bytes(32));
            $user->setVerificationToken($token);
            $user->setVerificationTokenExpiresAt(new \DateTime('+24 hours'));

            $entityManager->persist($user);
            $entityManager->flush();

            $emailService->sendVerificationEmail($user->getEmail(), $token);

            $this->addFlash('success', 'Un email de confirmation vous a été envoyé.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyEmail(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $token = $request->query->get('token');
        $user = $entityManager->getRepository(User::class)->findOneBy([
            'verificationToken' => $token
        ]);

        if (!$user) {
            throw $this->createNotFoundException('Token invalide');
        }

        if ($user->getVerificationTokenExpiresAt() < new \DateTime()) {
            throw $this->createNotFoundException('Token expiré');
        }

        $user->setIsVerified(true);
        $user->setVerificationToken(null);
        $user->setVerificationTokenExpiresAt(null);

        $entityManager->flush();

        $this->addFlash('success', 'Votre compte a été vérifié avec succès !');
        return $this->redirectToRoute('app_login');
    }
} 