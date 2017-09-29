<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
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
     * @var string
     * 
     * @ORM\Column(type="string")
     * 
     * @Assert\NotBlank()
     */
    protected $address;
    
    /**
     * @var int
     * 
     * @ORM\Column(type="integer", name="mobile_number")
     * 
     * @Assert\Length( 
     *      min = 11,
     *      max = 11,
     *      minMessage = "Номер мобильного должен содержать 11 цифр",
     *      maxMessage = "Номер мобильного должен содержать 11 цифр"
     * )
     * @Assert\NotBlank()
     */
    protected $mobileNumber;
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @return 
     */
    public function getAddress()
    {
        return $this->address;
    }
    
    /**
     * @param string $address
     * 
     * @return self
     */
    public function setAddress($address): self
    {
        $this->address = $address;
        
        return $this->address;
    }
    
    /**
     * @return 
     */
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }
    
    /**
     * @param int $mobileNumber
     * 
     * @return self
     */
    public function setMobileNumber($mobileNumber): self
    {
        $this->mobileNumber = $mobileNumber;
        
        return $this->mobileNumber;
    }
    
}
