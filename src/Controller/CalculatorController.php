<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\CalculatorType;
use App\Repository\OrderRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/calculator', name: 'calculator_')]
#[IsGranted('ROLE_ADMINISTRATOR')]
class CalculatorController extends AbstractController
{
    private function generateForm(
        Order $order,
        Request $request,
        EntityManagerInterface $entityManager,
        array $twigParameters = []
    ): Response {
        $form = $this->createForm(CalculatorType::class, $order);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order->setSavedAt(new DateTime());
            $order->setStatus(Order::STATUS_NOT_A_COMMAND);
            $entityManager->persist($order);
            $entityManager->flush();
            return $this->redirectToRoute('calculator_history');
        }
        return $this->render('calculator/index.html.twig', array_merge($twigParameters, [
            "form" => $form->createView(),
        ]));
    }

    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $order = new Order();
        return $this->generateForm($order, $request, $entityManager);
    }

    #[Route('/history', name: 'history')]
    public function history(Request $request, OrderRepository $orderRepository): Response
    {
        $search = $request->query->get('search');
        $page = $request->query->get('page', '1');
        $limit = $request->query->get('limit', '4');
        $page = max(intval($page), 1);
        $limit = max(intval($limit), 1);
        $offset = ($page - 1) * $limit;

        if (!empty($search)) {
            $orders = $orderRepository->findLikeNameOrReference($search, $offset, $limit);
            $count = $orderRepository->findLikeNameOrReferenceCount($search);
        } else {
            $orders = $orderRepository->findBy(
                [],
                ['savedAt' => 'DESC', 'id' => 'DESC'],
                $limit,
                $offset
            );
            $count = $orderRepository->count([]);
        }

        return $this->render('calculator/history.html.twig', [
            'orders' => $orders,
            'current_page' => $page,
            'total_pages' => ceil($count / $limit),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, Order $order): Response
    {
        return $this->generateForm($order, $request, $entityManager, ['order' => $order]);
    }

    #[Route('/{id}/delete', name: 'delete')]
    public function delete(Request $request, EntityManagerInterface $entityManager, Order $order): Response
    {
        $token = (string)$request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $order->getId(), $token)) {
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('calculator_history', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/accept', name: 'accept', defaults: ['status' => Order::STATUS_ACCEPTED])]
    #[Route('/{id}/refuse', name: 'refuse', defaults: ['status' => Order::STATUS_REFUSED])]
    public function status(Order $order, EntityManagerInterface $entityManager, int $status): Response
    {
        $order->setStatus($status);
        $entityManager->flush();

        return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
    }
}
