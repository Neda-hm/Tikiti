<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Entreprise;
use App\Entity\Categories;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoriesRepository")
 * @Vich\Uploadable
 */
class Categories
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
    private $nom;

    /**
    * @ORM\Column(type="string")
    */
   private $urlIcone;

    /**
    * @Vich\UploadableField(mapping="categorie_icone", fileNameProperty="urlIcone")
    * @var File
    */
   private $icone;
   /**
    * @ORM\Column(type="datetime")
    * @var \DateTime
    */
   private $updatedAt;

    /**
     * Une catégorie peut avoir plusieur entreprises
     * @ORM\OneToMany(targetEntity="Entreprise", mappedBy="categorie")
     */
    private $entreprises;

    public function __construct()
    {
        $this->entreprises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Entreprise[]
     */
    public function getEntreprises(): Collection
    {
        return $this->entreprises;
    }

    public function addEntreprise(Entreprise $entreprise): self
    {
        if (!$this->entreprises->contains($entreprise)) {
            $this->entreprises[] = $entreprise;
            $entreprise->setCategorie($this);
        }

        return $this;
    }

    public function removeEntreprise(Entreprise $entreprise): self
    {
        if ($this->entreprises->contains($entreprise)) {
            $this->entreprises->removeElement($entreprise);
            // set the owning side to null (unless already changed)
            if ($entreprise->getCategorie() === $this) {
                $entreprise->setCategorie(null);
            }
        }

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getUrlIcone(): ?string
    {
        return $this->urlIcone;
    }

    public function setUrlIcone(string $urlIcone): self
    {
        $this->urlIcone = $urlIcone;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function setIcone(File $icone = null)
    {
        $this->icone = $icone;

        
        if ($icone) {
        
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getIcone()
    {
        return $this->icone;
    }

   
}
