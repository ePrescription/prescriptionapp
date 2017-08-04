<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('portal.login');
});

Route::get('/login', function () {
    return view('portal.login');
});

Route::post('/dologin', array('as' => 'user.dologin', 'uses' => 'Doctor\DoctorController@userlogin'));

Route::any('doctor/login', array('as' => 'doctor.doctorloginform', 'uses' => 'Doctor\DoctorController@doctorloginform'));
Route::post('doctor/dologin', array('as' => 'doctor.dologin', 'uses' => 'Doctor\DoctorController@doctorlogin'));

Route::get('/dashboard', function () {
    return view('portal.index');
});


Route::get('/logout', function()
{
    Auth::logout();
    Session::flush();
    return Redirect::to('/');
});

/*Route::group(array('prefix' => 'hospital', 'namespace' => 'Common'), function()
{
   Route::get('rest/api/get/hospitals', array('as' => 'common.hospitals', 'uses' => 'CommonController@getHospitalByKeyword'));
   Route::get('rest/api/{patientId}/profile', array('as' => 'patient.profile', 'uses' => 'CommonController@getPatientProfile'));
});*/

Route::group(['prefix' => 'api'], function(){
    Route::post('token', 'AuthenticateController@authenticateUser');
    /*Route::group(['middleware' => 'jwt-auth'], function () {
        Route::post('getuserdetails', 'AuthenticateController@getUserDetails');
    });*/
});

Route::group(['prefix' => 'hospital'], function()
{
   Route::group(['namespace' => 'Common'], function()
   {
       Route::get('rest/api/get/hospitals', array('as' => 'common.hospitals', 'uses' => 'CommonController@getHospitalByKeyword'));
       //Route::get('rest/api/{patientId}/profile', array('as' => 'patient.profile', 'uses' => 'CommonController@getPatientProfile'));
   });

});

Route::group(array('prefix' => 'patient'), function()
{
    Route::get('{id}/dashboard', function () {
        return view('portal.patient-dashboard');
    });

    Route::group(['namespace' => 'Common'], function()
    {
        Route::get('rest/api/{patientId}/profile', array('as' => 'patient.profile', 'uses' => 'CommonController@getPatientProfile'));
        Route::get('rest/api/details', array('as' => 'patient.patient', 'uses' => 'CommonController@searchPatientByPid'));
    });

    Route::group(['namespace' => 'Lab'], function()
    {
        Route::get('rest/api/{patientId}/labtests', array('as' => 'patient.labtests', 'uses' => 'LabController@getTestsForLabForPatient'));
        Route::get('rest/api/labtests', array('as' => 'patient.lid', 'uses' => 'LabController@getLabTestsByLidForPatient'));
        Route::get('rest/api/lab/{labId}', array('as' => 'patient.labdetails', 'uses' => 'LabController@getLabTestDetailsForPatient'));
    });

    Route::group(['namespace' => 'Pharmacy'], function()
    {
        Route::get('rest/api/{patientId}/prescriptions', array('as' => 'patient.prescriptions', 'uses' => 'PharmacyController@getPrescriptionListForPatient'));
        Route::get('rest/api/prescription', array('as' => 'patient.searchbyprid', 'uses' => 'PharmacyController@getPrescriptionByPridForPatient'));
        Route::get('rest/api/prescription/{prescriptionId}', array('as' => 'patient.prescriptiondetails', 'uses' => 'PharmacyController@getPrescriptionDetailsForPatient'));
    });

    // Move all the web calls here

    Route::group(['namespace' => 'Doctor'], function()
    {
        Route::get('rest/{patientId}/details', array('as' => 'patient.details', 'uses' => 'DoctorController@getPatientAllDetails'));
        Route::get('rest/{patientId}/appointments', array('as' => 'patient.appointments', 'uses' => 'DoctorController@getPatientAppointments'));
        Route::get('rest/api/{patientId}/feereceipts', array('as' => 'patient.feereceipts', 'uses' => 'DoctorController@getFeeReceiptsByPatient'));
    });

});


