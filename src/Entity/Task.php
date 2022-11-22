<?php

namespace App\Entity;

use App\Entity\Task;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class Task
{
public function __construct() {
    $this->Task = new ArrayCollection();
    $this->createdAt = new \DateTime();
    $this->isDone = false;
}

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="DateTime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Vous devez saisir un titre.")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Vous devez saisir du contenu.")
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDone;

    /**
     * @ORM\ManyToOne(targetEntity="User", fetch="EAGER", inversedBy="tasks")
     */
    private $author;

 
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getTitle(): String
    {
        return $this->title;
    }

    public function setTitle(String $title)
    {
        $this->title = $title;
    }

    public function getContent() : String
    {
        return $this->content;
    }

    public function setContent(String $content)
    {
        $this->content = $content;
    }

    public function isDone() :Boolean
    {
        return $this->isDone;
    }

    public function toggle(Boolean $flag)
    {
        $this->isDone = $flag;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author)
    {
        $this->author = $author;

        return $this;
    }
}
