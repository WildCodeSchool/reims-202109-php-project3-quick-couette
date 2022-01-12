<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public const ARTICLES = [
        [
            'name' => 'Housse',
            'length' => 240,
            'width' => 220,
            'quantity' => 100,
        ],
        [
            'name' => 'Housse',
            'length' => 260,
            'width' => 240,
            'quantity' => 30,
        ],
        [
            'name' => 'Oreiller',
            'length' => 60,
            'width' => 60,
            'quantity' => 100,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->referenceRepository->getReferences() as $key => $order) {
            if ($order instanceof Order) {
                $order = $this->getReference($key);
                foreach (self::ARTICLES as $data) {
                    $article = (new Article())
                        ->SetName($data['name'])
                        ->setLength($data['length'])
                        ->setWidth($data['width'])
                        ->setQuantity($data['quantity'])
                        ->setCommand($order)
                    ;
                    $manager->persist($article);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          OrderFixtures::class,
        ];
    }
}
