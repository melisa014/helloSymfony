<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Articles
 *
 * @ORM\Table(name="articles", indexes={@ORM\Index(name="IDX_BFDD3168A76ED395", columns={"user_id"})})
 * @ORM\Entity
 */
class Articles
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="articles_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    private $content;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=35, nullable=false)
     */
    private $author;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
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
     * @return Users
     */
    public function getUser():Users
    {
        return $this->user;
    }
    
    /**
     * Установить связь с таблицей авторов
     * 
     * @param Users $user
     * 
     * @return self
     */
    public function setUser(Users $user): self
    {
        $this->user = $user;
        
        return $this;
    }

}

