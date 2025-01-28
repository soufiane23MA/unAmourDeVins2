<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(SessionInterface $session, ProduitRepository $produitRepository): Response
    {
        $panier = $session->get('panier',[]);
        $panierData = [];
        foreach($panier as $id =>$quantite)
        {
            $panierData[]= [
                'produit' => $produitRepository->find($id),
                'quantite' => $quantite
            ];
            
        }
 
        return $this->render('panier/index.html.twig', [
            'items'=> $panierData,
        ]);
    }

    /**
     * je crée tius d'abord la route qui me dérige vers le panier
     * route("/panier/add/{id}"), name = "panier_add")
     * c'est la route qui rajoute le produit avec (id) vers le panier, 
     * je peux egalement appeler la session directe sans passer par get(session) de la request, 
     * il faut simplement applé le service de sessionInterface qui me permettra d'acceder à la session directement
     */
    #[Route('/panier/add/{id}', name:'add_panier')]
    
   /* public function add($id, Request $request)
    {
        $session = $request->getSession();
        $panier = $session->get('panier',[]);
        if(empty($panier[$id]))
        {
             $panier[$id]= 1;
        }else
        {
            $panier[$id] ++;
        }
         $session->set('panier',$panier);
          return $this->render('/panier/index.html.twig');

    }*/

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
          return $this->render('/panier/index.html.twig');

    }
}
