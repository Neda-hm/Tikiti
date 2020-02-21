<?php

namespace App\Controller\Admin;

use App\Entity\UserProfessionnel;
use App\Form\UserProfessionnelType;
use App\Repository\UserProfessionnelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/user/professionnel")
 */
class UserProfessionnelController extends AbstractController
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    /**
     * @Route("/", name="user_professionnel_index", methods={"GET"})
     */
    public function index(UserProfessionnelRepository $userProfessionnelRepository): Response
    {
        return $this->render('admin/user_professionnel/index.html.twig', [
            'user_professionnels' => null,
        ]);
    }

    /**
     * @Route("/new", name="user_professionnel_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $userProfessionnel = new UserProfessionnel();
        $form = $this->createForm(UserProfessionnelType::class, $userProfessionnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $data = $form->getData();
            $plainPassword = $data->getUser()->getPassword();
            $encoded = $this->encoder->encodePassword($userProfessionnel->getUser(), $plainPassword);
            $userProfessionnel->getUser()->setPassword($encoded);      

            $entityManager->persist($userProfessionnel);
            $entityManager->flush();

            return $this->redirectToRoute('user_professionnel_index');
        }

        return $this->render('admin/user_professionnel/new.html.twig', [
            'user_professionnel' => $userProfessionnel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_professionnel_show", methods={"GET"})
     */
    public function show(UserProfessionnel $userProfessionnel): Response
    {
        return $this->render('admin/user_professionnel/show.html.twig', [
            'user_professionnel' => $userProfessionnel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_professionnel_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserProfessionnel $userProfessionnel): Response
    {
        $form = $this->createForm(UserProfessionnelType::class, $userProfessionnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_professionnel_index');
        }

        return $this->render('admin/user_professionnel/edit.html.twig', [
            'user_professionnel' => $userProfessionnel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_professionnel_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserProfessionnel $userProfessionnel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userProfessionnel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userProfessionnel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_professionnel_index');
    }
}
