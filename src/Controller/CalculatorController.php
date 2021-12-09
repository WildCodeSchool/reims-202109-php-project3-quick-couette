<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\CalculatorType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'calculator_')]
class CalculatorController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        $order = new Order();
        $form = $this->createForm(CalculatorType::class, $order);
        $form->handleRequest($request);
        $showResults = false;
        if ($form->isSubmitted() && $form->get('articles')->isValid()) {
            // TODO: calculate
            // $order->setLength(...);
            // $order->setLeftover(...);
            $showResults = true;
        }
        return $this->render('calculator/index.html.twig', [
            "form" => $form->createView(),
            "showResults" => $showResults,
        ]);
    }

    #[Route('/save', name: 'save')]
    public function save(Request $request): Response
    {
        $order = new Order();
        $form = $this->createForm(CalculatorType::class, $order);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order->setSavedAt(new DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($order->getArticles() as $article) {
                $entityManager->persist($article);
            }
            $entityManager->persist($order);
            $entityManager->flush();
            return $this->redirectToRoute('calculator_history');
        }
        return $this->render('calculator/index.html.twig', [
            "form" => $form->createView(),
            "showResults" => true,
        ]);
    }
}
