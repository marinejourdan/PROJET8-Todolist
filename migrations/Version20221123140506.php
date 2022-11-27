<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221123140506 extends AbstractMigration implements ContainerAwareInterface

{
    use ContainerAwareTrait;
    
    public function getDescription(): string
    {
        return '';
    }

    private $passwordHasher;
    
    public function setPasswordHasher(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function up(Schema $schema): void
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $taskRepository = $em->getRepository(Task::class);
        $tasks= $taskRepository->findAll();

        $userRepository = $em->getRepository(User::class);
        $anonymousUser= $userRepository->findOneBy(['email' => 'anonyme@anonyme.fr']);
        foreach($tasks as $task){
            if (!$task->getAuthor()){
                $task->setAuthor($anonymousUser); 
            }
            $em->persist($task);
        }
        $em->flush();

    }

    public function down(Schema $schema): void
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $taskRepository = $em->getRepository(Task::
        class);
        $tasks= $taskRepository->findAll();
        $userRepository = $em->getRepository(User::class);
        $anonymousUser= $userRepository->findOneBy(['email' => 'anonyme@anonyme.fr']);
        foreach($tasks as $task){
            if ($task->getAuthor() == $anonymousUser){
                $task->setAuthor(null); 
            }
            $em->persist($task);
        }
        $em->flush();

    }
}
