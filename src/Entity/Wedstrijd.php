<?php

namespace App\Entity;

use App\Repository\WedstrijdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WedstrijdRepository::class)
 */
class Wedstrijd
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Speler::class, inversedBy="wedstrijden")
     * @ORM\JoinColumn(nullable=false)
     */
    private $spelerHalf;

    /**
     * @ORM\ManyToOne(targetEntity=Speler::class, inversedBy="wedstrijdenHeel")
     * @ORM\JoinColumn(nullable=false)
     */
    private $spelerHeel;

    /**
     * @ORM\ManyToOne(targetEntity=Speler::class, inversedBy="gewonnenWedstrijden")
     */
    private $winnaar;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $timestamp;

    /**
     * @ORM\OneToMany(targetEntity=EloLog::class, mappedBy="wedstrijd")
     */
    private $eloLogs;

    public function __construct()
    {
        $this->eloLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpelerHalf(): ?Speler
    {
        return $this->spelerHalf;
    }

    public function setSpelerHalf(?Speler $spelerHalf): self
    {
        $this->spelerHalf = $spelerHalf;

        return $this;
    }

    public function getSpelerHeel(): ?Speler
    {
        return $this->spelerHeel;
    }

    public function setSpelerHeel(?Speler $spelerHeel): self
    {
        $this->spelerHeel = $spelerHeel;

        return $this;
    }

    public function getWinnaar(): ?Speler
    {
        return $this->winnaar;
    }

    public function getVerliezer(): ?Speler
    {
        return $this->winnaar == $this->spelerHalf ? $this->spelerHeel : $this->spelerHalf;
    }

    public function setWinnaar(?Speler $winnaar): self
    {
        $this->winnaar = $winnaar;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeImmutable
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeImmutable $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return Collection|EloLog[]
     */
    public function getEloLogs(): Collection
    {
        return $this->eloLogs;
    }

    public function addEloLog(EloLog $eloLog): self
    {
        if (!$this->eloLogs->contains($eloLog)) {
            $this->eloLogs[] = $eloLog;
            $eloLog->setWedstrijd($this);
        }

        return $this;
    }

    public function removeEloLog(EloLog $eloLog): self
    {
        if ($this->eloLogs->removeElement($eloLog)) {
            // set the owning side to null (unless already changed)
            if ($eloLog->getWedstrijd() === $this) {
                $eloLog->setWedstrijd(null);
            }
        }

        return $this;
    }
}
