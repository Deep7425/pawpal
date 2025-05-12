<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type");


header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
header ("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header ("Access-Control-Allow-Headers: *");

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::any('/upload-prescription-image', 'API\MedicineController@uploadPrescriptionImage');
Route::any('/notifyDoctorForVcall', 'API\HomeController@notifyDoctorForVcall');
Route::any('/checkVersionOfApp', 'API\HomeController@checkVersionOfApp');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([],function () {
	Route::get('/getListItemGanga','API\HomeController@getListItemGanga');
    Route::post('/putItem','API\HomeController@putItem');
    Route::post('/updateItem','API\HomeController@updateItem');
    Route::post('/deleteItem','API\HomeController@deleteItem');
    Route::post('/login','API\UserController@login');
	Route::post('/resendOtp','API\UserController@resendOtp');
	Route::post('/otpVerified', 'API\UserController@otpVerified');
	Route::post('/otpVerifiedNew', 'API\UserController@otpVerifiedNew');
	Route::post('/forgot-password', 'API\UserController@forgot');
	Route::get('/book-appointment', 'API\HomeController@bookAppointment');
	Route::match(['get', 'post'],'/thyrocarelogin', 'API\LabController@thyrocarelogin')->name('thyrocarelogin');
	Route::match(['get', 'post'],'/getPatientPortalSliders', 'API\HomeController@getPatientPortalSliders');
	Route::match(['get', 'post'],'/appointmentCheckout', 'API\HomeController@appointmentCheckout')->name('appointmentCheckout');
	Route::match(['get', 'post'],'/getPatientOpd', 'API\PatientEhrController@getPatientOpd');
	Route::match(['get', 'post'],'/subscriptionPay', 'API\SubscriptionController@subscriptionPay');
	Route::match(['get', 'post'],'/labCheckout', 'API\LabController@labCheckout')->name('labCheckout');
	Route::match(['get', 'post'],'/getThyrocarePackageGroup', 'API\LabController@getThyrocarePackageGroup')->name('getThyrocarePackageGroup');
	Route::match(['get', 'post'],'/createLaborderAddresses', 'API\LabController@createLaborderAddresses')->name('createLaborderAddresses');
	Route::match(['get', 'post'],'/getMyLabOrders', 'API\LabController@getMyLabOrders')->name('getMyLabOrders');
	Route::match(['get', 'post'],'/getRefreshToken', 'API\UserController@getRefreshToken');
    Route::group([
      // 'middleware' => 'auth:api'
    ], function() {
		Route::post('details', 'API\UserController@details');
		/********* Patient Portal APi ***********/
		Route::match(['get', 'post'],'/download-subscription-receipt', 'API\SubscriptionController@downloadSubscriptionReceipt');
		Route::any('/saveuserlocation', 'API\UserController@saveUserLocation');
		Route::any('/checkFirstDirectTeleAppointment', 'API\HomeController@checkFirstDirectTeleAppointment');
		Route::any('/checkFirstTeleAppointment', 'API\HomeController@checkFirstTeleAppointment');
		Route::post('/logoutUser','API\UserController@logoutUser');
		Route::post('/updateFcmToken','API\UserController@updateFcmToken');
		Route::post('/addUser','API\UserController@addUser');
		Route::post('/change-password', 'API\UserController@ChangePassword');
		Route::match(['get', 'post'],'/getOnCallDoctors', 'API\HomeController@getOnCallDoctors');
		Route::match(['get', 'post'],'/getOnCallDoctorsDemo', 'API\HomeController@getOnCallDoctorsDemo');
		Route::match(['get', 'post'],'/getCountryPhoneCode', 'API\HomeController@getCountryPhoneCode');
		Route::match(['get', 'post'],'/getNewsFeedsData', 'API\HomeController@getNewsFeedsData');
		Route::match(['get', 'post'],'/searchDoctors', 'API\HomeController@searchDoctors');
		Route::match(['get', 'post'],'/getDocSpeciality', 'API\HomeController@getDocSpeciality');
		Route::match(['get', 'post'],'/getDocByPractice', 'API\HomeController@getDocByPractice');
		Route::match(['get', 'post'],'/getCountry', 'API\HomeController@getCountry');
		Route::match(['get', 'post'],'/getState', 'API\HomeController@getState');
		Route::match(['get', 'post'],'/getCity', 'API\HomeController@getCity');
		Route::match(['get', 'post'],'/getMyProfile', 'API\HomeController@getMyProfile');
		Route::match(['get', 'post'],'/updateMyProfile', 'API\HomeController@updateMyProfile');
		Route::match(['get', 'post'],'/getUserImage', 'API\HomeController@getUserImage');
		Route::match(['get', 'post'],'/searchDoctorsByAddress', 'API\HomeController@searchDoctorsByAddress');
		Route::match(['get', 'post'],'/addAppointment', 'API\HomeController@addAppointment');
		Route::match(['get', 'post'],'/addAppointmentDemo', 'API\HomeController@addAppointmentDemo');
		Route::match(['get', 'post'],'/searchDoctorsByFilters', 'API\HomeController@searchDoctorsByFilters');
		Route::match(['get', 'post'],'/getDoctorCounsultMaxFees', 'API\HomeController@getDoctorCounsultMaxFees');
		Route::match(['get', 'post'],'/getDoctorSlotsByDay', 'API\HomeController@getDoctorSlotsByDay');
		Route::match(['get', 'post'],'/getDocById', 'API\HomeController@getDocById');
		Route::match(['get', 'post'],'/getDocDetailById', 'API\HomeController@getDocDetailById');
		Route::match(['get', 'post'],'/cancelAppointment', 'API\HomeController@cancelAppointment');
		Route::match(['get', 'post'],'/updateSchedule', 'API\HomeController@updateSchedule');
		Route::match(['get', 'post'],'/checkAppointmentStatus', 'API\HomeController@checkAppointmentStatus');
		Route::match(['get', 'post'],'/staticPages', 'API\HomeController@staticPages')->name('staticPages');
		Route::match(['get', 'post'],'/move-on-1mg', 'API\HomeController@moveTo1MgSite')->name('moveTo1MgSite');
		Route::match(['get', 'post'],'/getSponseredDoc', 'API\HomeController@getSponseredDoc');
		Route::match(['get', 'post'],'/getHospitalInfoById', 'API\HomeController@getHospitalInfoById')->name('getHospitalInfoById');
		Route::match(['get', 'post'],'/makeFollowUpAppt', 'API\HomeController@makeFollowUpAppt')->name('makeFollowUpAppt');

		Route::match(['get', 'post'],'/getDoctorSlots', 'API\HomeController@getDoctorSlots');
		Route::match(['get', 'post'],'/getDocBySpeciality', 'API\HomeController@getDocBySpeciality');
		Route::match(['get', 'post'],'/getPatients', 'API\CommonController@getPatients');
		Route::match(['get', 'post'],'/getComplimentsData', 'API\CommonController@getComplimentsData');
		Route::match(['get', 'post'],'/getWaitingTimeData', 'API\CommonController@getWaitingTimeData');

		Route::match(['get', 'post'],'/getFoodPreferenceMaster', 'API\CommonController@getFoodPreferenceMaster');
		Route::match(['get', 'post'],'/getSmokingHabitsMaster', 'API\CommonController@getSmokingHabitsMaster');
		Route::match(['get', 'post'],'/getOccupationMaster', 'API\CommonController@getOccupationMaster');
		Route::match(['get', 'post'],'/getAlcoholConsumptionMaster', 'API\CommonController@getAlcoholConsumptionMaster');
		Route::match(['get', 'post'],'/getActivityLevelMaster', 'API\CommonController@getActivityLevelMaster');
		Route::match(['get', 'post'],'/getTopSpecialities', 'API\CommonController@getTopSpecialities');
		Route::match(['get', 'post'],'/getReferLinkMsg', 'API\CommonController@getReferLinkMsg');
		Route::match(['get', 'post'],'/getCouponCodeLists', 'API\CommonController@getCouponCodeLists');

		Route::match(['get', 'post'],'/getPatientPrescriptionData', 'API\PatientEhrController@getPatientPrescriptionData');
		Route::match(['get', 'post'],'/getPatientPrescription', 'API\PatientEhrController@getPatientPrescription');
		Route::match(['get', 'post'],'/getPatientOpdNew', 'API\PatientEhrController@getPatientOpdNew');
		Route::match(['get', 'post'],'/getClinicalNoteByApp', 'API\PatientEhrController@getClinicalNoteByApp');
		Route::match(['get', 'post'],'/downloadReceipt', 'API\PatientEhrController@downloadReceipt');
		Route::match(['get', 'post'],'/uploadDocument', 'API\PatientEhrController@uploadDocument');
		Route::match(['get', 'post'],'/getUserDocument', 'API\PatientEhrController@getUserDocument');
		Route::match(['get', 'post'],'/deletePrescription', 'API\PatientEhrController@deletePrescription');
		Route::match(['get', 'post'],'/deleteDocument', 'API\PatientEhrController@deleteDocument');
		Route::match(['get', 'post'],'/feedback', 'API\PatientEhrController@feedback');
		Route::match(['get', 'post'],'/latestappointmentfeedback', 'API\PatientEhrController@latestappointmentfeedback');
		Route::match(['get', 'post'],'/checkAppointmentCouponCode', 'API\PatientEhrController@checkAppointmentCouponCode');
		Route::post('/getCashcack', 'API\CommonController@getCashcack');
		Route::post('/get-rewards', 'API\CommonController@getRewards');
		Route::post('/send-invite-sms', 'API\CommonController@sendInviteSms');
		Route::post('/get-ref-page-data', 'API\CommonController@getRefPageData');
		Route::post('/put-ref-code', 'API\CommonController@registerReferred');
		
		/** Static pages **/
		Route::match(['get', 'post'],'/getStaticPage', 'API\CommonController@getStaticPage');

		/** Health Tracker **/
		Route::match(['get', 'post'],'/updateSteps', 'API\CommonController@updateSteps');
		Route::match(['get', 'post'],'/getTotalSteps', 'API\CommonController@getTotalSteps');
		Route::match(['get', 'post'],'/updateMedicineDetails', 'API\CommonController@updateMedicineDetails');
		Route::match(['get', 'post'],'/getMedicineReminderList', 'API\CommonController@getMedicineReminderList');
		Route::match(['get', 'post'],'/deleteMedicineReminder', 'API\CommonController@deleteMedicineReminder');
		Route::match(['get', 'post'],'/getMedicineListPdf', 'API\CommonController@getMedicineListPdf');

		/*Bp record*/
		Route::match(['get', 'post'],'/updateBpRecordDetails', 'API\CommonController@updateBpRecordDetails');
		Route::match(['get', 'post'],'/bpRecordList', 'API\CommonController@bpRecordList');
		Route::match(['get', 'post'],'/deleteBpRecord', 'API\CommonController@deleteBpRecord');
		Route::match(['get', 'post'],'/getBpListPdf', 'API\CommonController@getBpListPdf');
		/*diabetesRecordList*/
		Route::match(['get', 'post'],'/updateDiabetesRecordDetails', 'API\CommonController@updateDiabetesRecordDetails');
		Route::match(['get', 'post'],'/diabetesRecordList', 'API\CommonController@diabetesRecordList');
		Route::match(['get', 'post'],'/deleteDiabetesRecord', 'API\CommonController@deleteDiabetesRecord');
		Route::match(['get', 'post'],'/getDiaListPdf', 'API\CommonController@getDiabetesListPdf');
		/*weightRecordList*/
		Route::match(['get', 'post'],'/updateWeightDetails', 'API\CommonController@updateWeightDetails');
		Route::match(['get', 'post'],'/weightList', 'API\CommonController@weightList');
		Route::match(['get', 'post'],'/deleteWeightRecord', 'API\CommonController@deleteWeightRecord');
		Route::match(['get', 'post'],'/getweightListPdf', 'API\CommonController@getweightListPdf');
		/*temptRecordList*/
		Route::match(['get', 'post'],'/updateTempDetails', 'API\CommonController@updateTempDetails');
		Route::match(['get', 'post'],'/tempList', 'API\CommonController@tempList');
		Route::match(['get', 'post'],'/deleteTempRecord', 'API\CommonController@deleteTempRecord');
		Route::match(['get', 'post'],'/gettempListPdf', 'API\CommonController@gettempListPdf');
		/*search reslut api's*/
		Route::match(['get', 'post'],'/saveSearchResults', 'API\CommonController@saveSearchResults');
		Route::match(['get', 'post'],'/usersBuymedicineHits', 'API\CommonController@usersBuymedicineHits');
		Route::match(['post'],'/usersAdsHits', 'API\CommonController@usersAdsHits');
		/** getOfferBanners **/
		Route::match(['get', 'post'],'/getOfferBanners', 'API\CommonController@getOfferBanners');
		Route::match(['get', 'post'],'/getOfferBannersNew', 'API\CommonController@getOfferBannersNew');
		Route::match(['post'],'/getAds', 'API\CommonController@getAds');
		/* Locality */
		Route::match(['get', 'post'],'/getLocalitiesByCity', 'API\CommonController@getLocalitiesByCity');
		Route::match(['get', 'post'],'/getLocalitiesbySearch', 'API\CommonController@getLocalitiesbySearch');
		Route::match(['get', 'post'],'/getcityIdByLocality', 'API\CommonController@getcityIdByLocality');
		Route::match(['get', 'post'],'/support', 'API\CommonController@support')->name('support');

		/* Organizations */
		Route::match(['get', 'post'],'/appointmentCheckoutDetails', 'API\CommonController@appointmentCheckoutDetails');

		/* Organizations */
		Route::match(['get', 'post'],'/getOrganizations', 'API\CommonController@getOrganizations')->name('getOrganizations');
		Route::match(['get', 'post'],'/getBlogCount', 'API\CommonController@getBlogCount');

		/* Lab Apis */
		Route::match(['get', 'post'],'/getthyrocareData', 'API\LabController@getthyrocareData')->name('getthyrocareData');
		/* Thyrocare Package Group*/
		
		Route::match(['get', 'post'],'/getLaborderAddresses', 'API\LabController@getLaborderAddresses')->name('getLaborderAddresses');
		Route::match(['get', 'post'],'/deleteLaborderAddress', 'API\LabController@deleteLaborderAddress')->name('deleteLaborderAddress');

		/*  Lab Apis */
		Route::match(['get', 'post'],'/checkCouponCode', 'API\LabController@checkCouponCode')->name('checkCouponCode');
		Route::match(['get', 'post'],'/getUniqueOrderId', 'API\LabController@getUniqueOrderId')->name('getUniqueOrderId');
		Route::match(['get', 'post'],'/createLabOrder', 'API\LabController@createLabOrder')->name('createLabOrder');
		Route::match(['get', 'post'],'/cancelLabOrder', 'API\LabController@cancelLabOrder')->name('cancelLabOrder');
		Route::match(['get', 'post'],'/createLabOrderOnline', 'API\LabController@createLabOrderOnline')->name('createLabOrderOnline');
		Route::match(['get', 'post'],'/getMyLabOrderData', 'API\LabController@getMyLabOrderData')->name('getMyLabOrderData');
		Route::match(['get', 'post'],'/getMyLabReports', 'API\LabController@getMyLabReports')->name('getMyLabReports');
		Route::match(['get', 'post'],'/getLabCartData', 'API\LabController@getLabCartData');
		Route::match(['get', 'post'],'/addLabCartData', 'API\LabController@addLabCartData');
		Route::match(['get', 'post'],'/deleteLabCartData', 'API\LabController@deleteLabCartData');
		Route::match(['get', 'post'],'/GetAppointmentSlots', 'API\LabController@GetAppointmentSlots');
		Route::match(['get', 'post'],'/PincodeAvailability', 'API\LabController@PincodeAvailability');
		Route::match(['get', 'post'],'/getLabTestSlots', 'API\LabController@getLabTestSlots');
        Route::match(['post'],'/ViewCart', 'API\LabController@ViewCart');
        Route::match(['post'],'/getLabByName', 'API\LabController@getLabByName');
        Route::match(['post'],'/getDefLabByName', 'API\LabController@getDefLabByName');
        Route::match(['post'],'/getLabsByIds', 'API\LabController@getLabsByIds');
		Route::match(['get', 'post'],'/getLabPackage', 'API\LabController@getLabPackage');
		Route::match(['get', 'post'],'/getLabCompanies', 'API\LabController@getLabCompanies');


		Route::match(['get', 'post'],'/createCustomLabOrder', 'API\LabController@createCustomLabOrder')->name('createCustomLabOrder');
		Route::match(['get', 'post'],'/createCustomLabOrderOnline', 'API\LabController@createCustomLabOrderOnline')->name('createCustomLabOrderOnline');
		Route::match(['post'],'/labRequestViaPrescription', 'API\LabController@labRequestViaPrescription');
		Route::match(['post'],'/checkLabPinCode', 'API\LabController@checkLabPinCode');
		Route::match(['post'],'/getLabPackageById', 'API\LabController@getLabPackageById');
		
		/*  Subscription Apis */
		Route::match(['get', 'post'],'/offer-plans', 'API\SubscriptionController@getOffersPlans');
		Route::match(['get', 'post'],'/getSubscriptionPlans', 'API\SubscriptionController@getSubscriptionPlans');
		Route::match(['get', 'post'],'/getMySubscription', 'API\SubscriptionController@getMySubscription');
		Route::match(['get', 'post'],'/checkMySubscription', 'API\SubscriptionController@checkMySubscription');
		Route::match(['get', 'post'],'/checkRefCode', 'API\SubscriptionController@checkRefCode');  
        Route::match(['post'],'/checkFcmToken', 'API\CommonController@checkFcmToken'); 		
        Route::match(['post'],'/updateFcmToken', 'API\CommonController@updateFcmToken'); 		
        Route::match(['post'],'/updateUserNotifyStatus', 'API\CommonController@updateUserNotifyStatus'); 		
        Route::match(['post'],'/get-ques-by-type', 'API\CommonController@getQuesByType');
		Route::match(['get', 'post'],'/getReferCodeLists', 'API\CommonController@getReferCodeLists');
		Route::match(['get', 'post'],'/wallet-details', 'API\CommonController@getWalletDetails');
		Route::match(['get', 'post'],'/apply-wallet-amt', 'API\CommonController@applyWalletAmt');
		/******Medicine APIS******/
		Route::match(['post'],'/delete-prescription-image', 'API\MedicineController@deletePrescriptionImage');
		Route::match(['post'],'/crt-med-order', 'API\MedicineController@createMedicineOrder');
		Route::match(['post'],'/get-pres-image', 'API\MedicineController@getMedPrescription');
		Route::match(['post'],'/get-med-order', 'API\MedicineController@getMedOrder');
		Route::match(['post'],'/view-invoice', 'API\MedicineController@viewInvoice');
		Route::match(['get', 'post'],'/updateMedCart', 'API\MedicineController@updateMedCart');
		Route::match(['get', 'post'],'/getMedCart', 'API\MedicineController@getMedCart');
		Route::match(['get', 'post'],'/deleteMedCart', 'API\MedicineController@deleteMedCart');
		Route::match(['get','post'],'/updateMedQty', 'API\MedicineController@updateMedQty');
		Route::match(['get','post'],'/checkMedCouponCode', 'API\MedicineController@checkMedCouponCode');
		Route::match(['get', 'post'],'/searchMedicine', 'API\MedicineController@searchMedicine');
		Route::match(['get', 'post'],'/vieworderPres', 'API\MedicineController@vieworderPres');
		Route::match(['get', 'post'],'/cancel-med-order', 'API\MedicineController@cancelOrder');


		Route::match(['get', 'post'],'/send-video-notification', 'API\HomeController@sendVideoNotification');
		
		Route::match(['get', 'post'],'/ticket-Data', function(){
			dd("ss");
		});

		


    });
	
});