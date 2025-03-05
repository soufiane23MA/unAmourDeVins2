<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class FactureController extends AbstractController
{
    #[Route('/facture', name: 'app_facture')]
    public function index(): Response
    {
        return $this->render('facture/index.html.twig', [
            'controller_name' => 'FactureController',
        ]);
    }
    
    #[Route('/facture/{idCommande}', name: 'app_facture_pdf')]
public function generatePdf(string $idCommande, CommandeRepository $commandeRepository)
{
    //  Récupérer la commande
    $commande = $commandeRepository->findCommandeById((int) $idCommande);
    
    // Vérifier si la commande existe et si l'utilisateur est bien celui qui a passé la commande
    if (!$commande || $commande->getUser() !== $this->getUser()) {
        throw $this->createNotFoundException("Commande introuvable ou accès interdit.");
    }

    //  Récupérer les produits associés à la commande
    $produitComandes = $commande->getProduitCommandes(); // Assure-toi que cette relation existe dans l'entité Commande

    // Préparer Dompdf
    $options = new Options();
    $options->set('defaultFont', 'Arial');
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    //  Générer le HTML pour le PDF
    $html = $this->renderView('facture/index.html.twig', [
        'commande' => $commande,
        'produitComandes' => $produitComandes,
    ]);

    //  Charger le HTML dans Dompdf
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $pdfOutput = $dompdf->output(); // Générer le PDF en mémoire

    //  Retourner le fichier PDF en réponse
    return new Response($pdfOutput, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="facture.pdf"',
    ]);
}

}
