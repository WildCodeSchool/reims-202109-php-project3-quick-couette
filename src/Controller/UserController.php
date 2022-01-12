<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

#[Route('/profil', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('/', name: 'show')]
    /**
    * @IsGranted("IS_AUTHENTICATED_FULLY")
    */
    public function index(): Response
    {
        return $this->render('user/show.html.twig', [
        ]);
    }
}
