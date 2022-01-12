<?php

namespace App\DataFixtures;

use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture
{
    public const ORDER_COUNT = 50;

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::ORDER_COUNT; $i++) {
            $order = new Order();
            $order->setName('Hotel Mercure ' . ($i + 1));
            $order->setLength(140);
            $order->setReference('618118');
            $this->addReference('order' . $i, $order);
            $manager->persist($order);
        }
        $manager->flush();
    }
}
