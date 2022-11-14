<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\User;
use AppBundle\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $anonymousUser = new User();

        $anonymousUser->setUsername('anonyme');
        $password='anonyme';
        $password = $this->container->get('security.password_encoder')->encodePassword($anonymousUser, $anonymousUser->getPassword());
        $anonymousUser->setPassword($password);
        $anonymousUser->setEmail('anonyme@anonyme.fr');
        $anonymousUser->setRoles(['ROLE_USER']);

        $manager->persist($anonymousUser);

        $user1 = new User();

        $user1->setUsername('maurice');
        $password='maurice';
        $password = $this->container->get('security.password_encoder')->encodePassword($user1, $user1->getPassword());
        $user1->setPassword($password);
        $user1->setEmail('maurice@maurice.fr');
        $user1->setRoles(['ROLE_ADMIN']);

        $manager->persist($user1);

        $task= new Task();
        $task->setTitle ('coucou');
        $task->setContent('je suis donc un utilisateur anonyme');

        $manager->persist($task);

        $task1= new Task();
        $task1->setTitle ('bonjour');
        $task1->setContent('version à jour de symfony');
        $task1->setAuthor($user1);
    
        $manager->persist($task1);

        $manager->flush();

    }
}
