<?php

namespace App\Controller\UserProfile;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
    #[Route('/profile', name: 'user_profile')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $hasher,
    ): Response {
        $user = $this->getUser();
        if (! $user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->handleForm($request, $user, $entityManager, $hasher);

        return $this->render('user_profile/user_profile.html.twig', [
            'controller_name' => 'UserProfileController',
            'user' => $user,
            'form' => $form,
        ]);
    }

    private function handleForm(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $hasher,
    ): FormInterface
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($newPassword = $form->get('newPassword')->getData()) {
                if (! is_string($newPassword)) {
                    throw new \InvalidArgumentException('Wrong password type');
                }
                $hashedPassword = $hasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
            }
            $entityManager->flush();
        }

        return $form;
    }
}
