<?php

namespace App\Service\Panier;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService

{
protected $session;
protected $produitRepository;

public function __construct(RequestStack $requestStack,ProduitRepository $produitRepository)
{
	$this->session = $requestStack->getSession() ;
	$this->produitRepository = $produitRepository;
}

public function addProduit(int $id)
{
	$panier = $this->session->get('panier',[]);
	if(!empty($panier[$id]))
	{
		$panier[$id] ++;
	}else
	{
		$panier[$id]= 1;
	}
	$this->session->set('panier',$panier);
 

} 

public function removeProduit(int $id)
{
$panier = $this->session->get('panier',[]);

		if(!empty($panier[$id] ))
	{
			if($panier[$id] > 1 ){
			$panier[$id]--;
		} else{
				unset($panier[$id]);
		}  
	}

	$this->session->set('panier',$panier);
}
public function deleteProduit(int $id)
{
$panier = $this->session->get('panier',[]);

unset($panier[$id]);

$this->session->set('panier', $panier);

}

public function getPanierComplet( )
{
	$panier = $this->session->get('panier',[]);
	$panierData = [];
	//$total = 0
	foreach($panier as $id => $quantite)
{

	
	$produit= $this ->produitRepository->find($id);
		if($produit) {
			$panierData[]= [
					
				'produit' => $this->produitRepository->find($id),
				'quantite' => $quantite
			]; 
		}else {
			 // Si le produit n'existe pas, on le supprime du panier
			 unset($panier[$id]);
		}
  
} 
	 // Mettre à jour le panier dans la session
	 $this->session->set('panier', $panier);

		return $panierData;
 
}

public function getTotalPanier()
{
	$total = 0;
	//$panierData = $this->getPanierComplet();
	foreach($this->getPanierComplet() as $item)
	{
			//pour reduir mon code , ce n'ai pas la peine de créer une variable utilisable qu'une seule fois
			//$totalItem = $item['produit']->getPrix() * $item['quantite']; 
			if ($item['produit']) {
				$total += $item['produit']->getPrix() * $item['quantite'];
			}
			else {
				// Si le produit n'existe pas, tu peux le supprimer du panier
				$this->deleteProduit($item['id']); // Supprime le produit du panier
			}
			
	}
	return $total;
}

public function viderPanier(): void
{
    $this->session->set('panier', []);
}

}
