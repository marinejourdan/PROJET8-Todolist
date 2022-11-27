<?php

namespace Tests\App\Controller;

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
        // $this->assertStringContainsString('Se connecter', $crawler->filter('#loginbtn')->text());
    }

    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request(
            Request::METHOD_POST, 
            '/login',
            [
                'username' => 'maurice', 
                'password' => 'maurice'
            ]
        );

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

    }
}
