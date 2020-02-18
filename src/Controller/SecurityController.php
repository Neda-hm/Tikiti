<?php
/**
 * Created by PhpStorm.
 * User: Oumayma
 * Date: 29/05/2018
 * Time: 14:11
 */

namespace App\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;

use Symfony\Component\Security\Core\SecurityContextInterface;

class SecurityController extends BaseController
{
    public function renderLogin(array $data)
    {
        $requestAttributes = $this->container->get('request_stack')->getCurrentRequest()->attributes;

        if ('admin_login' === $requestAttributes->get('_route')) {
            return $this->render('admin/login.html.twig', $data);
        }else {
            return $this->render('FOSUserBundle:Security:login.html.twig', $data);
        }

    }

    /*public function checkAction()
    {
        echo "INside SecrutityController::checkAction";
    }*/

}