Route::group(array('prefix' => 'fronthospital', 'namespace' => 'Lab'), function()
{
    //Route::get('rest/api/{patientId}/labtests', array('as' => 'patient.labtests', 'uses' => 'LabController@getTestsForLabForPatient'));
    //Route::get('rest/api/labtests', array('as' => 'patient.lid', 'uses' => 'LabController@getLabTestsByLidForPatient'));
    Route::get('rest/api/lab/{labId}', array('as' => 'patient.labdetails', 'uses' => 'LabController@getLabTestDetailsForHospital'));


});

Route::group(array('prefix' => 'fronthospital', 'namespace' => 'Pharmacy'), function()
{
    //Route::get('rest/api/{patientId}/prescriptions', array('as' => 'patient.prescriptions', 'uses' => 'PharmacyController@getPrescriptionListForPatient'));
    //Route::get('rest/api/prescription', array('as' => 'patient.searchbyprid', 'uses' => 'PharmacyController@getPrescriptionByPridForPatient'));
    Route::get('rest/api/prescription/{prescriptionId}', array('as' => 'patient.prescriptiondetails', 'uses' => 'PharmacyController@getPrescriptionDetailsForHospital'));
});


Route::group(array('prefix' => 'fronthospital', 'namespace' => 'Doctor'), function()
{
    Route::get('{id}/dashboard', function () {
        return view('portal.hospital-dashboard');
    });

    Route::get('rest/api/{hospitalId}/patients', array('as' => 'hospital.patients', 'uses' => 'DoctorController@getPatientsByHospitalForFront'));


    Route::get('rest/api/{hospitalId}/addpatient', array('as' => 'hospital.addpatient', 'uses' => 'DoctorController@addPatientsByHospitalForFront'));
    Route::post('rest/api/{hospitalId}/savepatient', array('as' => 'hospital.savepatient', 'uses' => 'DoctorController@savePatientsByHospitalForFront'));


    Route::get('rest/api/{hospitalId}/patient/{patientId}/addappointment', array('as' => 'hospital.addappointment', 'uses' => 'DoctorController@addAppointmentByHospitalForFront'));
    Route::post('rest/api/{hospitalId}/patient/{patientId}/saveappointment', array('as' => 'hospital.saveappointment', 'uses' => 'DoctorController@saveAppointmentByHospitalForFront'));
    Route::get('rest/api/{hospitalId}/patient/{patientId}/completeappointment', array('as' => 'hospital.completeappointment', 'uses' => 'DoctorController@completeAppointmentByHospitalForFront'));

    Route::get('rest/api/{hospitalId}/patient/{patientId}/details', array('as' => 'hospital.patientdetails', 'uses' => 'DoctorController@PatientDetailsByHospitalForFront'));

    Route::get('rest/api/{hospitalId}/patient/{patientId}/edit', array('as' => 'hospital.patientdetailsedit', 'uses' => 'DoctorController@PatientEditByHospitalForFront'));
    Route::post('rest/api/{hospitalId}/patient/{patientId}/update', array('as' => 'hospital.savepatient', 'uses' => 'DoctorController@updatePatientsByHospitalForFront'));

    Route::get('rest/api/{hospitalId}/profile', array('as' => 'hospital.viewprofile', 'uses' => 'DoctorController@getProfile'));

    Route::get('rest/api/{hospitalId}/editprofile', array('as' => 'hospital.editprofile', 'uses' => 'DoctorController@editProfile'));
    //Route::post('rest/api/pharmacy', array('as' => 'pharmacy.editpharmacy', 'uses' => 'PharmacyController@editPharmacy'));
    Route::post('rest/api/hospital', array('as' => 'hospital.edithospital', 'uses' => 'DoctorController@editHospital'));


    Route::get('rest/api/{hospitalId}/doctorlist', array('as' => 'hospital.doctors', 'uses' => 'DoctorController@getDoctorsForFront'));


    //Fee Receipts
    Route::get('rest/api/{hospitalId}/doctor/{doctorId}/feereceipts', array('as' => 'hospital.feereceipts', 'uses' => 'DoctorController@getFeeReceiptsForFront'));
    Route::get('rest/api/receipt/{receiptId}/details', array('as' => 'hospital.feereceiptdetails', 'uses' => 'DoctorController@getReceiptDetailsForFront'));
    Route::get('rest/api/{hospitalId}/addfeereceipt', array('as' => 'hospital.addfeereceipt', 'uses' => 'DoctorController@addFeeReceiptForFront'));
    Route::post('rest/api/savefeereceipt', array('as' => 'hospital.feereceipt', 'uses' => 'DoctorController@saveFeeReceiptForFront'));

    Route::get('receipt/{receiptId}/sms/{mobile}', array('as' => 'feereceipt.sendsms', 'uses' => 'DoctorController@forwardFeeReceiptBySMS'));

    Route::get('rest/api/receipt/{receiptId}/mail/{email}', array('as' => 'apifeereceipt.sendmail', 'uses' => 'DoctorController@forwardFeeReceiptApiByMail'));
    Route::get('receipt/{receiptId}/mail/{email}', array('as' => 'feereceipt.sendmail', 'uses' => 'DoctorController@forwardFeeReceiptByMail'));
    //Route::get('rest/api/patient/receipts/{receipts}/sms/{mobile}', array('as' => 'feereceipt.sendsms', 'uses' => 'DoctorController@forwardFeeReceiptsBySMS'));
    //Route

    Route::get('payment/online', array('as' => 'payment.online', 'uses' => 'DoctorController@onlinePayment'));
    Route::post('payment/process', array('as' => 'payment.process', 'uses' => 'DoctorController@processPayment'));
    Route::any('payment/response/success', array('as' => 'payment.success', 'uses' => 'DoctorController@successPayment'));
    Route::any('payment/response/failure', array('as' => 'payment.failure', 'uses' => 'DoctorController@failurePayment'));
});

