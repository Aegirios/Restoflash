<?php

namespace App\Repository;

use App\Entity\TwigVars;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TwigVars>
 *
 * @method TwigVars|null find($id, $lockMode = null, $lockVersion = null)
 * @method TwigVars|null findOneBy(array $criteria, array $orderBy = null)
 * @method TwigVars[]    findAll()
 * @method TwigVars[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TwigVarsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TwigVars::class);
    }

//    /**
//     * @return TwigVars[] Returns an array of TwigVars objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TwigVars
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
