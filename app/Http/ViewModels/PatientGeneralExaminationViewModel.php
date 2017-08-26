<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/07/2017
 * Time: 8:31 PM
 */

namespace App\Http\ViewModels;


class PatientGeneralExaminationViewModel
{
    private $patientId;
    private $patientGeneralExamination;

    private $createdBy;
    private $updatedBy;
    private $createdAt;
    private $updatedAt;

    public function __construct()
    {
        $this->patientGeneralExamination = array();
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
    public function getPatientGeneralExamination()
    {
        return $this->patientGeneralExamination;
    }

    /**
     * @param mixed $patientGeneralExamination
     */
    public function setPatientGeneralExamination($patientGeneralExamination)
    {
        array_push($this->patientGeneralExamination, (object) $patientGeneralExamination);
        //$this->patientGeneralExamination = $patientGeneralExamination;
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