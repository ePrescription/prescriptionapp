<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/8/2016
 * Time: 5:09 PM
 */

namespace App\prescription\repositories\repoimpl;

use App\Http\ViewModels\FeeReceiptViewModel;
use App\Http\ViewModels\NewAppointmentViewModel;
use App\Http\ViewModels\PatientDrugHistoryViewModel;
use App\Http\ViewModels\PatientFamilyIllnessViewModel;
use App\Http\ViewModels\PatientGeneralExaminationViewModel;
use App\Http\ViewModels\PatientLabTestViewModel;
use App\Http\ViewModels\PatientPastIllnessViewModel;
use App\Http\ViewModels\PatientPersonalHistoryViewModel;
use App\Http\ViewModels\PatientPregnancyViewModel;
use App\Http\ViewModels\PatientProfileViewModel;
use App\Http\ViewModels\PatientScanViewModel;
use App\Http\ViewModels\PatientSymptomsViewModel;
use App\Http\ViewModels\PatientUrineExaminationViewModel;
use App\prescription\model\entities\Doctor;
use App\prescription\model\entities\DoctorAppointments;
use App\prescription\model\entities\FeeReceipt;
use App\prescription\model\entities\Hospital;
use App\prescription\model\entities\LabTestDetails;
use App\prescription\model\entities\Patient;
use App\prescription\model\entities\PatientDrugHistory;
use App\prescription\model\entities\PatientLabTests;
use App\prescription\model\entities\PatientPrescription;
use App\prescription\model\entities\PatientSurgeries;
use App\prescription\model\entities\PatientSymptoms;
use App\prescription\model\entities\PrescriptionDetails;
use App\prescription\repositories\repointerface\HospitalInterface;
use App\prescription\utilities\ErrorEnum\ErrorEnum;
use App\prescription\utilities\Exception\HospitalException;
use App\Http\ViewModels\PatientPrescriptionViewModel;

use App\prescription\utilities\Exception\UserNotFoundException;
use App\prescription\utilities\UserType;
use App\User;
use App\Role;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Exception;
use Numbers_Words;
use Config as CA;


class HospitalImpl implements HospitalInterface{

    /**
     * Get list of hospitals
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getHospitals()
    {
        $hospitals = null;

        try
        {
            $query = DB::table('hospital as h')->select('h.id', 'h.hospital_id', 'h.hospital_name',
                DB::raw('CONCAT(h.address, " ", c.city_name, " ", co.name) as hospital_details'));
                //'h.address as hospital_details', 'c.city_name', 'co.name as country');
            $query->join('users as u', 'u.id', '=', 'h.hospital_id');
            $query->join('cities as c', 'c.id', '=', 'h.city');
            $query->join('countries as co', 'co.id', '=', 'h.country');
            $query->where('u.delete_status', '=', 1);

            $hospitals = $query->get();

            //dd($hospitals);
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_LIST_ERROR, $exc);
        }

        //dd($hospitals);
        return $hospitals;
    }

    /**
     * Get list of hospitals by keyword
     * @param $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getHospitalByKeyword($keyword = null)
    {
        $hospitals = null;


        try
        {
            $query = DB::table('hospital as h')->distinct()->select('h.id', 'h.hospital_id', 'h.hospital_name',
                'h.address', 'c.city_name', 'co.name as country');
            $query->join('users as u', 'u.id', '=', 'h.hospital_id');
            $query->join('cities as c', 'c.id', '=', 'h.city');
            $query->join('countries as co', 'co.id', '=', 'h.country');
            $query->where('h.hospital_name', 'LIKE', '%'.$keyword.'%');
            $query->where('u.delete_status', '=', 1);

            //$query->distinct()
            $hospitals = $query->get();
            //dd($hospitals);
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_LIST_ERROR, $exc);
        }

        return $hospitals;
    }

    //Get Appointment details

    /**
     * Get list of appointments for the hospital and the doctor
     * @param $hospitalId, $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getAppointmentsByHospitalAndDoctor($hospitalId, $doctorId)
    {
        $appointments = null;

        try
        {
            $query = DB::table('doctor_appointment as da')->select('da.id', 'da.patient_id',
                'p.name as patient_name', 'p.pid',
                'da.hospital_id', 'h.hospital_name',
                'da.doctor_id', 'd.name as doctor_name',
                //'da.appointment_date',
                DB::raw('DATE_FORMAT(da.appointment_date, "%d-%b-%Y") as appointment_date'),
                'da.appointment_time', 'da.appointment_type', 'da.brief_history as notes');
            $query->join('hospital as h', 'h.hospital_id', '=', 'da.hospital_id');
            $query->join('patient as p', 'p.patient_id', '=', 'da.patient_id');
            $query->join('doctor as d', 'd.doctor_id', '=', 'da.doctor_id');
            $query->where('da.hospital_id', '=', $hospitalId);
            $query->where('da.doctor_id', '=', $doctorId);
            $query->orderBy('da.appointment_date', 'DESC');

            //dd($query->toSql());

            $appointments = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::APPOINTMENT_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::APPOINTMENT_LIST_ERROR, $exc);
        }

        return $appointments;
    }

    /**
     * Get list of doctors for the hospital
     * @param $hospitalId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getDoctorsByHospitalId($hospitalId)
    {
        $doctors = null;

        try
        {
            $query = DB::table('hospital_doctor as hd')->join('users as usr1', 'usr1.id', '=', 'hd.hospital_id');
            $query->join('users as usr2', 'usr2.id', '=', 'hd.doctor_id');
            $query->join('doctor as d', 'd.doctor_id', '=', 'hd.doctor_id');
            $query->where('usr2.delete_status', '=', 1);
            $query->where('hd.hospital_id', '=', $hospitalId);
            $query->select('d.id as id', 'd.doctor_id as doctorId', 'd.name as doctorName', 'd.did as doctorUniqueId');
            //dd($query->toSql());
            $doctors = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_DOCTOR_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_DOCTOR_LIST_ERROR, $exc);
        }

        return $doctors;
    }

    /**
     * Get list of hospitals for the doctor
     * @param $email
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getHospitalsForDoctor($email)
    {
        $hospitals = null;

        try
        {
            $query = DB::table('hospital_doctor as hd')->join('users as usr1', 'usr1.id', '=', 'hd.doctor_id');
            $query->join('hospital as h', 'h.hospital_id', '=', 'hd.hospital_id');
            $query->where('hd.doctor_id', function($query) use($email){
                $query->select('usr.id')->from('users as usr');
                $query->where(DB::raw('TRIM(usr.email)'), '=', trim($email));
            });
            $query->where('usr1.delete_status', '=', 1);
            $query->select('h.id as id', 'h.hospital_name');
            //dd($query->toSql());
            $hospitals = $query->get();
            //dd($hospitals);
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_LIST_ERROR, $exc);
        }

        return $hospitals;
    }

    /**
     * Get list of hospitals for the doctor
     * @param $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getHospitalsByDoctorId($doctorId)
    {
        $hospitals = null;

        try
        {
            $query = DB::table('hospital_doctor as hd')->join('users as usr1', 'usr1.id', '=', 'hd.doctor_id');
            $query->join('hospital as h', 'h.hospital_id', '=', 'hd.hospital_id');
            $query->where('hd.doctor_id', '=', $doctorId);
            $query->where('usr1.delete_status', '=', 1);
            $query->select('h.id as id', 'h.hospital_name', 'h.address as hospital_address');
            //dd($query->toSql());
            $hospitals = $query->get();
            //dd($hospitals);
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_LIST_ERROR, $exc);
        }

        return $hospitals;
    }

    /**
     * Get doctor details
     * @param $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getDoctorDetails($doctorId)
    {
        $doctorDetails = null;

        try
        {
            $query = DB::table('doctor as d')->join('users as usr1', 'usr1.id', '=', 'd.doctor_id');
            $query->where('d.doctor_id', '=', $doctorId);
            $query->where('usr1.delete_status', '=', 1);
            $query->select('d.id as id', 'd.doctor_id as doctorId', 'd.name as doctorName', 'd.did as doctorUniqueId',
                'd.specialty as department', 'd.designation',
                DB::raw('CONCAT(d.qualifications, " (", d.specialty, ") ", d.experience, " years") as doctorDetails'));

            //DB::raw('CONCAT(h.address, " ", c.city_name, " ", co.name) as hospital_details'));

            $doctorDetails = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_DOCTOR_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_DOCTOR_LIST_ERROR, $exc);
        }

        return $doctorDetails;
    }

    //Get Patient List
    /**
     * Get list of patients for the hospital and patient name
     * @param $hospitalId, $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientsByHospital($hospitalId, $keyword = null)
    //public function getPatientsByHospital($hospitalId)
    {
        $patients = null;

        try
        {
            $query = DB::table('hospital_patient as hp')->select('p.id', 'p.patient_id', 'p.pid', 'p.name', 'p.age', 'p.gender', 'p.telephone',
                            'h.hospital_id', 'h.hospital_name');
            $query->join('hospital as h', 'h.hospital_id', '=', 'hp.hospital_id');
            $query->join('patient as p', 'p.patient_id', '=', 'hp.patient_id');
            $query->where('hp.hospital_id', '=', $hospitalId);
            if($keyword != null)
            {
                $query->where('p.name', 'LIKE', '%'.$keyword.'%');
            }

            $query->orderBy('hp.created_at', 'DESC');
            //$query->where('p.name', 'LIKE', '%'.$keyword.'%');

            //dd($query->toSql());
            $patients = $query->get();
            //$patients = $query->paginate(15);
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $exc);
        }

        return $patients;
    }

    /**
     * Get list of patients for the hospital and doctor
     * @param $hospitalId, $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientsByHospitalAndDoctor($hospitalId, $doctorId)
    {
        $patients = null;

        try
        {
            $query = DB::table('hospital_patient as hp')->select('p.id', 'p.patient_id', 'p.pid', 'p.name', 'p.age', 'p.gender', 'p.telephone',
                'h.hospital_id', 'h.hospital_name');
            $query->join('hospital as h', 'h.hospital_id', '=', 'hp.hospital_id');
            $query->join('patient as p', 'p.patient_id', '=', 'hp.patient_id');
            $query->join('hospital_doctor as hd', 'hd.hospital_id', '=', 'hp.hospital_id');
            $query->where('hp.hospital_id', '=', $hospitalId);
            $query->where('hd.doctor_id', '=', $doctorId);

            $query->orderBy('hp.created_at', 'DESC');
            //$query->where('p.name', 'LIKE', '%'.$keyword.'%');

            //dd($query->toSql());
            $patients = $query->get();
            //dd($patients);
            //$patients = $query->paginate(15);
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $exc);
        }

        return $patients;
    }

    //Get Patient Details
    /**
     * Get patient details by patient id
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientDetailsById($patientId)
    {
        $patientDetails = null;

        try
        {
            $query = DB::table('patient as p')->select('p.id', 'p.patient_id', 'p.name as name', 'p.address','p.pid', 'c.city_name',
                            'co.name as country','p.telephone', 'p.email', 'p.relationship', 'p.patient_spouse_name as spouseName',
                            'p.dob', 'p.age', 'p.place_of_birth', 'p.nationality', 'p.gender'

                            ,'da.appointment_date', 'da.appointment_time', 'da.brief_history');
            $query->leftJoin('doctor_appointment as da', 'da.patient_id', '=', 'p.patient_id');
            $query->leftJoin('cities as c', 'c.id', '=', 'p.city');
            $query->leftJoin('countries as co', 'co.id', '=', 'p.country');
            $query->where('p.patient_id', $patientId);
            $query->orderBy('da.appointment_date', 'DESC');
            $query->orderBy('da.appointment_time', 'DESC');

            //'p.main_symptoms_id', 'p.sub_symptoms_id', 'p.symptoms_id'

            //dd($query->toSql());

            $patientDetails = $query->get();
            //dd($patientDetails);
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_DETAILS_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_DETAILS_ERROR, $exc);
        }

        return $patientDetails;
    }

    //Get Patient Profile
    /**
     * Get patient profile by patient id
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientProfile($patientId)
    {
        $patientProfile = null;

        try
        {
            $query = DB::table('patient as p')->select('p.id', 'p.patient_id', 'p.name', 'p.pid', 'p.age',
                'p.gender', 'p.email', 'p.relationship', 'p.patient_spouse_name as spouseName', 'p.telephone'
                );
            $query->join('users as usr', 'usr.id', '=', 'p.patient_id');
            $query->where('p.patient_id', $patientId);
            $query->where('usr.delete_status', '=', 1);

            //'p.main_symptoms_id', 'p.sub_symptoms_id', 'p.symptoms_id'

            //dd($query->toSql());
            $patientProfile = $query->get();
            //dd($patientProfile);
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::PATIENT_PROFILE_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_PROFILE_ERROR, $exc);
        }

        return $patientProfile;
    }

    //Get Prescription List
    /**
     * Get list of prescriptions for the selected hospital and doctor
     * @param $hospitalId, $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPrescriptions($hospitalId, $doctorId)
    {
        $prescriptions = null;

        try
        {
            $query = DB::table('patient as p')->select('pp.id as prescription_id', 'pp.unique_id as unique_id',
                'p.patient_id', 'p.pid', 'p.name', 'pp.prescription_date', 'pp.brief_description as notes');
            $query->join('patient_prescription as pp', 'pp.patient_id', '=', 'p.patient_id');
            $query->where('pp.hospital_id', '=', $hospitalId);
            $query->where('pp.doctor_id', '=', $doctorId);
            $query->orderBy('pp.id', 'DESC');

            //dd($query->toSql());

            $prescriptions = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $exc);
        }

        return $prescriptions;
    }

    /**
     * Get list of prescriptions for the patient
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPrescriptionByPatient($patientId)
    {
        $prescriptions = null;

        try
        {
            $query = DB::table('patient as p')->select('pp.id as prescription_id', 'pp.unique_id as unique_id',
                'pp.patient_id', 'p.pid', 'p.name', 'pp.prescription_date', 'pp.brief_description as notes');
            $query->join('patient_prescription as pp', 'pp.patient_id', '=', 'p.patient_id');
            $query->where('pp.patient_id', '=', $patientId);
            $query->orderBy('pp.id', 'DESC');

            $prescriptions = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $exc);
        }

        return $prescriptions;
    }

    /**
     * Get prescription details for the patient by prescription Id
     * @param $prescriptionId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPrescriptionDetails($prescriptionId)
    {
        $prescriptionInfo = null;
        $prescriptionDetails = null;
        $patientDetails = null;
        $doctorDetails = null;
        $hospitalDetails = null;
        $patientPrescription = null;

        try
        {
            /*$query = DB::table('patient as p')->select('p.patient_id', 'p.name', 'p.pid', 'p.telephone',
                'd.brand_name', 'pd.dosage', 'pd.no_of_days', 'pd.morning', 'pd.afternoon', 'pd.night');
            $query->join('patient_prescription as pp', 'pp.patient_id', '=', 'p.patient_id');
            $query->join('prescription_details as pd', 'pd.patient_prescription_id', '=', 'pp.id');
            $query->join('drugs as d', 'd.id', '=', 'pd.drug_id');
            $query->where('pp.id', '=', $prescriptionId);*/

