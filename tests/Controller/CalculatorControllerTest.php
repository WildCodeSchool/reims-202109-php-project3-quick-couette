<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CalculatorControllerTest extends WebTestCase
{
    /**
     * @dataProvider getHistoryPageLinks
     */
    public function testHistoryPageLinks(
        int $pageRequest,
        int $expectedMin,
        int $expectedMax,
        ?int $expectedActive
    ): void {
        $client = static::createClient();

        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('admin@quick-couette.fr');
        $client->loginUser($user);

        $crawler = $client->request('GET', "/calculator/history?page=$pageRequest");

        $this->assertResponseIsSuccessful();

        $expectedPage = $expectedMin - 1;
        $children = $crawler->filter('.history-pages')->children();
        foreach ($children as $child) {
            ++$expectedPage;
            $this->assertEquals($child->nodeValue, "$expectedPage");
            $this->assertEquals($child->nodeName, $expectedPage === $expectedActive ? 'span' : 'a');
        }
        $this->assertEquals($expectedPage, $expectedMax);
    }

    public function getHistoryPageLinks(): iterable
    {
        return [
            [-1, 1, 11, 1],
            [1, 1, 11, 1],
            [5, 1, 11, 5],
            [6, 1, 11, 6],
            [7, 2, 12, 7],
            [29, 24, 34, 29],
            [30, 25, 35, 30],
            [32, 25, 35, 32],
            [33, 25, 35, 33],
            [100, 25, 35, null],
        ];
    }
}
