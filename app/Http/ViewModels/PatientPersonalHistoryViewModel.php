<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/07/2017
 * Time: 12:07 PM
 */

namespace App\Http\ViewModels;


class PatientPersonalHistoryViewModel
{
    private $patientId;
    //private $hospitalId;
    //private $doctorId;
    private $patientPersonalHistory;
    private $createdBy;
    private $updatedBy;
    private $createdAt;
    private $updatedAt;

    public function __construct()
    {
        $this->patientPersonalHistory = array();
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
    /*public function getHospitalId()
    {
        return $this->hospitalId;
    }*/

    /**
     * @param mixed $hospitalId
     */
    /*public function setHospitalId($hospitalId)
    {
        $this->hospitalId = $hospitalId;
    }*/

    /**
     * @return mixed
     */
    /*public function getDoctorId()
    {
        return $this->doctorId;
    }*/

    /**
     * @param mixed $doctorId
     */
    /*public function setDoctorId($doctorId)
    {
        $this->doctorId = $doctorId;
    }*/

    /**
     * @return mixed
     */
    public function getPatientPersonalHistory()
    {
        return $this->patientPersonalHistory;
    }

    /**
     * @param mixed $patientPersonalHistory
     */
    public function setPatientPersonalHistory($patientPersonalHistory)
    {
        array_push($this->patientPersonalHistory, (object) $patientPersonalHistory);
        //$this->patientPersonalHistory = $patientPersonalHistory;
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