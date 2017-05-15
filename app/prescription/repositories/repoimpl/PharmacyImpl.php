<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/28/2016
 * Time: 12:27 PM
 */

namespace App\prescription\repositories\repoimpl;


use App\Http\ViewModels\PharmacyViewModel;
use App\prescription\model\entities\Pharmacy;
use App\prescription\repositories\repointerface\PharmacyInterface;
use App\prescription\utilities\ErrorEnum\ErrorEnum;
use App\prescription\utilities\Exception\HospitalException;
use App\prescription\utilities\Exception\PharmacyException;

use App\User;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class PharmacyImpl implements PharmacyInterface
{
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
            /*$pharmacyProfile = Pharmacy::where('pharmacy_id', '=', $pharmacyId)
                ->get(array('id', 'pharmacy_id', 'name', 'address', ''))->toArray();*/
            //$pharmacyProfile = Pharmacy::where('pharmacy_id', $pharmacyId)->get();

            $query = DB::table('pharmacy as p')->join('cities as c', 'c.id', '=', 'p.city');
            $query->join('countries as co', 'co.id', '=', 'p.country');
            $query->where('p.pharmacy_id', '=', $pharmacyId);
            $query->select('p.id', 'p.pharmacy_id', 'p.name as pharmacy_name', 'p.address', 'c.id as city_id', 'c.city_name',
                'co.id as country_id', 'co.name as country_name', 'p.phid', 'p.telephone', 'p.email');

            //dd($query->toSql());
            $pharmacyProfile = $query->get();
            //dd($pharmacyProfile);

            //dd($pharmacyProfile);
        }
        catch(QueryException $queryExc)
        {
            //dd($queryExc);
            throw new PharmacyException(null, ErrorEnum::PHARMACY_PROFILE_VIEW_ERROR, $queryExc);
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
            $query = DB::table('patient as p')->join('patient_prescription as pp', 'pp.patient_id', '=', 'p.patient_id');
            $query->join('hospital_pharmacy as hp', 'hp.hospital_id', '=', 'pp.hospital_id');
            $query->where('hp.hospital_id', '=', $hospitalId);
            $query->where('hp.pharmacy_id', '=', $pharmacyId);
            //$query->select('p.id', 'p.patient_id', 'p.name', 'p.pid', 'p.telephone','p.age', 'p.gender');
            $query->distinct()->select('p.id', 'p.patient_id', 'p.name', 'p.pid', 'p.telephone','p.age', 'p.gender');

            //dd($query->toSql());
            $patients = $query->get();
        }
        catch(QueryException $queryExc)
        {
            //dd($queryExc);
            throw new PharmacyException(null, ErrorEnum::PHARMACY_PATIENT_LIST_ERROR, $queryExc);
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

            $query = DB::table('patient as p')->join('patient_prescription as pp', 'pp.patient_id', '=', 'p.patient_id');
            $query->join('hospital_pharmacy as hp', 'hp.hospital_id', '=', 'pp.hospital_id');
            $query->where('hp.hospital_id', '=', $hospitalId);
            $query->where('hp.pharmacy_id', '=', $pharmacyId);
            $query->select('p.id', 'p.patient_id', 'p.name', 'p.pid', 'pp.id as prescription_id', 'pp.unique_id as prid', 'pp.prescription_date');

            //dd($query->toSql());
            $prescriptions = $query->get();
            //dd($prescriptions);
        }
        catch(QueryException $queryEx)
        {
            throw new PharmacyException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $queryEx);
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
            $query = DB::table('patient as p')->join('patient_prescription as pp', 'pp.patient_id', '=', 'p.patient_id');
            $query->where('pp.unique_id', '=', $prId);
            $query->select('p.id', 'p.patient_id', 'p.name', 'p.pid', 'pp.id as prescription_id',
                            'pp.unique_id as prid', 'pp.prescription_date');

            //dd($query->toSql());
            $prescription = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new PharmacyException(null, ErrorEnum::PRESCRIPTION_LIST_PRID_ERROR, $queryEx);
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

    public function editPharmacy(PharmacyViewModel $pharmacyVM)
    {
        $status = true;
        $pharmacy = null;

        try
        {
            $user = User::find($pharmacyVM->getPharmacyId());

            if(!is_null($user))
            {
                $pharmacy = Pharmacy::where('pharmacy_id', '=', $pharmacyVM->getPharmacyId())->first();
                //dd($pharmacy);

                if(!is_null($pharmacy))
                {
                    $pharmacy->name = $pharmacyVM->getName();
                    $pharmacy->address = $pharmacyVM->getAddress();
                    $pharmacy->city = $pharmacyVM->getCity();
                    $pharmacy->country = $pharmacyVM->getCountry();
                    //$pharmacy->phid = "PH".$pharmacyVM->getPharmacyId().time();
                    $pharmacy->phid = 'PH'.crc32(uniqid(rand()));
                    $pharmacy->telephone = $pharmacyVM->getTelephone();
                    //$pharmacy->email = $pharmacyVM->getEmail();
                    //$pharmacy->pharmacy_photo = $pharmacyVM->getPharmacyPhoto();
                    $pharmacy->created_by = $pharmacyVM->getCreatedBy();
                    $pharmacy->updated_by = $pharmacyVM->getUpdatedBy();
                    $pharmacy->created_at = $pharmacyVM->getCreatedAt();
                    $pharmacy->updated_at = $pharmacyVM->getUpdatedAt();

                    $user->pharmacy()->save($pharmacy);
                }


            }
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            $status = false;
            throw new PharmacyException(null, ErrorEnum::PHARMACY_SAVE_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $status = false;
            throw new PharmacyException(null, ErrorEnum::PHARMACY_SAVE_ERROR, $exc);
        }

        return $status;
    }


    /**
     * Get the list of prescriptions for the selected patient
     * @param $patientId
     * @throws $pharmacyException
     * @return array | null
     * @author Vimal
     */

    public function getPrescriptionListForPatient($patientId)
    {
        $prescriptions = null;

        try
        {

            $query = DB::table('patient as p')->join('patient_prescription as pp', 'pp.patient_id', '=', 'p.patient_id');
            $query->join('hospital_pharmacy as hp', 'hp.hospital_id', '=', 'pp.hospital_id');
            $query->where('p.patient_id', '=', $patientId);
            $query->select('p.id', 'p.patient_id', 'p.name', 'p.pid', 'pp.id as prescription_id', 'pp.unique_id as prid', 'pp.prescription_date');

            $prescriptions = $query->get();
            //dd($prescriptions);
        }
        catch(QueryException $queryEx)
        {
            throw new PharmacyException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new PharmacyException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $exc);
        }

        return $prescriptions;
    }




    public function getPrescriptionListForDoctor($doctorId, $hospitalId)
    {
        $prescriptions = null;

        try
        {

            $query = DB::table('patient as p')->join('patient_prescription as pp', 'pp.patient_id', '=', 'p.patient_id');
            $query->join('hospital_doctor as hp', 'hp.hospital_id', '=', 'pp.hospital_id');
            $query->where('hp.hospital_id', '=', $hospitalId);
            $query->where('hp.doctor_id', '=', $doctorId);
            $query->select('p.id', 'p.patient_id', 'p.name', 'p.pid', 'pp.id as prescription_id', 'pp.unique_id as prid', 'pp.prescription_date');

            $prescriptions = $query->get();
            //dd($prescriptions);
        }
        catch(QueryException $queryEx)
        {
            throw new PharmacyException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new PharmacyException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $exc);
        }

        return $prescriptions;
    }
}