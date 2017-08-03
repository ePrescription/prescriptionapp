<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 02/08/2017
 * Time: 5:33 PM
 */

namespace App\Http\ViewModels;


class PatientDrugHistoryViewModel
{
    private $patientId;
    private $drugHistory;
    private $surgeryHistory;

    private $createdBy;
    private $updatedBy;
    private $createdAt;
    private $updatedAt;

    public function __construct()
    {
        $this->drugHistory = array();
        $this->surgeryHistory = array();
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
    public function getDrugHistory()
    {
        return $this->drugHistory;
    }

    /**
     * @param mixed $drugHistory
     */
    public function setDrugHistory($drugHistory)
    {
        array_push($this->drugHistory, (object) $drugHistory);
        //$this->drugHistory = $drugHistory;
    }

    /**
     * @return array
     */
    public function getSurgeryHistory()
    {
        return $this->surgeryHistory;
    }

    /**
     * @param array $surgeryHistory
     */
    public function setSurgeryHistory($surgeryHistory)
    {
        array_push($this->surgeryHistory, (object) $surgeryHistory);
        //$this->surgeryHistory = $surgeryHistory;
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