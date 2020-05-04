<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Entreprise;


/**
 * @ORM\Entity(repositoryClass="App\Repository\HoraireTravailRepository")
 */
class HoraireTravail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
    * @ORM\ManyToOne(targetEntity="Entreprise", inversedBy="heures")
    */
    private $entreprise;
     /**
     *
     * @ORM\Column(type="string")
     */
    private $jours;
       /**
     *
     * @ORM\Column(type="string")
     */
    private $heureDebutMatin;
     /**
     *
     * @ORM\Column(type="string")
     */
    private $heureFinMatin;
       /**
     *
     * @ORM\Column(type="string")
     */
    private $heureDebutAp;
  /**
     *
     * @ORM\Column(type="string")
     */
    private $heureFinAp;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeureDebutMatin()
    {
        return $this->heureDebutMatin;
    }

    public function setHeureDebutMatin($heureDebutMatin): self
    {
        $this->heureDebutMatin = $heureDebutMatin;

        return $this;
    }

    public function getHeureFinMatin()
    {
        return $this->heureFinMatin;
    }

    public function setHeureFinMatin($heureFinMatin): self
    {
        $this->heureFinMatin = $heureFinMatin;

        return $this;
    }

    public function getHeureDebutAp()
    {
        return $this->heureDebutAp;
    }

    public function setHeureDebutAp($heureDebutAp): self
    {
        $this->heureDebutAp = $heureDebutAp;

        return $this;
    }

    public function getHeureFinAp()
    {
        return $this->heureFinAp;
    }

    public function setHeureFinAp($heureFinAp): self
    {
        $this->heureFinAp = $heureFinAp;

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
   
    
    public function getJours(): ?string
    {
        return $this->jours;
    }

    public function setJours(string $jours): self
    {
        $this->jours = $jours;

        return $this;
    }
    
    
  }