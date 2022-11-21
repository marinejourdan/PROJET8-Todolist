<?php

namespace App\DataFixtures\ORM;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
   
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $anonymousUser = new User();

        $anonymousUser->setUsername('anonyme');
        $password = 'anonyme';
        $passwordNew = $this->passwordEncoder->encodePassword($anonymousUser, $password);
        $anonymousUser->setPassword($passwordNew);
        $anonymousUser->setEmail('anonyme@anonyme.fr');
        $anonymousUser->setRoles(['ROLE_USER']);

        $manager->persist($anonymousUser);

        $user1 = new User();

        $user1->setUsername('maurice');
        $password = 'maurice';
        $passwordNew = $this->passwordEncoder->encodePassword($user1, $password);
        $user1->setPassword($passwordNew);
        $user1->setEmail('maurice@maurice.fr');
        $user1->setRoles(['ROLE_ADMIN']);

        $manager->persist($user1);

        $task = new Task();
        $task->setTitle('coucou');
        $task->setContent('je suis donc un utilisateur anonyme');

        $manager->persist($task);

        $task1 = new Task();
        $task1->setTitle('bonjour');
        $task1->setContent('version à jour de symfony');

        $manager->persist($task1);

        $manager->flush();
    }
}