Route::group(array('prefix' => 'hospital', 'namespace' => 'Doctor'), function()
{
    Route::get('{id}/dashboard', function () {
        return view('portal.hospital-dashboard');
    });
    Route::post('send', 'DoctorController@sendEmail');

    /*Route::group(['middleware' => 'prescription.auth'], function () {

        Route::get('rest/api/patient', array('as' => 'patient.patient', 'uses' => 'DoctorController@searchPatientByPid'));
        Route::get('rest/api/{hospitalId}/doctors', array('as' => 'hospital.doctors', 'uses' => 'DoctorController@getDoctorsByHospitalId'));

    });*/

    //Route::get('rest/api/{doctorId}/doctordetails', array('as' => 'doctor.details', 'uses' => 'DoctorController@getDoctorDetails'));

    //MOBILE
   Route::get('rest/api/{hospitalId}/doctornames', array('as' => 'hospital.doctors', 'uses' => 'DoctorController@getDoctorNames'));
   Route::get('rest/api/{hospitalId}/patientnames', array('as' => 'patient.searchnames', 'uses' => 'DoctorController@getPatientNames'));

   Route::get('rest/api/hospitals', array('as' => 'doctor.hospitals', 'uses' => 'DoctorController@getHospitalByKeyword'));
   Route::post('rest/api/login', array('as' => 'doctor.login', 'uses' => 'DoctorController@login'));
   //Route::get('rest/api/login', array('as' => 'doctor.login', 'uses' => 'DoctorController@login'));
   Route::get('rest/api/{hospitalId}/{doctorId}/appointments', array('as' => 'doctor.appointments', 'uses' => 'DoctorController@getAppointmentsByHospitalAndDoctor'));
   Route::get('rest/api/{hospitalId}/patients', array('as' => 'hospital.patients', 'uses' => 'DoctorController@getPatientsByHospital'));
   Route::get('rest/api/{patientId}/details', array('as' => 'patient.details', 'uses' => 'DoctorController@getPatientDetailsById'));
   Route::get('rest/api/{patientId}/profile', array('as' => 'patient.profile', 'uses' => 'DoctorController@getPatientProfile'));
   Route::get('rest/api/{hospitalId}/doctor/{doctorId}/prescriptions', array('as' => 'hospital.prescriptions', 'uses' => 'DoctorController@getPrescriptions'));
   Route::get('rest/api/{patientId}/prescriptions', array('as' => 'patient.prescriptions', 'uses' => 'DoctorController@getPrescriptionByPatient'));
   Route::get('rest/api/prescriptions', array('as' => 'patient.allprescriptions', 'uses' => 'DoctorController@getAllPrescriptions'));
   Route::get('rest/api/prescription/{prescriptionId}', array('as' => 'patient.prescriptiondetails', 'uses' => 'DoctorController@getPrescriptionDetails'));
   Route::get('rest/api/patients', array('as' => 'patient.names', 'uses' => 'DoctorController@searchPatientByName'));
   Route::get('rest/api/patient', array('as' => 'patient.patient', 'uses' => 'DoctorController@searchPatientByPid'));
   Route::get('rest/api/labtests', array('as' => 'lab.labtests', 'uses' => 'DoctorController@getLabTests'));
   Route::get('rest/api/patient/{patientId}/labtests', array('as' => 'patient.labtests', 'uses' => 'DoctorController@getLabTestsByPatient'));
   Route::get('rest/api/{hospitalId}/doctor/{doctorId}/labtests', array('as' => 'hospital.labtests', 'uses' => 'DoctorController@getLabTestsForPatient'));
   Route::get('rest/api/labtests/{labTestId}', array('as' => 'patient.labtestdetails', 'uses' => 'DoctorController@getLabTestDetails'));
   Route::get('rest/api/brands', array('as' => 'drug.brands', 'uses' => 'DoctorController@getTradeNames'));
   Route::get('rest/api/formulations', array('as' => 'formulation.names', 'uses' => 'DoctorController@getFormulationNames'));
   //Route::post('rest/api/brands', array('as' => 'drug.brands', 'uses' => 'DoctorController@getBrandNames'));
   Route::post('rest/api/patient/prescription', array('as' => 'patient.saveprescription', 'uses' => 'DoctorController@savePatientPrescription'));
   Route::post('rest/api/patient/labtests', array('as' => 'patient.savelabtests', 'uses' => 'DoctorController@savePatientLabTests'));

   Route::post('rest/api/register', array('as' => 'patient.register', 'uses' => 'DoctorController@savePatientProfile'));
   Route::put('rest/api/profile', array('as' => 'patient.profile', 'uses' => 'DoctorController@editPatientProfile'));

   Route::get('rest/api/pidorname', array('as' => 'patient.searchpatient', 'uses' => 'DoctorController@searchByPatientByPidOrName'));
   Route::post('rest/api/appointment', array('as' => 'patient.appointment', 'uses' => 'DoctorController@saveNewAppointment'));

   Route::get('rest/api/{hospitalId}/doctors', array('as' => 'hospital.doctors', 'uses' => 'DoctorController@getDoctorsByHospitalId'));

   Route::get('rest/api/patientstatus', array('as' => 'hospital.isnewpatient', 'uses' => 'DoctorController@checkIsNewPatient'));

   Route::get('rest/api/doctor/{doctorId}/doctordetails', array('as' => 'hospital.doctordetails', 'uses' => 'DoctorController@getDoctorDetails'));

   //Fee Receipts
   Route::get('rest/api/{hospitalId}/doctor/{doctorId}/feereceipts', array('as' => 'hospital.feereceipts', 'uses' => 'DoctorController@getFeeReceipts'));
   Route::get('rest/api/receipt/{receiptId}/details', array('as' => 'hospital.feereceiptdetails', 'uses' => 'DoctorController@getReceiptDetails'));
   Route::post('rest/api/feereceipt', array('as' => 'hospital.feereceipt', 'uses' => 'DoctorController@saveFeeReceipt'));

   Route::get('rest/api/receipt/{receiptId}/sms/{mobile}', array('as' => 'apifeereceipt.sendsms', 'uses' => 'DoctorController@forwardFeeReceiptApiBySMS'));
   Route::get('rest/api/doctor/hospitals', array('as' => 'doctor.hospitals', 'uses' => 'DoctorController@getHospitalsForDoctor'));
   Route::get('rest/api/{doctorId}/hospitals', array('as' => 'doctor.associatedhospitals', 'uses' => 'DoctorController@getHospitalsByDoctorId'));



});

