<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserControllerTest extends WebTestCase
{

    public function testListUserRedirectToLoginWhenNotLogged()
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get('router');

        $client->request(Request::METHOD_GET, $urlGenerator->generate('user_list'));

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertRegExp('/\/login$/', $client->getResponse()->headers->get('location'));

    }

    public function testListUserWhenLogged()
    {
        $client = $this->createAuthorizedClient();
        $crawler= $client->request('GET', '/users');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Liste des utilisateurs', $crawler->filter('h1')->text());
        
    }

     public function testCreateUserPageWhenLogged()
    {
        $client = $this->createAuthorizedClient();
        $crawler=$client->request('GET', '/users/create');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Créer un utilisateur', $crawler->filter('h1')->text());
    }


    public function testAddUserWhenLogged()
    {
        $client = $this->createAuthorizedClient();
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request(
            Request::METHOD_GET, 
            $urlGenerator->generate('user_create')
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('utilisateur', $crawler->filter('h1')->text());

        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = 'ezaes';
        $form['user[password][first]']= 'ezezars';
        $form['user[password][second]']= 'ezezars';
        $form['user[roles][0]']= 'ROLE_USER';
        $form['user[email]'] = 'zaeazef@jean.fr';

        $client->submit($form);
        //echo $client->getResponse()->getContent();
        $client->followRedirect('/users');
    }


    public function testEditUserPageWhenLogged()
    {
        $client = $this->createAuthorizedClient();
        $urlGenerator = $client->getContainer()->get('router');

        $crawler=$client->request('GET','users/5/edit');
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Modifier', $crawler->filter('h1')->text());
    }


    public function testEditUserwhenLogged()
    {
        $client = $this->createAuthorizedClient();
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request(
            Request::METHOD_GET, 
            $urlGenerator->generate('user_create')
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('utilisateur', $crawler->filter('h1')->text());

        $form = $crawler->selectButton('Ajouter')->form();


        $crawler=$client->request(Request::METHOD_GET, $urlGenerator->generate('user_create'));
        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = 'zezaert';
        $form['user[password][first]']= 'pzedr';
        $form['user[password][second]']= 'pzedr';
        $form['user[roles][0]']= 'ROLE_USER';
        $form['user[email]'] = 'azerzaef@jean.fr';

        $client->submit($form);
        //echo $client->getResponse()->getContent();
        $client->followRedirect('/users');
       
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
