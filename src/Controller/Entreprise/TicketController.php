<?php

namespace App\Controller\Entreprise;

use App\Entity\Ticket;
use App\Form\Ticket1Type;
use App\Repository\TicketRepository;
use App\Entity\Entreprise;

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

    public function __construct(TicketRepository $TicketRepository )
    {
      
     
        $this->TicketRepository = $TicketRepository;
    }
    /**
     * @Route("/{id}", name="ticket_index_front", methods={"GET"})
     */
    public function index(TicketRepository $ticketRepository, Entreprise $entreprise): Response
    {
        return $this->render('entreprise/ticket/index.html.twig', [
            'tickets' => $ticketRepository->findBy(['entreprise' => $entreprise]),
            'entrepriseId' => $entreprise->getId(),
            'entreprise' => $entreprise
        ]);
    }

    /**
     * @Route("/new/{id}", name="ticket_new_front", methods={"GET","POST"})
     */
    public function new(Request $request, $id): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(Ticket1Type::class, $ticket);
        $form->handleRequest($request);
        if ($form->isSubmitted() ) {

            $entreprise = $this->getDoctrine()->getRepository('App:Entreprise')->find($id);

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
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('ticket_index_front', ['id' => $ticket->getEntreprise()->getId()]);
        }

        return $this->render('entreprise/ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ticket_show_front", methods={"GET"})
     */
    public function show(Ticket $ticket): Response
    {
        return $this->render('entreprise/ticket/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ticket_edit_front", methods={"GET","POST"})
     */
    public function edit(Request $request, Ticket $ticket): Response
    {
        $form = $this->createForm(Ticket1Type::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ticket_index_front', ['id' => $ticket->getEntreprise()->getId()]);
        }

        return $this->render('entreprise/ticket/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ticket_delete_front", methods={"DELETE"})
     */
    public function delete(Request $request, Ticket $ticket): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ticket_index_front', ['id' => $ticket->getEntreprise()->getId()]);
    }
}