Route::group(array('prefix' => 'hospital','namespace' => 'Pharmacy'), function() {

    Route::get('rest/api/patient/prescription/{prescriptionId}/mail/{email}', array('as' => 'patient.sendemail', 'uses' => 'PharmacyController@forwardPrescriptionDetailsByMail'));
    //Route::post('rest/api/patient/prescription/{prescriptionId}/mail', array('as' => 'patient.sendemail', 'uses' => 'PharmacyController@forwardPrescriptionDetailsByMail'));
    Route::get('rest/api/patient/prescription/{prescriptionId}/sms/{mobile}', array('as' => 'patient.sendsms', 'uses' => 'PharmacyController@forwardPrescriptionDetailsBySMS'));

});

Route::group(array('prefix' => 'hospital','namespace' => 'Lab'), function() {

    Route::get('rest/api/patient/labtest/{labTestId}/mail/{email}', array('as' => 'patient.sendemail', 'uses' => 'LabController@forwardLabDetailsByMail'));
    Route::get('rest/api/patient/labtest/{labTestId}/sms/{mobile}', array('as' => 'patient.sendsms', 'uses' => 'LabController@forwardLabDetailsBySMS'));

});

Route::group(['prefix' => 'pharmacy'], function()
{
    Route::get('{id}/dashboard', function () {
        return view('portal.pharmacy-dashboard');
    });


    Route::group(['namespace' => 'Pharmacy'], function()
    {
        Route::get('rest/api/{pharmacyId}/profile', array('as' => 'pharmacy.viewprofile', 'uses' => 'PharmacyController@getProfile'));
        Route::get('rest/api/{pharmacyId}/hospital/{hospitalId}/patients', array('as' => 'pharmacy.patients', 'uses' => 'PharmacyController@getPatientListForPharmacy'));
        Route::get('rest/api/{pharmacyId}/hospital/{hospitalId}/prescriptions', array('as' => 'pharmacy.prescriptions', 'uses' => 'PharmacyController@getPrescriptionListForPharmacy'));
        Route::get('rest/api/prescription/{prescriptionId}', array('as' => 'pharmacy.prescriptiondetails', 'uses' => 'PharmacyController@getPrescriptionDetails'));
        Route::get('rest/api/prescription', array('as' => 'pharmacy.searchbyprid', 'uses' => 'PharmacyController@getPrescriptionByPrid'));

        Route::get('rest/api/{pharmacyId}/editprofile', array('as' => 'pharmacy.editprofile', 'uses' => 'PharmacyController@editProfile'));
        //Route::post('rest/api/pharmacy', array('as' => 'pharmacy.editpharmacy', 'uses' => 'PharmacyController@editPharmacy'));
        Route::post('rest/api/pharmacy', array('as' => 'pharmacy.editpharmacy', 'uses' => 'PharmacyController@editPharmacy'));

        Route::get('rest/api/prescription/{prescriptionId}/mail', array('as' => 'patient.sendemail', 'uses' => 'PharmacyController@forwardPrescriptionDetailsByMail'));
        Route::get('rest/api/prescription/{prescriptionId}/sms', array('as' => 'patient.sendsms', 'uses' => 'PharmacyController@forwardPrescriptionDetailsBySMS'));

        //Route::get('rest/api/{labId}/changepassword', array('as' => 'lab.changepassword', 'uses' => 'PharmacyController@editChangePassword'));
        //Route::post('rest/api/pharmacy', array('as' => 'pharmacy.editpharmacy', 'uses' => 'PharmacyController@editPharmacy'));
        //Route::get('rest/api/lab', array('as' => 'lab.editlab', 'uses' => 'PharmacyController@saveChangesPassword'));

    });

    /*Route::group(['namespace' => 'Common'], function()
    {
        //Route::get('rest/api/{pharmacyId}/profile', array('as' => 'pharmacy.viewprofile', 'uses' => 'PharmacyController@getProfile'));
        Route::get('rest/api/prescription/{prescriptionId}', array('as' => 'pharmacy.patients', 'uses' => 'CommonController@getPatientListForPharmacy'));
    });*/

});


