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

    public function addTaskWhenLogged()
    
    {
        $client = $this->createAuthorizedClient();
        $urlGenerator = $client->getContainer()->get('router');
    
        $crawler=$client->request(Request::METHOD_POST, $urlGenerator->generate('user_create'));
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'bonjour';
        $form['task[content]'] = 'comment ca va ';
    
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/tasks'));
    
        $this->assertSelectorTextContains('div.alert.alert-success',"La tâche a bien été ajouté.");  
    }
    
    public function testEditTaskPageWhenLogged()
    {
        $client = $this->createAuthorizedClient();

        for ($i=0; $i<10;$i++){
            $task= New Task;
            $task->setContent('lk,dlk,fdld,ffd');
            $task->setTitle('knqdojioe');
            $client->getContainer()->get('doctrine.orm.entity_manager')->persist($task);
        }
        $client->getContainer()->get('doctrine.orm.entity_manager')->flush();
        $urlGenerator = $client->getContainer()->get('router');
        $crawler=$client->request('GET', $urlGenerator->generate('task_edit', ['id' => 9]));
        
        $this->assertContains('Modifier', $crawler->filter('h1')->text());
    }
    
    public function EditTaskWhenLogged()
    
    {
        $client = $this->createAuthorizedClient();
        $urlGenerator = $client->getContainer()->get('router');
    
        $crawler=$client->request(Request::METHOD_POST, $urlGenerator->generate('task_edit', ['id' => 9]));
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'bonjour';
        $form['task[content]'] = 'comment ca va ';
    
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/tasks'));
    
        $this->assertSelectorTextContains('div.alert.alert-success',"La tâche a bien été modifiée.");  

    }

    public function DeleteTaskWhenLogged()
    
    {
        $client = $this->createAuthorizedClient();
        $urlGenerator = $client->getContainer()->get('router');
    
        $crawler=$client->request(Request::METHOD_POST, $urlGenerator->generate('task_delete', ['id' => 9]));
    
        $this->assertTrue($client->getResponse()->isRedirect('/tasks'));
        $this->assertSelectorTextContains('div.alert.alert-success',"La tâche a bien été supprimée.");  
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
