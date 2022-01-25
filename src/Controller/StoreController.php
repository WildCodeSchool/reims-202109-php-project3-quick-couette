<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Order;
use App\Entity\User;
use App\Form\StoreOrderType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/store', name: 'store_')]
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

    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StoreOrderType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            /** @var User $user */
            $user = $this->getUser();

            /** @var array $data */
            $data = $form->getData();
            $currentDate = new DateTime();
            $order = (new Order())
                ->setName($user->getUserIdentifier())
                ->setReference($currentDate->format('YmdHi'))
                ->setSavedAt($currentDate)
                ->setLength(0)
                ->setWidth(290)
                ->setWithdrawLength(0)
                ->setWithdrawWidth(0)
                ->setStatus(Order::STATUS_WAITING)
                ->setComment($data['comment'])
                ->setUser($user)
            ;

            foreach (self::ARTICLES as $key => $articleData) {
                $quantity = $data["quantity_$key"];
                if ($quantity < 1) {
                    continue;
                }
                $article = (new Article())
                    ->setName($articleData['name'])
                    ->setLength($articleData['length'])
                    ->setWidth($articleData['width'])
                    ->setQuantity($quantity)
                ;
                $order->addArticle($article);
                $entityManager->persist($article);
            }

            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('profile_index');
        }
        return $this->render('store/index.html.twig', [
            'form' => $form->createView(),
            'articles' => self::ARTICLES,
        ]);
    }
}
