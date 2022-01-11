<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\CalculatorType;
use App\Form\HistorySearchType;
use App\Repository\OrderRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'calculator_')]
class CalculatorController extends AbstractController
{
    private function generateForm(Order $order, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CalculatorType::class, $order);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order->setSavedAt(new DateTime());
            $entityManager->persist($order);
            $entityManager->flush();
            return $this->redirectToRoute('calculator_history');
        }
        return $this->render('calculator/index.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    #[Route('/', name: 'index')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $order = new Order();
        return $this->generateForm($order, $request, $entityManager);
    }

    #[Route('/history', name: 'history')]
    public function history(Request $request, OrderRepository $orderRepository): Response
    {
        $form = $this->createForm(HistorySearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string */
            $search = $form->get('search')->getData();
            $orders = $orderRepository->findLikeNameOrReference($search);
        } else {
            $orders = $orderRepository->findBy(
                [],
                ['savedAt' => 'DESC']
            );
        }

        return $this->render('calculator/history.html.twig', [
            'orders' => $orders,
            'searchForm' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, EntityManagerInterface $entityManager, Order $order): Response
    {
        return $this->generateForm($order, $request, $entityManager);
    }

    #[Route('/{id}/delete', name: 'delete')]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, EntityManagerInterface $entityManager, Order $order): Response
    {
        $token = (string)$request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $order->getId(), $token)) {
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('calculator_history', [], Response::HTTP_SEE_OTHER);
    }
}
