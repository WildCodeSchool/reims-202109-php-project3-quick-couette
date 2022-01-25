<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoreController extends AbstractController
{
    public const ARTICLES = [
        [
            'name' => 'King size',
            'length' => 260,
            'width' => 240,
        ],
        [
            'name' => 'Double standard',
            'length' => 240,
            'width' => 220,
        ],
        [
            'name' => 'Oreiller',
            'length' => 60,
            'width' => 60,
        ],
    ];

    #[Route('/store', name: 'store')]
    public function index(): Response
    {
        return $this->render('store/index.html.twig', [
            'controller_name' => 'StoreController',
        ]);
    }
}
