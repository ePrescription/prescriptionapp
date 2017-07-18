<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/07/2017
 * Time: 7:01 PM
 */

namespace App\Http\ViewModels;


class PatientPastIllnessViewModel
{
    private $patientId;

    private $patientPastIllness;
    private $createdBy;
    private $updatedBy;
    private $createdAt;
    private $updatedAt;

    public function __construct()
    {
        $this->patientPastIllness = array();
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
     * @return mixed
     */
    public function getPatientPastIllness()
    {
        return $this->patientPastIllness;
    }

    /**
     * @param mixed $patientPastIllness
     */
    public function setPatientPastIllness($patientPastIllness)
    {
        array_push($this->patientPastIllness, (object) $patientPastIllness);
        //$this->patientPastIllness = $patientPastIllness;
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