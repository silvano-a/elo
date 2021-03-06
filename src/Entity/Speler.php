<?php

namespace App\Entity;

use App\Repository\SpelerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpelerRepository::class)
 */
class Speler
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $naam;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rating;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Wedstrijd::class, mappedBy="spelerHalf", orphanRemoval=true)
     */
    private $wedstrijdenHalf;

    /**
     * @ORM\OneToMany(targetEntity=Wedstrijd::class, mappedBy="spelerHeel", orphanRemoval=true)
     */
    private $wedstrijdenHeel;

    /**
     * @ORM\OneToMany(targetEntity=Wedstrijd::class, mappedBy="winnaar")
     */
    private $gewonnenWedstrijden;

    /**
     * @ORM\OneToMany(targetEntity=EloLog::class, mappedBy="speler")
     */
    private $eloLogs;

    public function __construct()
    {
        $this->wedstrijdenHalf = new ArrayCollection();
        $this->wedstrijdenHeel = new ArrayCollection();
        $this->gewonnenWedstrijden = new ArrayCollection();
        $this->eloLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNaam(): ?string
    {
        return $this->naam;
    }

    public function setNaam(string $naam): self
    {
        $this->naam = $naam;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Wedstrijd[]
     */
    public function getWedstrijdenHalf(): Collection
    {
        return $this->wedstrijdenHalf;
    }

    public function addWedstrijden(Wedstrijd $wedstrijden): self
    {
        if (!$this->wedstrijdenHalf->contains($wedstrijden)) {
            $this->wedstrijdenHalf[] = $wedstrijden;
            $wedstrijden->setSpelerHalf($this);
        }

        return $this;
    }

    public function removeWedstrijden(Wedstrijd $wedstrijden): self
    {
        if ($this->wedstrijdenHalf->removeElement($wedstrijden)) {
            // set the owning side to null (unless already changed)
            if ($wedstrijden->getSpelerHalf() === $this) {
                $wedstrijden->setSpelerHalf(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Wedstrijd[]
     */
    public function getWedstrijdenHeel(): Collection
    {
        return $this->wedstrijdenHeel;
    }

    public function addWedstrijdenHeel(Wedstrijd $wedstrijdenHeel): self
    {
        if (!$this->wedstrijdenHeel->contains($wedstrijdenHeel)) {
            $this->wedstrijdenHeel[] = $wedstrijdenHeel;
            $wedstrijdenHeel->setSpelerHeel($this);
        }

        return $this;
    }

    public function removeWedstrijdenHeel(Wedstrijd $wedstrijdenHeel): self
    {
        if ($this->wedstrijdenHeel->removeElement($wedstrijdenHeel)) {
            // set the owning side to null (unless already changed)
            if ($wedstrijdenHeel->getSpelerHeel() === $this) {
                $wedstrijdenHeel->setSpelerHeel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Wedstrijd[]
     */
    public function getGewonnenWedstrijden(): Collection
    {
        return $this->gewonnenWedstrijden;
    }

    public function isOnHotstreak()
    {

        $totalMatches = array_merge($this->getWedstrijdenHalf()->toArray(), $this->getWedstrijdenHeel()->toArray());

        uasort($totalMatches, function ($a, $b) {
            return (int) $a->getId() < (int) $b->getId();
        });

        $counter = count($totalMatches);
        $onStreak = true;

        while($counter > (count($totalMatches) - 3)) {
            --$counter;


            if ($totalMatches[$counter]->getWinnaar() !== $this) {
                $onStreak = false;
                break;
            }

            if($counter === 0) {
                break;
            }
        }

        return $onStreak;


    }

    public function addGewonnenWedstrijden(Wedstrijd $gewonnenWedstrijden): self
    {
        if (!$this->gewonnenWedstrijden->contains($gewonnenWedstrijden)) {
            $this->gewonnenWedstrijden[] = $gewonnenWedstrijden;
            $gewonnenWedstrijden->setWinnaar($this);
        }

        return $this;
    }

    public function removeGewonnenWedstrijden(Wedstrijd $gewonnenWedstrijden): self
    {
        if ($this->gewonnenWedstrijden->removeElement($gewonnenWedstrijden)) {
            // set the owning side to null (unless already changed)
            if ($gewonnenWedstrijden->getWinnaar() === $this) {
                $gewonnenWedstrijden->setWinnaar(null);
            }
        }

        return $this;
    }

    public function getMatchCount(): int
    {
        return (int) count($this->getWedstrijdenHalf()) + count($this->wedstrijdenHeel);
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
            $eloLog->setSpeler($this);
        }

        return $this;
    }

    public function removeEloLog(EloLog $eloLog): self
    {
        if ($this->eloLogs->removeElement($eloLog)) {
            // set the owning side to null (unless already changed)
            if ($eloLog->getSpeler() === $this) {
                $eloLog->setSpeler(null);
            }
        }

        return $this;
    }
}
