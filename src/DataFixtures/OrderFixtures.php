<?php

namespace App\DataFixtures;

use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $order = new Order();
        $order->setName('Hotel Mercure :');

        $this->addReference('order1', $order);
        //$this->getReference('order1')

        $manager->persist($order);

        $manager->flush();
    }
}
