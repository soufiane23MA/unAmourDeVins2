<?php

namespace App\Controller;

use App\Repository\PlatRepository;
use App\Repository\AccordRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AccordController extends AbstractController
{
    #[Route('/accord-mets-vins', name: 'app_accord_mets_vins')]
    public function accordMetsVins(PlatRepository $platRepository): Response
    {
         // je Récupére tous les plats
         $plats = $platRepository->findAll();

        return $this->render('accord/index.html.twig', [
             'plats'=>$plats // je passe tous les plats à la vue 
        ]);
    }
     /**
      * cette methode permet de récuper les 
      */
      #[Route('/api/accords/{platId}', name: 'accord_produits', methods: ['GET'])]
public function getAccordsByPlat($platId, AccordRepository $accordRepository): JsonResponse
{
    // Récupérer les produits associés au plat
    $accords = $accordRepository->findProduitsByPlat($platId);

    // Transformer les produits en un tableau pour le JSON
    $produitsData = [];
    foreach ($accords as $accord) {
        $produitsData[] = [
            'id' => $accord->getProduit()->getId(),
            'name' => $accord->getProduit()->getNomProduit(),
            'prix'=>$accord->getProduit()->getPrix(),
            'plat' => $accord->getPlat()->getNomPlat(),
            'image'=>$accord->getProduit()->getImage(),
            'detail'=>$accord->getproduit()->getDetailProduit()
        ];
    }

    return new JsonResponse($produitsData);
}
}
