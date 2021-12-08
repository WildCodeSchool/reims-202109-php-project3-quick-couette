<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Order;

class HistoricController extends AbstractController
{
    #[Route('/historic', name: 'historic')]
    public function index(): Response
    {
         $orders = $this->getDoctrine()
             ->getRepository(Order::class)
             ->findAll();

        return $this->render('historic/index.html.twig', [
            'historics' => $orders,
        ]);
    }
}
