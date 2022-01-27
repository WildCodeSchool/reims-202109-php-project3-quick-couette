<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Order;
use App\Entity\User;
use App\Repository\OrderRepository;

#[Route('/profile', name: 'profile_')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(OrderRepository $orderRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $orders = $orderRepository->findByUser($user);

        if ($this->isGranted('ROLE_ADMINISTRATOR')) {
            $waitingOrders = $orderRepository->findByStatus(Order::STATUS_WAITING);
        }

        return $this->render('profile/index.html.twig', [
            "orders" => $orders,
            "waitingOrders" => $waitingOrders ?? [],
        ]);
    }
}
