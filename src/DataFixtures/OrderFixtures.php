<?php

namespace App\DataFixtures;

use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $orderCount = 2;
        for ($i = 0; $i < $orderCount; $i++) {
            $order = new Order();
            $order->setName('Hotel Mercure :');
            $order->setLength(140);
            $order->setReference('618118');
            $this->addReference('order' . $i, $order);
            $manager->persist($order);
        }
        $manager->flush();
    }
}
