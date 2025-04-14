<?php

namespace App\Controller;
 
use App\Form\SearchPriceType;
use App\Form\SearchRegionType;
use App\Form\SearchDomaineType;
use App\Form\SearchProductType;
use App\Repository\RegionRepository;
use App\Repository\DomaineRepository;
use App\Repository\ProduitRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SearchController extends AbstractController {
    #[Route('/search', name: 'price_search')]
    public function index(
        ProduitRepository $pr, 
        Request $request, 
        DomaineRepository $domaineRepository,
        RegionRepository $regionRepository, 
        PaginatorInterface $paginator): Response
    {  
        $regions = $regionRepository->findAll();
        $domaines = $domaineRepository->findAll();
        
        $form = $this->createForm(SearchPriceType::class);
        $formSearchRegion = $this->createForm(SearchRegionType::class, null, ['regions' => $regions]);
        $formSearchDomaine = $this->createForm(SearchDomaineType::class, null, ['domaines' => $domaines]);
        $form->handleRequest($request);
        
    
        $prix = $request->query->get('prix'); // Récupère le prix de l'URL
        $produitsPagines = null;
    
        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $prix = $data['Prix']; // Récupère le prix du formulaire
    
            // Redirige vers l'URL avec le paramètre prix
            return $this->redirectToRoute('price_search', [
                'prix' => $prix,
                'page' => 1,
            ]);
        }
        // Si un prix est présent (dans l'URL ou le formulaire), on récupère les produits
        if ($prix !== null) {
            $produits = $pr->findByPriceMax($prix); // Appelle la méthode du repository
            // Pagination des résultats
            $produitsPagines = $paginator->paginate(
                $produits,
                $request->query->getInt('page', 1),
                4
            );
        }

        // la logique pour chercher les regions

        $formSearchRegion->handleRequest($request);
        if ($formSearchRegion->isSubmitted() && $formSearchRegion->isValid()) {
            $regionId = $formSearchRegion->get('region')->getData();
            if ($regionId) {
                $produits = $pr->findByRegion($regionId);
                $produitsPagines = $paginator->paginate($produits, $request->query->getInt('page', 1), 4);
            }
        }
           // Traitement de la recherche par domaine
           $formSearchDomaine->handleRequest($request);
           if ($formSearchDomaine->isSubmitted() && $formSearchDomaine->isValid()) {
               $domaineId = $formSearchDomaine->get('domaine')->getData();
               if ($domaineId) {
                   $produits = $pr->findByDomaine($domaineId);
                   $produitsPagines = $paginator->paginate($produits, $request->query->getInt('page', 1), 4);
               }
           }
        return $this->render('search/index.html.twig', [
            'formSearch' => $form,
            'formSearchRegion' => $formSearchRegion->createView(),
            'formSearchDomaine' => $formSearchDomaine->createView(),
            'produitsPagines' => $produitsPagines,
            
           
        ]);
    }
    // methode qui recupere les produits de la bar de recherche par mot-clé
    //ne change plus rien

    #[Route('/search/product', name: 'product_search', methods: ['GET'])]

    
    public function recherche(Request $request, ProduitRepository $produitRepository,PaginatorInterface $paginator): Response
    {
        // Créer le formulaire de recherche
        $form = $this->createForm(SearchProductType::class);
        $form->handleRequest($request);
    
        
        $query = '';
        $produitsPagines = null;
    
        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer la donnée 'query' du formulaire
            
            $query = $form->get('query')->getData();
    
            // Rechercher les produits correspondants
            if ($query) {
                // Utilisation du QueryBuilder au lieu d'un tableau
            $queryBuilder = $produitRepository->createQueryBuilderByNom($query);
                $produitsPagines = $paginator->paginate(
                    $queryBuilder, /* query NOT result */
                    $request->query->getInt('page', 1)/*page number*/,
                    3/*limit per page*/
                    );
            }  
        }
        // Toujours retourner une réponse
        return $this->render('search/results.html.twig', [
            'form' => $form->createView(),
            'produitsPagines' => $produitsPagines,
            'query' => $query,
        ]);
    }
    /**
     * methode pour la recherche par région
     */
    #[Route('/search/regions', name: 'region_search')]
    public function afficherLesRegions(RegionRepository $regionRepository):Response
    {
        $regions = $regionRepository->findAll();
        return $this->render('search/regions.html.twig', [
            'regions' => $regions,
        ]);

    }
    /**
     * la methode pour recuperer tous les domaine pour la recherche du seidbar
     */
    #[Route('/search/domaines', name: 'domaine_search')]
    public function afficherLesDomaines(DomaineRepository $domaineRepository):Response
    {
        $domaines = $domaineRepository->findAll();
        return $this->render('search/domaines.html.twig',[
            'domaines' => $domaines
        ]);
    }

    /**
     * ------ methode pour récuperer les région par l'id et remplir le dropdown
     */
    #[Route('/search/region/{id}', name: 'search_produits_par_region')]
public function produitsParRegion(
    int $id, 
    ProduitRepository $produitRepository, 
    RegionRepository $regionRepository,
    Request $request, 
    PaginatorInterface $paginator
): Response {
    $region = $regionRepository->find($id);
    if (!$region) {
        throw $this->createNotFoundException("Région non trouvée.");
    }

    $produits = $produitRepository->findBy(['region' => $region]);

    $produitsPagines = $paginator->paginate(
        $produits,
        $request->query->getInt('page', 1),
        4
    );

    return $this->render('search/index.html.twig', [
        'produitsPagines' => $produitsPagines,
        'formSearch' => null,
    ]);
}


    

}


 
