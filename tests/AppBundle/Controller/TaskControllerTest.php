<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TaskControllerTest extends WebTestCase
{

    public function testListUserRedirectToLoginWhenNotLogged()
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get('router');

        $client->request(Request::METHOD_GET, $urlGenerator->generate('user_list'));

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertRegExp('/\/login$/', $client->getResponse()->headers->get('location'));

    }


    public function testListTaskWhenLogged()
    {
        $client = $this->createAuthorizedClient();
        
        $crawler= $client->request('GET', '/tasks');
        $this->assertContains('Liste des tâches', $crawler->filter('h1')->text());
        
    }

    public function testListTaskWhenNotLogged()
    {
        $client = $this->createClient();
        
        $crawler= $client->request('GET', '/tasks');
        $this->assertContains('Liste des tâches', $crawler->filter('h1')->text());
        
    }

     public function testCreateTaskPageWhenLogged()
    {
        $client = $this->createAuthorizedClient();
        
        $crawler=$client->request('GET', '/tasks/create');
        $this->assertContains('Créer une tâche', $crawler->filter('h1')->text());
    }

    //public function testEditUserPageWhenLogged()
    // Treouver cmment aujouter le user id dans la route
    // {
    //     $client = $this->createAuthorizedClient();
    //     $user = factory(AppBundle\Entity\User)->create();
        
    //     $crawler=$client->request('GET', '/users' $user->id'/edit');
    //     $this->assertContains('Modifier', $crawler->filter('h1')->text());
    // }

    public function testCreateTaskWithRedirectToListWhenLogged()
    {            

        $client = $this->createAuthorizedClient();
        $urlGenerator = $client->getContainer()->get('router');

        $client->request(Request::METHOD_POST, $urlGenerator->generate('task_list'));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegExp('/\/task_list$/', $client->getResponse()->headers->get('location'));
    }

    protected function createAuthorizedClient()
    {
        $client = static::createClient();
        $container = static::$kernel->getContainer();
        $session = $container->get('session');
        $user = self::$kernel
            ->getContainer()
            ->get('doctrine')
            ->getRepository('AppBundle:User')
            ->findOneByUsername('maurice')
        ;

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $session->set('_security_main', serialize($token));
        $session->save();

        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }

}
