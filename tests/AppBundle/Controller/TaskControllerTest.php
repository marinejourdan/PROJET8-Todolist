<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TaskControllerTest extends WebTestCase
{

    public function testListTaskRedirectToLoginWhenNotLogged()
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
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Liste des tâches', $crawler->filter('h1')->text());
        
    }

    public function testListTaskWhenNotLogged()
    {
        $client = $this->createClient();
        
        $crawler= $client->request('GET', '/tasks');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Liste des tâches', $crawler->filter('h1')->text());
        
    }

     public function testCreateTaskPageWhenLogged()
    {
        $client = $this->createAuthorizedClient();
        
        $crawler=$client->request('GET', '/tasks/create');
        $this->assertContains('Créer une tâche', $crawler->filter('h1')->text());
    }

    public function testAddTaskWhenLogged()
    
    {
        $client = $this->createAuthorizedClient();
        $urlGenerator = $client->getContainer()->get('router');
    
        $crawler=$client->request(Request::METHOD_GET, $urlGenerator->generate('task_create'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('tâche', $crawler->filter('h1')->text());

        $form = $crawler->selectButton('Ajouter')->form();

        $form['task[title]'] = 'lUYKIueryttyr';
        $form['task[content]'] = 'corzetva ';
    
        $client->submit($form);
        //echo $client->getResponse()->getContent();
        $client->followRedirect('/tasks');
    }
    
    public function testEditTaskPageWhenLogged()
    {
        $client = $this->createAuthorizedClient();
        $urlGenerator = $client->getContainer()->get('router');
        $crawler=$client->request('GET', $urlGenerator->generate('task_edit', ['id' => 11]));
        
        $this->assertContains('Modifier', $crawler->filter('h1')->text());
    }
    
    public function testEditTaskWhenLogged()
    
    {
        $client = $this->createAuthorizedClient();
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request(
            Request::METHOD_GET, 
            $urlGenerator->generate('task_edit', ['id'=>12])
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('tâche', $crawler->filter('h1')->text());
        

        $form = $crawler->selectButton('Modifier')->form();
        

        $form['task[title]'] = 'bsdfur';
        $form['task[content]'] = 'cosffa ';
    
        $client->submit($form);
        echo $client->getResponse()->getContent();
        $client->followRedirect('/tasks');
    }

    public function testDeleteTaskWhenLogged()
    
    {
        $client = $this->createAuthorizedClient();
        $urlGenerator = $client->getContainer()->get('router');
    
        $crawler=$client->request(Request::METHOD_POST, $urlGenerator->generate('task_delete', ['id' => 32]));
    
        $this->assertTrue($client->getResponse()->isRedirect('/tasks'));
        
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
