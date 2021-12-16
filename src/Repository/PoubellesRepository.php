<?php

namespace App\Repository;

use App\Entity\Poubelles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Poubelles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Poubelles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Poubelles[]    findAll()
 * @method Poubelles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PoubellesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Poubelles::class);
    }

    // /**
    //  * @return Poubelles[] Returns an array of Poubelles objects
    //  */

    public function findByCouleurAndVille($couleur, $ville)
    {
        return $this->createQueryBuilder('p')
	        ->innerJoin('p.couleur', 'c' )
	        ->innerJoin('p.ville', 'v' )
            ->andWhere('c.class = :couleur')
            ->andWhere('v.cp = :ville')
            ->setParameter('couleur', $couleur)
            ->setParameter('ville', $ville)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Poubelles
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
