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
    
    //public function testSecurity PasswordEncoder()
    //{
        //$user=New User;
        //$this->assertEquals('hello-world', $user->get('security.password_encoder')->encodePassword($user, $user->getPassword());
    //}

    public function testListUserWhenLogged()
    {
        $client = $this->createAuthorizedClient();
        
        $crawler= $client->request('GET', '/users');
        $this->assertContains('Liste des utilisateurs', $crawler->filter('h1')->text());
        
    }

     public function testCreateUserPageWhenLogged()
    {
        $client = $this->createAuthorizedClient();
    
        $crawler=$client->request('GET', '/users/create');
        $this->assertContains('Créer un utilisateur', $crawler->filter('h1')->text());
    }


    public function testAddUser()
    {
        $client = $this->createAuthorizedClient();
        $urlGenerator = $client->getContainer()->get('router');

        $crawler=$client->request(Request::METHOD_GET, $urlGenerator->generate('user_create'));
        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'bobby';
        //$form['user[password]'] = 'hijfgfgkl';
        //$form['user[roles]'] = '[ROLE_USER]';
        $form['user[email]'] = 'bobby@bobby.fr';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/users'));

        $this->assertSelectorTextContains('div.alert alert-success',"L'utilisateur a bien été ajouté.");  

    }

    //public function testEditUserPageWhenLogged()
    // Treouver cmment aujouter le user id dans la route
    // {
    //     $client = $this->createAuthorizedClient();
    //     $user = factory(AppBundle\Entity\User)->create();
        
    //     $crawler=$client->request('GET', '/users' $user->id'/edit');
    //     $this->assertContains('Modifier', $crawler->filter('h1')->text());
    // }

    public function testCreateUserWithRedirectToListWhenLogged()
    {            

        $client = $this->createAuthorizedClient();
        $urlGenerator = $client->getContainer()->get('router');

        $client->request(Request::METHOD_POST, $urlGenerator->generate('user_list'));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegExp('/\/user_list$/', $client->getResponse()->headers->get('location'));
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
