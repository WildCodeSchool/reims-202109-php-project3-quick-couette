<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\CalculatorType;
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
    #[IsGranted('ROLE_ADMINISTRATOR')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $order = new Order();
        return $this->generateForm($order, $request, $entityManager);
    }

    #[Route('/history', name: 'history')]
    public function history(): Response
    {
        $orders = $this->getDoctrine()
            ->getRepository(Order::class)
            ->findAll();

        return $this->render('calculator/history.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    #[IsGranted('ROLE_ADMINISTRATOR')]
    public function edit(Request $request, EntityManagerInterface $entityManager, Order $order): Response
    {
        return $this->generateForm($order, $request, $entityManager);
    }

    #[Route('/{id}/delete', name: 'delete')]
    #[IsGranted('ROLE_ADMINISTRATOR')]
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
