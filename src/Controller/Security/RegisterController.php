<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app.register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordEncoder,
        UserRepository $repo
    ) {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password
            $user->setPassword(
                $passwordEncoder->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $repo->save($user, true);
            $this->addFlash('success', 'Vous êtes bien inscrit à notre application');
            return $this->redirectToRoute('login');
        }
        return $this->render('Security/register.html.twig', [
            'form' => $form,
        ]);
    }
}