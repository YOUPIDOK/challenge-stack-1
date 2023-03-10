<?php

namespace App\Repository\Objective;

use App\Entity\Objective\Objective;
use App\Entity\User\Client;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Objective>
 *
 * @method Objective|null find($id, $lockMode = null, $lockVersion = null)
 * @method Objective|null findOneBy(array $criteria, array $orderBy = null)
 * @method Objective[]    findAll()
 * @method Objective[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObjectiveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Objective::class);
    }

    public function save(Objective $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Objective $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findObjectivesExpired(): array
    {
        $today = new DateTime();
        return $this->createQueryBuilder('obj')
            ->andWhere('obj.endAt <= :today')
            ->setParameter('today', $today)
            ->getQuery()
            ->getResult()
        ;
    }

    public function searchObjectifQb(Client $client, ?string $label = null, ?DateTime $start = null, ?DateTime $end = null)
    {
        $qb = $this
            ->createQueryBuilder('obj')
            ->where('obj.client = :client')
            ->setParameter('client', $client)
            ->orderBy('obj.endAt', 'ASC');

        if ($label !== null) {
            $qb
                ->andWhere('obj.label LIKE :label')
                ->setParameter('label', '%' . $label . '%')
            ;
        }

        if ($start !== null) {
            $qb
                ->andWhere('obj.startAt >= :start')
                ->setParameter('start', $start);
        }

        if ($end !== null) {
            $qb
                ->andWhere('obj.endAt <= :end')
                ->setParameter('end', $end);
        }

        return $qb;

    }


//    /**
//     * @return Objective[] Returns an array of Objective objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Objective
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
