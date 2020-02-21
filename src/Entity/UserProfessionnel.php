<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserProfessionnelRepository")
 */
class UserProfessionnel extends User
{

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

   

}
