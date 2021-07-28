<?php

namespace App\Repository;

use App\Entity\Speler;
use App\Entity\Wedstrijd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Wedstrijd|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wedstrijd|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wedstrijd[]    findAll()
 * @method Wedstrijd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WedstrijdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wedstrijd::class);
    }

    public function getOpponentEloLogFromSpeler(Speler $speler)
    {
        $qb = $this->createQueryBuilder('w');
        return $qb
            ->select('w, e')
            ->innerJoin('w.eloLogs', 'e', Join::WITH, 'e.speler != :speler')
            ->where('w.spelerHalf = :speler')
            ->orWhere('w.spelerHeel = :speler')
            ->setParameter('speler', $speler)
            ->getQuery()->getResult()
        ;
    }

    public function getAantalWinsForSpeler(Speler $speler)
    {
        return $this->createQueryBuilder('w')
            ->select('count(w.id)')
            ->andWhere('w.winnaar = :speler')
            ->setParameter('speler', $speler)
            ->getQuery()->getSingleScalarResult();
    }

    public function getAantalLossesForSpeler(Speler $speler)
    {
        return $this->createQueryBuilder('w')
            ->select('count(w.id)')
            ->andWhere('w.spelerHalf = :speler')
            ->orWhere('w.spelerHeel = :speler')
            ->andWhere('w.winnaar != :speler')
            ->setParameter('speler', $speler)
            ->getQuery()->getSingleScalarResult();
    }
}
