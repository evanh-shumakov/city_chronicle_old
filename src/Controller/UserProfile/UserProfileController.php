<?php

namespace App\Controller\UserProfile;

use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
    #[Route('/profile', name: 'user_profile')]
    public function index(): Response
    {
        return $this->render('user_profile/user_profile.html.twig', [
            'controller_name' => 'UserProfileController',
            'user' => $this->getUser(),
            'form' => $this->createForm(UserType::class, $this->getUser()),
        ]);
    }
}
