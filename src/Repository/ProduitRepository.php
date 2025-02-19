<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
    /*public function findByFilters($region = null, $domaine = null, $prixMin = null, $prixMax = null)
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.domaine', 'd')
            ->leftJoin('d.region', 'r');

        if ($region) {
            $qb->andWhere('r.id = :region')
                ->setParameter('region', $region);
        }

        if ($domaine) {
            $qb->andWhere('d.id = :domaine')
                ->setParameter('domaine', $domaine);
        }

        if ($prixMin) {
            $qb->andWhere('p.prix >= :prixMin')
                ->setParameter('prixMin', $prixMin);
        }

        if ($prixMax) {
            $qb->andWhere('p.prix <= :prixMax')
                ->setParameter('prixMax', $prixMax);
        }

        return $qb->getQuery()->getResult();
    }*/
   /* public function findByPriceRange($prixMin,$prixMax)
    {
        return $this->createQueryBuilder('p')
             
            ->andWhere('p.prix <= :prixMax')
            ->setParameter('prixMax', $prixMax)
            ->getQuery()
            ->getResult();
    }*/

}
