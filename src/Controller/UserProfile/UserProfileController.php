<?php

namespace App\Controller\UserProfile;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserProfileController extends AbstractController
{
    #[Route('/profile', name: 'user_profile')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $hasher,
    ): Response {
        if (! $this->getUser() instanceof PasswordAuthenticatedUserInterface) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->handleForm($request, $entityManager, $hasher);

        return $this->render('user_profile/user_profile.html.twig', [
            'controller_name' => 'UserProfileController',
            'user' => $this->getUser(),
            'form' => $form,
        ]);
    }

    private function handleForm(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $hasher,
    ): FormInterface
    {
        $form = $this->createForm(UserType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($newPassword = $form->get('newPassword')->getData()) {
                $hashedPassword = $hasher->hashPassword($this->getUser(), $newPassword);
                $this->getUser()->setPassword($hashedPassword);
            }
            $entityManager->flush();
        }

        return $form;
    }
}
