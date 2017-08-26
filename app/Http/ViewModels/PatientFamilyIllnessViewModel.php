<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/07/2017
 * Time: 9:30 PM
 */

namespace App\Http\ViewModels;


class PatientFamilyIllnessViewModel
{
    private $patientId;

    private $patientFamilyIllness;
    private $createdBy;
    private $updatedBy;
    private $createdAt;
    private $updatedAt;

    public function __construct()
    {
        $this->patientFamilyIllness = array();
    }

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
     * @return array
     */
    public function getPatientFamilyIllness()
    {
        return $this->patientFamilyIllness;
    }

    /**
     * @param array $patientFamilyIllness
     */
    public function setPatientFamilyIllness($patientFamilyIllness)
    {
        array_push($this->patientFamilyIllness, (object) $patientFamilyIllness);
        //$this->patientFamilyIllness = $patientFamilyIllness;
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