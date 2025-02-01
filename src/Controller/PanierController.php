<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(SessionInterface $session, ProduitRepository $produitRepository): Response
    {
        $panier = $session->get('panier',[]);
        $panierData = [];
        //$total = 0
        foreach($panier as $id => $quantite)
        {
            //$produit= $produitRepository->find($id)
            $panierData[]= [
                 
                'produit' => $produitRepository->find($id),
                'quantite' => $quantite
            ];   
        } 
        $total = 0;
        foreach($panierData as $item)
        {
            $totalItem = $item['produit']->getPrix() * $item['quantite'];
            $total += $totalItem;
        }
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
    public function addProduitpanier( $id , SessionInterface $session)
    {
        $panier = $session->get('panier',[]);
        if(empty($panier[$id]))
        {
             $panier[$id]= 1;
        }else
        {
            $panier[$id] ++;
        }
         $session->set('panier',$panier);
         return $this->redirectToRoute('app_panier');

    }
    #[Route('/panier/remove/{id}', name:'remove_panier')]
    public function removeProduitPanier($id, SessionInterface $session)
    {
       
         $panier = $session->get('panier',[]);
        
         if(!empty($panier[$id] ))
         {
           if($panier[$id] > 1 ){
            $panier[$id]--;
           } else{
            unset($panier[$id]);
           }  
         }
         
         $session->set('panier',$panier);
         return $this->redirectToRoute('app_panier');
    }
     
    #[Route('/panier/delete/{id}', name:'delete_panier')]
    public function deleteProduitPanier($id, SessionInterface $session)
    {
       
         $panier = $session->get('panier',[]);
          
         if (!isset($panier[$id])) {
            $this->addFlash('warning', 'Produit non trouvé dans le panier.');
            return $this->redirectToRoute('app_panier');
        }
    
        unset($panier[$id]);
    
        $session->set('panier', $panier);
        $this->addFlash('success', 'Produit supprimé du panier.');
    
         return $this->redirectToRoute('app_panier');

    }
    

}
