<?php
namespace App\prescription\utilities\ErrorEnum;

use MyCLabs\Enum\Enum;

class ErrorEnum extends Enum{

    const SUCCESS = 1;
    const FAILURE = 0;
    const USER_NOT_EXIST = 2;
    const UNKNOWN_ERROR = 100;

    //const USER_NOT_FOUND_ERROR = 101;
    
    //Doctor Error code 101 - 150

    const HOSPITAL_LIST_ERROR = 101;
    const HOSPITAL_LIST_SUCCESS = 102;
    const DOCTOR_LOGIN_FAILURE = 103;
    const HOSPITAL_ID_ERROR = 104;

    //Appointments
    const APPOINTMENT_LIST_ERROR = 105;
    const APPOINTMENT_LIST_SUCCESS = 106;
    const HOSPITAL_DOCTOR_LIST_ERROR = 107;
    const HOSPITAL_DOCTOR_LIST_SUCCESS = 108;
    const HOSPITAL_NO_DOCTORS_FOUND = 109;

    //Patient List
    const PATIENT_LIST_ERROR = 110;
    const PATIENT_LIST_SUCCESS = 111;
    const PATIENT_DETAILS_ERROR = 112;
    const PATIENT_DETAILS_SUCCESS = 113;
    const PATIENT_PROFILE_ERROR = 114;
    const PATIENT_PROFILE_SUCCESS = 115;
    const PATIENT_PROFILE_SAVE_ERROR = 116;
    const PATIENT_PROFILE_SAVE_SUCCESS = 117;
    const PATIENT_NEW_APPOINTMENT_ERROR = 118;
    const PATIENT_NEW_APPOINTMENT_SUCCESS = 119;
    const NEW_PATIENT_ERROR = 120;
    //Prescription List

    const PRESCRIPTION_LIST_ERROR = 121;
    const PRESCRIPTION_LIST_SUCCESS = 122;
    const PRESCRIPTION_DETAILS_ERROR = 123;
    const PRESCRIPTION_DETAILS_SUCCESS = 124;
    const PRESCRIPTION_DETAILS_SAVE_ERROR = 125;
    const PRESCRIPTION_DETAILS_SAVE_SUCCESS = 126;

    //Drug Brands
    const BRAND_LIST_ERROR = 131;
    const BRAND_LIST_SUCCESS = 132;

    //Lab Tests List
    const LAB_LIST_ERROR = 141;
    const LAB_LIST_SUCCESS = 142;
    const LAB_DETAILS_ERROR = 143;
    const LAB_DETAILS_SUCCESS = 144;
    const NO_LABTEST_FOUND = 145;
    const LABTESTS_DETAILS_SAVE_ERROR = 146;
    const LABTESTS_DETAILS_SAVE_SUCCESS = 147;

    //User Login 160 - 170

    const USER_LOGIN_SUCCESS = 160;

    //Pharmacy Profile constants 171 to 190

    const PHARMACY_PROFILE_VIEW_ERROR = 171;
    const PHARMACY_PATIENT_LIST_ERROR = 172;
    const PRESCRIPTION_LIST_PRID_ERROR = 173;
    const PHARMACY_SAVE_ERROR = 174;
    const PHARMACY_SAVE_SUCCESS = 175;

    //Lab Profile constants 191 to 210

    const LAB_PROFILE_VIEW_ERROR = 191;
    const LAB_PATIENT_LIST_ERROR = 192;
    const LAB_TESTS_LIST_ERROR = 193;
    const LAB_LIST_LID_ERROR = 194;
    const LAB_SAVE_ERROR = 195;
    const LAB_SAVE_SUCCESS = 196;

    //Helper constants 211 to 220
    const CITIES_LIST_ERROR = 211;

    const DOCTOR_DETAILS_ERROR = 221;
    const DOCTOR_DETAILS_SUCCESS = 222;


    //Hospital Profile constants 300 to 399

    const HOSPITAL_PROFILE_VIEW_ERROR = 171;
    const HOSPITAL_PATIENT_LIST_ERROR = 172;
    const HOSPITAL_SAVE_ERROR = 174;
    const HOSPITAL_SAVE_SUCCESS = 175;

}
