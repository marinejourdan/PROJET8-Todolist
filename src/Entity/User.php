<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table("user")
 * @ORM\Entity
 * @UniqueEntity("email")
 */
class User implements UserInterface, \Serializable
{
    public function __construct() {
        $this->User = new ArrayCollection();
    }
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir un nom d'utilisateur.")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir une adresse email.")
     * @Assert\Email(message="Le format de l'adresse n'est pas correcte.")
     */
    private $email;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = ['ROLE_USER'];

    /**
     * @ORM\OneToMany(targetEntity="Task", cascade={"remove"}, fetch="EAGER", mappedBy="author")
     */
    private $tasks;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): String
    {
        return $this->username;
    }

    public function setUsername(String $username)
    {
        $this->username = $username;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword(): String
    {
        return $this->password;
    }

    public function setPassword(String $password)
    {
        $this->password = $password;
    }

    public function getEmail(): String
    {
        return $this->email;
    }

    public function setEmail(String $email)
    {
        $this->email = $email;
    }

    public function getRoles()
    {
        $roles = $this->roles;

        return $roles;
    }

    public function setRoles(Array $roles)
    {
        $this->roles = $roles;
    }

    public function getTasks(): ArrayCollection
    {
        return $this->tasks;
    }

    public function setTasks(ArrayCollection $tasks)
    {
        $this->tasks = $tasks;

        return $this;
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->email,
            $this->password,
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function supportsClass($class)
    {
        return $class === User::class;
    }
}
