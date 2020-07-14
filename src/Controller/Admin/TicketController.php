<?php

namespace App\Controller\Admin;

use App\Entity\Ticket;
use App\Entity\Entreprise;

use App\Form\TicketType;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ticket")
 */
class TicketController extends AbstractController
{
    private $TicketRepository;

    public function __construct(TicketRepository $TicketRepository) {
        $this->TicketRepository = $TicketRepository;
    }
    /**
     * @Route("/", name="ticket_index", methods={"GET"})
     */
    public function index(TicketRepository $TicketRepository): Response
    {
        return $this->render('admin/ticket/index.html.twig', [
            'tickets' => $TicketRepository->findAll(),
            ]);
    }

    /**
     * @Route("/new", name="ticket_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);
        $entreprise = $ticket->getEntreprise();
        
        if ($form->isSubmitted() ) {

            $ticket->setDate(new \DateTime($ticket->getDateTemp()));
            $d_ticket = $this->TicketRepository->findOneBy(['entreprise' => $entreprise , 'date'=>$ticket->getDate()], ['id' => 'DESC']);
            if ($d_ticket)
         {  if ($ticket->getDate()==$d_ticket->getDate())
          { $num= $d_ticket->getNum() ;
              $ticket->setNum($num+1);}
           else{
           $ticket->setNum(1);}
        }else $ticket->setNum(1);
            $ticket->setEntreprise($entreprise);



           

           // $ticket->setDate(new \DateTime($ticket->getDateTemp()));
           // $ticket->setNum($ticket->getUser()->getId().$randomString);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('ticket_index');
        }

        return $this->render('admin/ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }
    

    /**
     * @Route("/{id}", name="ticket_show", methods={"GET"})
     */
    public function show(Ticket $ticket): Response
    {
        return $this->render('admin/ticket/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ticket_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ticket $ticket): Response
    {
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            $ticket->setDate(new \DateTime($ticket->getDateTemp()));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ticket_index');
        }

        return $this->render('admin/ticket/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ticket_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Ticket $ticket): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ticket_index');
    }
}
