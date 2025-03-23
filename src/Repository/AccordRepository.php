<?php

namespace App\Repository;

use App\Entity\Accord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Accord>
 */
class AccordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Accord::class);
    }

    //    /**
    //     * @return Accord[] Returns an array of Accord objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Accord
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
  /*  public function findProduitsByPlat($platId): array
{
    return $this->createQueryBuilder('a')
        ->leftJoin('a.produit', 'p')  // Jointure avec la table Produit
        ->leftJoin('a.plat', 'pl')
        ->andWhere('pl.id = :platId')
        ->setParameter('platId', $platId)
        ->select('p.id', 'p.nomProduit as name', 'plat.nomPlat as plat') // Sélectionne les champs nécessaires
        ->getQuery()
        ->getResult();
}*/
// src/Repository/AccordRepository.php
// src/Repository/AccordRepository.php
public function findProduitsByPlat(int $platId): array
{
    return $this->createQueryBuilder('a')
        ->leftJoin('a.produit', 'p') // Jointure avec l'entité Produit
        ->leftJoin('a.plat', 'pl') // Jointure avec l'entité Plat, alias 'pl'
        ->andWhere('pl.id = :platId') // Utilise l'alias 'pl' pour la condition
        ->setParameter('platId', $platId)
        ->getQuery()
        ->getResult(); // Renvoie un tableau d'objets Accord
}

}
