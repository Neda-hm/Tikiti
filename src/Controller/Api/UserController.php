<?php

namespace App\Controller\Api;

use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Repository\UserRepository;

class UserController extends FOSRestController
{

    
    private $userRepository;
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
    }


        /**
         * @Route("/register", name="api_auth_register",  methods={"POST"})
         * @param Request $request
         * @param UserManagerInterface $userManager
         * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
         */
        public function register(Request $request, UserManagerInterface $userManager)
        {
            $data= [
                'username' => $request->get('username'),
                'password' => $request->get('password'),
                'email' => $request->get('email'),
                'nom' => $request->get('nom'),
                'prenom' => $request->get('prenom'),
                'ville' => $request->get('ville'),
                'adresse' => $request->get('adresse'),
                'lat' => $request->get('lat'),
                'lng' => $request->get('lng'),
                'codePostale' => $request->get('codePostale'),
                'telephone' => $request->get('telephone')
        ];
    
            $validator = Validation::createValidator();
    
            $constraint = new Assert\Collection(array(
                // the keys correspond to the keys in the input array
                'username' => new Assert\Length(array('min' => 1)),
                'password' => new Assert\Length(array('min' => 1)),
                'email' => new Assert\Email(),
                'nom' => new Assert\Length(array('min' => 1)),
                'prenom' => new Assert\Length(array('min' => 1)),
                'ville' => new Assert\Length(array('min' => 1)),
                'adresse' => new Assert\Length(array('min' => 1)),
                'lat' => new Assert\Length(array('min' => 1)),
                'lng' => new Assert\Length(array('min' => 1)),
                'codePostale' => new Assert\Length(array('min' => 1)),
                'telephone' => new Assert\Length(array('min' => 1)),
            ));
    
            $violations = $validator->validate($data1, $constraint);
    
            if ($violations->count() > 0) {
                return new JsonResponse(["error" => (string)$violations], 500);
            }

            // Find user by username
            $user = $userManager->findUserBy(['username' => $data['username']]);
            
            // Test if user exist
            if( $user ) {
                return new JsonResponse(["error" => 'Username existe'], 403);
            }

             // Find user by email
             $user = $userManager->findUserBy(['email' => $data['email']]);
            
             // Test if email exist
             if( $user ) {
                 return new JsonResponse(["error" => 'email existe'], 403);
             }

    
            $user = new User();
            $user
                ->setUsername($data['username'])
                ->setPlainPassword($data['password'])
                ->setEmail($data['email'])
                ->setEnabled(true)
                ->setRoles(['ROLE_USER'])
                ->setSuperAdmin(false)
                ->setNom($data['nom'])
                ->setPrenom($data['prenom'])
                ->setAdresse($data['adresse'])
                ->setLat($data['lat'])
                ->setLng($data['lng'])
                ->setTel($data['telephone'])
                ->setVille($data['ville'])
                ->setCodePostale($data['codePostale'])
            ;
    
            try {
                $userManager->updateUser($user, true);
            } catch (\Exception $e) {
                return new JsonResponse(["error" => $e->getMessage()], 500);
            }        
    
           
            return new JsonResponse(["success" => "registred"], 200);
            
        }
        /**
         * @Route("/login", name="api_auth_login",  methods={"POST"})
         * @param Request $request
         * @param UserManagerInterface $userManager
         * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
         */
        public function login(Request $request, UserManagerInterface $userManager)
        {
          

            $data = [
                'username' => $request->get('username'),
                'password' => $request->get('password'),

            ];

            $validator = Validation::createValidator();

            $constraint = new Assert\Collection(array(
                // the keys correspond to the keys in the input array
                'username' => new Assert\Length(array('min' => 1)),
                'password' => new Assert\Length(array('min' => 1)),
               

            ));
            
            $violations = $validator->validate($data, $constraint);


            if ($violations->count() > 0) {
                return new JsonResponse(["error" => (string)$violations], 500);
            }

            // Find user by email or username
            $user = $this->userRepository->findByUsernameEmail($data['username']);
           
                    
            if( $user instanceof User) {
                
                if ( $this->encoder->isPasswordValid($user, $data['password']) ) {

                    $result = [
                        'username' => $user->getUsername(),
                        'nom' => $user->getNom(),
                        'prenom' => $user->getPrenom(),
                        'ville' => $user->getVille(),
                        'adresse' => $user->getAdresse(),
                        'email' => $user->getEmail(),
                        'tel' => $user->getTel(),
                        'codePostal' => $user->getCodePostale(),
                        'id' => $user->getId()
                    ];
                    
                  return new jsonResponse(['data' => $result],200);
              
                     } else {

                    return new JsonResponse(["error" => 'Email ou mot de passe éronné'], 403);

                }

            } 
             else {
                
                return new JsonResponse(["error" => 'Email ou mot de passe éronné'], 403);

            }   

            $violations = $validator->validate($data, $constraint); 
    }

}
