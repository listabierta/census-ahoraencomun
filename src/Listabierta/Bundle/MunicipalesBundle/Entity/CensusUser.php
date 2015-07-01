<?php

namespace Listabierta\Bundle\MunicipalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CensusUser
 */
class CensusUser
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var string
     */
    private $dni;

    /**
     * @var string
     */
    private $email;

    /**
     * @var boolean
     */
    private $isActive;

    /**
     * @var string
     */
    private $phone;


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
     * Set name
     *
     * @param string $name
     * @return CensusUser
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return CensusUser
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set dni
     *
     * @param string $dni
     * @return CensusUser
     */
    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get dni
     *
     * @return string 
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return CensusUser
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
     * Set isActive
     *
     * @param boolean $isActive
     * @return CensusUser
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return CensusUser
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }
    /**
     * @var string
     */
    private $token;


    /**
     * Set token
     *
     * @param string $token
     * @return CensusUser
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }
    /**
     * @var string
     */
    private $provinceGeolocation;

    /**
     * @var string
     */
    private $provinceLatitude;

    /**
     * @var string
     */
    private $provinceLongitude;


    /**
     * Set provinceGeolocation
     *
     * @param string $provinceGeolocation
     *
     * @return CensusUser
     */
    public function setProvinceGeolocation($provinceGeolocation)
    {
        $this->provinceGeolocation = $provinceGeolocation;

        return $this;
    }

    /**
     * Get provinceGeolocation
     *
     * @return string
     */
    public function getProvinceGeolocation()
    {
        return $this->provinceGeolocation;
    }

    /**
     * Set provinceLatitude
     *
     * @param string $provinceLatitude
     *
     * @return CensusUser
     */
    public function setProvinceLatitude($provinceLatitude)
    {
        $this->provinceLatitude = $provinceLatitude;

        return $this;
    }

    /**
     * Get provinceLatitude
     *
     * @return string
     */
    public function getProvinceLatitude()
    {
        return $this->provinceLatitude;
    }

    /**
     * Set provinceLongitude
     *
     * @param string $provinceLongitude
     *
     * @return CensusUser
     */
    public function setProvinceLongitude($provinceLongitude)
    {
        $this->provinceLongitude = $provinceLongitude;

        return $this;
    }

    /**
     * Get provinceLongitude
     *
     * @return string
     */
    public function getProvinceLongitude()
    {
        return $this->provinceLongitude;
    }
}
