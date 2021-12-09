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
    #[Route('/', name: 'index', defaults: ['save' => false])]
    #[Route('/save', name: 'save', defaults: ['save' => true])]
    public function index(Request $request, EntityManagerInterface $entityManager, bool $save): Response
    {
        $order = new Order();
        $form = $this->createForm(CalculatorType::class, $order);
        $form->handleRequest($request);
        $showResults = $save;
        if ($form->isSubmitted()) {
            if ($save && $form->isValid()) {
                $order->setSavedAt(new DateTime());
                foreach ($order->getArticles() as $article) {
                    $entityManager->persist($article);
                }
                $entityManager->persist($order);
                $entityManager->flush();
                return $this->redirectToRoute('calculator_history');
            }
            if (!$save && $form->get('articles')->isValid()) {
                // TODO: calculate
                // $order->setLength(...);
                // $order->setLeftover(...);
                $showResults = true;
            }
        }
        return $this->render('calculator/index.html.twig', [
            "form" => $form->createView(),
            "showResults" => $showResults,
        ]);
    }
}
