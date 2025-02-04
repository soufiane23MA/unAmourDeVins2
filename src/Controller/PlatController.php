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

    // Transformer les produits en un tableau pour le JSON
    $produitsData = [];
    foreach ($accords as $accord) {
        $produitsData[] = [
            'id' => $accord->getProduit()->getId(),
            'name' => $accord->getProduit()->getNomProduit(), // Assurez-vous que cette méthode existe
        ];
    }

    return new JsonResponse($produitsData);
}



}
