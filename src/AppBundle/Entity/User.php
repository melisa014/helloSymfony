<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
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
     * @Assert\Length( 
     *      min = 12,
     *      max = 12,
     *      minMessage = "Введите номер в формате +79991234567",
     *      maxMessage = "Введите номер в формате +79991234567"
     * )
     * @Assert\NotBlank()
     */
    protected $mobileNumber;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Message", mappedBy="user")
     */
    protected $messages;

     public function __construct()
    {
        $this->messages = new ArrayCollection();
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
     * @param Message $message
     * 
     * @return self
     */
    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) { 
            $user->setHouse($this); 
            $this->messages->add($message); 
        }

        return $this;
    }
            
    /**
     * @param Message $message
     * 
     * @return self
     */
    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) { 
            $this->messages->removeElement($message); 
        }

        return $this;
    }
    
    /**
     * Возвращает список всех юзеров в коллекции
     * 
     * @return ArrayCollection
     */
    public function getMessages(): ArrayCollection
    {
        return $this->messages; 
    }
    
}
