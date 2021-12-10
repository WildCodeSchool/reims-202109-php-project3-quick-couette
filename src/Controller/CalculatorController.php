<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\CalculatorType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'calculator_')]
class CalculatorController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $order = new Order();
        $form = $this->createForm(CalculatorType::class, $order);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order->setSavedAt(new DateTime());
            foreach ($order->getArticles() as $article) {
                $entityManager->persist($article);
            }
            $entityManager->persist($order);
            $entityManager->flush();
            return $this->redirectToRoute('calculator_history');
        }
        return $this->render('calculator/index.html.twig', [
            "form" => $form->createView(),
        ]);
    }
}
