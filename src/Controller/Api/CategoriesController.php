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
use App\Repository\CategoriesRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\UserRepository;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class CategoriesController extends AbstractController
{
    private $CategoriesRepository;
    private $EntrepriseRepository;
    private $userRepository;
   

    public function __construct(CategoriesRepository $CategoriesRepository , 
    EntrepriseRepository $EntrepriseRepository, UserRepository $userRepository )
    {
      
        $this->CategoriesRepository = $CategoriesRepository;
        $this ->EntrepriseRepository = $EntrepriseRepository;
        $this->userRepository = $userRepository;
    }


        /**
         * @Route("/categories", name="categories",  methods={"Get"})
         * @param Request $request
         */

        public function Categories(Request $request){

            $api_key = $request->get('api_key');
            $user = $this->userRepository->findOneBy(['api_key' => $api_key]);
            if ( !$user ) {

                return new jsonResponse(["error"=> 'invalid token'],400);
            }


            $categories = $this->CategoriesRepository->findAll();
        
            $data = [];
            foreach ($categories as $categories) {
                $cat = [ 'nom' => $categories->getNom(),
                'icone' => 'public/uploads/categories/icones/'.$categories->getUrlIcone(),
                'id' => $categories->getId()];
                $data[] = $cat;
                return new jsonResponse(["categories"=> $data],200);


                }
        }

        /**
         * @Route("/categorie-entreprise/{id}", name="entreprise_categories_liste",  methods={"Get"})
         * @param Request $request
         */

            public function categoriesEntreprise(Request $request, $id) {

                $api_key = $request->get('api_key');
                $user = $this->userRepository->findOneBy(['api_key' => $api_key]);
                if ( !$user ) {
    
                    return new jsonResponse(["error"=> 'invalid token'],400);
                }

                

            //recherche catégorie par id
            $categorie = $this->CategoriesRepository->find($id);

            //puisque entreprise est relié avec la catégorie symfony recupère tout les entreprise en relation avec la catégorie qu'on a cherché
            $entreprises = $categorie->getEntreprises();

            $result = [];
            foreach ($entreprises as $entreprise) {
                $data = [
                    'id' => $entreprise->getId(),
                    'nom' => $entreprise->getUser()->getUsername(),
                    'logo' => 'public/uploads/entreprises/images/' . $entreprise->getUrlLogo() ,
                    'ville' => $entreprise->getUser()->getVille(),
                    'adresse' => $entreprise->getUser()->getAdresse(),
                    'email' => $entreprise->getUser()->getEmail(),
                    'tel' => $entreprise->getUser()->getTel(),
                    'codePostal' => $entreprise->getUser()->getCodePostale(),
                    'lat' => $entreprise->getUser()->getLat(),
                    'lng' => $entreprise->getUser()->getLng()

                ];

                $result[] = $data;
            }

            return new jsonResponse(["entreprises"=> $result],200);
         
            }

            /**
             * @Route("/entreprise/{id}", name="entreprise", methods={"Get"})
             * @param Request $request
             */
            
            public function entreprise($id, Request $request){

                $api_key = $request->get('api_key');
                $user = $this->userRepository->findOneBy(['api_key' => $api_key]);
                if ( !$user ) {
    
                    return new jsonResponse(["error"=> 'invalid token'],400);
                }

                $entreprise = $this->EntrepriseRepository->find($id);
                if ( !$entreprise ) {

            
                    return new JsonResponse(['error' => "entreprise n'existe pas"], 422);
                }
                    $result = [
                        'id' => $entreprise->getId(),
                        'username' => $entreprise->getUser()->getUsername(),
                        'ville' => $entreprise->getUser()->getVille(),
                        'adresse' => $entreprise->getUser()->getAdresse(),
                        'email' => $entreprise->getUser()->getEmail(),
                        'tel' => $entreprise->getUser()->getTel(),
                        'codePostal' => $entreprise->getUser()->getCodePostale(),
                        'lat' => $entreprise->getUser()->getLat(),
                        'lng' => $entreprise->getUser()->getLng(),
                    ];
                    
                        return new jsonResponse(['data' => $result],200);
}


        /**
         * @Route("/entreprise-event/{id}", name="entreprise_event_liste",  methods={"Get"})
         * @param Request $request
         */

        public function entrepriseEvent(Request $request, $id) {
            $api_key = $request->get('api_key');
            $user = $this->userRepository->findOneBy(['api_key' => $api_key]);
            if ( !$user ) {

                return new jsonResponse(["error"=> 'invalid token'],400);
            }

            $entreprise = $this->EntrepriseRepository->find($id);

            $event = $entreprise->getEvent();

            $result = [];
            foreach ($event as $event) {
                $data = [
                    'id' => $event->getId(),
                    'titre' => $event->getTitre(),
                    'dateDebut' =>$event->getDateDebut(),
                   'dateFin' =>$event->getDateFin(),
                   'heureDebut' =>$event->getheureDebut(),
                   'heureFin' =>$event->getheureFin(),
                   'dateDebutTemp' =>$event->getdateDebutTemp(),
                   'dateFinTemp' =>$event->getdateFinTemp(),


                ];

                $result[] = $data;
            }

            return new jsonResponse(["entreprises"=> $result],200);
         
            }        
        
    }