<?php

namespace App\Controller\Admin;

use App\Entity\HoraireTravail;
use App\Form\HoraireTravailType;
use App\Repository\HoraireTravailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EntrepriseRepository;
use App\Entity\Entreprise;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotNullValidator;

/**
 * @Route("/horaire")
 */
class HoraireTravailController extends AbstractController
{
    private $entrepriseRepository;

    public function __construct(EntrepriseRepository $entrepriseRepository) {
        $this->entrepriseRepository = $entrepriseRepository;
    }
    /**
     * @Route("/{id}", name="horaire_index", methods={"GET"})
     */
    public function index(Entreprise $entreprise): Response
    {
        return $this->render('admin/horaire_travail/index.html.twig', [
            'horaire_travails' => $entreprise->getHeures(),
            'nomEntreprise' => $entreprise->getUser()->getUsername(),
            'entrepriseId' => $entreprise->getId(),
        
        ]);
    }

    /**
     * @Route("/new/{id}", name="horaire_new", methods={"GET","POST"})
     */
    public function new(Request $request, $id): Response
    {
        $horaireTravail = new HoraireTravail();
        $horaireTravail->setEntreprise($this->entrepriseRepository->find($id));

        $form = $this->createForm(HoraireTravailType::class, $horaireTravail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($horaireTravail);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('horaire_index',['id'=> $id]));
        }

        return $this->render('admin/horaire_travail/new.html.twig', [
            'horaire_travail' => $horaireTravail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="horaire_show", methods={"GET"})
     */
    public function show(HoraireTravail $horaireTravail): Response
    {
        return $this->render('admin/horaire_travail/show.html.twig', [
            'horaire_travail' => $horaireTravail,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="horaire_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, HoraireTravail $horaireTravail): Response
    {
        $form = $this->createForm(HoraireTravailType::class, $horaireTravail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            


            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('horaire_index',['id'=> $horaireTravail->getEntreprise()->getId()]));
        }

        return $this->render('admin/horaire_travail/edit.html.twig', [
            'horaire_travail' => $horaireTravail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="horaire_delete", methods={"DELETE"})
     */
    public function delete(Request $request, HoraireTravail $horaireTravail): Response
    {
        if ($this->isCsrfTokenValid('delete'.$horaireTravail->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($horaireTravail);
            $entityManager->flush();
        }

        return $this->redirect($this->generateUrl('horaire_index',['id'=> $horaireTravail->getEntreprise()->getId()]));
    }
}
