<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`code`")
 */
class Code 
{
    /**
     * @var int 
     * 
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="code_id_primary_key")
     */
    private $id;
    
    /**
     * @var srting
     * 
     * @ORM\Column(type="string", name="mobile_number")
     */
    protected $mobileNumber;

    /**
     * @var int 
     * 
     * @ORM\Column(type="integer")
     */
    private $value;
    
    /**
     * @var DateTime 
     * 
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;
    
    /** @var bool
     * 
     * @ORM\Column(type="boolean", name="is_login")
     */
    protected $isLogin;
    
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
    public function getMobileNumber(): string
    {
        return $this->mobileNumber;
    }
    
    /**
     * @param string $mobileNumber
     * 
     * @return self
     */
    public function setMobileNumber(string $mobileNumber): self
    {
        $this->mobileNumber = $mobileNumber;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
    
    /**
     * @param int $value
     * 
     * @return self
     */
    public function setValue(int $value): self
    {
        $this->value = $value;
        
        return $this;
    }
    
    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
    
    /**
     * @param DateTime $createdAt
     * 
     * @return self
     */
    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    /**
     * @return bool
     */
    public function getIsLogin(): bool
    {
        return $this->isLogin;
    }
    
    /**
     * @param bool $isLogin
     * 
     * @return self
     */
    public function setisLogin(bool $isLogin): self
    {
        $this->isLogin = $isLogin;
        
        return $this;
    }

}
