<?php

namespace App\Controller\Entreprise;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use App\Repository\TicketRepository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class EntrepriseController extends AbstractController
{
    private $encoder;
    private $userRepository;
    private $TicketRepository;

    public function __construct(UserPasswordEncoderInterface $encoder,UserRepository $userRepository, TicketRepository $TicketRepository ) {
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
        $this->TicketRepository= $TicketRepository;


    }

    

     /**
     * @Route("/profile/{id}", name="profile_show", methods={"GET"})
     */
    public function profile(Entreprise $entreprise)
    {
        return $this->render('entreprise/profile.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }

    
    /**
     * @Route("/{id}/edit", name="entreprise_edit_front", methods={"GET","POST"})
     */
    public function edit(Request $request, entreprise $entreprise): Response
    {
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $data = $form->getData();

            return $this->redirectToRoute('entreprise_index_front');

        }

        return $this->render('entreprise/edit.html.twig', [
            'entreprise' =>$entreprise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/index", name="entreprise_index_front", methods={"GET"})
     */
    public function index(EntrepriseRepository $EntrepriseRepository,UserRepository $userRepository): Response
    {
        $entreprise = $this->getUser()->getUserPro();
        
        $evenements = $this->getDoctrine()->getRepository("App:Evenement")->findBy(['id'=>$entreprise]);
 

        $ticket = $this->TicketRepository->findOneBy(['id' => $entreprise->getId()], ['id' => 'DESC']);
        $numServi = $this->TicketRepository->numServi($entreprise);


         return $this->render(
         	'entreprise/index.html.twig', 
         	['nbrReservation' => $numServi, 'nbrEvent' => sizeof($evenements), 'nbrClient'=> $numServi]
         ) ;
        
    }


    
}