Route::group(['prefix' => 'doctor'], function()
{


    Route::post('/changehospital', array('as' => 'doctor.changehospital', 'uses' => 'Doctor\DoctorController@changeHospital'));

    Route::get('{id}/dashboard', function () {
        return view('portal.doctor-dashboard');
    });

    Route::group(['namespace' => 'Doctor'], function()
    {
        Route::get('rest/api/{doctorId}/hospital/{hospitalId}/patients', array('as' => 'doctor.patients', 'uses' => 'DoctorController@getPatientsByDoctorForFront'));
        Route::get('rest/api/mainsymptoms', array('as' => 'doctor.symptoms', 'uses' => 'DoctorApiController@getMainSymptoms'));

        Route::get('rest/api/{mainsymptomId}/subsymptoms', array('as' => 'doctor.subsymptoms', 'uses' => 'DoctorApiController@getSubSymptomsForMainSymptoms'));
        Route::get('rest/api/{subsymptomId}/symptoms', array('as' => 'doctor.symptoms', 'uses' => 'DoctorApiController@getSymptomsForSubSymptoms'));
        Route::get('rest/api/{patientId}/patienthistory', array('as' => 'doctor.patienthistory', 'uses' => 'DoctorApiController@getPersonalHistory'));
        Route::get('rest/api/{patientId}/pastillness', array('as' => 'doctor.patientpastillness', 'uses' => 'DoctorApiController@getPatientPastIllness'));
        Route::get('rest/api/{patientId}/familyillness', array('as' => 'doctor.familyillness', 'uses' => 'DoctorApiController@getPatientFamilyIllness'));
        Route::get('rest/api/{patientId}/generalexamination', array('as' => 'doctor.generalexamination', 'uses' => 'DoctorApiController@getPatientGeneralExamination'));
        Route::post('rest/api/personalhistory', array('as' => 'doctor.savepersonalhistory', 'uses' => 'DoctorApiController@savePersonalHistory'));
        Route::post('rest/api/generalexamination', array('as' => 'doctor.savegeneralexamination', 'uses' => 'DoctorApiController@savePatientGeneralExamination'));
        Route::post('rest/api/pastillness', array('as' => 'doctor.savepastillness', 'uses' => 'DoctorApiController@savePatientPastIllness'));
        Route::post('rest/api/familyillness', array('as' => 'doctor.savefamilyillness', 'uses' => 'DoctorApiController@savePatientFamilyIllness'));
        Route::get('rest/api/{patientId}/examinationdates', array('as' => 'doctor.examinationdates', 'uses' => 'DoctorApiController@getExaminationDates'));

        Route::get('rest/api/{patientId}/pregnancydetails', array('as' => 'doctor.pregnancydetails', 'uses' => 'DoctorApiController@getPregnancyDetails'));
        Route::post('rest/api/pregnancydetails', array('as' => 'doctor.savepregnancydetails', 'uses' => 'DoctorApiController@savePatientPregnancyDetails'));
        Route::get('rest/api/{patientId}/scandetails', array('as' => 'doctor.scandetails', 'uses' => 'DoctorApiController@getPatientScanDetails'));
        Route::post('rest/api/scandetails', array('as' => 'doctor.savescandetails', 'uses' => 'DoctorApiController@savePatientScanDetails'));

        Route::get('rest/api/{patientId}/symptomdetails', array('as' => 'doctor.symptomdetails', 'uses' => 'DoctorApiController@getPatientSymptoms'));
        Route::post('rest/api/symptomdetails', array('as' => 'doctor.savesymptomdetails', 'uses' => 'DoctorApiController@savePatientSymptoms'));

        Route::get('rest/api/{patientId}/drughistory', array('as' => 'doctor.drughistory', 'uses' => 'DoctorApiController@getPatientDrugHistory'));
        Route::post('rest/api/drughistory', array('as' => 'doctor.savedrughistory', 'uses' => 'DoctorApiController@savePatientDrugHistory'));

        Route::get('rest/api/{patientId}/urinetests', array('as' => 'doctor.urinetests', 'uses' => 'DoctorApiController@getPatientUrineTests'));
        Route::post('rest/api/urinetests', array('as' => 'doctor.saveurinetests', 'uses' => 'DoctorApiController@savePatientUrineTests'));
        Route::get('rest/api/{patientId}/motiontests', array('as' => 'doctor.motiontests', 'uses' => 'DoctorApiController@getPatientMotionTests'));
        Route::post('rest/api/motiontests', array('as' => 'doctor.savemotiontests', 'uses' => 'DoctorApiController@savePatientMotionTests'));

        Route::get('rest/api/familyillness', array('as' => 'doctor.allfamilyillness', 'uses' => 'DoctorApiController@getAllFamilyIllness'));
        Route::get('rest/api/pastillness', array('as' => 'doctor.allpastillness', 'uses' => 'DoctorApiController@getAllPastIllness'));
        Route::get('rest/api/generalexaminations', array('as' => 'doctor.allgeneralexaminations', 'uses' => 'DoctorApiController@getAllGeneralExaminations'));
        Route::get('rest/api/personalhistory', array('as' => 'doctor.allpersonalhistory', 'uses' => 'DoctorApiController@getAllPersonalHistory'));
        Route::get('rest/api/pregnancy', array('as' => 'doctor.allpregnancydetails', 'uses' => 'DoctorApiController@getAllPregnancy'));
        Route::get('rest/api/scans', array('as' => 'doctor.allscandetails', 'uses' => 'DoctorApiController@getAllScans'));
    });


    Route::group(['namespace' => 'Pharmacy'], function()
    {
        Route::get('rest/api/{doctorId}/hospital/{hospitalId}/prescriptions', array('as' => 'doctor.prescriptions', 'uses' => 'PharmacyController@getPrescriptionListForDoctor'));
        Route::get('rest/api/prescription/{prescriptionId}', array('as' => 'doctor.prescriptiondetails', 'uses' => 'PharmacyController@getPrescriptionDetailsForDoctor'));
        Route::get('rest/api/prescription', array('as' => 'doctor.searchbyprid', 'uses' => 'PharmacyController@getPrescriptionByPrid'));


        //Route::get('rest/api/patient/{prescriptionId}/mail', array('as' => 'patient.sendemail', 'uses' => 'PharmacyController@forwardPrescriptionDetailsByMail'));
        //Route::get('rest/api/patient/{prescriptionId}/sms', array('as' => 'patient.send sms', 'uses' => 'PharmacyController@forwardPrescriptionDetailsBySMS'));

        //Route::get('rest/api/{labId}/changepassword', array('as' => 'lab.changepassword', 'uses' => 'PharmacyController@editChangePassword'));
        //Route::post('rest/api/pharmacy', array('as' => 'pharmacy.editpharmacy', 'uses' => 'PharmacyController@editPharmacy'));
        //Route::get('rest/api/lab', array('as' => 'lab.editlab', 'uses' => 'PharmacyController@saveChangesPassword'));

    });


    Route::group(['namespace' => 'Lab'], function()
    {
        Route::get('rest/api/{doctorId}/hospital/{hospitalId}/labtests', array('as' => 'doctor.labtests', 'uses' => 'LabController@getTestsForDoctor'));
        Route::get('rest/api/lab/{labTestId}', array('as' => 'doctor.labdetails', 'uses' => 'LabController@getLabTestDetailsForDoctor'));
        Route::get('rest/api/labtests', array('as' => 'doctor.lid', 'uses' => 'LabController@getLabTestsByLid'));

        //Route::get('rest/api/patient/{prescriptionId}/mail', array('as' => 'patient.sendemail', 'uses' => 'LabController@forwardLabDetailsByMail'));
        //Route::get('rest/api/patient/{prescriptionId}/sms', array('as' => 'patient.send sms', 'uses' => 'LabController@forwardLabDetailsBySMS'));

        //Route::get('rest/api/{labId}/changepassword', array('as' => 'lab.changepassword', 'uses' => 'LabController@editChangePassword'));
        //Route::post('rest/api/lab', array('as' => 'lab.editlab', 'uses' => 'LabController@editLab'));
        //Route::get('rest/api/lab', array('as' => 'lab.editlab', 'uses' => 'LabController@saveChangesPassword'));
    });
});


