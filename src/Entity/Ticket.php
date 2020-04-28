<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 */
class Ticket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
  /**
     * @ORM\Column(type="string")
     */
    protected $num;

    
    /**
         * @ORM\Column(type="date")
         */
        private $date;

    /**
         *
         * @ORM\Column(type="string")
         */
        private $heure;

        /**
        * @ORM\ManyToOne(targetEntity="Entreprise", inversedBy="ticket")
        * @ORM\JoinColumn(nullable=false)
        */
        private $entreprise;

    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="ticket")
     * @ORM\JoinColumn(nullable=false)
     */
        private $user;

        private $dateTemp;


    public function getId(): ?int
    {
        return $this->id;
    }
    public function getDateTemp(): ?string
    {
        return $this->dateTemp;
    }

    public function setDateTemp(string $dateTemp): self
    {
        $this->dateTemp = $dateTemp;

        return $this;
    }
    public function getNum(): ?string
    {
        return $this->num;
    }

    public function setNum(string $num): self
    {
        $this->num = $num;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getHeure(): ?string
    {
        return $this->heure;
    }

    public function setHeure(string $heure): self
    {
        $this->heure = $heure;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }
}
