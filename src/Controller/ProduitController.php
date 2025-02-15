<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\SearchType;
use App\Repository\AccordRepository;
use App\Repository\RegionRepository;
use App\Repository\DomaineRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(EntityManagerInterface $entityManager,ProduitRepository $repository): Response
    {
       // $produits = $entityManager->getRepository(Produit::class)->findAll();
       $produits = $repository->findAll();
        return $this->render('produit/index.html.twig', [
            'produits'=>$produits
        ]);
    }

    //#[Route('/produit/{id}', name: 'detail_produit')]
   /* public function affichDetailProduit(Produit $produit): Response
    {
       
        $domaine = $produit->getDomaine();
        $region = $domaine ? $domaine->getRegion(): null;
        return $this->render('produit/detail.html.twig', [
            'produit'=>$produit,
            'domaine'=>$domaine,
            'region'=>$region
        ]);
    }*/
    #[Route('/produit/{id}/detail', name: 'detail_produit')]
    public function affichDetailDomaineProduit(int $id,ProduitRepository $repository)
    {
        $produit = $repository->findProduitWithDomaineAndRegion( $id);
        $domaine = $produit->getDomaine();
        $region =  $domaine->getRegion() ;
        
        return  $this->render('produit/detail.html.twig', [
            'produit'=>$produit,
            'domaine'=>$domaine,
            'region'=>$region
        ]);
         
    }
     /*
     * methode pour récuperer les produits en vente exclusive
     */
    #[Route('/produit/exclusif', name: 'app_exclusif')]
    public function affichProduitsExclusifs( ProduitRepository $produitRepository)
    {
        //$produits = $produitRepository->findBy(['exclusif'=> true],['nomProduit'=>'ASC']); premiére methode 
        $produits = $produitRepository->findAllExclusifs();
        
        
        return $this ->render( 'produit/exclusif.html.twig',[  
             'produits'=> $produits,
             
              
             
        ]);   
    }
    #[Route('/produit/accords/{platId}', name: 'produits_accords', methods: ['GET'])]
    public function afficherProduitsAccords(int $platId, AccordRepository $accordRepository): Response
    {
        // Récupérer les produits associés au plat
        $accords = $accordRepository->findBy(['plat' => $platId]) ;
        
    
        // Passer les produits à la vue
        return  $this->render('produit/accords.html.twig', [
            'accords' => $accords,
           
        ]);
    }
    #[Route('/produit/regions', name: 'produit_regions', methods: ['GET'])]
    public function getRegions(RegionRepository $regionRepository): JsonResponse
    {
        $regions = $regionRepository->findAll();
        $data = [];
    
        foreach ($regions as $region) {
            $data[] = [
                'id' => $region->getId(),
                'nom' => $region->getNomRegion(),
            ];
        }
    
        return $this->json($data);
    }
    #[Route('/produit/regions/{regionId}/domaines', name: 'produit_domaines', methods: ['GET'])]
public function getDomaines(int $regionId, DomaineRepository $domaineRepository): JsonResponse
{
    $domaines = $domaineRepository->findBy(['region' => $regionId]);
    $data = [];

    foreach ($domaines as $domaine) {
        $data[] = [
            'id' => $domaine->getId(),
            'nom' => $domaine->getNom(),
        ];
    }

    return $this->json($data);
}
#[Route('/produit/domaines/{domaineId}/produits', name: 'produit_par_domaine', methods: ['GET'])]
public function getProduitsParDomaine(int $domaineId, ProduitRepository $produitRepository): JsonResponse
{
    $produits = $produitRepository->findBy(['domaine' => $domaineId]);
    $data = [];

    foreach ($produits as $produit) {
        $data[] = [
            'id' => $produit->getId(),
            'nom' => $produit->getNomProduit(),
            'prix' => $produit->getPrix(),
        ];
    }

    return $this->json($data);
}
    
}
