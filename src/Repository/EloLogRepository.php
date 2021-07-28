<?php

namespace App\Repository;

use App\Entity\EloLog;
use App\Entity\Speler;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EloLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method EloLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method EloLog[]    findAll()
 * @method EloLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EloLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EloLog::class);
    }
}
