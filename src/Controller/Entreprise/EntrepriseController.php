<?php

namespace App\Controller\Entreprise;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
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


    public function __construct(UserPasswordEncoderInterface $encoder,UserRepository $userRepository) {
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;

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
          //  $entreprise->setLat($data->getLat());
           // $entreprise->setLng($data->getLng());
            

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
        $reservations = $this->getDoctrine()->getRepository("App:Ticket")->findBy(['entreprise'=>$entreprise]);
        $evenements = $this->getDoctrine()->getRepository("App:Evenement")->findBy(['entreprise'=>$entreprise]);
        $nbrClient = $this->getDoctrine()->getRepository("App:Ticket")->countClients($entreprise);
 
         return $this->render(
         	'entreprise/index.html.twig', 
         	['nbrReservation' => sizeof($reservations), 'nbrEvent' => sizeof($evenements), 'nbrClient'=> $nbrClient]
         ) ;
        
    }


    
}