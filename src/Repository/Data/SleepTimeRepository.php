<?php

namespace App\Repository\Data;

use App\Entity\Data\SleepTime;
use App\Entity\User\Client;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SleepTime>
 *
 * @method SleepTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method SleepTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method SleepTime[]    findAll()
 * @method SleepTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SleepTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SleepTime::class);
    }

    public function save(SleepTime $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SleepTime $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchByClient(Client $client, ?DateTime $start = null, ?DateTime $end = null)
    {
        $qb = $this
            ->createQueryBuilder('sleepTime')
            ->innerJoin('sleepTime.dailyReport', 'dailyReport')
            ->where('dailyReport.client = :client')
            ->setParameter('client', $client)
            ->orderBy('dailyReport.date', 'ASC');


        if ($start !== null) {
            $qb
                ->andWhere($qb->expr()->gte('dailyReport.date', ':start'))
                ->setParameter('start', new DateTime($start->format('Y-m-d')));
        }

        if ($end !== null) {
            $qb
                ->andWhere($qb->expr()->lte('dailyReport.date', ':end'))
                ->setParameter('end', new DateTime($end->format('Y-m-d')));
        }

        return $qb
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return SleepTime[] Returns an array of SleepTime objects
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

//    public function findOneBySomeField($value): ?SleepTime
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