Route::group(['prefix' => 'lab'], function()
{
    Route::get('{id}/dashboard', function () {
        return view('portal.lab-dashboard');
    });

    Route::group(['namespace' => 'Lab'], function()
    {
        Route::get('rest/api/{labId}/profile', array('as' => 'lab.viewprofile', 'uses' => 'LabController@getProfile'));
        Route::get('rest/api/{labId}/hospital/{hospitalId}/patients', array('as' => 'lab.patients', 'uses' => 'LabController@getPatientListForLab'));
        Route::get('rest/api/{labId}/hospital/{hospitalId}/labtests', array('as' => 'lab.labtests', 'uses' => 'LabController@getTestsForLab'));
        Route::get('rest/api/labtests', array('as' => 'lab.lid', 'uses' => 'LabController@getLabTestsByLid'));

        Route::get('rest/api/{labId}/editprofile', array('as' => 'lab.editprofile', 'uses' => 'LabController@editProfile'));
        //Route::post('rest/api/lab', array('as' => 'lab.editlab', 'uses' => 'LabController@editLab'));
        Route::post('rest/api/lab', array('as' => 'lab.editlab', 'uses' => 'LabController@editLab'));
        Route::get('rest/api/lab/{labId}', array('as' => 'lab.labdetails', 'uses' => 'LabController@getLabTestDetails'));

        Route::get('rest/api/patient/{prescriptionId}/mail', array('as' => 'patient.sendemail', 'uses' => 'LabController@forwardLabDetailsByMail'));
        Route::get('rest/api/patient/{prescriptionId}/sms', array('as' => 'patient.send sms', 'uses' => 'LabController@forwardLabDetailsBySMS'));




        Route::get('rest/api/lab/report/{labTestId}/upload', array('as' => 'patient.labtestupload', 'uses' => 'LabController@getLabTestUploadForLab'));
        Route::post('rest/api/lab/report/{labTestId}/saveupload', array('as' => 'patient.labtestsaveupload', 'uses' => 'LabController@getLabTestUploadSaveForLab'));

        //Route::get('rest/api/{labId}/changepassword', array('as' => 'lab.changepassword', 'uses' => 'LabController@editChangePassword'));
        //Route::post('rest/api/lab', array('as' => 'lab.editlab', 'uses' => 'LabController@editLab'));
        //Route::get('rest/api/lab', array('as' => 'lab.editlab', 'uses' => 'LabController@saveChangesPassword'));
    });
});