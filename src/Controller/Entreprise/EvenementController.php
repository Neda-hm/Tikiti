<?php

namespace App\Controller\Entreprise;

use App\Entity\Evenement;
use App\Entity\Entreprise;
use App\Form\Evenement1Type;
use App\Entity\EntrepriseType;

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
     * @Route("/index/{id}", name="evenement_index_front", methods={"GET"})
     */
    public function index(EvenementRepository $evenementRepository, Entreprise $entreprise): Response
    {
        return $this->render('entreprise/evenement/index.html.twig', [
            'evenements' => $evenementRepository->findBy(['entreprise' => $entreprise]),
            'entrepriseId' => $entreprise->getId(),
            'entreprise' => $entreprise
        ]);
    }

    /**
     * @Route("/new/{id}", name="evenement_new_front", methods={"GET","POST"})
     */
    public function new(Request $request, $id): Response
    {
        $evenement = new Evenement();
        $evenement->setEntreprise($this->entrepriseRepository->find($id));
        $form = $this->createForm(Evenement1Type::class, $evenement);
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

                return $this->redirect($this->generateUrl('evenement_index_front',['id'=> $id]));
            }
        }

        return $this->render('entreprise/evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="evenement_edit_front", methods={"GET","POST"})
     */
    public function edit(Request $request, Evenement $evenement): Response
    {
        $form = $this->createForm(Evenement1Type::class, $evenement);
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

            }

            return $this->redirectToRoute('evenement_index_front', ['id'=>$evenement->getEntreprise()->getId()]);
        }

        return $this->render('entreprise/evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="evenement_delete_front", methods={"DELETE"})
     */
    public function delete(Request $request, Evenement $evenement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('evenement_index_front', ['id'=>$evenement->getEntreprise()->getId()]);
    }
}
