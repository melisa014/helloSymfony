<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Класс сущности Статьи для Кулинарной книги
 * 
 * @ORM\Entity
 * @ORM\Table(name="articles")
 */
class Articles {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
    
    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string") 
     */
    private $content;
    
    /**
     * @ORM\Column(type="datetime") 
     */
    private $date;
    
    /**
     * Автор статьи
     *
     * @ORM\Column(type="string")
     */
    private $author;
    
    /**
     * Внешний ключ для связи с User. Many Articles have One User.
     * 
     * @ORM\ManyToOne(targetEntity="User", inversedBy="id", fetch="EAGER")
     * @ORM\Column(name="user_id")
     *
     * @var User
     */
    private $user;
    
    /**
     * Получить id
     * 
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * Получить название статьи
     * 
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Установить название статьи
     * 
     * @param string $name
     * 
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * Получить текст статьи
     * 
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
    
    /**
     * Установить текст статьи
     * 
     * @param string $content
     * 
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        
        return $this;
    }
    
    /**
     * Получить дату создания статьи
     * 
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }
    
    /**
     * Установить дату создания статьи
     * 
     * @param DateTime $date
     * 
     * @return self
     */
    public function setDate(DateTime $date): self
    {
        $this->date = $date;
        
        return $this;
    }
    
    /**
     * Получить автора статьи
     * 
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }
    
    /**
     * Установить автора статьи
     * 
     * @param string $author
     * 
     * @return self
     */
    public function setAuthor(string $author): self
    {
        $this->author = $author;
        
        return $this;
    }
    
    /**
     * Получить связь с таблицой авторов
     * 
     * @return 
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Установить связь с таблицей авторов
     * 
     * @param User $user
     * 
     * @return self
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        
        return $this;
    }
    
}
