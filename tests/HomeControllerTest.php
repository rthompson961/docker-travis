<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertEquals(5, $crawler->filter('p')->count());
        $this->assertSelectorTextSame('p.orange', 'orange - ready');
        $this->assertSelectorTextSame('p.apple', 'apple - ready');
        $this->assertSelectorTextSame('p.banana', 'banana - ready');
        $this->assertSelectorTextSame('p.strawberry', 'strawberry - ready');

        $crawler = $client->request('GET', '/check/orange');
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('p.orange', 'orange - checking');
    }
}
