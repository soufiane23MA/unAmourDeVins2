<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $produits = $entityManager->getRepository(Produit::class)->findAll();
        return $this->render('produit/index.html.twig', [
            'produits'=>$produits
        ]);
    }
    #[Route('/produit/{id}', name: 'detail_produit')]
    public function affichDetailProduit(Produit $produit,int $id): Response
    {
        
        return $this->render('produit/detail.html.twig', [
            'produit'=>$produit
        ]);
    }
}
