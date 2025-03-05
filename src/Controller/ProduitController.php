<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\SearchType;
use App\Repository\AccordRepository;
use App\Repository\RegionRepository;
use App\Repository\DomaineRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(Request $request, EntityManagerInterface $entityManager,PaginatorInterface $paginator, ProduitRepository $repository,RegionRepository $regionRepository): Response
    {
       // $produits = $entityManager->getRepository(Produit::class)->findAll();
       $produits = $repository->findAll();
       $regions = $regionRepository->findAll();

       $produitsPagines = $paginator->paginate(
        $produits, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        6/*limit per page*/
        );

    return $this->render('/produit/index.html.twig', [
        'produitsPagines' => $produitsPagines,
        'regions' => $regions
    ]);

        // return $this->render('produit/index.html.twig', [
        //     'produits'=>$produits,
        //     'regions'=>$regions
        // ]);
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
        $accords = $produit->getAccords();
        
        return  $this->render('produit/detail.html.twig', [
            'produit'=>$produit,
            'domaine'=>$domaine,
            'region'=>$region,
            'accords'=> $accords
        ]);
         
    }
     /*
     * methode pour récuperer les produits en vente exclusive
     */
    #[Route('/produit/exclusif', name: 'app_exclusif')]
    public function affichProduitsExclusifs( ProduitRepository $produitRepository,PaginatorInterface $paginator,Request $request)
    {
        //$produits = $produitRepository->findBy(['exclusif'=> true],['nomProduit'=>'ASC']); premiére methode 
        $produits = $produitRepository->findAllExclusifs();
        $produitsPagines = $paginator->paginate(
            $produits, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
            );
    
        return $this->render('produit/exclusif.html.twig', [
            'produitsPagines' => $produitsPagines,
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
     
    // fonction pour filtrer par region 
        #[Route('/produit/region/{id}', name: 'produits_par_region', methods: ['GET'])]
    public function produitsParRegion(int $id, RegionRepository $regionRepository, ProduitRepository $produitRepository, DomaineRepository $domaineRepository): Response
    {
        // Récupérer la région
        $region = $regionRepository->find($id);
      
        
        // Vérifier si la région existe
        if (!$region) {
            throw $this->createNotFoundException("Cette région n'existe pas.");
        }

        // Récupérer les produits liés à cette région par rapport des domaine qu'ils lui appartien

        $domaines= $domaineRepository->findBy(['region'=>$region]);
         
        $produits =  [];
        foreach($domaines as $domaine)
        {
            $produits = array_merge($produits, $produitRepository->findBy(['domaine' => $domaine]));
        }
        
        // Afficher la vue des produits en passant les produits et la région
        return $this->render('produit/filter_region.html.twig', [
            'produits' => $produits,
            'region' => $region,
           //'regions'=>$regionRepository->findAll()
            
        ]);
    }
         
     
    //fontion pour filtrer les produits par domaine
    #[Route('/produit/domaines/{domaineId}', name: 'produit_par_domaine', methods: ['GET'])]
    public function produitParDomaine($domaineId,ProduitRepository $produitRepository, DomaineRepository $domaineRepository, RegionRepository $regionRepository)
    {
        $domaine= $domaineRepository->find($domaineId);// recuperer les domaines
        $region =  $domaine->getRegion() ;// recuperer la region du domaine
         
       
        //recuperer les produits du domaine
        $produits = $produitRepository->findBy(['domaine' => $domaineId]); 
       
      
   
        return $this->render('produit/filter_domaine.html.twig', [
        'produits' => $produits,
        'domaine'=>$domaine,
        'region'=>$region
    //'regions'=>$regionRepository->findAll()
        
    ]);
    }
    // src/Controller/ProduitController.php

    /*#[Route('/produit/prix', name: 'produit_par_prix')]
   
 
    public function filtreParPrix(Request $request, ProduitRepository $produitRepository)
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        // Définis des valeurs par défaut pour le prix minimum et maximum
        
        $prixMax = 80;

        if ($form->isSubmitted() && $form->isValid()) {
            // on récupere une valeur afficher par le slider
            $prixMax = $form->get('Prix')->getData();
        }

        // Appelle la méthode du repository avec les deux paramètres
        $produits = $produitRepository->findByPriceMax( $prixMax);

        return $this->render('produit/filter_prix.html.twig', [
            'form' => $form->createView(),
            'produits' => $produits,
            'prixMax'=>$prixMax
        ]);
    }*/
        

      
     
    }

      
  
        

    


