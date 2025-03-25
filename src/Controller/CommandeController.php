<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\ProduitCommande;
use App\Services\PanierService;
use Doctrine\ORM\EntityManager;
use App\Services\CommandeService;
 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CommandeController extends AbstractController
{
    /**
     * 
     */
    #[Route('/commande/preparation', name: 'app_commande_preparation')]
    public function preparationCommande(PanierService $panierService): Response
        {
            $user = $this->getUser();
            if(!$user) {
                return $this->redirectToRoute('app_login');
            }
            $panier = $panierService->getPanierComplet();
            
            
            if(empty($panier)) {
                $this->addFlash('error', 'Votre panier est vide');
                return $this->redirectToRoute('app_panier');
            }
            // Calcul du total
            $total = 0;
            foreach ($panier as $item) {
                $total += $item['produit']->getPrix() * $item['quantite'];
            }

            return $this->render('commande/preparation.html.twig', [
                'panier' => $panier,
                'total' => $total // Ajout du total
                
            ]);
        }




    #[Route('/commande', name: 'app_commande', methods: ['POST'])]
    public function creerCommande( CommandeService $commandeService,EntityManagerInterface $entityManager): Response
    {
    /**
     * @var mixed
     * si l'utilisateur n'est pas connecté , on le redirige vers la page login ou s'enregistrer
     */

        $user = $this->getUser();
        if(!$user)
        {
            return $this->redirectToRoute('app_login'); 
            // Rediriger si l'utilisateur n'est pas connecté pour
            // qu'il se connecte.
        }
        // Créer la commande via le service
        try 
        {
            $commande = $commandeService->creerCommande($user);
            
            return $this->redirectToRoute('app_commande_confirmation',
                        ['idCommande' => $commande->getId()]);   
        }  
            catch (\Exception $e) 
            {
                // Gérer les erreurs (par exemple, panier vide)
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('app_panier');
            }
    }
    


    #[Route('/commande/valider', name: 'app_commande_valider', methods: ['POST'])]
    public function validerCommande(Request $request,CommandeService $commandeService ,EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login'); // Rediriger vers la connexion si non connecté
        }
        try {
             // Récupère la commande existante au lieu d'en créer une nouvelle
             $commande = $this->$entityManager->getRepository(Commande::class)->findOneBy([
                'user' => $user,
                'statut' => Commande::STATUT_EN_COURS
            ]);
        
            // 2-Récupérer le mode de livraison choisi
            $modeLivraison = $request->request->get('mode_livraison');
            $commandeService->validerCommande($commande, $modeLivraison);
          // $commande = $commandeService->validerCommande($user, $modeLivraison);
             // 3. Rediriger vers paiement
            return $this->redirectToRoute('app_paiement', [
            'idCommande' => $commande->getId()
        ]);
            
            
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_commande_preparation');
        }
       

        // Récupérer la commande en cours de l'utilisateur
        //$commande = $entityManager->getRepository(Commande::class)->findOneBy([
          //  'user' => $user,
            //'statut' => 'en cours'
          //  'statut'=> Commande::STATUT_EN_COURS
        //]);

       // if (!$commande) {
            //prevenir l'utilisateur qu'il n'a pas trouveé de commande en cours
          //  $this->addFlash('error', 'Aucune commande en cours trouvée.');
           // Si pas de commande, retour à la page commande
           // return $this->redirectToRoute('app_commande'); 
      //  }
       // try {
            // Valider la commande via le service
         //   $commandeService->validerCommande($commande, $modeLivraison);
            // Rediriger vers la page de confirmation
          //  return $this->redirectToRoute('app_paiement', ['idCommande' => $commande->getId()]);
      //  }// catch (\Exception $e) {
          //  $this->addFlash('error', $e->getMessage());
          //  return $this->redirectToRoute('app_commande');
      //  }

    }


    #[Route('/commande/confirmation/{id}', name: 'app_commande_confirmation')]
    public function confirmation(Commande $commande): Response
    {
        // Vérifier que la commande appartient bien à l'utilisateur connecté
        if ($commande->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Accès interdit.');
        }

        return $this->render('commande/confirmation.html.twig', [
            'commande' => $commande,
        ]);
    }

    
    /*#[Route('/commande/confirmer/{id}', name: 'app_commande_confirmer', methods: ['POST'])]
    public function confirmerCommande(int $id, CommandeService $commandeService ,EntityManagerInterface $entityManager): Response
    {
        // Récupérer la commande
        $commande = $entityManager->getRepository(Commande::class)->find($id);

        // Vérifier que la commande existe et appartient bien à l'utilisateur connecté
        if (!$commande || $commande->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException('Commande introuvable ou accès interdit.');
        }

        try {
            // Confirmer la commande via le service
            $commandeService->confirmerCommande($commande);
    
            // Rediriger vers la page de confirmation finale
            return $this->redirectToRoute('app_commande_finale', ['id' => $commande->getId()]);
        }catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_commande_confirmation', ['id' => $commande->getId()]);
        }
         
    }*/

    #[Route('/commande/finale/{id}', name: 'app_commande_finale')]
    public function commandeFinale(Commande $commande): Response
    {
        // Vérifier que la commande appartient bien à l'utilisateur connecté
        if ($commande->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Accès interdit.');
        }

        return $this->render('commande/finale.html.twig', [
            'commande' => $commande,
        ]);
    }
    
        
    
    /*#[Route('/commande/valider', name: 'app_commande_valider')]
public function validerCommande(EntityManagerInterface $entityManager, Security $security): Response
{
    // Récupérer l'utilisateur connecté
    $user = $security->getUser();

    if (!$user) {
        return $this->redirectToRoute('app_login'); // Rediriger vers la connexion si non connecté
    }

    // Récupérer la commande en cours de l'utilisateur
    $commande = $entityManager->getRepository(Commande::class)->findOneBy([
        'user' => $user,
        'statut' => 'en cours'
    ]);

    if (!$commande) {
        return $this->redirectToRoute('app_commande'); // Si pas de commande, retour à la page commande
    }

    // Mettre à jour le statut de la commande
    $commande->setStatut('en attente de paiement');
    $entityManager->flush();

    // Rediriger vers la page de paiement
    return $this->redirectToRoute('app_paiement');
}*/

 
#[Route('/paiement/{idCommande}', name: 'app_paiement')]
public function choixPaiement(int $idCommande, EntityManagerInterface $entityManager,Security $security): Response
{
    // Récupérer la commande
    $commande = $entityManager->getRepository(Commande::class)->find($idCommande);

    // Vérifier que la commande existe et appartient bien à l'utilisateur connecté
    if (!$commande || $commande->getUser() !== $this->getUser()) {
        throw $this->createNotFoundException('Commande introuvable ou accès interdit.');
    }
    

    // Afficher la page de choix du paiement
    return $this->render('commande/paiement.html.twig', [
        'commande' => $commande,
    ]);
}
#[Route('/paiement/valider/{idCommande}', name: 'app_paiement_valider', methods: ['POST'])]
public function validerPaiement(int $idCommande, Request $request, EntityManagerInterface $entityManager,CommandeService $commandeService): Response
{
    // Récupérer la commande
    $commande = $entityManager->getRepository(Commande::class)->find($idCommande);
    //dump($commande);
   // die();
    

    // Vérifier que la commande existe et appartient bien à l'utilisateur connecté
    if (!$commande || $commande->getUser() !== $this->getUser()) {
        throw $this->createNotFoundException('Commande introuvable ou accès interdit.');
    }

    // Traiter le paiement (exemple simplifié)
    try {
        // Ici, je pourrais appeler un service de paiement (Stripe, PayPal, etc.)
        // Pour l'instant, on simule un paiement réussi
         // 1. Appeler le service pour valider le paiement
         $commandeService->validerPaiement($commande, $request->request->get('mode_paiement'));
         // Exemple de statut après paiement
         // 2. Rediriger vers la confirmation
        // Rediriger vers une page de confirmation de paiement
        return $this->redirectToRoute('app_commande_finale', ['id' => $commande->getId()]);

        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors du paiement.');
            return $this->redirectToRoute('app_paiement', ['idCommande' => $commande->getId()]);
        }
         
}

    
}
