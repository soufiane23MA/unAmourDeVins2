<?php

namespace App\Controller;

use App\Repository\PlatRepository;
use App\Repository\AccordRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PlatController extends AbstractController
{
    #[Route('/plats', name: 'app_plats')]
    public function index(): Response
    {
        return $this->render('plats/index.html.twig', [
            'controller_name' => 'PlatController',
        ]);
    }
    #[Route('/api/plats', name: 'plats_list', methods: ['GET'])]
    public function getPlats(PlatRepository $platRepository): JsonResponse
    {
        // Récupérer tous les plats
        $plats = $platRepository->findAll();

        // Transforme les plats en tableau associatif pour renvoyer sous forme de JSON
        $platsData = [];
        foreach ($plats as $plat) {
            $platsData[] = [
                'id' => $plat->getId(),
                'name' => $plat->getNomPlat(), // Assurez-vous que vous avez la bonne méthode dans votre entité Plat
            ];
        }

        return new JsonResponse($platsData);
    }
    #[Route('/api/accords/{platId}', name: 'plats_accords', methods: ['GET'])]
public function getAccordsByPlat($platId, AccordRepository $accordRepository): JsonResponse
{
    
    // Récupérer les produits associés au plat
    $accords = $accordRepository->findProduitsByPlat($platId);
    
   /* return $this->render('plats/produits.html.twig', [
        'accords' => $accords,
    ]);*/

    // Transformer les produits en un tableau pour le JSON
    $produitsData = [];
    foreach ($accords as $accord) {
        $produitsData[] = [
            'id' => $accord->getProduit()->getId(),
            'name' => $accord->getProduit()->getNomProduit(), 
            'plat' =>$accord->getPlat()->getNomPlat() ,// Assurez-vous que cette méthode existe
             
        ];
    }

    return new JsonResponse($produitsData);
}
// src/Controller/PlatController.php
#[Route('/plats/{platId}/produits', name: 'app_plats_produits')]
public function produits(int $platId, PlatRepository $platRepository, AccordRepository $accordRepository): Response
{
    // Récupère le plat
    $plat = $platRepository->find($platId);
    if (!$plat) {
        throw $this->createNotFoundException('Plat non trouvé');
    }

    // Récupère les produits associés au plat
    //$produits = $accordRepository->findProduitsByPlat($platId);
    // Récupère les accords (produits associés au plat)
    $accords = $accordRepository->findBy(['plat' => $plat]);
    $produits =[];
    foreach ($accords as $accord) {
        $produit = $accord->getProduit(); //pour recuperer tous les attributs du produit
        $produits[] = [
            'id' => $accord->getProduit()->getId(),
            'name' => $accord->getProduit()->getNomProduit(), 
            'image' => $produit->getImage(), // Image du produit
            'detail' => $produit->getDetailProduit(), // Détail du produit
            'prix' => $produit->getPrix()
        ];
    }


    return $this->render('plats/produits.html.twig', [
        'plat' => $plat,
        'produits' => $produits,
    ]);
}



}
