<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
    public function forgotPassword(Request $request,EntityManagerInterface $em,MailerInterface $mailer): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($user) {
                // Générer un token aléatoire
                $token = bin2hex(random_bytes(32));
                $user->setResetToken($token);
                $em->flush();
                // Générer le lien de réinitialisation
                $resetUrl = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                  // Envoyer l'email (Mailhog)
                  $emailMessage = (new Email())
                  ->from('no-reply@monsite.com')
                  ->to($user->getEmail())
                  ->subject('Réinitialisation de votre mot de passe')
                  ->html("<p>Bonjour,</p><p>Voici votre lien pour réinitialiser votre mot de passe : <a href=\"$resetUrl\">Réinitialiser</a></p>");

              $mailer->send($emailMessage);
              $this->addFlash('success', 'Un lien de réinitialisation a été envoyé à votre adresse email.');
              return $this->redirectToRoute('app_login');
          } else {
              $this->addFlash('danger', 'Aucun utilisateur trouvé avec cet email.');
          }
      }

      return $this->render('security/forgot_password.html.twig');
        // Logique pour gérer la demande de réinitialisation (envoi du lien, etc.)
        // Par exemple, vérifier si l'email existe, puis envoyer un email avec un token
        
      
    }
    /**
     * methode pour permettre à l'utilisateur de changer son mot de passe 
     */
    #[Route(path: '/reset-password/{token}', name: 'app_reset_password')]
        public function resetPassword(string $token, Request $request,EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // je Valider le token  
        // Récupérer l'utilisateur avec le token 
         
        $user = $entityManager->getRepository(User::class)->findOneBy(['resetToken' => $token]);
        if (!$user) {
            throw $this->createNotFoundException('Aucun utilisateur trouvé pour ce token.');
        }
        if ($request->isMethod('POST')) {
            $newPassword = $request->request->get('password');
            if ($newPassword) {
                $user->setPassword(
                    $passwordHasher->hashPassword($user, $newPassword)
                );
                $user->setResetToken(null); // on supprime le token
                $entityManager->flush();
                $this->addFlash('success', 'Mot de passe modifié avec succès.');
                return $this->redirectToRoute('app_login');
            }
        }
        return $this->render('security/reset_password.html.twig', [
            'token' => $token,
        ]);

                // Afficher le formulaire pour le nouveau mot de passe

    }
    








}

















