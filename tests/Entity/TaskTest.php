<?php
namespace Tests\App\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
   
    public function testTaskCreate()
    {
        $task= New Task();

        $task->setCreatedAt(new \Datetime('2017-03-06'));
        $this->assertEquals(new \Datetime('2017-03-06'), $task->getCreatedAt());

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