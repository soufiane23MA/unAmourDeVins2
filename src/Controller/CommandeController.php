<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Commande;
use App\Service\PanierService;
use App\Entity\ProduitCommande;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManager;
 
use App\Service\CommandeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CommandeController extends AbstractController
{
    /**
     * 
     */
    #[Route('/commande/preparation', name: 'app_commande_preparation')]
    public function preparationCommande(PanierService $panierService): Response
        {
            $user = $this->getUser();
            if(!$user) {
                return $this->redirectToRoute('app_login');
            }
            $panier = $panierService->getPanierComplet();
            
            
            if(empty($panier)) {
                $this->addFlash('error', 'Votre panier est vide');
                return $this->redirectToRoute('app_panier');
            }
            // Calcul du total
            $total = 0;
            foreach ($panier as $item) {
                $total += $item['produit']->getPrix() * $item['quantite'];
            }

            return $this->render('commande/preparation.html.twig', [
                'panier' => $panier,
                'total' => $total // Ajout du total
                
            ]);
        }


    #[Route('/commande', name: 'app_commande', methods: ['POST'])]
    public function creerCommande( CommandeService $commandeService,EntityManagerInterface $entityManager): Response
    {
    /**
     * @var mixed
     * si l'utilisateur n'est pas connecté , on le redirige vers la page login ou s'enregistrer
     */

        $user = $this->getUser();
        if(!$user)
        {
            return $this->redirectToRoute('app_login'); 
            // Rediriger si l'utilisateur n'est pas connecté pour
            // qu'il se connecte.
        }
        // Créer la commande via le service
        try 
        {
            $commande = $commandeService->creerCommande($user);
            
            return $this->redirectToRoute('app_commande_confirmation',
                        ['idCommande' => $commande->getId()]);   
        }  
            catch (\Exception $e) 
            {
                // Gérer les erreurs (par exemple, panier vide)
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('app_panier');
            }
    }


    #[Route('/commande/valider', name: 'app_commande_valider', methods: ['POST'])]
    public function validerCommande(Request $request,CommandeService $commandeService ,EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login'); // Rediriger vers la connexion si non connecté
        }
        try {
             // Récupère la commande existante au lieu d'en créer une nouvelle
             $commande = $entityManager->getRepository(Commande::class)->findOneBy([
                'user' => $user,
                'statut' => Commande::STATUT_EN_COURS
            ]);
            if (!$commande) {
                // Si aucune commande en cours, on en crée une nouvelle
                $commande = $commandeService->creerCommande($user);
            }
        
            // 2-Récupérer le mode de livraison choisi
            $modeLivraison = $request->request->get('mode_livraison');
            
        // 3. Valider la commande
            $commandeService->validerCommande($commande, $modeLivraison);
          // $commande = $commandeService->validerCommande($user, $modeLivraison);
             // 4. Rediriger vers paiement
            return $this->redirectToRoute('app_paiement', [
            'idCommande' => $commande->getId()
        ]);
            
            
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_commande_preparation');
        }
    }


    #[Route('/commande/confirmation/{id}', name: 'app_commande_confirmation')]
    public function confirmation(Commande $commande): Response
    {
        // Vérifier que la commande appartient bien à l'utilisateur connecté
        if ($commande->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Accès interdit.');
        }

        return $this->render('commande/confirmation.html.twig', [
            'commande' => $commande,
        ]);
    }


    #[Route('/commande/finale/{id}', name: 'app_commande_finale')]
    public function commandeFinale(Commande $commande): Response
    {
        // Vérifier que la commande appartient bien à l'utilisateur connecté
        if ($commande->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Accès interdit.');
        }

        return $this->render('commande/finale.html.twig', [
            'commande' => $commande,
        ]);
    }
    
        
    
     

 
