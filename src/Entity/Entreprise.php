<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Type;
use App\Entity\User;
use App\Entity\Ticket;
use App\Entity\Evenement;
use App\Entity\HoraireTravail;
use App\Entity\Categories;
use App\Entity\Entreprise;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntrepriseRepository")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Entreprise
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\OneToOne(targetEntity="User", mappedBy="userPro", cascade={"persist", "remove"})
     */
    private $user;
    /**
    * @ORM\OneToMany(targetEntity="Evenement", cascade={"remove"}, mappedBy="entreprise")
    */
    private $event;

    /**
         * @ORM\OneToMany(targetEntity="Ticket", cascade={"remove"}, mappedBy="entreprise")
        */
    private $ticket;

    /**
     * Plusieurs entreprises peuvent avoir une catégorie 
     * @ORM\ManyToOne(targetEntity="Categories", inversedBy="entreprise")
    */
     private $categorie;

    /**
    * @ORM\OneToMany(targetEntity="HoraireTravail", cascade={"remove"}, mappedBy="entreprise")
    */
    private $heures;
     /**
     * @ORM\Column(type="string")
     */
    private $UrlLogo;
     /**
     * @Vich\UploadableField(mapping="entreprises_images", fileNameProperty="UrlLogo")
     * @var File
     */
    private $logo;
    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;
      /**
     * @ORM\Column(type="string")
     */
    protected $num_servi;

    public function __construct()
    {
        $this->event = new ArrayCollection();
        $this->heures = new ArrayCollection();
        $this->ticket = new ArrayCollection();
    }
    
    public function setLogo(File $UrlLogo = null)
    {
        $this->logo = $UrlLogo;

        
        if ($UrlLogo) {
        
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getLogo()
    {
        return $this->logo;
    }
    public function setUrlLogo($UrlLogo)
    {
        $this->UrlLogo = $UrlLogo;
    }

    public function getUrlLogo()
    {
        return $this->UrlLogo;
    }
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    public function setUpdatedAt(\DateTime $datetime)
    {
        $this->updatedAt = $datetime;
    
        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newUserPro = null === $user ? null : $this;
        if ($user->getUserPro() !== $newUserPro) {
            $user->setUserPro($newUserPro);
        }

        return $this;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getEvent(): Collection
    {
        return $this->event;
    }

    public function addEvent(Evenement $event): self
    {
        if (!$this->event->contains($event)) {
            $this->event[] = $event;
            $event->setEntreprise($this);
        }

        return $this;
    }

    public function removeEvent(Evenement $event): self
    {
        if ($this->event->contains($event)) {
            $this->event->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getEntreprise() === $this) {
                $event->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|HoraireTravail[]
     */
    public function getHeures(): Collection
    {
        return $this->heures;
    }

    public function addHeure(HoraireTravail $heure): self
    {
        if (!$this->heures->contains($heure)) {
            $this->heures[] = $heure;
            $heure->setEntreprise($this);
        }

        return $this;
    }

    public function removeHeure(HoraireTravail $heure): self
    {
        if ($this->heures->contains($heure)) {
            $this->heures->removeElement($heure);
            // set the owning side to null (unless already changed)
            if ($heure->getEntreprise() === $this) {
                $heure->setEntreprise(null);
            }
        }

        return $this;
    }

    public function getCategorie(): ?Categories
    {
        return $this->categorie;
    }

    public function setCategorie(?Categories $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTicket(): Collection
    {
        return $this->ticket;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->ticket->contains($ticket)) {
            $this->ticket[] = $ticket;
            $ticket->setEntreprise($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->ticket->contains($ticket)) {
            $this->ticket->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getEntreprise() === $this) {
                $ticket->setEntreprise(null);
            }
        }

        return $this;
    }

    public function getNumServi(): ?string
    {
        return $this->num_servi;
    }

    public function setNumServi(string $num_servi): self
    {
        $this->num_servi = $num_servi;

        return $this;
    }


   
}
