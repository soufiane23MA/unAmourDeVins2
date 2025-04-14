<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils,Request $request): Response
    {

        $session = $request->getSession();
        
        if ($this->getUser()) {
          
            $panier = $session->get('panier', []);
        
            if (!empty($panier)) {
                return $this->redirectToRoute('app_panier'); // Rediriger vers le panier si des produits sont présents
            } else {
                return $this->redirectToRoute('app_produit'); // Rediriger vers la page produits quand le panier est vide
            }
        }
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // 

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

  /**
   * la methodepour genérer la rintialisation du mot de passe utilisateur 
   */

    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(Request $request): Response
    {
        // Logique pour gérer la demande de réinitialisation (envoi du lien, etc.)
        // Par exemple, vérifier si l'email existe, puis envoyer un email avec un token
        
        return $this->render('security/forgot_password.html.twig');
    }
}

















