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
use Symfony\Component\Serializer\SerializerInterface;



class UserController extends FOSRestController
{

    
    private $userRepository;
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
        $length = 70;
        $this->randomString  = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
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
    
            $violations = $validator->validate($data, $constraint);
    
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
                ->setApiKey(md5($data['username'].$this->randomString))
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
                        'id' => $user->getId(),
                        'api_key' => $user->getApiKey()
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


            /**
             * @Route("/update-user/{id}", name="update_user", methods={"PUT"})
             * @param Request $request
             * @param UserManagerInterface $userManager
             */
            public function updateUser($id, UserManagerInterface $userManager, Request $request){

                $api_key = $request->get('api_key');
                $user = $this->userRepository->findOneBy(['api_key' => $api_key]);
                if ( !$user ) {
    
                    return new jsonResponse(["error"=> 'invalid token'],400);
                }

        //search user exp 3
        $user = $this->userRepository->find($id);

        // if user don't exist
        if ( !$user ) {

            
            return new JsonResponse(['error' => "Utilisateur n'existe pas"], 422);
        }

        // if nom != null then update nom
        empty($request->get('nom')) ? true : $user->setNom($request->get('nom'));
        empty($request->get('prenom')) ? true : $user->setPrenom($request->get('prenom'));
        empty($request->get('tel')) ? true : $user->setTel($request->get('tel'));
        empty($request->get('adress')) ? true : $user->setAdresse($request->get('adresse'));
        empty($request->get('codePostal')) ? true : $user->setCodePostale($request->get('codePostale'));
        empty($request->get('ville')) ? true : $user->setVille($request->get('ville'));
        empty($request->get('lat')) ? true : $user->setLat($request->get('lat'));
        empty($request->get('lng')) ? true : $user->setLng($request->get('lng'));
        empty($request->get('password')) ? true : $user->setPlainPassword($request->get('password'));

        try {
            $userManager->updateUser($user, true);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }        

       
        return new JsonResponse(["success" => "updated"], 200);

        /* example
        // On sauvegarde en base
        $userManager = $this->getDoctrine()->getManager();
        $userManager->persist($user);
        $userManager->flush();
        */
    }


            /**
             * @Route("/user/{id}", name="user", methods={"Get"})
             * @param Request $request
             * @param UserManagerInterface $userManager
             */
            public function AccountUser($id, UserManagerInterface $userManager, Request $request){

                $api_key = $request->get('api_key');
                $user = $this->userRepository->findOneBy(['api_key' => $api_key]);
                if ( !$user ) {
    
                    return new jsonResponse(["error"=> 'invalid token'],400);
                }

                $user = $this->userRepository->find($id);
                if ( !$user ) {

            
                    return new JsonResponse(['error' => "Utilisateur n'existe pas"], 422);
                }
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
}
}
