<?php

namespace App\Controller\Admin;

use App\Entity\Evenement;
use App\Entity\Entreprise;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use App\Repository\EntrepriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Mapping as ORM;
/**
 * @Route("/evenement")
 */
class EvenementController extends AbstractController
{

    private $entrepriseRepository;

    public function __construct(EntrepriseRepository $entrepriseRepository) {
        $this->entrepriseRepository = $entrepriseRepository;
    }
    /**
     * @Route("/{id}", name="evenement_index", methods={"GET"})
     */
    public function index(Entreprise $entreprise): Response
    {
        return $this->render('admin/evenement/index.html.twig', [
            'evenements' => $entreprise->getEvent(), 
            'entrepriseId' => $entreprise->getId(),
            'nomEntreprise' => $entreprise->getUser()->getUsername()
        ]);
    }

    /**
     * @Route("/new/{id}", name="evenement_new", methods={"GET","POST"})
     */
    public function new(Request $request, $id): Response
    {
        $evenement = new Evenement();
        $evenement->setEntreprise($this->entrepriseRepository->find($id));
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $data = $form->getData();

            $dateD = new \DateTime( $data->getDateDebutTemp());
            $dateF = new \DateTime($data->getDateFinTemp());
            if ($dateD > $dateF ) {

                $this->addFlash('error', 'Dete début doit être inférierur à la date fin');
                
            } else {

                $evenement->setDateDebut(new \DateTime( $data->getDateDebutTemp()));
                $evenement->setDateFin(new \DateTime($data->getDateFinTemp()));

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($evenement);
                $entityManager->flush();

                return $this->redirect($this->generateUrl('evenement_index',['id'=> $id]));
            }
        }

        return $this->render('admin/evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="evenement_show", methods={"GET"})
     */
    public function show(Evenement $evenement): Response
    {
        return $this->render('admin/evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="evenement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Evenement $evenement): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('evenement_index',['id'=> $evenement->getEntreprise()->getId()]));
        }

        return $this->render('admin/evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="evenement_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Evenement $evenement): Response
    {

        $id = $evenement->getEntreprise()->getId();
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirect($this->generateUrl('evenement_index',['id'=> $id]));
    }
}
