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
use App\Entity\ticket;


use App\Entity\user;
use App\Entity\Entreprise;
use App\Repository\UserRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;

class TicketController extends AbstractController
{
    private $userRepository;
    private $EntrepriseRepository;
    private $em;
    private $TicketRepository;

   

    public function __construct(UserRepository $userRepository , EntrepriseRepository $EntrepriseRepository,
        EntityManagerInterface $em , TicketRepository $TicketRepository )
    {
      
        $this->userRepository = $userRepository;
        $this ->EntrepriseRepository = $EntrepriseRepository;
        $this->em = $em;
        $this->TicketRepository = $TicketRepository;
    }

    /**
     * @Route("/reserver-ticket", name="ticket", methods={"Post"})
     */
   public function Ticket(Request $request)
    {

        $api_key = $request->get('api_key');
            $user = $this->userRepository->findOneBy(['api_key' => $api_key]);
            if ( !$user ) {

                return new jsonResponse(["error"=> 'invalid token'],400);
            }
        $user = $this->userRepository->find($request->get('user_id'));
        $entreprise = $this->EntrepriseRepository->find($request->get('entreprise_id'));

       
        
        
        $ticket = new Ticket();
        $ticket
        ->setDate(new \DateTime($request->get('date')))
        ->setHeure($request->get('heure'))
        ->setUser($user)
        ->setEntreprise($entreprise);
        $d_ticket = $this->TicketRepository->findOneBy(['entreprise' => $entreprise , 'date'=>$ticket->getDate()], ['id' => 'DESC']);
        if ($d_ticket)
     {  if ($ticket->getDate()==$d_ticket->getDate())
      { $num= $d_ticket->getNum() ;
          $ticket->setNum($num+1);}
       else{
       $ticket->setNum(1);}
    }else $ticket->setNum(1);
       
        
        try {
            $this->em->persist($ticket);
            $this->em->flush();

        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }        

       
        return new JsonResponse(["success" => "registred"], 200);
        
    }
    
            /**
             * @Route("/user-ticket/{id}", name="ticket_user", methods={"Get"})
             * @param Request $request
             */
            
            public function TicketUser ($id, Request $request){

                $api_key = $request->get('api_key');
                $user = $this->userRepository->findOneBy(['api_key' => $api_key]);
                if ( !$user ) {
    
                    return new jsonResponse(["error"=> 'invalid token'],400);
                }
                $user = $this->userRepository->find($id);

                $ticket = $user->getticket();

                $result = [];

                foreach ($ticket as $ticket) {
                    $data = [
                    'Num' => $ticket->getnum(),
                    'Entreprise' => $ticket->getEntreprise()->getUser()->getUsername(),
                    'Utilisateur' => $ticket->getUser()->getUsername(),
                    'Date' => $ticket->getdate(),
                    'Heure' => $ticket->getheure(),
    
                    ];
                    $result[] = $data;
                }
                        return new jsonResponse(['data' => $result],200);
                    
                     }


}

