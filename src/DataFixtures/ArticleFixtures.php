<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $articleCount = 10;
        for ($i = 0; $i < $articleCount; $i++) {
            $article = new Article();
            $article->SetName('housse');
            $article->setWidth(230);
            $article->setLength(140);
            $article->setQuantity(3);
            $article->setCommand($this->getReference('order1'));
            $manager->persist($article);
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
          OrderFixtures::class,
        ];
    }
}
