<?php
// src/Controller/FactureController.php
namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Facture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class FactureController extends AbstractController
{
    public function genererFacture(Commande $commande, EntityManagerInterface $em): Response
    {
        try {
            if ($commande->getStatut() !== Commande::STATUT_PAYEE) {
                throw new \Exception("Génération de facture impossible : commande non payée.");
            }

            // Calculs HT/TVA (ex: 20%)
            $tauxTva = 0.20;
            $montantTTC = (float)$commande->getMontantTTC();
            $montantHT = round($montantTTC / (1 + $tauxTva), 2);
            $tva = round($montantTTC - $montantHT, 2);

            // Création de la facture
            $facture = new Facture();
            $facture->setDateFacture(new \DateTime());
            $facture->setNumeroFacture($this->genererNumeroFacture($em));
            $facture->setMontantHT($montantHT);
            $facture->setTva($tva);
            $facture->setTotalFacture($montantTTC);
            $facture->setCommande($commande);

            // Génération PDF
            $pdfPath = $this->genererPdf($facture);
            $facture->setPdfPath($pdfPath);

            $em->persist($facture);
            $em->flush();

            return $this->json(['success' => true, 'facture' => $facture]);

        } catch (\Exception $e) {
            return $this->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    private function genererNumeroFacture(EntityManagerInterface $em): string
    {
        $lastFacture = $em->getRepository(Facture::class)->findOneBy([], ['id' => 'DESC']);
        $lastId = $lastFacture ? (int)explode('-', $lastFacture->getNumeroFacture())[2] : 0;
        return 'FA-' . date('Y') . '-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
    }

    private function genererPdf(Facture $facture): string
    {
        $dompdf = new \Dompdf\Dompdf();
        $html = $this->renderView('facture/index.html.twig', [
            'facture' => $facture,
            'commande' => $facture->getCommande(),
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfDir = $this->getParameter('kernel.project_dir') . '/public/factures/';
        if (!file_exists($pdfDir)) {
            mkdir($pdfDir, 0777, true);
        }
        $pdfFilename = $facture->getNumeroFacture() . '.pdf';
        file_put_contents($pdfDir . $pdfFilename, $dompdf->output());

        return 'factures/' . $pdfFilename;
    }
}