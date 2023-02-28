<?php

namespace App\Repository\Objective;

use App\Entity\Objective\SelectObjectives;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SelectObjectives>
 *
 * @method SelectObjectives|null find($id, $lockMode = null, $lockVersion = null)
 * @method SelectObjectives|null findOneBy(array $criteria, array $orderBy = null)
 * @method SelectObjectives[]    findAll()
 * @method SelectObjectives[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SelectObjectivesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SelectObjectives::class);
    }

    public function save(SelectObjectives $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SelectObjectives $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SelectObjectives[] Returns an array of SelectObjectives objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SelectObjectives
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
