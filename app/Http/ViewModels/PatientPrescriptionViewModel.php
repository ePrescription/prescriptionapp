<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/31/2016
 * Time: 5:54 PM
 */

namespace App\Http\ViewModels;


class PatientPrescriptionViewModel{

    private $patientId;
    private $doctorId;
    private $hospitalId;
    private $briefDescription;
    private $drugDetails;
    private $prescriptionDate;
    private $intakeForm;
    private $createdBy;
    private $modifiedBy;
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
     * @param mixed $hospitalId
     */
    public function setHospitalId($hospitalId)
    {
        $this->hospitalId = $hospitalId;
    }

    /**
     * @return mixed
     */
    public function getBriefDescription()
    {
        return $this->briefDescription;
    }

    /**
     * @param mixed $briefDescription
     */
    public function setBriefDescription($briefDescription)
    {
        $this->briefDescription = $briefDescription;
    }

    /**
     * @return mixed
     */
    public function getDrugDetails()
    {
        return $this->drugDetails;
    }

    /**
     * @param mixed $drugDetails
     */
    public function setDrugDetails($drugDetails)
    {
        $this->drugDetails = $drugDetails;
    }

    /**
     * @return mixed
     */
    public function getPrescriptionDate()
    {
        return $this->prescriptionDate;
    }

    /**
     * @param mixed $prescriptionDate
     */
    public function setPrescriptionDate($prescriptionDate)
    {
        $this->prescriptionDate = $prescriptionDate;
    }

    /**
     * @return mixed
     */
    public function getIntakeForm()
    {
        return $this->intakeForm;
    }

    /**
     * @param mixed $intakeForm
     */
    public function setIntakeForm($intakeForm)
    {
        $this->intakeForm = $intakeForm;
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


    /*public function __toString()
    {
        return $this;
    }*/

}