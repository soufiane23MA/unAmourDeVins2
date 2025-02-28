<?php

namespace App\Controller;
 
use App\Form\SearchPriceType;
use App\Form\SearchProductType;
use App\Repository\ProduitRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SearchController extends AbstractController {
    #[Route('/search', name: 'app_search')]
    public function index(ProduitRepository $pr, Request $request,PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(SearchProductType::class);
        $form->handleRequest($request);
        $produitsPagines = null;

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();  
            $produits = $pr->findByPriceMax($data);
            $produitsPagines = $paginator->paginate(
                $produits, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                4/*limit per page*/
                );
                 
;            return $this->render('search/index.html.twig',[
                'produitsPagines' => $produitsPagines,
                
            ]);
            
        }
        return $this->render('base.html.twig',[
            'formSearch'=>$form,
            'produitsPagines' => null
             
        ]);
    }
    // methode qui recupere les produits de la bar de recherche par mot-clé
    //ne change plus rien

    #[Route('/search/product', name: 'product_search')]
    
    public function recherche(Request $request, ProduitRepository $produitRepository,PaginatorInterface $paginator): Response
    {
        // Créer le formulaire de recherche
        $form = $this->createForm(SearchPriceType::class);
        $form->handleRequest($request);
    
        $produits = [];
        $query = '';
        $produitsPagines = null;
    
        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer la donnée 'query' du formulaire
            $query = $form->get('query')->getData();
    
            // Rechercher les produits correspondants
            if ($query) {
                $produits = $produitRepository->findByNom($query);
                $produitsPagines = $paginator->paginate(
                    $produits, /* query NOT result */
                    $request->query->getInt('page', 1)/*page number*/,
                    4/*limit per page*/
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

}

 
