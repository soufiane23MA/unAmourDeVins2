<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Facture;
use App\Entity\Commande;
use App\Entity\ProduitCommande;
 
use Doctrine\ORM\EntityManagerInterface;
 
 


class CommandeService 

		{
		private $entityManager;
		private $panierService;
		private $factureService;


		public function __construct(
			EntityManagerInterface $entityManager,
			PanierService $panierService,
			// FactureService $factureService
			)
		{
			$this -> entityManager = $entityManager;
			$this -> panierService = $panierService;
			// $this -> factureService =$factureService;
		}
		/**
			 * Crée une nouvelle commande pour l'utilisateur donné.
			 *
			 * @param User $user L'utilisateur qui passe la commande.(l'utilisateur connecter si tu préfere :) )
			 * @return Commande La commande créée.
			 * 
			 */
			public function creerCommande(User $user ) : Commande
			{
				// Récupérer les produits du panier remplie par l'utilisateur
				$produitsPanier = $this->panierService->getPanierComplet();
					// Vérifier que le panier n'est pas vide, ça évite les erruers
					if (empty($produitsPanier)) {
						throw new \Exception('Le panier est vide.'); // !!!!! change le message !!!!
				}
					// Créer une nouvelle commande
					$commande = new Commande();
					$commande->setUser($user); // Associer l'utilisateur connecté à la commande
					$commande->setUserNom($user->getNom());
					$commande->setUserPrenom($user->getPrenom());
					$commande->setStatut(Commande::STATUT_EN_COURS);// mettre en place un statut par defaut 
					$commande->setDateCommande(new \DateTime());
					// Générer un numéro unique pour la commande
					$numeroCommande = 'CMD-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
					$commande->setNumeroCommande($numeroCommande);
					// Calcul du total
					$total = 0;
					foreach ($produitsPanier as $item) {
							$total += $item['produit']->getPrix() * $item['quantite'];
					}
					$commande->setMontantTTC($total);
					
							// Enregistrer la commande dans la base de données
					$this ->entityManager->persist($commande);// j'ai utilisé $this car entityManager est déjà injécté ans le constructeur
					
					$this ->entityManager->flush();

					// Ajouter les produits dans la commande (ProduitCommande)
					
					foreach ($produitsPanier as $item) {
						$produitCommande = new ProduitCommande();
						$produitCommande->setCommande($commande);
						$produitCommande->setProduit($item['produit']);
						$produitCommande->setQuantite($item['quantite']);
						
						$this ->entityManager->persist($produitCommande);
					}
						
					// Enregistrer les modifications en base de données

							$this->entityManager->flush();
							// Retourner la commande créée
							return $commande;

			}
			/**
			 * l'implémentation de cette commande permet de 
			 * Mettre à jour le mode de livraison et le statut d'une commande.
			 * d'enregistrer les modifications en base de données.
			 */
			public function validerCommande(Commande $commande,string $modeLivraison):void  //il ne retourne rien
			{
				
				// je m'assure que la commande est en mode "en Cours"
				if ($commande->getStatut() !== Commande::STATUT_EN_COURS){
					throw new \Exception('La commande doit être "en cours" pour validation.');
				} 
				
				// !!! n'oublie de changer le message 
				// Mettre à jour le mode de livraison

				$commande->setModeLivraison($modeLivraison);
					// Enregistrer les modifications en base de données
					$this->entityManager->flush();
				}
			
					
				
				/*public function validerPaiement(Commande $commande, string $modePaiement): void
				{
						
					// 1. Validation
					if (!in_array($modePaiement, ['carte', 'paypal'])) {
						throw new \Exception('Mode de paiement invalide');
				}
				 // Calcul du montant TTC si pas déjà fait
				 if (!$commande->getMontantTTC()) {
					$commande->setMontantTTC($commande->getTotal());

				// 2. Met à jour la commande
					$commande
							->setStatut(Commande::STATUT_PAYEE)
							->setDateCommande(new \DateTime());
							// 3. Création facture
							$facture = $this->factureService->creerFacture($commande);
							$commande->setFacture($facture);
    					$this->entityManager->flush();
						 
							
						//	3   Vide le panier associé à cette commande
							$this->panierService->viderPanier();
								
					
				}*/
				public function validerPaiement(Commande $commande, string $modePaiement): void
{

	// 1. Validation du mode de paiement
	if (!in_array($modePaiement, ['carte', 'paypal'])) {
		throw new \Exception('Mode de paiement invalide. Choisissez "carte" ou "paypal".');
	}
	
	// dd($commande->getStatut() !== Commande::STATUT_VALIDEE);
	// 2. Vérification du statut (doit être "validée" pour passer à "payée")
	// if ($commande->getStatut() !== Commande::STATUT_VALIDEE) {
	// 	throw new \Exception('La commande doit être validée avant paiement.');
	// }
	
	// 3. Calcul du montant TTC si absent
	if (!$commande->getMontantTTC()) {
		$commande->setMontantTTC($commande->getTotal());
	}

    // 4. Mise à jour de la commande
    $commande->setStatut(Commande::STATUT_PAYEE);
    $commande->setDateCommande(new \DateTime());

    // 5. Création de la facture (si elle n'existe pas déjà)
    // if (!$commande->getFacture()) {
    //     // $facture = $this->factureService->creerFacture($commande);
		// 		$facture = new Facture();
				
				
    //     $commande->setFacture($facture);
    // }

    // 6. Persistance et vidage du panier
    $this->entityManager->flush();
    $this->panierService->viderPanier();
	}


}






		

		


