<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

#[Route('/profil', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('/', name: 'show')]
    public function show(): Response
    {
        return $this->render('user/show.html.twig', [
        ]);
    }
}
