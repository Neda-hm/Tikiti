<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/login-admin", name="login_admin")
     */
    public function login()
    {
        $nom = "admin";
        return $this->render('admin/login.html.twig');
    }
}
