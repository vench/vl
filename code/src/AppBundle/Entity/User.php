<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM; 


 

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;
    
    /**
     *
     * @var Education
     * 
     * @ORM\ManyToOne(targetEntity="Education")
     * @ORM\JoinColumn(name="education_id", referencedColumnName="id", unique=false) 
     */
    private $education;

    /** 
     * @var Region[] Description
     * 
     * @ORM\ManyToMany(targetEntity="Region")
     * @ORM\JoinTable(name="users_region",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="region_id", referencedColumnName="id")}
     *      )
     */
    private $regions;
    
    
    /**
     * 
     */
    public function __construct() {
        $this->regions = new \Doctrine\Common\Collections\ArrayCollection();
         
    }

        /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * 
     * @return \AppBundle\Entity\Education
     */
    public function getEducation() {
        return $this->education;
    }

    /**
     * 
     * @param \AppBundle\Entity\Education $education
     */
    public function setEducation(Education $education) {
        $this->education = $education;
    }


}