            $prescriptionQuery = DB::table('patient_prescription as pp')->select('pp.id as prescriptionId', 'pp.unique_id as PRID',
                                    'pp.brief_description as notes', 'pp.prescription_date');
            $prescriptionQuery->where('pp.id', '=', $prescriptionId);
            $prescriptionInfo = $prescriptionQuery->get();

            $patientQuery = DB::table('patient as p')->select('p.id', 'p.patient_id', 'p.name', 'p.pid',
                    'pp.prescription_date','p.telephone', 'p.email');
            $patientQuery->join('patient_prescription as pp', 'pp.patient_id', '=', 'p.patient_id');
            $patientQuery->where('pp.id', '=', $prescriptionId);
            $patientDetails = $patientQuery->get();

            $doctorQuery = DB::table('doctor as d')->select('d.id', 'd.doctor_id', 'd.name', 'd.did', 'd.telephone', 'd.email');
            $doctorQuery->join('patient_prescription as pp', 'pp.doctor_id', '=', 'd.doctor_id');
            $doctorQuery->where('pp.id', '=', $prescriptionId);
            $doctorDetails = $doctorQuery->get();

            $hospitalQuery = DB::table('hospital as h')->select('h.id', 'h.hospital_id', 'h.hospital_name', 'h.hid', 'h.telephone', 'h.email');
            $hospitalQuery->join('patient_prescription as pp', 'pp.hospital_id', '=', 'h.hospital_id');
            $hospitalQuery->where('pp.id', '=', $prescriptionId);
            $hospitalDetails = $hospitalQuery->get();

            $query = DB::table('prescription_details as pd')->select('b.id as trade_id',
                        DB::raw('TRIM(UPPER(b.brand_name)) as trade_name'),
                        //'d.id as formulation_id',
                        //DB::raw('TRIM(UPPER(d.drug_name)) as formulation_name'),
                        'b.id as formulation_id',
                        DB::raw('TRIM(UPPER(b.brand_name)) as formulation_name'),
                        'pd.dosage', 'pd.no_of_days', 'pd.intake_form',
                        'pd.morning', 'pd.afternoon', 'pd.night', 'pd.drug_status');
            $query->join('patient_prescription as pp', 'pp.id', '=', 'pd.patient_prescription_id');
            $query->join('brands as b', 'b.id', '=', 'pd.brand_id');
            $query->join('drugs as d', 'd.id', '=', 'pd.drug_id');
            $query->where('pp.id', '=', $prescriptionId);
            $prescriptionDetails = $query->get();

            if(!empty($prescriptionDetails))
            {
                $patientPrescription["PrescriptionInfo"] = $prescriptionInfo;
                $patientPrescription["PatientProfile"] = $patientDetails;
                $patientPrescription["DoctorProfile"] = $doctorDetails;
                $patientPrescription["HospitalProfile"] = $hospitalDetails;
                $patientPrescription["PatientDrugDetails"] = $prescriptionDetails;
            }

