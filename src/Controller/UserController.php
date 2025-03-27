<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/profile', name: 'app_user_profile')]
    public function profile(Security $security,CommandeRepository $commandeRepository): Response
    {
        $user = $security->getUser();  // Obtenez l'utilisateur connecté
        $commandes= $commandeRepository->findBy(['user'=> $user]);

        
        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'commandes'=>$commandes,
        ]);
    }
}
