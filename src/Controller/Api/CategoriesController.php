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
use FOS\UserBundle\Model\categoriesManagerInterface;
use App\Repository\CategoriesRepository;




class CategoriesController extends AbstractController
{

           /**
         * @Route("/Categorie", name="categories",  methods={"POST"})
         * @param Request $request
         * @param categoriesManagerInterface $categoriesManager
         * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
         */
        public function Categories(CategoriesRepository $CategoriesRepository)
{


    $categorie = $CategoriesRepository->findAll();

    // On spécifie qu'on utilise l'encodeur JSON
    $encoders = [new JsonEncoder()];

    // On instancie le "normaliseur" pour convertir la collection en tableau
    $normalizers = [new ObjectNormalizer()];

    // On instancie le convertisseur
    $serializer = new Serializer($normalizers, $encoders);

    // On convertit en json
    $jsonContent = $serializer->serialize($categorie, 'json', [
        'categorie' => function ($object) {
            return $object->getId();
        }
    ]);

    // On instancie la réponse
    $response = new Response($jsonContent);

    // On ajoute l'entête HTTP
    $response->headers->set('Content-Type', 'application/json');

    
          return new jsonResponse(['categories' => $response],200);
      
        }


}
