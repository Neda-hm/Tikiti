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

    /**
     * @Route("/map", name="map_front", methods={"GET"})
     */
    public function Map(EntrepriseRepository $EntrepriseRepository): Response
    {


        return $this->render('entreprise/_map.html.twig', [
            'Entreprise' => $EntrepriseRepository->findAll(),
            ]);
    }
}
