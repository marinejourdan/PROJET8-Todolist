<?php
namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;


class UserTest extends TestCase
{
   
    public function testUserCreate()
    {
       // $user= New User(, 'jeanjean','jean@jean.fr','password', ['ROLE_USER']);
       $user= New User();
       $user->setUsername('Jean');
       $this->assertEquals("Jean", $user->getUserName());

       $user->setEmail('Jean@jean.fr');
       $this->assertEquals("Jean@jean.fr", $user->getEmail());

       $user->setPassword('password');
       $this->assertEquals("password", $user->getPassword());

       $user->setRoles(['ROLE_USER']);
       $this->assertEquals(['ROLE_USER'], $user->getRoles());
       
       $task1= new Task();
       $task2= new Task();
       $taskCollection = new ArrayCollection([$task1, $task2]);
       $user->setTasks($taskCollection);
       $this->assertEquals($taskCollection,$user->getTasks());
    }
}