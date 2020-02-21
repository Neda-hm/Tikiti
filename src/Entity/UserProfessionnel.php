<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserProfessionnelRepository")
 */
class UserProfessionnel
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

     /**
     * 
     * @ORM\Column(type="string")
     */
    private $UrlLogo;

   
    
    public function getUrlLogo(): ?string
    {
        return $this->UrlLogo;
    }

    public function setUrlLogo(string $UrlLogo): self
    {
        $this->UrlLogo = $UrlLogo;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

   

}
