<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\ProduitCommande;
use Doctrine\ORM\EntityManager;
use App\Service\Panier\PanierService;
use Doctrine\ORM\EntityManagerInterface;
 
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function creerCommande(PanierService $panierService,EntityManagerInterface $entityManager): Response
    {
        // récuperer l'utilisateur connecter et le panier en session 
        $user = $this->getUser();
        $produitsPanier = $panierService->getPanierComplet();
       
        // Créer une nouvelle commande
        $commande = new Commande();
        $commande->setUser($user); // Associer l'utilisateur à la commande
        $commande->setStatut(Commande::STATUT_EN_COURS);// mettre en place un statut par defaut 
        $commande->setDateCommande(new \DateTime());
            // Enregistrer la commande dans la base de données
        $entityManager->persist($commande);
        $entityManager->flush();
        
        // Ajouter les produits dans la commande (ProduitCommande)
        
        foreach ($produitsPanier as $item) {
            $produitCommande = new ProduitCommande();
            $produitCommande->setCommande($commande);
            $produitCommande->setProduit($item['produit']);
            $produitCommande->setQuantite($item['quantite']);
            
            $entityManager->persist($produitCommande);
        }
            $entityManager->flush(); 
             // Vider le panier dans la session après avoir créé la commande
            // Vide le panier après la commande
        
            // $panierService->viderPanier();

        return $this->render('commande/index.html.twig', [
             'items'=> $produitsPanier
        ]);
         
         
    }
    #[Route('/commande/valider', name: 'app_commande_valider')]
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
}

 
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

    
}
