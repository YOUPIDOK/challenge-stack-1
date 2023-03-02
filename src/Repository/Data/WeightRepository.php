<?php

namespace App\Repository\Data;

use App\Entity\Data\Weight;
use App\Entity\User\Client;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Weight>
 *
 * @method Weight|null find($id, $lockMode = null, $lockVersion = null)
 * @method Weight|null findOneBy(array $criteria, array $orderBy = null)
 * @method Weight[]    findAll()
 * @method Weight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeightRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Weight::class);
    }

    public function save(Weight $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Weight $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLastWeightByClient(?Client $client)
    {
        if ($client === null) {
            return null;
        }
        return $this
            ->createQueryBuilder('weight')
            ->innerJoin('weight.dailyReport', 'dailyReport')
            ->where('dailyReport.client = :client')
            ->setParameter('client', $client)
            ->setMaxResults(1)
            ->orderBy('dailyReport.date', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function searchByClient(Client $client, ?DateTime $start = null, ?DateTime $end = null)
    {
        $qb = $this
            ->createQueryBuilder('weight')
            ->innerJoin('weight.dailyReport', 'dailyReport')
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
//     * @return Weight[] Returns an array of Weight objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Weight
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
