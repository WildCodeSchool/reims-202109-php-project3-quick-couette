<?php

namespace App\DataFixtures;

use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture
{
    public const COUNT = 20;

    public const NAMES = [
        'IBIS',
        'Mercure',
        'Novotel',
        'Kyriad',
        'Continental',
        'B&B',
        'Azur',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::NAMES as $name) {
            for ($i = 0; $i < self::COUNT; ++$i) {
                $order = (new Order())
                    ->setName("$name " . ($i + 1))
                    ->setReference(substr(md5($name . $i), 0, 5))
                    ->setLength(180000)
                ;
                $this->addReference("order_{$name}_{$i}", $order);
                $manager->persist($order);
            }
        }
        $manager->flush();
    }
}
