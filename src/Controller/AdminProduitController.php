<?php

namespace App\Controller;

use App\Entity\Produit;
 
use App\Form\ProduitType;
use Doctrine\ORM\EntityManager;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminProduitController extends AbstractController
{
    #[Route('/admin/produits', name: 'app_admin_produits')]
    public function index(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();
        

        return $this->render('admin/produits/index.html.twig', [
            'produits'=> $produits
        ]);
    }
    /**
      * fonction pour confirmer la soumission du formulaire 
      */
      #[Route('/admin/produits/edit/{id}', name: 'edit_produit')] 
      public function editProduit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
    // Créer le formulaire pour l'édition du produit
    $form = $this->createForm(ProduitType::class, $produit);
    $form->handleRequest($request);  // Récupère les données envoyées par le formulaire

    // Si le formulaire est soumis et valide
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();  // Enregistre les modifications en base de données

        // Redirige vers la liste des produits
        return $this->redirectToRoute('app_admin_produits');
    }

    // Si le formulaire n'est pas soumis ou n'est pas valide, on l'affiche
    return $this->render('admin/produits/edit.html.twig', [
        'form' => $form->createView(),
        'produit'=> $produit
    ]);
    }
    
    #[Route('/admin/produits/delete/{id}', name: 'delete_produit')]// route pour supprimer un produit de la base de donnée
    public function delete($id, ProduitRepository $produitRepository, EntityManagerInterface $entityManager)
    {
        $produit = $produitRepository->find($id);

    if ($produit) {
       
        $entityManager->remove($produit);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_admin_produits') ;
    
    }  
   /* #[Route('/admin/produits/new', name: 'new_produit')]
    public function newProduit(Request $request, EntityManagerInterface $entityManager): Response
    {
    $produit = new Produit(); // Crée un nouvel objet Produit
    $form = $this->createForm(ProduitType::class, $produit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($produit); // Prépare l’enregistrement
        $entityManager->flush(); // Enregistre en base de données

        return $this->redirectToRoute('app_admin_produits'); // Redirige vers la liste des produits
    }

    return $this->render('admin/produits/new.html.twig', [
        'form' => $form->createView()
    ]);
    }*/
    #[Route('/admin/produits/new', name: 'new_produit')]
public function newProduit(Request $request, EntityManagerInterface $entityManager): Response
{
    $produit = new Produit(); // Crée un nouvel objet Produit
    $form = $this->createForm(ProduitType::class, $produit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        

        // Récupération du fichier uploadé
        $imageFile = $form->get('image')->getData();
        

        if ($imageFile) { // Vérifie si un fichier a été envoyé
            $destination = $this->getParameter('kernel.project_dir') . '/public/imgs'; // Dossier de stockage
            $newFilename = uniqid() . '.' . $imageFile->guessExtension(); // Génère un nom unique
            // Renomme le fichier avec le nom du produit
            $filename = $produit->getNomProduit() . '.' . $imageFile->guessExtension(); // Utilise le nom du produit

            $imageFile->move($destination, $newFilename); // Déplace le fichier dans /public/imgs
           
 

            $produit->setImage($newFilename); // Stocke le nom du fichier en BDD
        }

        $entityManager->persist($produit); // Prépare l’enregistrement
        $entityManager->flush(); // Enregistre en base de données

        return $this->redirectToRoute('app_admin_produits'); // Redirige vers la liste des produits
    }

    return $this->render('admin/produits/new.html.twig', [
        'form' => $form->createView()
    ]);
}

     
      
    }

