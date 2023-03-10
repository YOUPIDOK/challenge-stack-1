<?php

namespace App\Repository;

use App\Entity\DailyReport;
use App\Entity\User\Client;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DailyReport>
 *
 * @method DailyReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method DailyReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method DailyReport[]    findAll()
 * @method DailyReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DailyReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DailyReport::class);
    }

    public function save(DailyReport $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DailyReport $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchDailyReportQb(Client $client, ?DateTime $start = null, ?DateTime $end = null)
    {
        $qb = $this
            ->createQueryBuilder('daily_report')
            ->where('daily_report.client = :client')
            ->setParameter('client', $client)
            ->orderBy('daily_report.date', 'DESC');

        if ($start !== null) {
            $qb
                ->andWhere('daily_report.date >= :start')
                ->setParameter('start', $start);
        }

        if ($end !== null) {
            $qb
                ->andWhere('daily_report.date <= :end')
                ->setParameter('end', $end);
        }

        return $qb;
    }

    public function searchByClient(Client $client, ?DateTime $start = null, ?DateTime $end = null)
    {
        $qb = $this
            ->createQueryBuilder('dailyReport')
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
//     * @return DailyReport[] Returns an array of DailyReport objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DailyReport
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
