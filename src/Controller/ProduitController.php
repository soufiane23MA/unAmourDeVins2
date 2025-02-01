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
    public function index(EntityManagerInterface $entityManager,ProduitRepository $repository): Response
    {
       // $produits = $entityManager->getRepository(Produit::class)->findAll();
       $produits = $repository->findAll();
        return $this->render('produit/index.html.twig', [
            'produits'=>$produits
        ]);
    }

   // #[Route('/produit/{id}', name: 'detail_produit')]
    /*public function affichDetailProduit(Produit $produit): Response
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
     
    
}
