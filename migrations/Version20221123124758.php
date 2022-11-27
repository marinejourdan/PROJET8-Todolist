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
final class Version20221123124758 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    private $passwordHasher;
    
    public function setPasswordHasher(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        
        $anonymousUser = new User();

        $anonymousUser->setUsername('anonyme');
        $password = 'anonyme';
        $password =$this->passwordHasher->hashPassword(
                $anonymousUser, 
                $password
            );
        $anonymousUser->setPassword($password);
        $anonymousUser->setEmail('anonyme@anonyme.fr');
        $anonymousUser->setRoles(['ROLE_USER']);

        $em->persist($anonymousUser);

        $user1 = new User();

        $user1->setUsername('maurice');
        $password = 'maurice';
        $password =$this->passwordHasher->hashPassword($user1, $password);
        $user1->setPassword($password);
        $user1->setEmail('maurice@maurice.fr');
        $user1->setRoles(['ROLE_ADMIN']);

        $em->persist($user1);

        $task = new Task();
        $task->setTitle('coucou');
        $task->setContent('je suis donc un utilisateur anonyme');

        $em->persist($task);

        $task1 = new Task();
        $task1->setTitle('bonjour');
        $task1->setContent('version à jour de symfony');

        $em->persist($task1);

        $em->flush();
        
    }
    

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
