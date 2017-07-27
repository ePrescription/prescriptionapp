<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 01/11/2016
 * Time: 7:45 PM
 */

namespace App\Http\ViewModels;


class PatientProfileViewModel
{
    private $patientId;
    private $name;
    private $address;
    private $city;
    private $country;
    private $pid;
    private $telephone;
    private $email;
    private $relationship;
    private $spouseName;
    private $patientPhoto;
    private $dob;
    private $age;
    private $placeOfBirth;
    private $nationality;
    private $gender;
    private $maritalStatus;
    private $hospitalId;
    private $doctorId;
    private $mainSymptomId;
    private $subSymptomId;
    private $symptomId;

    private $createdBy;
    private $updatedBy;
    private $createdAt;
    private $updatedAt;

    /**
     * @return mixed
     */
    public function getPatientId()
    {
        return $this->patientId;
    }

    /**
     * @param mixed $patientId
     */
    public function setPatientId($patientId)
    {
        $this->patientId = $patientId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @param mixed $pid
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getRelationship()
    {
        return $this->relationship;
    }

    /**
     * @param mixed $relationship
     */
    public function setRelationship($relationship)
    {
        $this->relationship = $relationship;
    }

    /**
     * @return mixed
     */
    public function getSpouseName()
    {
        return $this->spouseName;
    }

    /**
     * @param mixed $spouseName
     */
    public function setSpouseName($spouseName)
    {
        $this->spouseName = $spouseName;
    }

    /**
     * @return mixed
     */
    public function getPatientPhoto()
    {
        return $this->patientPhoto;
    }

    /**
     * @param mixed $patientPhoto
     */
    public function setPatientPhoto($patientPhoto)
    {
        $this->patientPhoto = $patientPhoto;
    }

    /**
     * @return mixed
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @param mixed $dob
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return mixed
     */
    public function getPlaceOfBirth()
    {
        return $this->placeOfBirth;
    }

    /**
     * @param mixed $placeOfBirth
     */
    public function setPlaceOfBirth($placeOfBirth)
    {
        $this->placeOfBirth = $placeOfBirth;
    }

    /**
     * @return mixed
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * @param mixed $nationality
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getMaritalStatus()
    {
        return $this->maritalStatus;
    }

    /**
     * @param mixed $maritalStatus
     */
    public function setMaritalStatus($maritalStatus)
    {
        $this->maritalStatus = $maritalStatus;
    }

    /**
     * @return mixed
     */
    public function getHospitalId()
    {
        return $this->hospitalId;
    }

    /**
     * @param mixed $hospitalId
     */
    public function setHospitalId($hospitalId)
    {
        $this->hospitalId = $hospitalId;
    }

    /**
     * @return mixed
     */
    public function getDoctorId()
    {
        return $this->doctorId;
    }

    /**
     * @param mixed $doctorId
     */
    public function setDoctorId($doctorId)
    {
        $this->doctorId = $doctorId;
    }

    /**
     * @return mixed
     */
    public function getMainSymptomId()
    {
        return $this->mainSymptomId;
    }

    /**
     * @param mixed $mainSymptomId
     */
    public function setMainSymptomId($mainSymptomId)
    {
        $this->mainSymptomId = $mainSymptomId;
    }

    /**
     * @return mixed
     */
    public function getSubSymptomId()
    {
        return $this->subSymptomId;
    }

    /**
     * @param mixed $subSymptomId
     */
    public function setSubSymptomId($subSymptomId)
    {
        $this->subSymptomId = $subSymptomId;
    }

    /**
     * @return mixed
     */
    public function getSymptomId()
    {
        return $this->symptomId;
    }

    /**
     * @param mixed $symptomId
     */
    public function setSymptomId($symptomId)
    {
        $this->symptomId = $symptomId;
    }

    /**
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param mixed $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return mixed
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param mixed $updatedBy
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

}