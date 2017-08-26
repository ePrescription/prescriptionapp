<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 31/07/2017
 * Time: 7:40 PM
 */

namespace App\Http\ViewModels;


class PatientPregnancyViewModel
{
    private $patientId;
    private $patientPregnancy;

    private $createdBy;
    private $updatedBy;
    private $createdAt;
    private $updatedAt;

    public function __construct()
    {
        $this->patientPregnancy = array();
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
    public function getPatientPregnancy()
    {
        return $this->patientPregnancy;
    }

    /**
     * @param mixed $patientPregnancy
     */
    public function setPatientPregnancy($patientPregnancy)
    {
        array_push($this->patientPregnancy, (object) $patientPregnancy);
        //$this->patientPregnancy = $patientPregnancy;
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