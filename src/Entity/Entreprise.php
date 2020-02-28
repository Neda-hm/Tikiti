<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Type;
use App\Entity\User;
use App\Entity\Evenement;
use App\Entity\EntrepriseType;
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

    public function __construct()
    {
        $this->event = new ArrayCollection();
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

    
}
