<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="friend")
 */
class Friend 
{
    /**
     * @var int
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="friend_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;
    
    /**
     * @var int
     * 
     * @ORM\Column(type="integer", name="friend_id")
     */    
    protected $friendId;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
    /**
     * @return int
     */
    public function getFriendId(): int
    {
        return $this->friendId;
    }
    
    /**
     * @param int $friendId
     * 
     * @return self
     */
    public function setFriendId($friendId): self
    {
        $this->friendId = $friendId;
        
        return $this;
    }
    
    /**
     * Узнать, какой пользователь является другом
     * 
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
    
    /**
     * Установить связь с таблицей пользователей
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
