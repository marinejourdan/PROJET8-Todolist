<?php

namespace Tests\App\Controller;

use App\Entity\Task;
use App\Entity\User;
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
        $this->assertMatchesRegularExpression('/\/login$/', $client->getResponse()->headers->get('location'));

    }


    public function testListTaskWhenLogged()
    {
        $client = $this->createAuthorizedClient();
        
        $crawler= $client->request('GET', '/tasks');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Liste des tâches', $crawler->filter('h1')->text());
        
    }

    public function testListTaskWhenNotLogged()
    {
        $client = $this->createClient();
        
        $crawler= $client->request('GET', '/tasks');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Liste des tâches', $crawler->filter('h1')->text());
        
    }

     public function testCreateTaskPageWhenLogged()
    {
        $client = $this->createAuthorizedClient();
        
        $crawler=$client->request('GET', '/tasks/create');
        $this->assertStringContainsString('Créer une tâche', $crawler->filter('h1')->text());
    }

    public function testAddTaskWhenLogged()
    
    {
        $client = $this->createAuthorizedClient();
        $urlGenerator = $client->getContainer()->get('router');
    
        $crawler=$client->request(Request::METHOD_GET, $urlGenerator->generate('task_create'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('tâche', $crawler->filter('h1')->text());

        $form = $crawler->selectButton('Ajouter')->form();

        $form['task[title]'] = 'je suis un test';
        $form['task[content]'] = 'je crée un task et ca fonctionne ';
    
        $client->submit($form);
        //echo $client->getResponse()->getContent();
        $client->followRedirect('/tasks');
    }
    
    public function testEditTaskPageWhenLogged()
    {
        $client = $this->createAuthorizedClient();
        $urlGenerator = $client->getContainer()->get('router');
        $crawler=$client->request('GET', $urlGenerator->generate('task_edit', ['id' => 25]));
        
        $this->assertStringContainsString('Modifier', $crawler->filter('h1')->text());
    }
    
    public function testEditTaskWhenLogged()
    
    {
        $client = $this->createAuthorizedClient();
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request(
            Request::METHOD_GET, 
            $urlGenerator->generate('task_edit', ['id'=>24])
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('tâche', $crawler->filter('h1')->text());
        

        $form = $crawler->selectButton('Modifier')->form();
        

        $form['task[title]'] = 'je suis un test';
        $form['task[content]'] = 'mon test de modification fonctionne! ';
    
        $client->submit($form);
        // echo $client->getResponse()->getContent();
        $client->followRedirect('/tasks');
    }

    public function testDeleteTaskWhenLogged()
    {
        $client = $this->createAuthorizedClient();
        $urlGenerator = $client->getContainer()->get('router');
    
        $tasks = self::$kernel
            ->getContainer()
            ->get('doctrine')
            ->getRepository('App\Entity\Task')
            ->findByAuthor([
                'id' => 2
            ])
        ;

        if(count($tasks) > 0){
           
            $task = $tasks[0];

            $crawler=$client->request(Request::METHOD_POST, $urlGenerator->generate('task_delete', ['id' => $task->getId()]));
    
            $this->assertEquals(302, $client->getResponse()->getStatusCode());
            $this->assertTrue($client->getResponse()->isRedirect('/tasks'));
            
        }

    }

    public function testToggleTaskAction()
    
    { 
        $client = $this->createAuthorizedClient();
        $urlGenerator = $client->getContainer()->get('router');

        $crawler=$client->request('POST', $urlGenerator->generate('task_toggle', ['id' => 25]));
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('tâche', $crawler->filter('h1')->text());
    }




    
    protected function createAuthorizedClient()
    {
        $client = static::createClient();
        $container = static::$kernel->getContainer();
        $session = $container->get('session');
        $user = self::$kernel
            ->getContainer()
            ->get('doctrine')
            ->getRepository('App\Entity\User')
            ->findOneByUsername('maurice')
        ;

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $session->set('_security_main', serialize($token));
        $session->save();

        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }

}
