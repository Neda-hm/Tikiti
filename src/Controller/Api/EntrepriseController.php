<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\categorie;
use App\Entity\Entreprise;

class EntrepriseController extends AbstractController
{

             /**
             * @Route("/Categorie", name="categorie", methods={"Get"})
             * @param Request $request
             */
            public function categorie(Request $request){

                $data= ['Nom catégorie' => $request->get('categorie') ];

                    $validator = Validation::createValidator();

                    $constraint = new Assert\Collection(array(
                        'categorie 1' => new Assert\Length(array('min' => 1)),
                        'categorie 2' => new Assert\Length(array('min' => 1)),
                        'categorie 3' => new Assert\Length(array('min' => 1)),
                        'categorie 4' => new Assert\Length(array('min' => 1)),
                    ));
    
                    $violations = $validator->validate($data, $constraint);
    
                 if ($violations->count() > 0) {
                     return new JsonResponse(["error" => (string)$violations], 500);
                  }

                  $entreprise = new entreprise();
                  $entreprise
                      ->setcategorie1($data['categorie1'])
                      ->setcategorie2($data['categorie2'])
                      ->setcategorie3($data['categorie3'])
                      ->setcategorie4($data['categorie4']);

                    $result = ['Categorie ' => $data->getcategorie()];
                    
                        return new jsonResponse(['data' => $result],200);
}
}
