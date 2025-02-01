<?php

namespace App\Controller;

 
use App\Service\Panier\PanierService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
 
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(PanierService $panierService): Response
    {
        $panierData = $panierService->getPanierComplet();
        $total = $panierService->getTotalPanier();
      
        return $this->render('panier/index.html.twig', [
            'items'=> $panierData,
            'total'=> $total
        ]);   
    }

    /**
     * je crée tous d'abord la route qui me dérige vers le panier
     * route("/panier/add/{id}"), name = "panier_add")
     * c'est la route qui rajoute le produit avec (id) vers le panier, 
     * je peux egalement appeler la session directe sans passer par get(session) de la request, 
     * il faut simplement applé le service de sessionInterface qui me permettra d'acceder à la session directement
     */
     
    #[Route('/panier/add/{id}', name:'add_panier')]
    public function addProduitpanier( $id,PanierService $panierService)
    {
        $panierService -> addProduit($id);

         return $this->redirectToRoute('app_panier');

    }

    #[Route('/panier/remove/{id}', name:'remove_panier')]

    public function removeProduitPanier($id, PanierService $panierService)
    {
         $panierService -> removeProduit($id);

         return $this->redirectToRoute('app_panier');
    }
     
    #[Route('/panier/delete/{id}', name:'delete_panier')]
    public function deleteProduitPanier($id, PanierService $panierService)
    {
       
        $panierService ->deleteProduit( $id);

         return $this->redirectToRoute('app_panier');

    }
    

}
