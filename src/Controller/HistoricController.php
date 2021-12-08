<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoricController extends AbstractController
{
    #[Route('/historic', name: 'historic')]
    public function index(): Response
    {
/*         $historics = $this->getDoctrine()
             ->getRepository(Historic::class)
             ->findAll();

        return $this->render('historic/index.html.twig', [
            'historics' => $historics,
        ]); */
        return $this->render('historic/index.html.twig');
    }
}
