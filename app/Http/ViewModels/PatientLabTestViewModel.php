<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/1/2016
 * Time: 1:27 PM
 */

namespace App\Http\ViewModels;


class PatientLabTestViewModel {

    private $patientId;
    private $doctorId;
    private $hospitalId;
    private $description;
    private $labTestDetails;
    private $labTestDate;
    private $createdBy;
    private $modifiedBy;

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
    public function getHospitalId()
    {
        return $this->hospitalId;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getLabTestDetails()
    {
        return $this->labTestDetails;
    }

    /**
     * @param mixed $labTestDetails
     */
    public function setLabTestDetails($labTestDetails)
    {
        $this->labTestDetails = $labTestDetails;
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
    public function getLabTestDate()
    {
        return $this->labTestDate;
    }

    /**
     * @param mixed $labTestDate
     */
    public function setLabTestDate($labTestDate)
    {
        $this->labTestDate = $labTestDate;
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
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * @param mixed $modifiedBy
     */
    public function setModifiedBy($modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;
    }


}