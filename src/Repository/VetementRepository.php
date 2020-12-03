<?php

namespace App\Repository;

use App\Entity\Vetement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vetement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vetement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vetement[]    findAll()
 * @method Vetement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VetementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vetement::class);
    }
    public function getDetailsVetements()
    {
        $qb=$this->createQueryBuilder('c');
        $qb->select('count(c.id) as nbVetement', 'avg(c.prixDeBase) as PrixMoyen', 'min(c.prixDeBase) as PrixMin')
            ->addSelect('max(c.prixDeBase) as PrixMax', 't.libelle')
            ->join( 'App:Type', 't')
            ->where('c.type=t.id')
            ->groupBy('t.libelle');
        //    ->addOrderBy('p.nom', 'ASC');
        return $qb->getQuery()->getResult();
    }
    // /**
    //  * @return Vetement[] Returns an array of Vetement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Vetement
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
