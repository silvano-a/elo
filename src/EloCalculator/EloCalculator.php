<?php

namespace App\EloCalculator;

use App\Entity\Speler;
use App\Entity\Wedstrijd;
use App\Repository\WedstrijdRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

final class EloCalculator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function calculateEloForSpeler(Speler $speler)
    {
        /* @var Speler $speler */
        $wedstrijdRepo = $this->entityManager->getRepository(Wedstrijd::class);
        $opponentEloLogs = $wedstrijdRepo->getOpponentEloLogFromSpeler($speler);

        $sum  = $this->sumOpponentEloLogs($opponentEloLogs);


        return $this->calculateElo(
            $sum,
            $wedstrijdRepo->getAantalWinsForSpeler($speler),
            $wedstrijdRepo->getAantalLossesForSpeler($speler)
        );
    }

    private function calculateElo(int $totalRating, int $aantalWins, int $aantalLosses)
    {
        return ((1200 + $totalRating) + ( 400 * ($aantalWins - $aantalLosses))) / (($aantalLosses + $aantalWins) + 1);
    }

    private function sumOpponentEloLogs($collection)
    {
        $sum = 0;

        foreach($collection as $item) {
            foreach($item->getEloLogs() as $log) {
                $sum += (int) $log->getRating();
            }
        }

        return $sum;
    }
}
