<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPageIsUp()
    {
        $client = static::createClient();

        $crawler = $client->request(
            Request::METHOD_GET, 
            '/login',
            []
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // $this->assertContains('Se connecter', $crawler->filter('#loginbtn')->text());
    }
}
