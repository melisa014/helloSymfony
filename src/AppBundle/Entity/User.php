<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use IFF\ChatBundle\Entity\Message;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class User extends BaseUser
{
    /**
     * @var int
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="user_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;
    
    /**
     * @var srting
     * 
     * @ORM\Column(type="string", name="mobile_number")
     * 
     * @Assert\NotBlank()
     */
    protected $mobileNumber;
    
    /**
     * @var array
     * 
     * @ORM\Column(type="text", name="friends")
     */
//    protected $friends;
    
    /**
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Friend", mappedBy="user")
     */
    protected $friends;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="IFF\ChatBundle\Entity\Message", mappedBy="userFrom")
     */
    protected $sentMessages;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="IFF\ChatBundle\Entity\Message", mappedBy="userTo")
     */
    protected $receivedMessages;

     public function __construct()
    {
        parent::__construct();
        
        $this->sentMessages = new ArrayCollection(); 
        $this->receivedMessages = new ArrayCollection(); 
//        $this->friends;
        $this->friends = new ArrayCollection();
        
        $this->email = $this->email ? $this->email : $this->id;
        $this->password = $this->password ? $this->password : '';
        $this->plainPassword = $this->plainPassword ? $this->plainPassword : '';
    }

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
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }
    
    /**
     * @param string $mobileNumber
     * 
     * @return self
     */
    public function setMobileNumber($mobileNumber): self
    {
        $this->mobileNumber = $mobileNumber;
        
        return $this;
    }
    
    /**
     * @param Friend $friend
     * 
     * @return self
     */
    public function addFriend(Friend $friend): self
    {
        if (!$this->friends->contains($friend)) { 
            $this->friends->add($friend); 
        }

        return $this;
    }
            
    /**
     * @param Friend $friend
     * 
     * @return self
     */
    public function removeFriend(Friend $friend): self
    {
        if ($this->friends->contains($friend)) { 
            $this->friends->removeElement($friend); 
        }

        return $this;
    }
    
    /**
     * Возвращает список всех друзей в коллекции
     * 
     * @return ArrayCollection
     */
    public function getFriends(): ArrayCollection
    {
        return $this->friends; 
    }
    
    /**
     * @param Message $sentMessage
     * 
     * @return self
     */
    public function addSentMessages(Message $sentMessage): self
    {
        if (!$this->sentMessages->contains($sentMessage)) { 
            $sentMessage->setHouse($this); 
            $this->sentMessages->add($sentMessage); 
        }

        return $this;
    }
            
    /**
     * @param Message $sentMessage
     * 
     * @return self
     */
    public function removeSentMessages(Message $sentMessage): self
    {
        if ($this->sentMessages->contains($sentMessage)) { 
            $this->sentMessages->removeElement($sentMessage); 
        }

        return $this;
    }
    
    /**
     * Возвращает список всех юзеров в коллекции
     * 
     * @return Collection
     */
    public function getSentMessages(): Collection
    {
        return $this->sentMessages; 
    }
    
    
    
    /**
     * @param Message $recervedMessage
     * 
     * @return self
     */
    public function addReceivedMessages(Message $recervedMessage): self
    {
        if (!$this->recervedMessage->contains($recervedMessage)) { 
            $recervedMessage->setHouse($this); 
            $this->recervedMessage->add($recervedMessage); 
        }

        return $this;
    }
            
    /**
     * @param Message $recervedMessage
     * 
     * @return self
     */
    public function removeReceivedMessages(Message $recervedMessage): self
    {
        if ($this->recervedMessage->contains($recervedMessage)) { 
            $this->recervedMessage->removeElement($recervedMessage); 
        }

        return $this;
    }
    
    /**
     * Возвращает список всех юзеров в коллекции
     * 
     * @return ArrayCollection
     */
    public function getReceivedMessages(): ArrayCollection
    {
        return $this->recervedMessage; 
    }
    
}
