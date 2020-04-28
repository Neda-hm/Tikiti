<?php

namespace App\Controller\Entreprise;

use App\Entity\HoraireTravail;
use App\Entity\Entreprise;
use App\Form\HoraireTravail1Type;
use App\Repository\HoraireTravailRepository;
use App\Repository\EntrepriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/horaire-travail")
 */
class HoraireTravailController extends AbstractController
{
    private $entrepriseRepository;

    public function __construct(EntrepriseRepository $entrepriseRepository) {
        $this->entrepriseRepository = $entrepriseRepository;
    }

    /**
     * @Route("/{id}", name="horaire_travail_index_front", methods={"GET"})
     */
    public function index(HoraireTravailRepository $horaireTravailRepository, Entreprise $entreprise): Response
    {
        return $this->render('entreprise/horaire_travail/index.html.twig', [
            'horaire_travails' => $horaireTravailRepository->findAll(),
            'entreprise' => $entreprise
        ]);
    }

    /**
     * @Route("/new/{id}", name="horaire_travail_new_front", methods={"GET","POST"})
     */
    public function new(Request $request , $id): Response
    {
        $horaireTravail = new HoraireTravail();
        $entreprise = $this->entrepriseRepository->find($id);
        $horaireTravail->setEntreprise($entreprise);

        $form = $this->createForm(HoraireTravail1Type::class, $horaireTravail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($horaireTravail);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('horaire_travail_index_front',['id'=> $id]));
        }

        return $this->render('entreprise/horaire_travail/new.html.twig', [
            'horaire_travail' => $horaireTravail,
            'form' => $form->createView(),
            'entreprise' => $entreprise
        ]);
    }

     /**
     * @Route("/{id}/show", name="horaire_travail_show_front", methods={"GET"})
     */
    public function show(Request $request, HoraireTravail $horaireTravail): Response
    {
        return $this->render('entreprise/horaire_travail/show.html.twig', [
            'horaire_travail' => $horaireTravail,
        ]);
    }
    

    /**
     * @Route("/{id}/edit", name="horaire_travail_edit_front", methods={"GET","POST"})
     */
    public function edit(Request $request, HoraireTravail $horaireTravail): Response
    {
        $form = $this->createForm(HoraireTravail1Type::class, $horaireTravail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('horaire_travail_index_front', ['id'=>$horaireTravail->getEntreprise()->getId()]);
        }

        return $this->render('entreprise/horaire_travail/edit.html.twig', [
            'horaire_travail' => $horaireTravail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="horaire_travail_delete_front", methods={"DELETE"})
     */
    public function delete(Request $request, HoraireTravail $horaireTravail): Response
    {
        if ($this->isCsrfTokenValid('delete'.$horaireTravail->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($horaireTravail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('horaire_travail_index_front', ['id'=>$horaireTravail->getEntreprise()->getId()]);
    }
}
