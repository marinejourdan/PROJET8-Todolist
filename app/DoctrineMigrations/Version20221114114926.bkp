<?php

declare(strict_types=1);

namespace Application\Migrations;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\DBAL\Schema\Schema;
use AppBundle\DataFixtures\AppFixtures;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221114114926 extends AbstractMigration implements ContainerAwareInterface
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
        return 'Basic application state';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(25) NOT NULL, password VARCHAR(64) NOT NULL, email VARCHAR(60) NOT NULL, roles LONGTEXT NOT NULL COMMENT '(DC2Type:array)', UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB;");
        $this->addSql("CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, created_at DATETIME NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, is_done TINYINT(1) NOT NULL, INDEX IDX_527EDB25F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB;");
        $this->addSql("ALTER TABLE task ADD CONSTRAINT FK_527EDB25F675F31B FOREIGN KEY (author_id) REFERENCES user (id);");
   
    }


    public function postUp(Schema $schema, UserPasswordHasherInterface $passwordHasher) : void
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $anonymousUser = new User();

        $anonymousUser->setUsername('anonyme');
        $password = 'anonyme';
        $password = $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
        $anonymousUser->setPassword($password);
        $anonymousUser->setEmail('anonyme@anonyme.fr');
        $anonymousUser->setRoles(['ROLE_USER']);

        $em->persist($anonymousUser);

        $user1 = new User();

        $user1->setUsername('maurice');
        $password = 'maurice';
        $password = $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
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

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE user');
    }
}
