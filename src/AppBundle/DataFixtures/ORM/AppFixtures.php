<?php

namespace App\DataFixtures;

use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    $user = new User();

    $user->setUsername('jean');
    $password='proutprout';
    $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
    $user->setPassword($password);
    $user->setEmail('prout@prout.fr');
    $user->setRoles(['ROLE_ADMIN']);

    $manager->persist($user);

    $manager->flush();
    }
}