            //dd($patientPrescription);

        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_DETAILS_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_DETAILS_ERROR, $exc);
        }

        //dd($patientPrescription);
        return $patientPrescription;
    }

    /**
     * Save Prescription for the patient
     * @param
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientPrescription(PatientPrescriptionViewModel $patientPrescriptionVM)
    {
        $status = true;
        $patientPrescription = null;

        try
        {
            //dd($patientPrescriptionVM);
            $doctorId = $patientPrescriptionVM->getDoctorId();
            $patientId = $patientPrescriptionVM->getPatientId();
            $hospitalId = $patientPrescriptionVM->getHospitalId();

            $doctorUser = User::find($doctorId);
            $hospitalUser = User::find($hospitalId);
            $patientUser = User::find($patientId);

            if (!is_null($doctorUser) && !is_null($hospitalUser) && !is_null($patientUser))
            {
                $patientPrescription = new PatientPrescription();
                $patientPrescription->hospital_id = $hospitalId;
                $patientPrescription->doctor_id = $doctorId;
                $patientPrescription->brief_description = $patientPrescriptionVM->getBriefDescription();
                $patientPrescription->drug_history = $patientPrescriptionVM->getDrugHistory();
                //$patientPrescription->unique_id = "PRID".time();
                $patientPrescription->unique_id = 'PRID'.crc32(uniqid(rand()));
                $patientPrescription->prescription_date = $patientPrescriptionVM->getPrescriptionDate();
                $patientPrescription->created_by = 'Admin';
                $patientPrescription->modified_by = 'Admin';
                $patientPrescription->created_at = $patientPrescriptionVM->getCreatedAt();
                $patientPrescription->updated_at = $patientPrescriptionVM->getUpdatedAt();
                $patientUser->prescriptions()->save($patientPrescription);
            }

            $this->savePrescriptionDetails($patientPrescription, $patientPrescriptionVM);
        }
        catch(QueryException $queryEx)
        {
            $status = false;
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_DETAILS_SAVE_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            $status = false;
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_DETAILS_SAVE_ERROR, $exc);
        }

        return $status;
    }

    private function savePrescriptionDetails($patientPrescription, PatientPrescriptionViewModel $patientPrescriptionVM)
    {
        $drugs = $patientPrescriptionVM->getDrugDetails();

        foreach($drugs as $drug)
        {
            $prescriptionDetails = new PrescriptionDetails();

            $drugObj = (object) $drug;
            $prescriptionDetails->drug_id = $drugObj->drugId;
            $prescriptionDetails->brand_id = $drugObj->brandId;
            //$prescriptionDetails->brief_description = "Test";
            //$prescriptionDetails->brief_description = "Test";
            $prescriptionDetails->dosage = $drugObj->dosage;
            $prescriptionDetails->no_of_days = $drugObj->noOfDays;
            $prescriptionDetails->intake_form = $drugObj->intakeForm;
            $prescriptionDetails->morning = $drugObj->morning;
            $prescriptionDetails->afternoon = $drugObj->afternoon;
            $prescriptionDetails->night = $drugObj->night;
            $prescriptionDetails->created_by = 'Admin';
            $prescriptionDetails->modified_by = 'Admin';

            $prescriptionDetails->created_at = $patientPrescriptionVM->getCreatedAt();
            $prescriptionDetails->updated_at = $patientPrescriptionVM->getUpdatedAt();

            $patientPrescription->prescriptiondetails()->save($prescriptionDetails);
        }
    }

    //Search by Patient Name
    /**
     * Get patient names by keyword
     * @param $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function searchPatientByName($keyword)
    {
        $patientNames = null;

        try
        {
            /*$query = DB::table('patient as p')->select('p.id', 'p.patient_id', 'p.name', 'p.pid');
            $query->join('users as usr', 'usr.id', '=', 'p.patient_id');
            $query->where('usr.delete_status', '=', 1);
            $query->where('p.name', 'LIKE', $keyword.'%');

            //dd($query->toSql());
            $patientNames = $query->get();*/

            $query = DB::table('patient as p')->select('p.id', 'p.patient_id', 'p.name', 'p.pid', 'p.telephone');
            $query->join('users as usr', 'usr.id', '=', 'p.patient_id');
            $query->where('usr.delete_status', '=', 1);
            $query->where('p.pid', 'LIKE', '%'.$keyword.'%');
            $query->orWhere('p.name', 'LIKE', '%'.$keyword.'%');

            //dd($query->toSql());
            $patientNames = $query->get();

        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $exc);
        }

        return $patientNames;
    }

    /**
     * Get patient names by keyword
     * @param $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientNames($hospitalId, $keyword)
    {
        $patientNames = null;

        try
        {
            $hospitalUser = User::find($hospitalId);

            if(!is_null($hospitalUser))
            {
                $query = DB::table('patient as p')->select('p.id', 'p.patient_id as patientId', 'p.name');
                $query->join('users as usr', 'usr.id', '=', 'p.patient_id');
                $query->join('hospital_patient as hp', 'hp.patient_id', '=', 'p.patient_id');
                /*$query->join('hospital_patient as hp', function($join){
                    $join->on('hp.patient_id', '=', 'p.patient_id');
                    $join->on('hp.patient_id', '=', 'usr.id');
                });*/
                $query->where('usr.delete_status', '=', 1);
                $query->where('hp.hospital_id', $hospitalId);
                $query->where('p.name', 'LIKE', '%'.$keyword.'%');

                //dd($query->toSql());

                $patientNames = $query->get();
            }
            //dd($query->toSql());


        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $exc);
        }

        return $patientNames;
    }

    //Search by Patient Pid
    /**
     * Get patient details by PID
     * @param $pid
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function searchPatientByPid($pid)
    {
        $patient = null;

        try
        {
            $query = DB::table('patient as p')->select('p.id', 'p.patient_id', 'p.name', 'p.pid', 'p.telephone');
            $query->join('users as usr', 'usr.id', '=', 'p.patient_id');
            $query->where('usr.delete_status', '=', 1);
            $query->where('p.pid', 'LIKE', '%'.$pid.'%');

            //dd($query->toSql());

            $patient = $query->get();
            //dd($patient);
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $exc);
        }

        return $patient;
    }

    /**
     * Get patient by Pid or Name
     * @param $keyWord
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function searchByPatientByPidOrName($keyWord = null)
    {
        $patients = null;

        try
        {
            $query = DB::table('patient as p')->select('p.id', 'p.patient_id', 'p.name', 'p.pid', 'p.telephone');
            $query->join('users as usr', 'usr.id', '=', 'p.patient_id');
            $query->where('usr.delete_status', '=', 1);
            $query->where('p.pid', 'LIKE', '%'.$keyWord.'%');
            $query->orWhere('p.name', 'LIKE', '%'.$keyWord.'%');

            //dd($query->toSql());
            $patients = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $exc);
        }

        return $patients;
    }

    /**
     * Save new appointments for the patient
     * @param $newAppointmentVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function saveNewAppointment(NewAppointmentViewModel $appointmentVM)
    {
        //dd($appointmentVM);
        $status = true;
        $patientPrescription = null;

        try
        {
            $doctorId = $appointmentVM->getDoctorId();
            $patientId = $appointmentVM->getPatientId();
            $hospitalId = $appointmentVM->getHospitalId();

            $doctorQuery = User::query();
            $doctorQuery->join('doctor as d', 'd.doctor_id', '=', 'users.id');
            $doctorQuery->where('d.doctor_id', '=', $doctorId);

            $doctorUser = $doctorQuery->first();

            $hospitalQuery = User::query();
            $hospitalQuery->join('hospital as h', 'h.hospital_id', '=', 'users.id');
            $hospitalQuery->where('h.hospital_id', '=', $hospitalId);

            $hospitalUser = $hospitalQuery->first();

            if(is_null($doctorUser))
            {
                throw new UserNotFoundException(null, ErrorEnum::USER_NOT_FOUND, null);
            }

            if(is_null($hospitalUser))
            {
                throw new UserNotFoundException(null, ErrorEnum::HOSPITAL_USER_NOT_FOUND, null);
            }

            $doctor = User::find($doctorId);
            $hospital = User::find($hospitalId);
            $patientUser = User::find($patientId);

            if (!is_null($doctor) && !is_null($hospital) && !is_null($patientUser))
            {
                $appointment = new DoctorAppointments();
                $appointment->patient_id = $patientId;
                $appointment->hospital_id = $hospitalId;
                $appointment->brief_history = $appointmentVM->getBriefHistory();
                $appointment->appointment_date = $appointmentVM->getAppointmentDate();
                $appointment->appointment_time = $appointmentVM->getAppointmentTime();
                $appointment->created_by = $appointmentVM->getCreatedBy();
                $appointment->modified_by = $appointmentVM->getUpdatedBy();
                $appointment->created_at = $appointmentVM->getCreatedAt();
                $appointment->updated_at = $appointmentVM->getUpdatedAt();

                $doctor->appointments()->save($appointment);
            }

        }
        catch(QueryException $queryEx)
        {
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_NEW_APPOINTMENT_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_NEW_APPOINTMENT_ERROR, $exc);
        }

        return $status;
    }

    //Drugs
    /**
     * Get brand names by keyword
     * @param $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getTradeNames($keyword)
    {
        $brands = null;
        //dd($keyword);

        try
        {
            /*$query = DB::table('brands as b')->select('b.id as tradeId',
                DB::raw('TRIM(UPPER(b.brand_name)) as tradeName'),
                'b.dosage_amount', 'b.dosage as quantity', 'b.dispensing_form',
                'd.id as formulationId',
                //'b.brand_name as tradeName', 'd.id as formulationId',
                DB::raw('TRIM(UPPER(d.drug_name)) as formulationName'));*/
            $query = DB::table('brands as b')->select('b.id as tradeId',
                DB::raw('TRIM(UPPER(b.brand_name)) as tradeName'),
                'b.dosage_amount', 'b.dosage as quantity', 'b.dispensing_form',
                'b.id as formulationId',
                //'b.brand_name as tradeName', 'd.id as formulationId',
                DB::raw('TRIM(UPPER(b.brand_name)) as formulationName'));
            $query->leftjoin('drugs as d', 'd.id', '=', 'b.drug_id');
            //$query->join('drugs as d', 'd.id', '=', 'b.drug_id');
            $query->where('b.brand_name', 'LIKE', $keyword.'%');
            $query->where('b.brand_status', '=', 1);
            //dd($query->toSql());
            $brands = $query->get();
            //dd($brands);
            /*$query = DB::table('drugs as d')->select('d.id', 'd.brand_name', 'd.drug_name');
            $query->where('d.brand_name', 'LIKE', $keyword.'%');
            $query->where('d.drug_status', '=', 1);

            $brands = $query->get();*/
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::BRAND_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::BRAND_LIST_ERROR, $exc);
        }

        return $brands;
    }

    /**
     * Get formulation names by keyword
     * @param $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getFormulationNames($keyword)
    {
        $formulations = null;
        //dd($keyword);

        try
        {
            $query = DB::table('drugs as d')->select('d.id as formulationId',
                DB::raw('TRIM(UPPER(d.drug_name)) as formulationName')
                ,'b.id as tradeId',
                //DB::raw('CONCAT_WS(" ",TRIM(UPPER(b.brand_name)), NULLIF(b.dosage_amount, ""), NULLIF(b.dosage, "") as tradeName'));
                DB::raw('CONCAT_WS(" ",TRIM(UPPER(b.brand_name)), NULLIF(b.dosage_amount, ""), NULLIF(b.dosage, "")) as tradeName'),
                'b.dosage_amount', 'b.dosage as quantity', 'b.dispensing_form');
                //DB::raw('CONCAT(TRIM(UPPER(b.brand_name)), " ", b.dosage_amount, " ", b.dosage) as tradeName'));

                //DB::raw('TRIM(UPPER(b.brand_name)) as tradeName'));
            $query->join('brands as b', 'b.drug_id', '=', 'd.id');
            $query->where('d.drug_name', 'LIKE', $keyword.'%');
            $query->where('d.drug_status', '=', 1);
            //dd($query->toSql());
            $formulations = $query->get();

            /*DB::raw('CONCAT(TRIM(UPPER(b.brand_name)),
                    COALESCE(b.dosage_amount," "), IF(LENGTH(b.dosage_amount), "", " ")) as tradeName'));*/
            /*$query = DB::table('brands as b')->select('b.id as tradeId',
                DB::raw('CONCAT(TRIM(UPPER(b.brand_name)), " ", b.dosage_amount, " ", b.dosage) as tradeName'), 'd.id as formulationId',
                //'b.brand_name as tradeName', 'd.id as formulationId',
                DB::raw('TRIM(UPPER(d.drug_name)) as formulationName'));
            $query->join('drugs as d', 'd.id', '=', 'b.drug_id');
            $query->where('b.brand_name', 'LIKE', '%'.$keyword.'%');
            $query->where('b.brand_status', '=', 1);*/

        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::FORMULATION_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::FORMULATION_LIST_ERROR, $exc);
        }

        return $formulations;
    }

    //Lab Tests
    /**
     * Get all lab tests
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getLabTests($keyword)
    {
        $labTests = null;

        try
        {
            //dd('Before query');
            $query = DB::table('labtest as lt')->select('lt.id',
                //DB::raw('TRIM(UPPER(lt.test_category)) as test_category'),
                DB::raw('TRIM(UPPER(lt.test_name)) as test_category'),
                DB::raw('TRIM(UPPER(lt.test_name)) as test_name'));
            $query->where('lt.test_status', '=', 1);
            $query->where('lt.test_name', 'LIKE', $keyword.'%');
            //$query = DB::table('labtest as lt')->select('lt.id', 'lt.test_name')->where('lt.test_status', '=', 1);
            //dd($query->toSql());
            $labTests = $query->get();
            //dd($labTests);
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::LAB_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::LAB_LIST_ERROR, $exc);
        }

        return $labTests;
    }

    /**
     * Get list of lab tests for the selected hospital and doctor
     * @param $hospitalId, $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getLabTestsForPatient($hospitalId, $doctorId)
    {
        $patientLabTests = null;

        try
        {
            $query = DB::table('patient as p')->select('pl.id as labtest_id', 'pl.unique_id as unique_id',
                'p.patient_id', 'p.pid', 'p.name', 'pl.labtest_date', 'pl.brief_description as notes');
            $query->join('patient_labtest as pl', 'pl.patient_id', '=', 'p.patient_id');
            $query->where('pl.hospital_id', '=', $hospitalId);
            $query->where('pl.doctor_id', '=', $doctorId);
            $query->orderBy('pl.id', 'DESC');

            $patientLabTests = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::LAB_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::LAB_LIST_ERROR, $exc);
        }

        return $patientLabTests;
    }

    /**
     * Get list of labtests for the patient
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getLabTestsByPatient($patientId)
    {
        $labTests = null;

        try
        {
            /*$query = DB::table('patient as p')->select('pl.id as labtest_id','pl.unique_id as unique_id', 'pl.patient_id', 'p.pid', 'p.name', 'l.test_name', 'pl.labtest_date');
            $query->join('patient_labtest as pl', 'pl.patient_id', '=', 'p.patient_id');
            $query->join('labtest_details as ld', 'ld.patient_labtest_id', '=', 'pl.id');
            $query->join('labtest as l', 'l.id', '=', 'ld.labtest_id');
            $query->where('pl.patient_id', '=', $patientId);
            $query->orderBy('pl.id', 'DESC');*/

            $query = DB::table('patient as p')->select('pl.id as labtest_id','pl.unique_id as unique_id', 'pl.patient_id',
                    'p.pid', 'p.name', 'pl.labtest_date', 'pl.brief_description as notes');
            $query->join('patient_labtest as pl', 'pl.patient_id', '=', 'p.patient_id');
            //$query->join('labtest_details as ld', 'ld.patient_labtest_id', '=', 'pl.id');
            //$query->join('labtest as l', 'l.id', '=', 'ld.labtest_id');
            $query->where('pl.patient_id', '=', $patientId);
            $query->orderBy('pl.id', 'DESC');

            $labTests = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $exc);
        }

        return $labTests;
    }

    /**
     * Get lab test details for the given lab test id
     * @param $labTestId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getLabTestDetails($labTestId)
    {
        $labTestInfo = null;
        $labTestDetails = null;
        $patientDetails = null;
        $doctorDetails = null;
        $hospitalDetails = null;

        $patientLabTests = null;

        try
        {
            /*$query = DB::table('patient as p')->select('p.patient_id', 'p.name', 'p.pid', 'p.telephone',
                'd.brand_name', 'pd.dosage', 'pd.no_of_days', 'pd.morning', 'pd.afternoon', 'pd.night');
            $query->join('patient_prescription as pp', 'pp.patient_id', '=', 'p.patient_id');
            $query->join('prescription_details as pd', 'pd.patient_prescription_id', '=', 'pp.id');
            $query->join('drugs as d', 'd.id', '=', 'pd.drug_id');
            $query->where('pp.id', '=', $prescriptionId);*/

            $labTestQuery = DB::table('patient_labtest as pl')->select('pl.id as labtestId', 'pl.unique_id as LTID',
                'pl.brief_description', 'pl.labtest_date');
            $labTestQuery->where('pl.id', '=', $labTestId);
            $labTestInfo = $labTestQuery->get();

            $patientQuery = DB::table('patient as p')->select('p.id', 'p.patient_id', 'p.name', 'p.pid',
                'pl.labtest_date','p.telephone', 'p.email');
            $patientQuery->join('patient_labtest as pl', 'pl.patient_id', '=', 'p.patient_id');
            $patientQuery->where('pl.id', '=', $labTestId);
            $patientDetails = $patientQuery->get();

            $doctorQuery = DB::table('doctor as d')->select('d.id', 'd.doctor_id', 'd.name', 'd.did', 'd.telephone', 'd.email');
            $doctorQuery->join('patient_labtest as pl', 'pl.doctor_id', '=', 'd.doctor_id');
            $doctorQuery->where('pl.id', '=', $labTestId);
            $doctorDetails = $doctorQuery->get();

            $hospitalQuery = DB::table('hospital as h')->select('h.id', 'h.hospital_id', 'h.hospital_name', 'h.hid', 'h.telephone', 'h.email');
            $hospitalQuery->join('patient_labtest as pl', 'pl.hospital_id', '=', 'h.hospital_id');
            $hospitalQuery->where('pl.id', '=', $labTestId);
            $hospitalDetails = $hospitalQuery->get();

            $query = DB::table('labtest_details as ld')->select('ld.id as ltid',
                'l.id', DB::raw('TRIM(UPPER(l.test_name)) as test_name'),
                DB::raw('TRIM(UPPER(l.test_name)) as test_category'),
                //'l.test_category',
                'ld.brief_description', 'pl.labtest_date', 'ld.labtest_report');
            $query->join('patient_labtest as pl', 'pl.id', '=', 'ld.patient_labtest_id');
            $query->join('labtest as l', 'l.id', '=', 'ld.labtest_id');
            $query->where('pl.id', '=', $labTestId);
            $labTestDetails = $query->get();

            if(!empty($labTestDetails))
            {
                $patientLabTests["LabTestInfo"] = $labTestInfo;
                $patientLabTests["PatientProfile"] = $patientDetails;
                $patientLabTests["DoctorProfile"] = $doctorDetails;
                $patientLabTests["HospitalProfile"] = $hospitalDetails;
                $patientLabTests["PatientLabTestDetails"] = $labTestDetails;
            }


            //dd($patientLabTests);

        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::LAB_DETAILS_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::LAB_DETAILS_ERROR, $exc);
        }

        //dd($patientLabTests);
        return $patientLabTests;
    }

    /**
     * Get patient appointments
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientAppointments($patientId)
    {
        $appointments = null;

        try
        {
            $query = DB::table('doctor_appointment as da')->join('hospital as h', 'h.hospital_id', '=', 'da.hospital_id');
            $query->join('patient as p', 'p.patient_id', '=', 'da.patient_id');
            $query->join('doctor as d', 'd.doctor_id', '=', 'da.doctor_id');
            $query->where('da.patient_id', $patientId);
            $query->select('p.id', 'p.patient_id', 'p.pid', 'p.name as patient_name', 'h.hospital_id', 'h.hospital_name',
                'd.doctor_id', 'd.name', 'da.appointment_date', 'da.appointment_time', 'da.brief_history as notes');

            $appointments = $query->paginate();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_APPOINTMENT_LIST_ERROR, $queryEx);
        }
        catch(Exception $ex)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_APPOINTMENT_LIST_ERROR, $ex);
        }

        return $appointments;
    }

    /**
     * Get patient appointments by hospital
     * @param $patientId, $hospitalId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientAppointmentsByHospital($patientId, $hospitalId)
    {
        $appointments = null;

        try
        {
            $query = DB::table('doctor_appointment as da')->join('hospital as h', 'h.hospital_id', '=', 'da.hospital_id');
            $query->join('patient as p', 'p.patient_id', '=', 'da.patient_id');
            $query->join('doctor as d', 'd.doctor_id', '=', 'da.doctor_id');
            $query->join('hospital_doctor as hd', 'hd.doctor_id', '=', 'da.doctor_id');
            $query->where('hd.hospital_id', $hospitalId);
            $query->where('da.patient_id', $patientId);
            $query->select('p.id', 'p.patient_id', 'p.pid', 'p.name as patient_name', 'h.hospital_id', 'h.hospital_name',
                'd.doctor_id', 'd.name', 'da.appointment_date', 'da.appointment_time', 'da.brief_history as notes');

            //
            $appointments = $query->paginate();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_APPOINTMENT_LIST_ERROR, $queryEx);
        }
        catch(Exception $ex)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_APPOINTMENT_LIST_ERROR, $ex);
        }

        return $appointments;
    }


    /**
     * Save patient profile
     * @param $patientProfileVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function saveNewPatientProfile(PatientProfileViewModel $patientProfileVM)
    {
        $status = true;
        $user = null;
        $patientId = null;
        $patient = null;
        $hospitalPatient = null;
        $hospitalId = null;

        try
        {
            $patientId = $patientProfileVM->getPatientId();
            $hospitalId = $patientProfileVM->getHospitalId();
            //dd($patientId);

            if($patientId == 0)
            {
                $user = $this->registerNewPatient($patientProfileVM);
                $this->attachPatientRole($user);
                $patient = new Patient();
            }
            else
            {
                $patient = Patient::where('patient_id', '=', $patientId)->first();
                if(!is_null($patient))
                {
                    //$user = User::find($companyId);
                    $user = $this->registerNewPatient($patientProfileVM);
                }
            }

            $patient->name = $patientProfileVM->getName();
            $patient->address = $patientProfileVM->getAddress();
            $patient->city = $patientProfileVM->getCity();
            $patient->country = $patientProfileVM->getCountry();
            $patient->pid = 'PID'.crc32(uniqid(rand()));
            //$patient->pid = 'PID'.md5(uniqid(rand()));
            $patient->telephone = $patientProfileVM->getTelephone();
            $patient->email = $patientProfileVM->getEmail();
            $patient->patient_photo = $patientProfileVM->getPatientPhoto();
            $patient->dob = $patientProfileVM->getDob();
            $patient->age = $patientProfileVM->getPlaceOfBirth();
            $patient->nationality = $patientProfileVM->getNationality();
            $patient->gender = $patientProfileVM->getGender();
            $patient->married = $patientProfileVM->getMaritalStatus();

            $patient->created_by = $patientProfileVM->getCreatedBy();
            $patient->created_at = $patientProfileVM->getCreatedAt();
            $patient->updated_by = $patientProfileVM->getUpdatedBy();
            $patient->updated_at = $patientProfileVM->getUpdatedAt();

            $user->patient()->save($patient);

            $user->patienthospitals()->attach($hospitalId, array('created_by' => $patientProfileVM->getCreatedBy(),
                'updated_by' => $patientProfileVM->getUpdatedBy()));
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_PROFILE_SAVE_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_PROFILE_SAVE_ERROR, $exc);
        }

        return $status;
    }

    /**
     * Save patient profile
     * @param $patientProfileVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientProfile(PatientProfileViewModel $patientProfileVM)
    {
        $status = true;
        $user = null;
        $patientId = null;
        $patient = null;
        $hospitalPatient = null;
        $hospitalId = null;

        try
        {
            $patientId = $patientProfileVM->getPatientId();
            $hospitalId = $patientProfileVM->getHospitalId();

            $hospitalUser = User::find($hospitalId);

            if(is_null($hospitalUser))
            {
                $status = false;
                throw new UserNotFoundException(null, ErrorEnum::HOSPITAL_USER_NOT_FOUND);
            }
            //dd($patientId);

            if($patientId == 0)
            {
                $user = $this->registerNewPatient($patientProfileVM);
                $this->attachPatientRole($user);
                $patient = new Patient();
                $patient->pid = 'PID'.crc32(uniqid(rand()));
                $patient->email = $patientProfileVM->getEmail();
            }
            else
            {
                $patient = Patient::where('patient_id', '=', $patientId)->first();
                //dd($patient);
                if(!is_null($patient))
                {
                    //$user = User::find($companyId);
                    $user = $this->registerNewPatient($patientProfileVM);
                }
                else
                {
                    $status = false;
                    throw new UserNotFoundException(null, ErrorEnum::USER_NOT_FOUND);
                }
            }

            $patient->name = $patientProfileVM->getName();
            $patient->address = $patientProfileVM->getAddress();
            $patient->city = $patientProfileVM->getCity();
            $patient->country = $patientProfileVM->getCountry();
            $patient->telephone = $patientProfileVM->getTelephone();
            $patient->relationship = $patientProfileVM->getRelationship();
            $patient->patient_spouse_name = $patientProfileVM->getSpouseName();
            $patient->patient_photo = $patientProfileVM->getPatientPhoto();
            $patient->dob = $patientProfileVM->getDob();
            $patient->age = $patientProfileVM->getAge();
            $patient->nationality = $patientProfileVM->getNationality();
            $patient->gender = $patientProfileVM->getGender();
            $patient->married = $patientProfileVM->getMaritalStatus();
            //$patient->main_symptoms_id = $patientProfileVM->getMainSymptomId();
            //$patient->sub_symptoms_id = $patientProfileVM->getSubSymptomId();
            //$patient->symptoms_id = $patientProfileVM->getSymptomId();

            $patient->created_by = $patientProfileVM->getCreatedBy();
            $patient->created_at = $patientProfileVM->getCreatedAt();
            $patient->updated_by = $patientProfileVM->getUpdatedBy();
            $patient->updated_at = $patientProfileVM->getUpdatedAt();

            $user->patient()->save($patient);

            if($patientId == 0)
            {
                $user->patienthospitals()->attach($hospitalId, array('created_by' => $patientProfileVM->getCreatedBy(),
                    'updated_by' => $patientProfileVM->getUpdatedBy()));
            }

        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_PROFILE_SAVE_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            $status = false;
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_PROFILE_SAVE_ERROR, $exc);
        }

        return $status;
        //return $patient;
    }

    /*private function savePatientSymptoms($patient, PatientProfileViewModel $patientProfileVM)
    {

    }*/

    /**
     * Check if a patient is a new patient or follow up patient
     * @param $hospitalId, $doctorId, $patientId
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function checkIsNewPatient($hospitalId, $doctorId, $patientId)
    {
        $isNewPatient = 'true';

        try
        {
            $doctorUser = User::find($doctorId);
            $hospitalUser = User::find($hospitalId);
            $patientUser = User::find($patientId);

            if (!is_null($doctorUser) && !is_null($hospitalUser) && !is_null($patientUser))
            {
                $query = DB::table('patient_prescription as pp')->where('patient_id', '=', $patientId);
                $query->where('hospital_id', '=', $hospitalId);
                $query->where('doctor_id', '=', $doctorId);

                //dd($query->toSql());
                $count = $query->count();

                if($count > 0)
                {
                    $isNewPatient = 'false';
                }
            }
            else
            {
                $isNewPatient = 'false';
            }

        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::NEW_PATIENT_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::NEW_PATIENT_ERROR, $exc);
        }

        return $isNewPatient;
    }

    private function registerNewPatient(PatientProfileViewModel $patientProfileVM)
    {
        $user = null;
        $patientId = $patientProfileVM->getPatientId();

        if($patientId == 0)
        {
            $user = new User();
        }
        else
        {
            $user = User::find($patientId);
        }

        $user->name = $patientProfileVM->getName();
        $user->email = $patientProfileVM->getEmail();
        $user->password = $patientProfileVM->getName();
        $user->delete_status = 1;
        $user->created_at = $patientProfileVM->getCreatedAt();
        $user->updated_at = $patientProfileVM->getUpdatedAt();

        $user->save();

        return $user;
    }

    private function attachPatientRole(User $user)
    {
        $role = Role::find(UserType::USERTYPE_PATIENT);

        if (!is_null($role))
        {
            $user->attachRole($role);
        }
    }

    /**
     * Save labtests for the patient
     * @param $patientLabTestVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientLabTests(PatientLabTestViewModel $patientLabTestVM)
    {
        $status = true;
        $patientLabTests = null;

        try
        {
            $doctorId = $patientLabTestVM->getDoctorId();
            $patientId = $patientLabTestVM->getPatientId();
            $hospitalId = $patientLabTestVM->getHospitalId();

            $doctorUser = User::find($doctorId);
            $hospitalUser = User::find($hospitalId);
            $patientUser = User::find($patientId);

            if (!is_null($doctorUser) && !is_null($hospitalUser) && !is_null($patientUser))
            {
                $patientLabTests = new PatientLabTests();
                $patientLabTests->hospital_id = $hospitalId;
                $patientLabTests->doctor_id = $doctorId;
                //$patientLabTests->unique_id = "LTID".time();

                $patientLabTests->unique_id = 'LTID'.crc32(uniqid(rand()));
                $patientLabTests->brief_description = $patientLabTestVM->getDescription();
                $patientLabTests->labtest_date = $patientLabTestVM->getLabTestDate();
                $patientLabTests->created_by = 'Admin';
                $patientLabTests->modified_by = 'Admin';
                $patientUser->labtests()->save($patientLabTests);
            }

            $this->saveLabTestDetails($patientLabTests, $patientLabTestVM);
        }
        catch(QueryException $queryEx)
        {
            $status = false;
            throw new HospitalException(null, ErrorEnum::LABTESTS_DETAILS_SAVE_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            $status = false;
            throw new HospitalException(null, ErrorEnum::LABTESTS_DETAILS_SAVE_ERROR, $exc);
        }

        return $status;
    }

    private function saveLabTestDetails($patientLabTests, PatientLabTestViewModel $patientLabTestVM)
    {
        $labTests = $patientLabTestVM->getLabTestDetails();
        foreach($labTests as $labTest)
        {
            $labTestDetails = new LabTestDetails();

            $labTestObj = (object) $labTest;
            $labTestDetails->labtest_id = $labTestObj->labtestId;
            $labTestDetails->brief_description = $labTestObj->description;
            $labTestDetails->created_by = 'Admin';
            $labTestDetails->modified_by = 'Admin';

            $patientLabTests->labtestdetails()->save($labTestDetails);
        }
    }

    /**
     * Get the hospital id for the given pharmacy or lab id
     * @param $userId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */
    public function getHospitalId($userTypeId, $userId)
    {
        $hospitalId = null;
        $query = null;

        try
        {
            if(UserType::USERTYPE_PHARMACY == $userTypeId)
            {
                //$hospitalId = DB::table('hospital_pharmacy as hp')->where('hp.pharmacy_id', $userId)->select('hp.hospital_id')->get();
                $query = DB::table('hospital_pharmacy as hp')->where('hp.pharmacy_id', $userId)->select('hp.hospital_id');
            }
            elseif(UserType::USERTYPE_LAB == $userTypeId)
            {
                //$hospitalId = DB::table('hospital_lab as hl')->where('hl.lab_id', $userId)->select('hl.hospital_id')->get();
                $query = DB::table('hospital_lab as hl')->where('hl.lab_id', $userId)->select('hl.hospital_id');
            }
            elseif(UserType::USERTYPE_DOCTOR == $userTypeId)
            {
                //$hospitalId = DB::table('hospital_lab as hl')->where('hl.lab_id', $userId)->select('hl.hospital_id')->get();
                $query = DB::table('hospital_doctor as hd')->where('hd.doctor_id', $userId)->select('hd.hospital_id');
            }

            //dd($query->toSql());
            $hospitalId = $query->get();

            //dd($hospitalId);

        }
        catch(QueryException $queryExc)
        {
            //dd($queryExc);
            throw new HospitalException(null, ErrorEnum::HOSPITAL_ID_ERROR, $queryExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::HOSPITAL_ID_ERROR, $exc);
        }

        return $hospitalId;
    }

    public function test()
    {
        //dd('Inside test function in implementation');
    }



    public function getProfile($hospitalId)
    {
        $hospitalProfile = null;

        try
        {
            /*$pharmacyProfile = Pharmacy::where('pharmacy_id', '=', $pharmacyId)
                ->get(array('id', 'pharmacy_id', 'name', 'address', ''))->toArray();*/
            //$pharmacyProfile = Pharmacy::where('pharmacy_id', $pharmacyId)->get();

            $query = DB::table('hospital as h')->join('cities as c', 'c.id', '=', 'h.city');
            $query->join('countries as co', 'co.id', '=', 'h.country');
            $query->where('h.hospital_id', '=', $hospitalId);
            $query->select('h.id', 'h.hospital_id', 'h.hospital_name as hospital_name', 'h.address', 'c.id as city_id', 'c.city_name',
                'co.id as country_id', 'co.name as country_name', 'h.hid', 'h.telephone', 'h.email');

            //dd($query->toSql());
            $hospitalProfile = $query->get();
            //dd($pharmacyProfile);

            //dd($pharmacyProfile);
        }
        catch(QueryException $queryExc)
        {
            //dd($queryExc);
            throw new HospitalException(null, ErrorEnum::PHARMACY_PROFILE_VIEW_ERROR, $queryExc);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PHARMACY_PROFILE_VIEW_ERROR, $exc);
        }

        return $hospitalProfile;
    }

    /**
     * Get the doctor names for the hospital
     * @param $hospitalId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getDoctorNames($hospitalId, $keyword)
    {
        $doctors = null;

        try
        {
            $query = DB::table('doctor as d');
            $query->join('users as usr', 'usr.id', '=', 'd.doctor_id');
            $query->join('hospital_doctor as hd', 'hd.doctor_id', '=', 'd.doctor_id');
            $query->where('usr.delete_status', '=', 1);
            $query->where('hd.hospital_id', '=', $hospitalId);
            if($keyword!="")
            {
                $query->where('d.name', 'LIKE', '%'.$keyword.'%');
            }
            $query->select('d.doctor_id as doctorId', 'd.name as doctorName', 'd.specialty as department', 'd.designation',
                        'hd.hospital_id as hospitalId');
            $query->orderBy('d.name', 'ASC');

            //dd($query->toSql());

            $doctors = $query->get();

        }
        catch(QueryException $queryExc)
        {
            //dd($queryExc);
            throw new HospitalException(null, ErrorEnum::HOSPITAL_NO_DOCTORS_FOUND, $queryExc);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_NO_DOCTORS_FOUND, $exc);
        }

        return $doctors;
    }

    //Fee Receipts

    /**
     * Get list of fee receipts for the hospital and doctor
     * @param $hospitalId, $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getFeeReceipts($hospitalId, $doctorId)
    {
        $feeReceipts = null;

        try
        {

            /*$hospitalQuery = User::query();
            $hospitalQuery->join('hospital as h', function($join) {
                $join->on('h.hospital_id', '=', 'users.id');
                $join->on('h.hospital_id', '=', DB::raw('?'));
            })->setBindings(array_merge($doctorQuery->getBindings(), array($hospitalId)));*/

            $doctorQuery = User::query();
            $doctorQuery->join('doctor as d', 'd.doctor_id', '=', 'users.id');
            $doctorQuery->where('d.doctor_id', '=', $doctorId);

            $doctor = $doctorQuery->first();

            //dd($doctor->doctor_id);

            $hospitalQuery = User::query();
            $hospitalQuery->join('hospital as h', 'h.hospital_id', '=', 'users.id');
            $hospitalQuery->where('h.hospital_id', '=', $hospitalId);

            //dd($hospitalQuery->toSql());
            $hospital = $hospitalQuery->first();

            if(is_null($doctor))
            {
                throw new UserNotFoundException(null, ErrorEnum::USER_NOT_FOUND, null);
            }

            if(is_null($hospital))
            {
                throw new UserNotFoundException(null, ErrorEnum::HOSPITAL_USER_NOT_FOUND, null);
            }

            if(!is_null($doctor) && !is_null($hospital))
            {
                $query = DB::table('fee_receipt as fr')->join('patient as p', 'p.patient_id', '=', 'fr.patient_id');
                $query->join('doctor as d', 'd.doctor_id', '=', 'fr.doctor_id');
                $query->where('fr.hospital_id', '=', $hospitalId);
                $query->where('fr.doctor_id', '=', $doctorId);
                $query->orderBy('fr.created_at', 'DESC');
                $query->select('fr.id as receiptId', 'p.id as patientId', 'p.name as patientName', 'p.pid as PID',
                        'p.relationship','p.patient_spouse_name as spouseName',
                        'p.telephone as contactNumber', 'd.name as doctorName', 'fr.fee');


                //dd($query->toSql());
                $feeReceipts = $query->get();
                //dd($feeReceipts);

            }

        }
        catch(QueryException $queryExc)
        {
            //dd($queryExc);
            throw new HospitalException(null, ErrorEnum::FEE_RECEIPT_LIST_ERROR, $queryExc);
        }
        catch(UserNotFoundException $userExc)
        {
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::FEE_RECEIPT_LIST_ERROR, $exc);
        }

        //dd($feeReceipts);
        return $feeReceipts;
    }

    /**
     * Get list of fee receipts for the patient
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getFeeReceiptsByPatient($patientId)
    {
        $feeReceipts = null;

        try
        {
            $patientQuery = User::query();
            $patientQuery->join('patient as p', 'p.patient_id', '=', 'users.id');
            $patientQuery->where('p.patient_id', '=', $patientId);
            $patientQuery->where('users.delete_status', '=', 1);

            $patient = $patientQuery->first();

            if(is_null($patient))
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }

            if(!is_null($patient))
            {
                $query = DB::table('fee_receipt as fr')->join('patient as p', 'p.patient_id', '=', 'fr.patient_id');
                $query->join('doctor as d', 'd.doctor_id', '=', 'fr.doctor_id');
                //$query->where('fr.doctor_id', '=', 'd.doctor_id');
                $query->where('p.patient_id', '=', $patientId);
                $query->orderBy('fr.created_at', 'DESC');
                $query->select('fr.id as receiptId', 'p.id as patientId', 'p.name as patientName', 'p.pid as PID',
                    'p.relationship','p.patient_spouse_name as spouseName',
                    'p.telephone as contactNumber', 'd.name as doctorName', 'fr.fee');

                //dd($query->toSql());
                $feeReceipts = $query->get();
                //dd($feeReceipts);

            }

        }
        catch(QueryException $queryExc)
        {
            //dd($queryExc);
            throw new HospitalException(null, ErrorEnum::FEE_RECEIPT_LIST_ERROR, $queryExc);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::FEE_RECEIPT_LIST_ERROR, $exc);
        }

        //dd($feeReceipts);
        return $feeReceipts;
    }

    /**
     * Get fee receipt details, doctor details, patient details
     * @param $hospitalId, $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getReceiptDetails($receiptId)
    {
        $feeInfo = null;
        $doctorId = null;
        $patientId = null;
        $hospitalId = null;
        $feeWords = null;
        $fees = null;

        $patientDetails = null;
        $doctorDetails = null;
        $hospitalDetails = null;
        $feeReceiptDetails = null;

        try
        {
            $feeDetailsQuery = DB::table('fee_receipt as fr')->where('fr.id', '=', $receiptId);
            $feeDetailsQuery->select('fr.id as receiptId', 'fr.patient_id as patientId', 'fr.doctor_id as doctorId',
                        'fr.hospital_id as hospitalId', 'fr.fee');

            $feeInfo = $feeDetailsQuery->first();

            //dd($feeInfo);

            $doctorId = $feeInfo->doctorId;
            $hospitalId = $feeInfo->hospitalId;
            $patientId = $feeInfo->patientId;
            $fees = $feeInfo->fee;

            $feeWords = $this->convertFee($fees);
            //dd($feeWords);

            //$feeDetails = (array)$feeInfo;
            $feeDetails['inWords'] = $feeWords;
            $feeDetails['fee'] = $fees;

            //array_push($feeDetails, $feeWords);
            //dd($feeDetails);

            $patientQuery = DB::table('patient as p')->select('p.id', 'p.patient_id', 'p.name', 'p.email', 'p.pid',
                'p.telephone', 'p.relationship', 'p.patient_spouse_name as spouseName', 'p.address');
            $patientQuery->where('p.patient_id', '=', $patientId);
            $patientDetails = $patientQuery->first();

            $doctorQuery = DB::table('doctor as d')->select('d.id', 'd.doctor_id', 'd.name', 'd.did', 'd.designation',
                        'd.specialty as department');
            $doctorQuery->where('d.doctor_id', '=', $doctorId);
            $doctorDetails = $doctorQuery->first();

            $hospitalQuery = DB::table('hospital as h')->select('h.id', 'h.hospital_id', 'h.hospital_name', 'h.hid',
                    'h.address', 'h.hospital_logo', 'c.city_name as cityName', 'co.name as country');
            $hospitalQuery->join('cities as c', 'c.id', '=', 'h.city');
            $hospitalQuery->join('countries as co', 'co.id', '=', 'h.country');
            $hospitalQuery->where('h.hospital_id', '=', $hospitalId);
            $hospitalDetails = $hospitalQuery->first();

            $feeReceiptDetails["patientDetails"] = $patientDetails;
            $feeReceiptDetails["doctorDetails"] = $doctorDetails;
            $feeReceiptDetails["hospitalDetails"] = $hospitalDetails;
            $feeReceiptDetails["feeDetails"] = $feeDetails;

        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::FEE_RECEIPT_DETAILS_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::FEE_RECEIPT_DETAILS_ERROR, $exc);
        }

        //dd($feeReceiptDetails);
        return $feeReceiptDetails;
    }

    private function convertFee($fee)
    {
        $feeInWords = null;
        $currency = CA::get('constants.currency');
        $locale = CA::get('constants.locale');


        $numberWords = new Numbers_Words();
        if(!is_null($numberWords))
        {
            //$feeInWords = $numberWords->toWords($fee);
            $feeInWords = $numberWords->toCurrency($fee, $locale, $currency);
        }


        //$feeInWords  = $fee." VALUE";
        return $feeInWords;
    }

    /**
     * Save fee receipt
     * @param $feeReceiptVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function saveFeeReceipt(FeeReceiptViewModel $feeReceiptVM)
    {
        $status = true;

        try
        {
            $doctorId = $feeReceiptVM->getDoctorId();
            $patientId = $feeReceiptVM->getPatientId();
            $hospitalId = $feeReceiptVM->getHospitalId();

            //$doctorUser = User::find($doctorId);
            //$hospitalUser = User::find($hospitalId);
            $patientUser = User::find($patientId);

            $doctorQuery = User::query();
            $doctorQuery->join('doctor as d', 'd.doctor_id', '=', 'users.id');
            $doctorQuery->where('d.doctor_id', '=', $doctorId);

            $doctorUser = $doctorQuery->first();

            $hospitalQuery = User::query();
            $hospitalQuery->join('hospital as h', 'h.hospital_id', '=', 'users.id');
            $hospitalQuery->where('h.hospital_id', '=', $hospitalId);

            $hospitalUser = $hospitalQuery->first();

            if(is_null($doctorUser))
            {
                throw new UserNotFoundException(null, ErrorEnum::USER_NOT_FOUND, null);
            }

            if(is_null($hospitalUser))
            {
                throw new UserNotFoundException(null, ErrorEnum::HOSPITAL_USER_NOT_FOUND, null);
            }


            if (!is_null($doctorUser) && !is_null($hospitalUser) && !is_null($patientUser))
            {
                $feeReceipt = new FeeReceipt();
                $feeReceipt->hospital_id = $hospitalId;
                $feeReceipt->patient_id = $patientId;
                $feeReceipt->doctor_id = $doctorId;
                //$patientLabTests->unique_id = "LTID".time();

                $feeReceipt->fee = $feeReceiptVM->getFees();
                $feeReceipt->created_by = 'Admin';
                $feeReceipt->modified_by = 'Admin';
                //$feeReceipt->created_by = $feeReceiptVM->getCreatedBy();
                //$feeReceipt->modified_by = $feeReceiptVM->getUpdatedBy();
                $feeReceipt->created_at = $feeReceiptVM->getCreatedAt();
                $feeReceipt->updated_at = $feeReceiptVM->getUpdatedAt();
                $feeReceipt->save();


                //$doctor->feereceipts()->save($feeReceipt);$doctorUser
                //$doctorUser->feereceipts()->save($feeReceipt);
            }

        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            $status = false;
            throw new HospitalException(null, ErrorEnum::FEE_RECEIPT_SAVE_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $status = false;
            throw new HospitalException(null, ErrorEnum::FEE_RECEIPT_SAVE_ERROR, $exc);
        }

        return $status;
    }

    /*Symptom section -- Begin */

    /**
     * Get all the symptoms
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getMainSymptoms()
    {
        $mainSymptoms = null;

        try
        {
            $query = DB::table('main_symptoms as ms')->where('ms.status', '=', 1);
            $query->select('ms.id', 'ms.main_symptom_name', 'ms.main_symptom_code');
            $mainSymptoms = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::MAIN_SYMPTOMS_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::MAIN_SYMPTOMS_LIST_ERROR, $exc);
        }

        return $mainSymptoms;
    }

    /**
     * Get all the sub symptoms for main symptom
     * @param $mainSymptomsId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getSubSymptomsForMainSymptoms($mainSymptomsId)
    {
        $subSymptoms = null;

        try
        {
            $query = DB::table('sub_symptoms as ss')->where('ss.status', '=', 1);
            $query->where('ss.main_symptom_id', $mainSymptomsId);
            $query->select('ss.id', 'ss.sub_symptom_name', 'ss.sub_symptom_code');
            $subSymptoms = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::SUB_SYMPTOMS_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::SUB_SYMPTOMS_LIST_ERROR, $exc);
        }

        return $subSymptoms;
    }

    /**
     * Get all the symptoms for sub symptom
     * @param $subSymptomId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getSymptomsForSubSymptoms($subSymptomId)
    {
        $symptoms = null;

        try
        {
            $query = DB::table('symptoms as s')->where('s.status', '=', 1);
            $query->where('s.sub_symptom_id', $subSymptomId);
            $query->select('s.id', 's.symptom_name', 's.symptom_code');
            $symptoms = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::SYMPTOMS_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::SYMPTOMS_LIST_ERROR, $exc);
        }

        return $symptoms;
    }

    /**
     * Get personal history for the patient
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPersonalHistory($patientId, $personalHistoryDate)
    {
        $feeInfo = null;
        $doctorId = null;

        $personalHistory = null;
        $patientHistory = null;
        $personalHistoryDetails = null;

        //$personalHistoryQuery

        try
        {
            $patientUser = User::find($patientId);

            if(is_null($patientUser))
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }

            /*$patientHistoryQuery = DB::table('personal_history as ph')->where('pph.patient_id', '=', $patientId);
            $patientHistoryQuery->join('personal_history_item as phi', 'phi.personal_history_id', '=', 'ph.id');
            $patientHistoryQuery->join('patient_personal_history as pph', function($join){
                $join->on('pph.personal_history_id', '=', 'ph.id');
                $join->on('pph.personal_history_id', '=', 'ph.id');
            });*/
            $patientHistoryQuery = DB::table('patient_personal_history as pph')->where('pph.patient_id', '=', $patientId)->where('pph.personal_history_date', '=', $personalHistoryDate);
            $patientHistoryQuery->join('personal_history as ph', 'ph.id', '=', 'pph.personal_history_id');
            $patientHistoryQuery->join('personal_history_item as phi', 'phi.id', '=', 'pph.personal_history_item_id');

            $patientHistoryQuery->select('pph.id', 'pph.patient_id as patientId', 'ph.id as personalHistoryId',
                'ph.personal_history_name as personalHistoryName', 'phi.id as personalHistoryItemId',
                'phi.personal_history_item_name as personalHistoryItemName');

            //dd($patientHistoryQuery->toSql());
            $patientHistory = $patientHistoryQuery->get();
            //dd($patientHistory);

            $personalHistoryQuery = DB::table('personal_history as ph')->join('personal_history_item as phi', 'phi.personal_history_id', '=', 'ph.id');
            $personalHistoryQuery->select('ph.id as personalHistoryId', 'ph.personal_history_name as personalHistoryName',
                'phi.id as personalHistoryItemId', 'phi.personal_history_item_name as personalHistoryItemName');
            $personalHistory = $personalHistoryQuery->get();

            /*if(!is_null($patientFeedback) && !empty($patientFeedback))
            {

            }*/

            $personalHistoryDetails["patientHistory"] = $patientHistory;
            $personalHistoryDetails["personalHistory"] = $personalHistory;
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::PERSONAL_HISTORY_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::PERSONAL_HISTORY_ERROR, $exc);
        }

        //dd($feeReceiptDetails);
        return $personalHistoryDetails;
    }

    /**
     * Get patient past illness
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientPastIllness($patientId, $pastIllnessDate)
    {
        $pastIllness = null;

        //dd($patientId);

        try
        {
            $patientUser = User::find($patientId);

            if(is_null($patientUser))
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }

            $query = DB::table('past_illness as pii')->select('ppi.id as patientPastIllnessId', 'pii.id as patientIllnessId', 'pii.illness_name as illnessName',
                'ppi.past_illness_name as otherIllnessName', 'ppi.relation', 'ppi.is_value_set as isValueSet');
            //$query->leftJoin('patient_past_illness as ppi', function($join){
            $query->join('patient_past_illness as ppi', function($join){
                $join->on('ppi.past_illness_id', '=', 'pii.id');
                $join->on('ppi.patient_id', '=', DB::raw('?'));
                $join->on('ppi.past_illness_date', '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), array($patientId, $pastIllnessDate)));
            $query->where('pii.status', '=', 1);
            //dd($query->toSql());

            $pastIllness = $query->get();
            //dd($pastIllness);
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::PATIENT_PAST_ILLNESS_DETAILS_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::PATIENT_PAST_ILLNESS_DETAILS_ERROR, $exc);
        }

        return $pastIllness;
    }

    /**
     * Get patient family illness
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientFamilyIllness($patientId, $familyIllnessDate)
    {
        $familyIllness = null;

        try
        {
            $patientUser = User::find($patientId);

            if(is_null($patientUser))
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }

            $query = DB::table('family_illness as fi')->select('fi.id as familyIllnessId', 'fi.illness_name as familyIllnessName',
                'pfi.id as patientIllnessId', 'pfi.family_illness_name as otherIllnessName', 'pfi.relation', 'pfi.is_value_set as isValueSet');
            //$query->leftJoin('patient_family_illness as pfi', function($join){
            $query->join('patient_family_illness as pfi', function($join){
                $join->on('pfi.family_illness_id', '=', 'fi.id');
                $join->on('pfi.patient_id', '=', DB::raw('?'));
                $join->on('pfi.family_illness_date', '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), array($patientId, $familyIllnessDate)));
            $query->where('fi.status', '=', 1);

            $familyIllness = $query->get();
            //dd($pastIllness);
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::PATIENT_FAMILY_ILLNESS_DETAILS_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::PATIENT_FAMILY_ILLNESS_DETAILS_ERROR, $exc);
        }

        return $familyIllness;
    }

    /**
     * Get patient general examination
     * @param $patientId, $generalExaminationDate
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientGeneralExamination($patientId, $generalExaminationDate)
    {
        $generalExamination = null;

        try
        {
            $patientUser = User::find($patientId);

            if(is_null($patientUser))
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }

            $query = DB::table('patient_general_examination as pge')->select('ge.id', 'ge.general_examination_name as generalExaminationName',
                'pge.id as patientExaminationId', 'pge.general_examination_value as generalExaminationValue', 'pge.is_value_set as isValueSet');
            //$query->rightJoin('general_examination as ge', function($join){
            $query->join('general_examination as ge', function($join){
                $join->on('ge.id', '=', 'pge.general_examination_id');
                $join->on('pge.patient_id', '=', DB::raw('?'));
                $join->on('pge.general_examination_date', '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), array($patientId, $generalExaminationDate)));
            $query->where('ge.status', '=', 1);

            $generalExamination = $query->get();
            //dd($pastIllness);
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::PATIENT_GENERAL_EXAMINATION_DETAILS_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::PATIENT_GENERAL_EXAMINATION_DETAILS_ERROR, $exc);
        }

        return $generalExamination;
    }

    /**
     * Get patient pregnancy details
     * @param $patientId, $pregnancyDate
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPregnancyDetails($patientId, $pregnancyDate)
    {
        $pregnancyDetails = null;

        try
        {
            $patientUser = User::find($patientId);

            if(is_null($patientUser))
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }

            $query = DB::table('patient_pregnancy as pp')->select('p.id as pregnancyId', 'p.pregnancy_details as pregnancyDetails',
                'pp.id as patientPregnancyId', 'pp.pregnancy_value as pregnancyValue',
                'pp.pregnancy_date as pregnancyExaminationDate', 'pp.is_value_set as isValueSet');
            //$query->rightJoin('pregnancy as p', function($join){
            $query->join('pregnancy as p', function($join){
                $join->on('p.id', '=', 'pp.pregnancy_id');
                $join->on('pp.patient_id', '=', DB::raw('?'));
                $join->on('pp.pregnancy_date', '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), array($patientId, $pregnancyDate)));
            $query->where('p.status', '=', 1);

            //dd($query->toSql());

            $pregnancyDetails = $query->get();
            //dd($pregnancyDetails);
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::PATIENT_PREGNANCY_DETAILS_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::PATIENT_PREGNANCY_DETAILS_ERROR, $exc);
        }

        return $pregnancyDetails;
    }

    /**
     * Get patient scan details
     * @param $patientId, $scanDate
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientScanDetails($patientId, $scanDate)
    {
        $scanDetails = null;

        try
        {
            $patientUser = User::find($patientId);

            if(is_null($patientUser))
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }

            $query = DB::table('patient_scan as ps')->select('s.id as scanId', 's.scan_name as scanName',
                'ps.id as patientScanId', 'ps.is_value_set as isValueSet', 'ps.scan_date as scanDate');
            //$query->rightJoin('scans as s', function($join){
            $query->join('scans as s', function($join){
                $join->on('s.id', '=', 'ps.scan_id');
                $join->on('ps.patient_id', '=', DB::raw('?'));
                $join->on('ps.scan_date', '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), array($patientId, $scanDate)));
            $query->where('s.status', '=', 1);

            //dd($query->toSql());

            $scanDetails = $query->get();
            //dd($pregnancyDetails);
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::PATIENT_SCAN_DETAILS_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::PATIENT_SCAN_DETAILS_ERROR, $exc);
        }

        return $scanDetails;
    }

    /**
     * Get patient symptom details
     * @param $patientId, $symptomDate
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientSymptoms($patientId, $symptomDate)
    {
        $symptomDetails = null;

        try
        {
            $patientUser = User::find($patientId);

            if(is_null($patientUser))
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }

            $query = DB::table('patient_symptoms as ps')->select('ps.patient_id as patientId', 'ps.main_symptom_id as mainSymptomId',
                'ms.main_symptom_name as mainSymptomName',
                'ps.sub_symptom_id as subSymptomId', 'ss.sub_symptom_name as subSymptomName',
                'ps.symptom_id as symptomId', 's.symptom_name as symptomName',
                'ps.is_value_set as isValueSet', 'ps.patient_symptom_date as symptomDate');
            $query->join('main_symptoms as ms', function($join){
                $join->on('ms.id', '=', 'ps.main_symptom_id');
                $join->on('ms.status', '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), array(1)));
            $query->join('sub_symptoms as ss', function($join){
                $join->on('ss.id', '=', 'ps.sub_symptom_id');
                $join->on('ss.status', '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), array(1)));
            $query->join('symptoms as s', function($join){
                $join->on('s.id', '=', 'ps.symptom_id');
                $join->on('s.status', '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), array(1)));
            $query->where('ps.patient_id', '=', $patientId);
            $query->where('ps.patient_symptom_date', '=', $symptomDate);

            //dd($query->toSql());

            $symptomDetails = $query->get();
            //dd($symptomDetails);
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::PATIENT_SYMPTOM_DETAILS_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::PATIENT_SYMPTOM_DETAILS_ERROR, $exc);
        }

        return $symptomDetails;
    }

    /**
     * Get patient drug history
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientDrugHistory($patientId)
    {
        $drugHistory = null;
        $surgeryHistory = null;
        $drugSurgeryHistory = null;

        try
        {
            $patientUser = User::find($patientId);

            if(is_null($patientUser))
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }

            $drugQuery = DB::table('patient_drug_history as pdh')->select('pdh.id as id', 'pdh.patient_id as patientId',
                'pdh.drug_name as drugName', 'pdh.dosage', 'pdh.timings');
            $drugQuery->where('pdh.patient_id', $patientId);

            //dd($query->toSql());

            $drugHistory = $drugQuery->get();

            $surgeryQuery = DB::table('patient_surgeries as ps')->select('ps.id as id', 'ps.patient_id as patientId',
                'ps.patient_surgeries as patientSurgeries', 'ps.operation_date as operationDate');
            $surgeryQuery->where('ps.patient_id', $patientId);

            $surgeryHistory = $surgeryQuery->get();

            $drugSurgeryHistory['drugHistory'] = $drugHistory;
            $drugSurgeryHistory['surgeryHistory'] = $surgeryHistory;


            //dd($drugHistory);
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::PATIENT_DRUG_HISTORY_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::PATIENT_DRUG_HISTORY_ERROR, $exc);
        }

        return $drugSurgeryHistory;
    }

    /**
     * Get patient urine tests
     * @param $patientId, $urineTestDate
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientUrineTests($patientId, $urineTestDate)
    {
        $urineTests = null;

        try
        {
            $patientUser = User::find($patientId);

            if(is_null($patientUser))
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }

            $query = DB::table('patient_urine_examination as pue')->select('ue.id as examinationId', 'ue.examination_name as examinationName',
                'pue.id as patientExaminationId', 'pue.is_value_set as isValueSet', 'pue.examination_date as examinationDate');
            //$query->rightJoin('scans as s', function($join){
            $query->join('urine_examination as ue', function($join){
                $join->on('ue.id', '=', 'pue.urine_examination_id');
                $join->on('pue.patient_id', '=', DB::raw('?'));
                $join->on('pue.examination_date', '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), array($patientId, $urineTestDate)));
            $query->where('ue.status', '=', 1);

            //dd($query->toSql());

            $urineTests = $query->get();
            //dd($pregnancyDetails);
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::PATIENT_URINE_DETAILS_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::PATIENT_URINE_DETAILS_ERROR, $exc);
        }

        return $urineTests;
    }

    /**
     * Get patient motion tests
     * @param $patientId, $motionTestDate
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientMotionTests($patientId, $motionTestDate)
    {
        $motionTests = null;

        try
        {
            $patientUser = User::find($patientId);

            if(is_null($patientUser))
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }

            $query = DB::table('patient_motion_examination as pme')->select('me.id as examinationId', 'me.examination_name as examinationName',
                'pme.id as patientExaminationId', 'pme.is_value_set as isValueSet', 'pme.examination_date as examinationDate');
            //$query->rightJoin('scans as s', function($join){
            $query->join('motion_examination as me', function($join){
                $join->on('me.id', '=', 'pme.motion_examination_id');
                $join->on('pme.patient_id', '=', DB::raw('?'));
                $join->on('pme.examination_date', '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), array($patientId, $motionTestDate)));
            $query->where('me.status', '=', 1);

            //dd($query->toSql());

            $motionTests = $query->get();
            //dd($pregnancyDetails);
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::PATIENT_MOTION_DETAILS_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::PATIENT_MOTION_DETAILS_ERROR, $exc);
        }

        return $motionTests;
    }

    /**
     * Get patient blood tests
     * @param $patientId, $bloodTestDate
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientBloodTests($patientId, $bloodTestDate)
    {
        $bloodTests = null;

        try
        {
            $patientUser = User::find($patientId);

            if(is_null($patientUser))
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }

            $query = DB::table('patient_blood_examination as pbe')->select('be.id as examinationId', 'be.examination_name as examinationName',
                'pbe.id as patientExaminationId', 'pbe.is_value_set as isValueSet', 'pbe.examination_date as examinationDate');
            //$query->rightJoin('scans as s', function($join){
            $query->join('blood_examination as be', function($join){
                $join->on('be.id', '=', 'pbe.blood_examination_id');
                $join->on('pbe.patient_id', '=', DB::raw('?'));
                $join->on('pbe.examination_date', '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), array($patientId, $bloodTestDate)));
            $query->where('be.status', '=', 1);

            //dd($query->toSql());

            $bloodTests = $query->get();
            //dd($pregnancyDetails);
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::PATIENT_BLOOD_DETAILS_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::PATIENT_BLOOD_DETAILS_ERROR, $exc);
        }

        return $bloodTests;
    }

    /**
     * Get patient ultrasound tests
     * @param $patientId, $ultraSoundDate
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientUltraSoundTests($patientId, $ultraSoundDate)
    {
        $ultraSound = null;

        try
        {
            $patientUser = User::find($patientId);

            if(is_null($patientUser))
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }

            $query = DB::table('patient_ultra_sound as pus')->select('us.id as examinationId', 'us.examination_name as examinationName',
                'pus.id as patientExaminationId', 'pus.is_value_set as isValueSet', 'pus.examination_date as examinationDate');
            //$query->rightJoin('scans as s', function($join){
            $query->join('ultra_sound as us', function($join){
                $join->on('us.id', '=', 'pus.ultra_sound_id');
                $join->on('pus.patient_id', '=', DB::raw('?'));
                $join->on('pus.examination_date', '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), array($patientId, $ultraSoundDate)));
            $query->where('us.status', '=', 1);

            //dd($query->toSql());

            $ultraSound = $query->get();
            //dd($pregnancyDetails);
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::PATIENT_ULTRASOUND_DETAILS_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::PATIENT_ULTRASOUND_DETAILS_ERROR, $exc);
        }

        return $ultraSound;
    }

    /**
     * Get all family illness
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getAllFamilyIllness()
    {
        $familyIllness = null;

        try
        {
            $query = DB::table('family_illness as fi')->where('fi.status', '=', 1);
            $query->select('fi.id', 'fi.illness_name');

            $familyIllness = $query->get();
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::FAMILY_ILLNESS_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::FAMILY_ILLNESS_ERROR, $exc);
        }

        return $familyIllness;
    }

    /**
     * Get all past illness
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getAllPastIllness()
    {
        $pastIllness = null;

        try
        {
            $query = DB::table('past_illness as pi')->where('pi.status', '=', 1);
            $query->select('pi.id', 'pi.illness_name');

            $pastIllness = $query->get();
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::PAST_ILLNESS_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::PAST_ILLNESS_ERROR, $exc);
        }

        return $pastIllness;
    }

    /**
     * Get all general examinations
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getAllGeneralExaminations()
    {
        $generalExaminations = null;

        try
        {
            $query = DB::table('general_examination as ge')->where('ge.status', '=', 1);
            $query->select('ge.id', 'ge.general_examination_name');

            $generalExaminations = $query->get();
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::GENERAL_EXAMINATIONS_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::GENERAL_EXAMINATIONS_ERROR, $exc);
        }

        return $generalExaminations;
    }

    /**
     * Get all personal history
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getAllPersonalHistory()
    {
        $personalHistory = null;

        try
        {
            $query = DB::table('personal_history as ph')->where('ph.status', '=', 1);
            $query->select('ph.id', 'ph.personal_history_name');

            $personalHistory = $query->get();
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::PERSONAL_HISTORY_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::PERSONAL_HISTORY_LIST_ERROR, $exc);
        }

        return $personalHistory;
    }

    /**
     * Get all pregnancy
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getAllPregnancy()
    {
        $pregnancy = null;

        try
        {
            $query = DB::table('pregnancy as p')->where('p.status', '=', 1);
            $query->select('p.id', 'p.pregnancy_details');

            $pregnancy = $query->get();
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::PREGNANCY_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::PREGNANCY_LIST_ERROR, $exc);
        }

        return $pregnancy;
    }

    /**
     * Get all scans
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getAllScans()
    {
        $scans = null;

        try
        {
            $query = DB::table('scans as s')->where('s.status', '=', 1);
            $query->select('s.id', 's.scan_name');

            $scans = $query->get();
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::SCAN_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::SCAN_LIST_ERROR, $exc);
        }

        return $scans;
    }

    /**
     * Get patient examination dates
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getExaminationDates($patientId)
    {
        $examinationDates = null;
        $generalExaminationDates = null;
        $pastIllnessDates = null;
        $familyIllnessDates = null;
        $personalHistoryDates = null;
        $pregnancyDates = null;
        $scanDates = null;
        $symptomDates = null;
        $ultraSoundDates = null;

        $patientLabTests = null;

        try
        {
            $patientUser = User::find($patientId);

            if(is_null($patientUser))
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }

            $examinationQuery = DB::table('patient_general_examination as pge')->where('pge.patient_id', '=', $patientId);
            $examinationQuery->select('pge.general_examination_date')->orderBy('pge.general_examination_date', 'DESC');
            //dd($examinationQuery->toSql());
            $generalExaminationDates = $examinationQuery->distinct()->get();
            //dd($generalExaminationDates);

            $pastIllnessQuery = DB::table('patient_past_illness as ppi')->where('ppi.patient_id', '=', $patientId);
            $pastIllnessQuery->select('ppi.past_illness_date')->orderBy('ppi.past_illness_date', 'DESC');
            $pastIllnessDates = $pastIllnessQuery->distinct()->get();

            $familyIllnessQuery = DB::table('patient_family_illness as pfi')->where('pfi.patient_id', '=', $patientId);
            $familyIllnessQuery->select('pfi.family_illness_date')->orderBy('pfi.family_illness_date', 'DESC');
            $familyIllnessDates = $familyIllnessQuery->distinct()->get();

            $personalHistoryQuery = DB::table('patient_personal_history as pph')->where('pph.patient_id', '=', $patientId);
            $personalHistoryQuery->select('pph.personal_history_date')->orderBy('pph.personal_history_date', 'DESC');
            $personalHistoryDates = $personalHistoryQuery->distinct()->get();

            $pregnancyDetailsQuery = DB::table('patient_pregnancy as pp')->where('pp.patient_id', '=', $patientId);
            $pregnancyDetailsQuery->select('pp.pregnancy_date')->orderBy('pp.pregnancy_date', 'DESC');
            $pregnancyDates = $pregnancyDetailsQuery->distinct()->get();

            $scanDetailsQuery = DB::table('patient_scan as ps')->where('ps.patient_id', '=', $patientId);
            $scanDetailsQuery->select('ps.scan_date')->orderBy('ps.scan_date', 'DESC');
            $scanDates = $scanDetailsQuery->distinct()->get();

            $symptomDatesQuery = DB::table('patient_symptoms as ps')->where('ps.patient_id', '=', $patientId);
            $symptomDatesQuery->select('ps.patient_symptom_date')->orderBy('ps.patient_symptom_date', 'DESC');
            $symptomDates = $symptomDatesQuery->distinct()->get();

            $ultraSoundDatesQuery = DB::table('patient_ultra_sound as pus')->where('pus.patient_id', '=', $patientId);
            $ultraSoundDatesQuery->select('pus.examination_date')->orderBy('pus.examination_date', 'DESC');
            $ultraSoundDates = $ultraSoundDatesQuery->distinct()->get();

            $examinationDates["generalExaminationDates"] = $generalExaminationDates;
            $examinationDates["pastIllnessDates"] = $pastIllnessDates;
            $examinationDates["familyIllnessDates"] = $familyIllnessDates;
            $examinationDates["personalHistoryDates"] = $personalHistoryDates;
            $examinationDates["pregnancyDates"] = $pregnancyDates;
            $examinationDates["scanDates"] = $scanDates;
            $examinationDates["symptomDates"] = $symptomDates;
            $examinationDates["ultraSoundDates"] = $ultraSoundDates;

            //dd($examinationDates);

        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::PATIENT_EXAMINATION_DATES_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_EXAMINATION_DATES_ERROR, $exc);
        }

        //dd($patientLabTests);
        return $examinationDates;
    }

    /**
     * Save patient personal history
     * @param $patientHistoryVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePersonalHistory(PatientPersonalHistoryViewModel $patientHistoryVM)
    {
        $status = true;

        try
        {
            $patientId = $patientHistoryVM->getPatientId();
            $patientUser = User::find($patientId);

            //dd($patientId);

            $patientPersonalHistory = $patientHistoryVM->getPatientPersonalHistory();
            //dd($patientPersonalHistory);

            if (!is_null($patientUser))
            {
                //DB::table('patient_personal_history')->where('patient_id', $patientId)->delete();

                foreach($patientPersonalHistory as $patientHistory)
                {
                    //dd($patientHistory);
                    $personalHistoryId = $patientHistory->personalHistoryId;
                    $personalHistoryItemId = $patientHistory->personalHistoryItemId;
                    $isValueSet = $patientHistory->isValueSet;
                    //$personalHistoryDate = \DateTime::createFromFormat('Y-m-d', $patientHistory->personalHistoryDate);
                    //$historyDate = $patientHistory->personalHistoryDate;

                    $historyDate = property_exists($patientHistory, 'personalHistoryDate') ? $patientHistory->personalHistoryDate : null;

                    if(!is_null($historyDate))
                    {
                        $personalHistoryDate = date('Y-m-d', strtotime($historyDate));
                    }
                    else
                    {
                        $personalHistoryDate = null;
                    }
                    //$generalExaminationDate = \DateTime::createFromFormat('Y-m-d', $examinationDate);


                    $patientUser->personalhistory()->attach($personalHistoryId,
                        array('personal_history_item_id' => $personalHistoryItemId,
                            'personal_history_date' => $personalHistoryDate,
                            'is_value_set' => $isValueSet,
                            'created_by' => 'Admin',
                            'modified_by' => 'Admin',
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s"),
                        ));

                    /*$count = DB::table('patient_personal_history as pph')
                        ->where('pph.personal_history_id', '=', $personalHistoryId)
                        ->where('pph.patient_id', '=', $patientId)->count();

                    if($count == 0)
                    {
                        $patientUser->personalhistory()->attach($personalHistoryId,
                            array('personal_history_item_id' => $personalHistoryItemId,
                                'created_by' => 'Admin',
                                'modified_by' => 'Admin',
                                'created_at' => date("Y-m-d H:i:s"),
                                'updated_at' => date("Y-m-d H:i:s"),
                            ));
                    }
                    else
                    {
                        $patientUser->personalhistory()->updateExistingPivot($personalHistoryId,
                            array('personal_history_item_id' => $personalHistoryItemId,
                                'created_by' => 'Admin',
                                'modified_by' => 'Admin',
                                'created_at' => date("Y-m-d H:i:s"),
                                'updated_at' => date("Y-m-d H:i:s"),
                            ));
                    }*/
                }

            }
            else
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }

        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_PERSONAL_HISTORY_SAVE_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_PERSONAL_HISTORY_SAVE_ERROR, $exc);
        }

        return $status;
    }

    /**
     * Save patient general examination details
     * @param $patientExaminationVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientGeneralExamination(PatientGeneralExaminationViewModel $patientExaminationVM)
    {
        $status = true;

        try
        {
            $patientId = $patientExaminationVM->getPatientId();
            $patientUser = User::find($patientId);

            $patientGeneralExamination = $patientExaminationVM->getPatientGeneralExamination();

            if (!is_null($patientUser))
            {

                //DB::table('patient_general_history')->where('patient_id', $patientId)->delete();

                foreach($patientGeneralExamination as $examination)
                {
                    //dd($patientHistory);
                    $generalExaminationId = $examination->generalExaminationId;
                    $generalExaminationValue = $examination->generalExaminationValue;
                    $isValueSet = $examination->isValueSet;
                    //$generalExaminationDate = \DateTime::createFromFormat('Y-m-d', $examination->generalExaminationDate);
                    //$examinationDate = $examination->examinationDate;

                    $examinationDate = property_exists($examination, 'examinationDate') ? $examination->examinationDate : null;

                    if(!is_null($examinationDate))
                    {
                        $generalExaminationDate = date('Y-m-d', strtotime($examinationDate));
                    }
                    else
                    {
                        $generalExaminationDate = null;
                    }

                    //dd($examinationDate);
                    //$generalExaminationDate = \DateTime::createFromFormat('Y-m-d', $examinationDate);
                    //$generalExaminationDate = date('Y-m-d', strtotime($examinationDate));

                    $patientUser->patientgeneralexaminations()->attach($generalExaminationId,
                        array('general_examination_value' => $generalExaminationValue,
                            'general_examination_date' => $generalExaminationDate,
                            'is_value_set' => $isValueSet,
                            'created_by' => 'Admin',
                            'modified_by' => 'Admin',
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s"),
                        ));


                    /*$count = DB::table('patient_general_examination as pge')
                        ->where('pge.general_examination_id', '=', $generalExaminationId)
                        ->where('pge.patient_id', '=', $patientId)->count();

                    if($count == 0)
                    {
                        $patientUser->patientgeneralexaminations()->attach($generalExaminationId,
                            array('general_examination_value' => $generalExaminationValue,
                                'created_by' => 'Admin',
                                'modified_by' => 'Admin',
                                'created_at' => date("Y-m-d H:i:s"),
                                'updated_at' => date("Y-m-d H:i:s"),
                            ));
                    }
                    else
                    {
                        $patientUser->patientgeneralexaminations()->updateExistingPivot($generalExaminationId,
                            array('general_examination_value' => $generalExaminationValue,
                                'created_by' => 'Admin',
                                'modified_by' => 'Admin',
                                'created_at' => date("Y-m-d H:i:s"),
                                'updated_at' => date("Y-m-d H:i:s"),
                            ));
                    }*/
                }

            }
            else
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_GENERAL_EXAMINATION_SAVE_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_GENERAL_EXAMINATION_SAVE_ERROR, $exc);
        }

        return $status;

    }

    /**
     * Save patient past illness details
     * @param $patientPastIllnessVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientPastIllness(PatientPastIllnessViewModel $patientPastIllnessVM)
    {
        $status = true;

        try
        {
            $patientId = $patientPastIllnessVM->getPatientId();
            $patientUser = User::find($patientId);

            $patientPastIllness = $patientPastIllnessVM->getPatientPastIllness();

            //$pivotData = array();

            if (!is_null($patientUser))
            {
                //DB::table('patient_past_illness')->where('patient_id', $patientId)->delete();

                foreach($patientPastIllness as $illness)
                {
                    //dd($patientHistory);
                    $pastIllnessId = $illness->pastIllnessId;
                    $pastIllnessName = $illness->pastIllnessName;
                    $isValueSet = $illness->isValueSet;

                    //$pastIllnessDate = \DateTime::createFromFormat('Y-m-d', $illness->pastIllnessDate);
                    //$relation = $illness->relation;
                    //$illnessDate = $illness->pastIllnessDate;
                    //dd($examinationDate);
                    //$generalExaminationDate = \DateTime::createFromFormat('Y-m-d', $examinationDate);
                    //$pastIllnessDate = date('Y-m-d', strtotime($illnessDate));

                    $illnessDate = property_exists($illness, 'pastIllnessDate') ? $illness->pastIllnessDate : null;

                    if(!is_null($illnessDate))
                    {
                        $pastIllnessDate = date('Y-m-d', strtotime($illnessDate));
                    }
                    else
                    {
                        $pastIllnessDate = null;
                    }

                    $patientUser->patientpastillness()->attach($pastIllnessId,
                        array('past_illness_name' => $pastIllnessName,
                            'past_illness_date' => $pastIllnessDate,
                            'is_value_set' => $isValueSet,
                            //'relation' => $relation,
                            'created_by' => 'Admin',
                            'modified_by' => 'Admin',
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s"),
                        ));

                    /*$count = DB::table('patient_past_illness as ppi')
                        ->where('ppi.past_illness_id', '=', $pastIllnessId)
                        ->where('ppi.patient_id', '=', $patientId)->count();

                    if($count == 0)
                    {
                        $patientUser->patientpastillness()->attach($pastIllnessId,
                            array('past_illness_name' => $pastIllnessName,
                                //'relation' => $relation,
                                'created_by' => 'Admin',
                                'modified_by' => 'Admin',
                                'created_at' => date("Y-m-d H:i:s"),
                                'updated_at' => date("Y-m-d H:i:s"),
                            ));
                    }
                    else
                    {
                        $patientUser->patientpastillness()->updateExistingPivot($pastIllnessId,
                            array('general_examination_value' => $pastIllnessName,
                                //'relation' => $relation,
                                'created_by' => 'Admin',
                                'modified_by' => 'Admin',
                                'created_at' => date("Y-m-d H:i:s"),
                                'updated_at' => date("Y-m-d H:i:s"),
                            ));
                    }*/
                }

            }
            else
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_PAST_ILLNESS_SAVE_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_PAST_ILLNESS_SAVE_ERROR, $exc);
        }

        return $status;
    }

    /**
     * Save patient family illness details
     * @param $patientFamilyIllnessVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientFamilyIllness(PatientFamilyIllnessViewModel $patientFamilyIllnessVM)
    {
        $status = true;

        try
        {
            $patientId = $patientFamilyIllnessVM->getPatientId();
            $patientUser = User::find($patientId);

            $patientFamilyIllness = $patientFamilyIllnessVM->getPatientFamilyIllness();

            if (!is_null($patientUser))
            {
                //DB::table('patient_family_illness')->where('patient_id', $patientId)->delete();

                foreach($patientFamilyIllness as $illness)
                {
                    //dd($patientHistory);
                    $familyIllnessId = $illness->familyIllnessId;
                    $familyIllnessName = $illness->familyIllnessName;
                    $relation = $illness->relation;
                    $isValueSet = $illness->isValueSet;
                    //$familyIllnessDate = \DateTime::createFromFormat('Y-m-d', $illness->familyIllnessDate);
                    //$illnessDate = $illness->familyIllnessDate;
                    //dd($examinationDate);
                    //$generalExaminationDate = \DateTime::createFromFormat('Y-m-d', $examinationDate);
                    //$familyIllnessDate = date('Y-m-d', strtotime($illnessDate));

                    $illnessDate = property_exists($illness, 'familyIllnessDate') ? $illness->familyIllnessDate : null;

                    if(!is_null($illnessDate))
                    {
                        $familyIllnessDate = date('Y-m-d', strtotime($illnessDate));
                    }
                    else
                    {
                        $familyIllnessDate = null;
                    }

                    $patientUser->patientfamilyillness()->attach($familyIllnessId,
                        array('family_illness_name' => $familyIllnessName,
                            'family_illness_date' => $familyIllnessDate,
                            'relation' => $relation,
                            'is_value_set' => $isValueSet,
                            'created_by' => 'Admin',
                            'modified_by' => 'Admin',
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s"),
                        ));


                    /*$count = DB::table('patient_family_illness as pfi')
                        ->where('pfi.family_illness_id', '=', $familyIllnessId)
                        ->where('pfi.patient_id', '=', $patientId)->count();

                    if($count == 0)
                    {
                        $patientUser->patientfamilyillness()->attach($familyIllnessId,
                            array('family_illness_name' => $familyIllnessName,
                                'relation' => $relation,
                                'created_by' => 'Admin',
                                'modified_by' => 'Admin',
                                'created_at' => date("Y-m-d H:i:s"),
                                'updated_at' => date("Y-m-d H:i:s"),
                            ));
                    }
                    else
                    {
                        $patientUser->patientfamilyillness()->updateExistingPivot($familyIllnessId,
                            array('family_illness_name' => $familyIllnessName,
                                'relation' => $relation,
                                'created_by' => 'Admin',
                                'modified_by' => 'Admin',
                                'created_at' => date("Y-m-d H:i:s"),
                                'updated_at' => date("Y-m-d H:i:s"),
                            ));
                    }*/
                }

            }
            else
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_FAMILY_ILLNESS_SAVE_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_FAMILY_ILLNESS_SAVE_ERROR, $exc);
        }

        return $status;
    }

    /**
     * Save patient pregnancy details
     * @param $patientPregnancyVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientPregnancyDetails(PatientPregnancyViewModel $patientPregnancyVM)
    {
        $status = true;

        try
        {
            $patientId = $patientPregnancyVM->getPatientId();
            $patientUser = User::find($patientId);

            $patientPregnancy = $patientPregnancyVM->getPatientPregnancy();

            if (!is_null($patientUser))
            {
                //DB::table('patient_family_illness')->where('patient_id', $patientId)->delete();

                foreach($patientPregnancy as $pregnancy)
                {
                    //dd($patientHistory);
                    $pregnancyId = $pregnancy->pregnancyId;
                    $pregnancyValue = $pregnancy->pregnancyValue;
                    $isValueSet = $pregnancy->isValueSet;
                    //$pregnancyDate = $pregnancy->pregnancyDate;

                    $pregnancyDate = property_exists($pregnancy, 'pregnancyDate') ? $pregnancy->pregnancyDate : null;

                    if(!is_null($pregnancyDate))
                    {
                        $patientPregnancyDate = date('Y-m-d', strtotime($pregnancyDate));
                    }
                    else
                    {
                        $patientPregnancyDate = null;
                    }

                    $patientUser->patientpregnancy()->attach($pregnancyId,
                        array('pregnancy_value' => $pregnancyValue,
                            'pregnancy_date' => $patientPregnancyDate,
                            'is_value_set' => $isValueSet,
                            'created_by' => 'Admin',
                            'modified_by' => 'Admin',
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s"),
                        ));

                }

            }
            else
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_PREGNANCY_DETAILS_SAVE_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_PREGNANCY_DETAILS_SAVE_ERROR, $exc);
        }

        return $status;
    }

    /**
     * Save patient scan details
     * @param $patientScanVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientScanDetails(PatientScanViewModel $patientScanVM)
    {
        $status = true;

        try
        {
            $patientId = $patientScanVM->getPatientId();
            $patientUser = User::find($patientId);

            $patientScans = $patientScanVM->getPatientScans();

            if (!is_null($patientUser))
            {
                //DB::table('patient_family_illness')->where('patient_id', $patientId)->delete();

                foreach($patientScans as $scans)
                {
                    //dd($patientHistory);
                    $scanId = $scans->scanId;
                    $isValueSet = $scans->isValueSet;
                    //$pregnancyDate = $pregnancy->pregnancyDate;

                    $scanDate = property_exists($scans, 'scanDate') ? $scans->scanDate : null;

                    if(!is_null($scanDate))
                    {
                        $patientScanDate = date('Y-m-d', strtotime($scanDate));
                    }
                    else
                    {
                        $patientScanDate = null;
                    }

                    $patientUser->patientscans()->attach($scanId,
                        array('scan_date' => $patientScanDate,
                            'is_value_set' => $isValueSet,
                            'created_by' => 'Admin',
                            'modified_by' => 'Admin',
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s"),
                        ));

                }

            }
            else
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_SCAN_SAVE_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_SCAN_SAVE_ERROR, $exc);
        }

        return $status;
    }

    /**
     * Save patient symptom details
     * @param $patientSymVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientSymptoms(PatientSymptomsViewModel $patientSymVM)
    {
        $status = true;

        try
        {
            $patientId = $patientSymVM->getPatientId();
            $patientUser = User::find($patientId);

            $patientSymptoms = $patientSymVM->getPatientSymptoms();

            if (!is_null($patientUser))
            {
                //DB::table('patient_family_illness')->where('patient_id', $patientId)->delete();

                foreach($patientSymptoms as $symptom)
                {
                    //dd($patientHistory);
                    $mainSymptomId = $symptom->mainSymptomId;
                    $subSymptomId = $symptom->subSymptomId;
                    $symptomId = $symptom->symptomId;
                    $isValueSet = $symptom->isValueSet;
                    //$pregnancyDate = $pregnancy->pregnancyDate;

                    $symptomDate = property_exists($symptom, 'symptomDate') ? $symptom->symptomDate : null;

                    if(!is_null($symptomDate))
                    {
                        $patientSymptomDate = date('Y-m-d', strtotime($symptomDate));
                    }
                    else
                    {
                        $patientSymptomDate = null;
                    }

                    $patientSymptom = new PatientSymptoms();
                    $patientSymptom->patient_id = $patientId;
                    $patientSymptom->main_symptom_id = $mainSymptomId;
                    $patientSymptom->sub_symptom_id = $subSymptomId;
                    $patientSymptom->symptom_id = $symptomId;
                    $patientSymptom->patient_symptom_date = $patientSymptomDate;
                    $patientSymptom->is_value_set = $isValueSet;
                    $patientSymptom->created_by = $patientSymVM->getCreatedBy();
                    $patientSymptom->modified_by = $patientSymVM->getUpdatedBy();
                    $patientSymptom->created_at = $patientSymVM->getCreatedAt();
                    $patientSymptom->updated_at = $patientSymVM->getUpdatedAt();

                    $patientSymptom->save();

                    /*$patientUser->patientscans()->attach($scanId,
                        array('scan_date' => $patientScanDate,
                            'is_value_set' => $isValueSet,
                            'created_by' => 'Admin',
                            'modified_by' => 'Admin',
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s"),
                        ));*/

                }

            }
            else
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_SYMPTOM_SAVE_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_SYMPTOM_SAVE_ERROR, $exc);
        }

        return $status;
    }

    /**
     * Save patient urine examination details
     * @param $patientUrineVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientUrineTests(PatientUrineExaminationViewModel $patientUrineVM)
    {
        $status = true;

        try
        {
            $patientId = $patientUrineVM->getPatientId();
            $patientUser = User::find($patientId);

            $patientExaminations = $patientUrineVM->getExaminations();

            if (!is_null($patientUser))
            {
                //DB::table('patient_family_illness')->where('patient_id', $patientId)->delete();

                foreach($patientExaminations as $examination)
                {
                    //dd($patientHistory);
                    $examinationId = $examination->examinationId;
                    $isValueSet = $examination->isValueSet;
                    //$pregnancyDate = $pregnancy->pregnancyDate;

                    $examinationDate = property_exists($examination, 'examinationDate') ? $examination->examinationDate : null;

                    if(!is_null($examinationDate))
                    {
                        $patientExaminationDate = date('Y-m-d', strtotime($examinationDate));
                    }
                    else
                    {
                        $patientExaminationDate = null;
                    }

                    $patientUser->patienturineexaminations()->attach($examinationId,
                        array('examination_date' => $patientExaminationDate,
                            'is_value_set' => $isValueSet,
                            'created_by' => 'Admin',
                            'modified_by' => 'Admin',
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s"),
                        ));

                }

            }
            else
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_URINE_DETAILS_SAVE_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_URINE_DETAILS_SAVE_ERROR, $exc);
        }

        return $status;
    }

    /**
     * Save patient motion examination details
     * @param $patientMotionVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientMotionTests(PatientUrineExaminationViewModel $patientMotionVM)
    {
        $status = true;

        try
        {
            $patientId = $patientMotionVM->getPatientId();
            $patientUser = User::find($patientId);

            $patientExaminations = $patientMotionVM->getExaminations();

            if (!is_null($patientUser))
            {
                //DB::table('patient_family_illness')->where('patient_id', $patientId)->delete();

                foreach($patientExaminations as $examination)
                {
                    //dd($patientHistory);
                    $examinationId = $examination->examinationId;
                    $isValueSet = $examination->isValueSet;
                    //$pregnancyDate = $pregnancy->pregnancyDate;

                    $examinationDate = property_exists($examination, 'examinationDate') ? $examination->examinationDate : null;

                    if(!is_null($examinationDate))
                    {
                        $patientExaminationDate = date('Y-m-d', strtotime($examinationDate));
                    }
                    else
                    {
                        $patientExaminationDate = null;
                    }

                    $patientUser->patientmotionexaminations()->attach($examinationId,
                        array('examination_date' => $patientExaminationDate,
                            'is_value_set' => $isValueSet,
                            'created_by' => 'Admin',
                            'modified_by' => 'Admin',
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s"),
                        ));

                }

            }
            else
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_MOTION_DETAILS_SAVE_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_MOTION_DETAILS_SAVE_ERROR, $exc);
        }

        return $status;
    }

    /**
     * Save patient blood examination details
     * @param $patientBloodVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientBloodTests(PatientUrineExaminationViewModel $patientBloodVM)
    {
        $status = true;

        try
        {
            $patientId = $patientBloodVM->getPatientId();
            $patientUser = User::find($patientId);

            $patientExaminations = $patientBloodVM->getExaminations();

            if (!is_null($patientUser))
            {
                //DB::table('patient_family_illness')->where('patient_id', $patientId)->delete();

                foreach($patientExaminations as $examination)
                {
                    //dd($patientHistory);
                    $examinationId = $examination->examinationId;
                    $isValueSet = $examination->isValueSet;
                    //$pregnancyDate = $pregnancy->pregnancyDate;

                    $examinationDate = property_exists($examination, 'examinationDate') ? $examination->examinationDate : null;

                    if(!is_null($examinationDate))
                    {
                        $patientExaminationDate = date('Y-m-d', strtotime($examinationDate));
                    }
                    else
                    {
                        $patientExaminationDate = null;
                    }

                    $patientUser->patientbloodexaminations()->attach($examinationId,
                        array('examination_date' => $patientExaminationDate,
                            'is_value_set' => $isValueSet,
                            'created_by' => 'Admin',
                            'modified_by' => 'Admin',
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s"),
                        ));

                }

            }
            else
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_BLOOD_DETAILS_SAVE_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_BLOOD_DETAILS_SAVE_ERROR, $exc);
        }

        return $status;
    }

    /**
     * Save patient ultra sound details
     * @param $patientUltraSoundVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientUltraSoundTests(PatientUrineExaminationViewModel $patientUltraSoundVM)
    {
        $status = true;

        try
        {
            $patientId = $patientUltraSoundVM->getPatientId();
            $patientUser = User::find($patientId);

            $patientExaminations = $patientUltraSoundVM->getExaminations();

            if (!is_null($patientUser))
            {
                //DB::table('patient_family_illness')->where('patient_id', $patientId)->delete();

                foreach($patientExaminations as $examination)
                {
                    //dd($patientHistory);
                    $examinationId = $examination->examinationId;
                    $isValueSet = $examination->isValueSet;
                    //$pregnancyDate = $pregnancy->pregnancyDate;

                    $examinationDate = property_exists($examination, 'examinationDate') ? $examination->examinationDate : null;

                    if(!is_null($examinationDate))
                    {
                        $patientExaminationDate = date('Y-m-d', strtotime($examinationDate));
                    }
                    else
                    {
                        $patientExaminationDate = null;
                    }

                    $patientUser->patientultrasounds()->attach($examinationId,
                        array('examination_date' => $patientExaminationDate,
                            'is_value_set' => $isValueSet,
                            'created_by' => 'Admin',
                            'modified_by' => 'Admin',
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s"),
                        ));

                }

            }
            else
            {
                throw new UserNotFoundException(null, ErrorEnum::PATIENT_USER_NOT_FOUND, null);
            }
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_ULTRASOUND_DETAILS_SAVE_ERROR, $queryEx);
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw new HospitalException(null, $userExc->getUserErrorCode(), $userExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_ULTRASOUND_DETAILS_SAVE_ERROR, $exc);
        }

        return $status;
    }

    /**
     * Save patient drug and surgery history
     * @param $drugHistoryVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientDrugHistory(PatientDrugHistoryViewModel $drugHistoryVM)
    {
        $status = true;
        $patientDrugHistory = null;

        try
        {
            $patientId = $drugHistoryVM->getPatientId();
            $patientUser = User::find($patientId);

            $patientDrugHistory = $drugHistoryVM->getDrugHistory();
            $patientSurgeryHistory = $drugHistoryVM->getSurgeryHistory();

            if (!is_null($patientUser))
            {

                foreach($patientDrugHistory as $history)
                {
                    $patientDrugHistory = new PatientDrugHistory();
                    //$patientDrugHistory->patient_id = $patientId;
                    $patientDrugHistory->drug_name = $history->drugName;
                    $patientDrugHistory->dosage = $history->dosage;
                    $patientDrugHistory->timings = $history->timings;
                    $patientDrugHistory->drug_history_date = $history->drugHistoryDate;
                    $patientDrugHistory->created_by = $drugHistoryVM->getCreatedBy();
                    $patientDrugHistory->modified_by = $drugHistoryVM->getUpdatedBy();
                    $patientDrugHistory->created_at = $drugHistoryVM->getCreatedAt();
                    $patientDrugHistory->updated_at = $drugHistoryVM->getUpdatedAt();

                    $patientUser->patientdrughistory()->save($patientDrugHistory);
                }

                foreach($patientSurgeryHistory as $history)
                {
                    $patientSurgeryHistory = new PatientSurgeries();
                    //$patientDrugHistory->patient_id = $patientId;
                    $patientSurgeryHistory->patient_surgeries = $history->surgeryName;
                    $patientSurgeryHistory->operation_date = $history->operationDate;
                    $patientSurgeryHistory->surgery_input_date = $history->surgeryInputDate;
                    $patientSurgeryHistory->created_by = $drugHistoryVM->getCreatedBy();
                    $patientSurgeryHistory->modified_by = $drugHistoryVM->getUpdatedBy();
                    $patientSurgeryHistory->created_at = $drugHistoryVM->getCreatedAt();
                    $patientSurgeryHistory->updated_at = $drugHistoryVM->getUpdatedAt();

                    $patientUser->patientsurgeries()->save($patientSurgeryHistory);
                }

                //$patientDrugHistory->drug_name = $drugHistoryVM->getd;
                //$patientLabTests->unique_id = "LTID".time();

                /*$patientLabTests->unique_id = 'LTID'.crc32(uniqid(rand()));
                $patientLabTests->brief_description = $patientLabTestVM->getDescription();
                $patientLabTests->labtest_date = $patientLabTestVM->getLabTestDate();
                $patientLabTests->created_by = 'Admin';
                $patientLabTests->modified_by = 'Admin';
                $patientUser->labtests()->save($patientLabTests);*/
            }

        }
        catch(QueryException $queryEx)
        {
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_DRUG_HISTORY_SAVE_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_DRUG_HISTORY_SAVE_ERROR, $exc);
        }

        return $status;
    }

    /*Symptom section -- End */
}