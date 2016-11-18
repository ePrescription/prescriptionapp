<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 02/11/2016
 * Time: 12:51 PM
 */

namespace App\Http\ViewModels;


class NewAppointmentViewModel
{
    private $patientId;
    private $hospitalId;
    private $doctorId;
    private $briefHistory;
    private $appointmentDate;
    private $appointmentTime;

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
    public function getBriefHistory()
    {
        return $this->briefHistory;
    }

    /**
     * @param mixed $briefHistory
     */
    public function setBriefHistory($briefHistory)
    {
        $this->briefHistory = $briefHistory;
    }

    /**
     * @return mixed
     */
    public function getAppointmentDate()
    {
        return $this->appointmentDate;
    }

    /**
     * @param mixed $appointmentDate
     */
    public function setAppointmentDate($appointmentDate)
    {
        $this->appointmentDate = $appointmentDate;
    }

    /**
     * @return mixed
     */
    public function getAppointmentTime()
    {
        return $this->appointmentTime;
    }

    /**
     * @param mixed $appointmentTime
     */
    public function setAppointmentTime($appointmentTime)
    {
        $this->appointmentTime = $appointmentTime;
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