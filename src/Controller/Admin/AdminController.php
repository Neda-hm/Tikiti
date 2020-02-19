<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/index", name="admin_homepage")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }
}
