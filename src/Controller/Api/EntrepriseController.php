<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Entreprise;

class EntrepriseController extends AbstractFOSRestController
{
    /**
    
    * @Method("POST")
     * @param Request $request
     * @param Entreprise $entreprise
     * @return JsonResponse
     */
    public function createEntreprise(
        Request $request,
        Entreprise $entreprise
        )
    
        
    
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        if (is_null($data) || !isset($data['id']) || !isset($data['user']) || !isset($data['event']) || !isset($data['heures']) || !isset($data['UrlLogo']) || !isset($data['logo']) || !isset($data['updatedAt'])) {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $entreprise->entreprise(
                JsonResponse::HTTP_BAD_REQUEST, "Invalid JSON format"
            );

            return new JsonResponse($data, $status);
        }

        $result = $entreprise->create($data);
        if ($result instanceof Entreprise) {
            $status = JsonResponse::HTTP_CREATED;
            $data = [
                'data' => [
                    'id' => $result->getid()
                ]
            ];
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $entreprise->entreprise($status, $result);
        }
            if ($result instanceof Entreprise) {
                $status = JsonResponse::HTTP_CREATED;
                $data = [
                    'data' => [
                        'user' => $result->getuser()

                    ]
                ];
            } else {
                $status = JsonResponse::HTTP_BAD_REQUEST;
                $data = $entreprise->entreprise($status, $result);
        }
        if ($result instanceof Entreprise) {
            $status = JsonResponse::HTTP_CREATED;
            $data = [
                'data' => [
                    'event' => $result->getevent()
                ]
            ];
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $entreprise->entreprise($status, $result);
    }
                
                    if ($result instanceof Entreprise) {
                        $status = JsonResponse::HTTP_CREATED;
                        $data = [
                            'data' => [
                                'heures' => $result->getheures()
                            ]
                        ];
                    } else {
                        $status = JsonResponse::HTTP_BAD_REQUEST;
                        $data = $entreprise->entreprise($status, $result);
                }



                
                    if ($result instanceof Entreprise) {
                        $status = JsonResponse::HTTP_CREATED;
                        $data = [
                            'data' => [
                                'UrlLogo' => $result->getUrlLogo()
                            ]
                        ];
                    } else {
                        $status = JsonResponse::HTTP_BAD_REQUEST;
                        $data = $entreprise->entreprise($status, $result);
                }



                if ($result instanceof Entreprise) {
                    $status = JsonResponse::HTTP_CREATED;
                    $data = [
                        'data' => [
                            'logo' => $result->getLogo()
                        ]
                    ];
                } else {
                    $status = JsonResponse::HTTP_BAD_REQUEST;
                    $data = $entreprise->entreprise($status, $result);
            }


                
                if ($result instanceof Entreprise) {
                $status = JsonResponse::HTTP_CREATED;
                $data = [
                    'data' => [
                        'updatedAt' => $result->getupdatedAt()
                    ]
                ];
                } else {
                    $status = JsonResponse::HTTP_BAD_REQUEST;
                    $data = $entreprise->entreprise($status, $result);
            }


        return new JsonResponse($data, $status);

        

    }
}
