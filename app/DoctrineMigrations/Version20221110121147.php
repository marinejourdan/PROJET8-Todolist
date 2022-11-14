<?php

declare(strict_types=1);

namespace Application\Migrations;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221110121147 extends AbstractMigration implements ContainerAwareInterface
{

    /** @var ContainerInterface */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }



    public function preUp(Schema $schema): void
    {
      
    }

    public function getDescription() : string
    {
        return '';

    }

    public function up(Schema $schema) : void
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

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