#[Route('/paiement/{idCommande}', name: 'app_paiement')]
public function choixPaiement(int $idCommande, EntityManagerInterface $entityManager,Security $security): Response
{
    // Récupérer la commande
    $commande = $entityManager->getRepository(Commande::class)->find($idCommande);

    // Vérifier que la commande existe et appartient bien à l'utilisateur connecté
    if (!$commande || $commande->getUser() !== $this->getUser()) {
        throw $this->createNotFoundException('Commande introuvable ou accès interdit.');
    }
    

    // Afficher la page de choix du paiement
    return $this->render('commande/paiement.html.twig', [
        'commande' => $commande,
    ]);
}
#[Route('/paiement/valider/{idCommande}', name: 'app_paiement_valider', methods: ['POST'])]
public function validerPaiement(int $idCommande, Request $request, EntityManagerInterface $entityManager,CommandeService $commandeService): Response
{
    // Récupérer la commande
    $commande = $entityManager->getRepository(Commande::class)->find($idCommande);
  
    

    // Vérifier que la commande existe et appartient bien à l'utilisateur connecté
    if (!$commande || $commande->getUser() !== $this->getUser()) {
        throw $this->createNotFoundException('Commande introuvable ou accès interdit.');
    }

    // Traiter le paiement (exemple simplifié)
    try {
        // Ici, je pourrais appeler un service de paiement (Stripe, PayPal, etc.)
        // Pour l'instant, on simule un paiement réussi
         // 1. Appeler le service pour valider le paiement
         $commandeService->validerPaiement($commande, $request->request->get('mode_paiement'));
        

         // génération de la facture
         if ($commande->getStatut() !== Commande::STATUT_PAYEE) {
             throw new \Exception("Génération de facture impossible : commande non payée.");
            }
            
            // Calculs HT/TVA (ex: 20%)
            $tauxTva = 0.20;
            $montantTTC = (float)$commande->getMontantTTC();
            $montantHT = round($montantTTC / (1 + $tauxTva), 2);
            $tva = round($montantTTC - $montantHT, 2);

            // Création de la facture/ hydrater la facture
            $facture = new Facture();
            $numeroFacture = 'FAC-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);  
            $facture->setDateFacture(new \DateTime());
            $facture->setNumeroFacture($numeroFacture);
            $facture->setMontantHT($montantHT);
            $facture->setTva($tva);
            $facture->setTotalFacture($montantTTC);
            $facture->setCommande($commande);
           

            //  Définition du chemin du fichier PDF AVANT de persister la facture

            $pdfFilename = $numeroFacture . '.pdf';
            $pdfDir = $this->getParameter('kernel.project_dir') . '/public/factures/';
            $publicPath = '/factures/' . $pdfFilename; 

            //  Enregistrement du pdfPath AVANT de générer le PDF
            $facture->setPdfPath($publicPath);
            //dd($facture);
           

        //persister la facture pour généer l'id facture
        $entityManager->persist($facture);
        
        $entityManager->flush();
        $dompdf = new \Dompdf\Dompdf();

        $html = $this->renderView('facture/index.html.twig', [
            'facture' => $facture,
            'commande' => $facture->getCommande(),
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        

        
        
       
       
        // Génération PDF
        
        //$facture->setPdfPath($pdfPath);
        //$entityManager->flush();
     
       
       
        // configurer le chemain du fichier PDF
        $pdfFilename = 'FAC-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) . '.pdf';
       
        $pdfDir = $this->getParameter('kernel.project_dir') . '/public/factures/';
        
        $publicPath = '/factures/' . $pdfFilename; 
        
        //$pdfFilename = $facture->getNumeroFacture().'.pdf';
         
         
        
 
        // 2. Vérification/création du dossier
        if (!file_exists($pdfDir)) {
            mkdir($pdfDir, 0755, true);  // 0755 est plus sécurisé que 0777
        }
        
         // 3. Génération et sauvegarde du PDF
        file_put_contents($pdfDir . $pdfFilename, $dompdf->output());
        // Mise à jour de l'entité
        $facture->setPdfPath($publicPath.$pdfFilename);
        // 4. Stockage du chemin relatif dans l'entité
       
        //$entityManager->persist($facture);
        $facture->setPdfPath($publicPath); // Stocke le chemin web
        //$entityManager->persist($facture);
        $entityManager->flush();
       

        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors du paiement.');
            return $this->redirectToRoute('app_paiement', ['idCommande' => $commande->getId()]);
        }
        return $this->redirectToRoute('confirmation_paiement', [
            'id' => $facture->getId() // On passe l'ID de la facture pour récupérer son chemin
        ]);
        

         
    }
    #[Route('/confirmation/{id}', name: 'confirmation_paiement')]
    public function confirmationFacture(FactureRepository $factureRepository,$id)
    {
        $facture = $factureRepository->find($id);
        // je verifie que la facture existe d'abord 
        if (!$facture) {
            throw $this->createNotFoundException('Facture introuvable');
        }
        return $this->render('commande/confirmation.html.twig', [
            'facture' => $facture
        ]);
        
    }

    
}
