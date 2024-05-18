<?php

namespace App\Repository;

use App\Entity\Mesures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mesures>
 *
 * @method Mesures|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mesures|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mesures[]    findAll()
 * @method Mesures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MesuresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mesures::class);
    }

    public function findMostRecent(): ?Mesures
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.dateHeure', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }


    public function findMostRecentTemperature(): ?Mesures
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.temperatureC IS NOT NULL')
            ->orderBy('m.dateHeure', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByDate(\DateTimeInterface $date): ?Mesures
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.dateHeure >= :date_start')
            ->andWhere('m.dateHeure < :date_end')
            ->setParameter('date_start', $date->setTime(0, 0))
            ->setParameter('date_end', $date->setTime(23, 59, 59))
            ->getQuery()
            ->getOneOrNullResult();
    }


    public function findMostRecentHumidite(): ?Mesures
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.humidite IS NOT NULL')
            ->orderBy('m.dateHeure', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findMostRecentLuminosite(): ?Mesures
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.luminosite IS NOT NULL')
            ->orderBy('m.dateHeure', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findMostRecentPression(): ?Mesures
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.pressure IS NOT NULL')
            ->orderBy('m.dateHeure', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function predictNightTemperature(): ?float
    {
        $start = new \DateTime('yesterday midnight'); // Début de la journée précédente à minuit
        $end = new \DateTime('yesterday 06:00:00'); // Début de la journée actuelle à 6h du matin

        $queryBuilder = $this->createQueryBuilder('m')
            ->select('AVG(m.temperatureC)')
            ->where('m.dateHeure >= :start')
            ->andWhere('m.dateHeure < :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }


    public function predictMorningTemperature(): ?float
    {
        $start = new \DateTime('yesterday 06:00:01'); // Début de la journée précédente à 6h01
        $end = new \DateTime('yesterday noon'); // Début de la journée précédente à midi

        $queryBuilder = $this->createQueryBuilder('m')
            ->select('AVG(m.temperatureC)')
            ->where('m.dateHeure >= :start')
            ->andWhere('m.dateHeure <= :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function predictAfternoonTemperature(): ?float
    {
        $start = new \DateTime('yesterday noon'); // Début de la journée précédente à midi
        $end = new \DateTime('today midnight'); // Début de la journée actuelle à minuit

        $queryBuilder = $this->createQueryBuilder('m')
            ->select('AVG(m.temperatureC)')
            ->where('m.dateHeure >= :start')
            ->andWhere('m.dateHeure <= :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }



//    /**
//     * @return Mesures[] Returns an array of Mesures objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Mesures
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
