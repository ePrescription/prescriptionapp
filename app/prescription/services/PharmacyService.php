<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/28/2016
 * Time: 12:07 PM
 */

namespace App\prescription\services;


use App\prescription\repositories\repointerface\HospitalInterface;
use App\prescription\repositories\repointerface\PharmacyInterface;
use App\prescription\utilities\Exception\PharmacyException;
use App\prescription\utilities\ErrorEnum\ErrorEnum;

use Illuminate\Support\Facades\DB;

use Exception;

class PharmacyService
{
    protected $pharmacyRepo;

    public function __construct(PharmacyInterface $pharmacyRepo)
    {
        $this->pharmacyRepo = $pharmacyRepo;
    }

    /**
     * Get the profile of the pharmacy
     * @param $pharmacyId
     * @throws $pharmacyException
     * @return array | null
     * @author Baskar
     */

    public function getProfile($pharmacyId)
    {
        $pharmacyProfile = null;

        try
        {
            $pharmacyProfile = $this->pharmacyRepo->getProfile($pharmacyId);
        }
        catch(PharmacyException $profileExc)
        {
            throw $profileExc;
        }
        catch(Exception $exc)
        {
            throw new PharmacyException(null, ErrorEnum::PHARMACY_PROFILE_VIEW_ERROR, $exc);
        }

        return $pharmacyProfile;
    }

    /**
     * Get the list of patients for the selected pharmacy
     * @param $pharmacyId, $hospitalId
     * @throws $pharmacyException
     * @return array | null
     * @author Baskar
     */

    public function getPatientListForPharmacy($pharmacyId, $hospitalId)
    {
        $patients = null;

        try
        {
            $patients = $this->pharmacyRepo->getPatientListForPharmacy($pharmacyId, $hospitalId);
        }
        catch(PharmacyException $profileExc)
        {
            throw $profileExc;
        }
        catch(Exception $exc)
        {
            throw new PharmacyException(null, ErrorEnum::PHARMACY_PATIENT_LIST_ERROR, $exc);
        }

        return $patients;

    }

    /**
     * Get the list of prescriptions for the selected pharmacy
     * @param $pharmacyId, $hospitalId
     * @throws $pharmacyException
     * @return array | null
     * @author Baskar
     */

    public function getPrescriptionListForPharmacy($pharmacyId, $hospitalId)
    {
        $prescriptions = null;

        try
        {
            $prescriptions = $this->pharmacyRepo->getPrescriptionListForPharmacy($pharmacyId, $hospitalId);
        }
        catch(PharmacyException $profileExc)
        {
            throw $profileExc;
        }
        catch(Exception $exc)
        {
            throw new PharmacyException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $exc);
        }

        return $prescriptions;
    }

    /**
     * Get prescription list by PRID
     * @param $prid
     * @throws $pharmacyException
     * @return array | null
     * @author Baskar
     */

    public function getPrescriptionByPrid($prId)
    {
        $prescription = null;

        try
        {
            $prescription = $this->pharmacyRepo->getPrescriptionByPrid($prId);
        }
        catch(PharmacyException $profileExc)
        {
            throw $profileExc;
        }
        catch(Exception $exc)
        {
            throw new PharmacyException(null, ErrorEnum::PRESCRIPTION_LIST_PRID_ERROR, $exc);
        }

        return $prescription;
    }

    /**
     * Edit Pharmacy Details
     * @param $pharmacyVM
     * @throws $pharmacyException
     * @return true | false
     * @author Baskar
     */

    public function editPharmacy($pharmacyVM)
    {
        $status = true;

        try
        {
            DB::transaction(function() use ($pharmacyVM, &$status)
            {
                $status = $this->pharmacyRepo->editPharmacy($pharmacyVM);
            });

        }
        catch(PharmacyException $profileExc)
        {
            $status = false;
            throw $profileExc;
        }
        catch (Exception $ex) {

            $status = false;
            throw new PharmacyException(null, ErrorEnum::PHARMACY_SAVE_ERROR, $ex);
        }

        return $status;
    }


    public function getPrescriptionListForPatient($patientId)
    {
        $prescriptions = null;

        try
        {
            $prescriptions = $this->pharmacyRepo->getPrescriptionListForPatient($patientId);
        }
        catch(PharmacyException $profileExc)
        {
            throw $profileExc;
        }
        catch(Exception $exc)
        {
            throw new PharmacyException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $exc);
        }

        return $prescriptions;
    }
}

