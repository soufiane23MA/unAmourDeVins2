<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/commande', name: 'admin_commande_')] // Tous les noms de routes commenceront par 'admin_commande_'
#[IsGranted('ROLE_ADMIN')]
final class OrderController extends AbstractController
{
    // tu laisse cette methode pour créer l'affichage de tous les commande dans le dashbord
    #[Route('/', name: 'index')]
    public function index(CommandeRepository $commandeRepository): Response
    {
        $commande= $commandeRepository->findAll();
        
        return $this->render('admin/commande/index.html.twig', [
             'commande'=> $commande,
        ]);
    }
    // methdoe pour afficher une seule commande  quand on clique sur un button par exemple
    #[Route('/{id}', name: 'show')]
    public function showCommade(Commande $commande):Response
    {
        //je récupére la facture liér à cette commande , il peux returner NULL 
        $facture = $commande->getFacture();
        
        return $this->render('admin/commande/show.html.twig', [
            'commande' => $commande,
            'facture' => $facture, // Passé au template 
        ]);

    }
}
