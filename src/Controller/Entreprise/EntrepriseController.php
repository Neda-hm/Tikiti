<?php

namespace App\Controller\Entreprise;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class EntrepriseController extends AbstractController
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    /**
     * @Route("/register", name="entreprise_register", methods={"GET","POST"})
     */
    public function registre(Request $request): Response
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $data = $form->getData();
            $plainPassword = $data->getUser()->getPassword();
            $encoded = $this->encoder->encodePassword($entreprise->getUser(), $plainPassword);
            $entreprise->getUser()->setPassword($encoded);
            $entreprise->getUser()->AddRole('ADMIN_ENTREPRISE');        

            $entityManager->persist($entreprise);
            $entityManager->flush();  
            
            $entityManager->persist($entreprise);
            $entityManager->flush();

            return $this->redirectToRoute('profile_show', ["id" => $entreprise->getId()]);
        }

        return $this->render('entreprise/registre.html.twig', [
            'user_pro' => $entreprise,
            'form' => $form->createView(),
        ]);
    
    }

    /**
     * @Route("/mot_de_passe_oublie", name="userPro_motdepasseoublie", methods={"GET","POST"})
     */
    public function mdpoublie(): Response
    {
        return $this->render('entreprise/forgetpassword.html.twig');
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
    public function index(EntrepriseRepository $EntrepriseRepository): Response
    {
        return $this->render('entreprise/index.html.twig', [
            'Entreprise' => $EntrepriseRepository->findAll(),
            ]);
    }
}
