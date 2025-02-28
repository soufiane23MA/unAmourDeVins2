<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository
{
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Produit::class);
        }

    //    /**
    //     * @return Produit[] Returns an array of Produit objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Produit
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery():l;kjh-W  
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findProduitWithDomaineAndRegion(int $id): ?Produit
    {
        // Créer une requête avec jointure
        return $this->createQueryBuilder('p')
            ->leftJoin('p.domaine', 'd') // Jointure avec Domaine
            ->leftJoin('d.region', 'r')  // Jointure avec Region
            ->addSelect('d', 'r') // Sélectionner les entités Domaine et Region
            ->where('p.id = :id') // Condition pour le produit spécifique
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult(); // Retourne un seul produit avec ses relations
    }
    public function findAllExclusifs()
    {
        return $this->createQueryBuilder('p')
             
            ->Where('p.exclusif = :exclusif')
            ->setParameter('exclusif',true)
            ->orderBy('p.nomProduit ','ASC')
            ->getQuery()
            ->getResult();
        
    }
     
       // src/Repository/ProduitRepository.php
    public function findByPriceMax( $prixMax)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.prix <= :prixMax') // Condition pour le prix maximum
            ->setParameter('prixMax', $prixMax)
            ->getQuery()
            ->getResult();
    }
    // la fonction qui permer de faire la requette pour la bar de recherche 
    //on creéer le queryBilder avec tous ce qu'on veux comme initation qui peuvent être soumis 
    /*public function findByNom(string $query)
    {
        $queryBuilder = $this->createQueryBuilder('p');// la requette prend les expressions AND & OR 
        $queryBuilder -> Where(
             
            $queryBuilder->expr()->orX(
                $queryBuilder->expr()->like('p.nomProduit',':query'),
                $queryBuilder->expr()->like('p.detailProduit',':query'),
            ),
        )
        ->setParameter('query','%'. $query.'%');
        return $queryBuilder 
            ->getQuery()
            ->getResult();

        }*/
        public function findByNom(string $query): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nomProduit LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    }
  
 
}
