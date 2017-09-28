<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 *
 * @ORM\Table(name="users", uniqueConstraints={@ORM\UniqueConstraint(name="login", columns={"username"})})
 * @ORM\Entity
 */
class Users
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="users_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=35, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=35, nullable=true)
     */
    private $email;
    
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
     * Получить имя пользователя
     * 
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
    
    /**
     * Установить имя пользователя
     * 
     * @param string $username
     * 
     * @return self
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;
        
        return $this;
    }
    
    /**
     * Получить email пользователя
     * 
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    
    /**
     * Установить email пользователя
     * 
     * @param string $email
     * 
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        
        return $this;
    }


}

