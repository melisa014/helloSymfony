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
    
    /**
     * Many Users have Many Users.
     * 
     * @var ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="User", mappedBy="myFriends")
     */
    protected $friendsWithMe;

    /**
     * Many Users have many Users.
     * 
     * @var ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="User", inversedBy="friendsWithMe")
     * @ORM\JoinTable(name="friends",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="friend_user_id", referencedColumnName="id")}
     *      )
     */
    protected $myFriends;

     public function __construct()
    {
        parent::__construct();
        
        $this->sentMessages = new ArrayCollection(); 
        $this->receivedMessages = new ArrayCollection(); 
        
        $this->friendsWithMe = new ArrayCollection();
        $this->myFriends = new ArrayCollection();
        
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
     * @param string $mobileNumber
     *
     * @return User
     */
    public function setMobileNumber(string $mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    /**
     * Add sentMessage
     *
     * @param Message $sentMessage
     *
     * @return User
     */
    public function addSentMessage(Message $sentMessage)
    {
        $this->sentMessages[] = $sentMessage;

        return $this;
    }

    /**
     * Remove sentMessage
     *
     * @param Message $sentMessage
     */
    public function removeSentMessage(Message $sentMessage)
    {
        $this->sentMessages->removeElement($sentMessage);
    }

    /**
     * Get sentMessages
     *
     * @return ArrayCollection
     */
    public function getSentMessages()
    {
        return $this->sentMessages;
    }

    /**
     * Add receivedMessage
     *
     * @param Message $receivedMessage
     *
     * @return User
     */
    public function addReceivedMessage(Message $receivedMessage)
    {
        $this->receivedMessages[] = $receivedMessage;

        return $this;
    }

    /**
     * Remove receivedMessage
     *
     * @param Message $receivedMessage
     */
    public function removeReceivedMessage(Message $receivedMessage)
    {
        $this->receivedMessages->removeElement($receivedMessage);
    }

    /**
     * Get receivedMessages
     *
     * @return ArrayCollection
     */
    public function getReceivedMessages()
    {
        return $this->receivedMessages;
    }

    /**
     * Add friendsWithMe
     *
     * @param User $friendsWithMe
     *
     * @return User
     */
    public function addFriendsWithMe(User $friendsWithMe)
    {
        if (!$this->friendsWithMe->contains($friendsWithMe)) {
            $this->friendsWithMe[] = $friendsWithMe;
        }
        
        return $this;
    }

    /**
     * Remove friendsWithMe
     *
     * @param User $friendsWithMe
     */
    public function removeFriendsWithMe(User $friendsWithMe)
    {
        $this->friendsWithMe->removeElement($friendsWithMe);
    }

    /**
     * Get friendsWithMe
     *
     * @return ArrayCollection
     */
    public function getFriendsWithMe()
    {
        return $this->friendsWithMe;
    }

    /**
     * Add myFriend
     *
     * @param User $myFriend
     *
     * @return User
     */
    public function addMyFriend(User $myFriend)
    {
        if (!$this->myFriends->contains($myFriend)) {
            $this->myFriends[] = $myFriend;
        }
        
        return $this;
    }

    /**
     * Remove myFriend
     *
     * @param User $myFriend
     */
    public function removeMyFriend(User $myFriend)
    {
        $this->myFriends->removeElement($myFriend);
    }

    /**
     * Get myFriends
     *
     * @return ArrayCollection
     */
    public function getMyFriends()
    {
        return $this->myFriends;
    }
}
