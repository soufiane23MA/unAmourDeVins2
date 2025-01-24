<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProduitFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    { 
         // Créer 20 produits fictifs
         for ($i = 0; $i < 20; $i++) {
            $produit = new Produit();
            $produit->setNomProduit('Produit ' . $i);
            $produit->setDetailProduit('Description du produit ' . $i);
            $produit->setPrix(mt_rand(10, 100)); // Prix aléatoire entre 10 et 100
            $produit->setContenance(mt_rand(75, 150)); // Contenance aléatoire entre 75 et 150 cl
            $produit->setImage('image' . $i . '.jpg'); // Nom de l'image
            $produit->setExclusif($i % 2 === 0); // Produit exclusif pour les indices pairs

            // Persister chaque produit dans la base de données
            $manager->persist($produit);
        }

        // Sauvegarder les données dans la base de données
        $manager->flush();
    }
}
