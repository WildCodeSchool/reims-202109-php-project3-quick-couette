<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $article = new Article();
        $article->setWidth("230");
        $article->setLength("140");
        $article->setQuantity("3");
        $article->setCommand($this->getReference('order1'));
        $manager->persist($article);

        $manager->flush();
    }
}
