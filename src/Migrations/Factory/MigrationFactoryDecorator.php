<?php
declare(strict_types=1);

namespace App\Migrations\Factory;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\Migrations\Version\MigrationFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class MigrationFactoryDecorator implements MigrationFactory
{
    private $migrationFactory;
    private $container;
    private $passwordHasher;

    public function __construct(
        MigrationFactory $migrationFactory, 
        ContainerInterface $container, 
        UserPasswordHasherInterface $passwordHasher
    )
    {
        $this->migrationFactory = $migrationFactory;
        $this->container        = $container;
        $this->passwordHasher=$passwordHasher;
    }

    public function createVersion(string $migrationClassName): AbstractMigration
    {
        $instance = $this->migrationFactory->createVersion($migrationClassName);

        if ($instance instanceof ContainerAwareInterface) {
            $instance->setContainer($this->container);
            $instance->setPasswordHasher($this->passwordHasher);
        }

        return $instance;
    }
}