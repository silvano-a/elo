<?php

namespace App\Entity;

use App\Repository\EloLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EloLogRepository::class)
 */
class EloLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Wedstrijd::class, inversedBy="eloLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $wedstrijd;

    /**
     * @ORM\ManyToOne(targetEntity=Speler::class, inversedBy="eloLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $speler;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rating;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWedstrijd(): ?Wedstrijd
    {
        return $this->wedstrijd;
    }

    public function setWedstrijd(?Wedstrijd $wedstrijd): self
    {
        $this->wedstrijd = $wedstrijd;

        return $this;
    }

    public function getSpeler(): ?Speler
    {
        return $this->speler;
    }

    public function setSpeler(?Speler $speler): self
    {
        $this->speler = $speler;

        return $this;
    }

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function setRating(string $rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}
