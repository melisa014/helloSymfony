<?php

namespace IFF\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Table(name="message")
 * @ORM\Entity
 */
class Message 
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    
    
    
    
    /**
     * Узнать, какой пользователь написал сообщение
     * 
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
    
    /**
     * Установить связь с таблицей домов
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
