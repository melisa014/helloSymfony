<?php

namespace IFF\ChatBundle\Entity;

use AppBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use JsonSerializable;

/**
 * @ORM\Table(name="message")
 * @ORM\Entity
 */
class Message implements JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="message_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $timestamp;
    
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_from", referencedColumnName="id")
     */
    private $userFrom;
    
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_to", referencedColumnName="id")
     */
    private $userTo;
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
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
     * @return DateTime
     */
    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }
    
    /**
     * @param DateTime $timestamp
     * 
     * @return self
     */
    public function setTimestamp(DateTime $timestamp): self
    {
        $this->timestamp = $timestamp;
        
        return $this;
    }

    /**
     * Узнать, какой пользователь написал сообщение
     * 
     * @return User
     */
    public function getUserFrom(): User
    {
        return $this->userFrom;
    }
    
    /**
     * Установить связь с таблицей пользователей
     * 
     * @param User $userFrom
     * 
     * @return self
     */
    public function setUserFrom(User $userFrom): self
    {
        $this->userFrom = $userFrom;
        
        return $this;
    }
    
    /**
     * Узнать, какому пользователю написано сообщение
     * 
     * @return User
     */
    public function getUserTo(): User
    {
        return $this->userTo;
    }
    
    /**
     * Установить связь с таблицей пользователей
     * 
     * @param User $userTo
     * 
     * @return self
     */
    public function setUserTo(User $userTo): self
    {
        $this->userTo = $userTo;
        
        return $this;
    }
    
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
