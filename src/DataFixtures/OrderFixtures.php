<?php

namespace App\DataFixtures;

use App\Entity\Order;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture implements DependentFixtureInterface
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
        $user = $this->getReference('user_user');
        foreach (self::NAMES as $name) {
            for ($i = 0; $i < self::COUNT; ++$i) {
                $order = (new Order())
                    ->setName("$name " . ($i + 1))
                    ->setReference(substr(md5($name . $i), 0, 5))
                    ->setLength(180000)
                    ->setWidth(290)
                    ->setWithdrawLength(1)
                    ->setWithdrawWidth(1)
                    ->setStatus(Order::STATUS_NOT_A_COMMAND)
                    ->setUser($user)
                    ->setSavedAt(new DateTime("2021-1-1"))
                ;
                $this->addReference("order_{$name}_{$i}", $order);
                $manager->persist($order);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          UserFixtures::class,
        ];
    }
}
