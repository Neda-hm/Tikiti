<?php

namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\AdminType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository ;
use App\Repository\TicketRepository;

class AdminController extends AbstractController
{

    private $userRepository;

    public function __construct(UserRepository $userRepository , TicketRepository $TicketRepository){
        $this->userRepository = $userRepository;
        $this->TicketRepository = $TicketRepository;
    }


    /**
     * @Route("/index", name="admin_homepage")
     */
    public function index()
    {
      
   
        $nbrTotal = count($this->userRepository->findAll());
        $nbUser = count($this->userRepository->findBy(['userPro' => null]));
        $nbrTicket = count($this->TicketRepository->findAll());

 
        $nbrEntreprise = $nbrTotal - $nbUser;
          
        $pourcentUser = round(($nbUser));
        $pourcentEntreprise = round(($nbrEntreprise ));
        $pourcentTicket = round(($nbrTicket ));

 
         return $this->render('admin/index.html.twig', [ 'pourcentTicket' =>  $pourcentTicket ,'pourcentUser' => $pourcentUser ,'pourcentEnt' => $pourcentEntreprise]) ;
    } 
    
     /**
     * @Route("/profile", name="admin_show", methods={"GET"})
     */
    public function show()
    {
        $admin = $this->getUser();
        return $this->render('admin/profile/show.html.twig', [
            'admin' => $admin,
        ]);
    }
     /**
     * @Route("/edit", name="admin_edit", methods={"GET","POST"})
     */
    public function edit(Request $request)
    {
        $admin = $this->getUser();

        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $data = $form->getData();

        
            return $this->redirectToRoute('admin_show');

        }
        return $this->render('admin/profile/edit.html.twig', [
            'admin' => $admin,
            'form' => $form->createView(),
        ]);
    
}
}
