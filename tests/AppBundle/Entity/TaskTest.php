<?php
namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
   
    public function testTaskCreate()
    {
        $task= New Task();

        $task->setCreatedAt('20/01/2017');
        $this->assertEquals("20/01/2017", $task->getCreatedAt());

        $task->setTitle('Bonjour');
        $this->assertEquals("Bonjour", $task->getTitle());

        $task->setContent('je suis une premiere tache d une action');
        $this->assertEquals("je suis une premiere tache d une action", $task->getContent());

        $task->toggle(true);
        $this->assertEquals(true, $task->isDone());

        $user = new User();
        $task->setAuthor($user);
        $this->assertEquals($user, $task->getAuthor());
    }
}