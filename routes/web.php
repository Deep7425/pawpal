<?php





/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
Route::get('/clear-cache', function () {
	Artisan::call('cache:clear');
	Artisan::call('view:clear');
	Artisan::call('config:clear');
	return '<h1>cache cleared</h1>';
});



Route::get('welcome', function () {
	return redirect('/');
})->name('welcome');
Route::get('503', function () {
	return view('errors.errorException');
})->name('error503');
Auth::routes();
// Login Routes
Route::match(['get', 'post'], '/user-verify-login', "Auth\LoginController@verifyLogin")->name("verifyLogin");
Route::match(['get', 'post'], '/generate-passwords', "Auth\LoginController@generatePasswords")->name("generatePasswords");
Route::match(['get', 'post'], '/check-user', "Auth\LoginController@checkUser")->name("checkUser");
Route::match(['get', 'post'], '/new-user', "Auth\LoginController@createNewUser")->name("createNewUser");
Route::match(['get', 'post'], '/logout/Organization', "Auth\LoginController@logoutOrganization")->name("logoutOrganization");
Route::post('/update-password', "Auth\LoginController@updatePassword")->name("updatePassword");
Route::match(['get', 'post'], '/user-send-otp', "Auth\LoginController@sendUserOtp")->name("sendUserOtp");
Route::match(['get', 'post'], '/user-confirm-otp', "Auth\LoginController@confirmOtp")->name("confirmOtp");
Route::post('/login-Institue', "Auth\LoginController@verifyOrganizationLogin")->name("loginInstitue");

/**
 * Mental Health Routes Start
 */
Route::post('/show-symp-pointer', 'Mental\MentalHealthController@showSympHighlight');
Route::match(['get', 'post'], '/view-assesment-ques/{slug}', 'Mental\MentalHealthController@viewAssesmentQues')->name('viewAssesmentQues');
Route::match(['get', 'post'], '/fetch-assesment-ques', 'Mental\MentalHealthController@fetchAssesmentQues')->name('fetchAssesmentQues');
Route::get('/pre/assesment/ques', 'Mental\MentalHealthController@fetchPreAssesmentQues')->name('fetchPreAssesmentQues');
Route::get('/pre/assesment/allAssessmentScore', 'Mental\MentalHealthController@allAssessmentScore')->name('allAssessmentScore');
Route::get('/pre/assesment/weeklyprogramHistory', 'Mental\MentalHealthController@weeklyprogramHistory')->name('weeklyprogramHistory');
Route::post('/submit-answers', 'Mental\MentalHealthController@saveAssesment');
Route::match(['get', 'post'], '/save-assesment', 'Mental\MentalHealthController@saveAssesmentWeb')->name('saveAssesmentWeb');
Route::post('/save-pre-assesment', 'Mental\MentalHealthController@savePreAssesment')->name('savePreAssesment');
Route::get('/assessment-result/{score}', 'Mental\MentalHealthController@assessmentResult')->name('mental.assessmentResult');
Route::get('/assessment-ques-result/{id}', 'Mental\MentalHealthController@assessmentQuesResult')->name('mental.assessmentQuesResult');
Route::post('/save-assesment-exp', 'Mental\MentalHealthController@saveAssesmentExp');
Route::post('/fetch-pre-assessment-record', 'Mental\MentalHealthController@fetchPreAssessment');
Route::post('/fetch-main-assessment-record', 'Mental\MentalHealthController@fetchMainAssessment');
Route::post('/fetch-assessment-record', 'Mental\MentalHealthController@fetchAssessmentRecord');
Route::post('/show-weeks', 'Mental\MentalHealthController@showWeeks');
Route::get('/fetch-assessment-matrix', 'Mental\MentalHealthController@fetchAssessmentMetrix')->name('fetchAssessmentMetrix');
Route::post('/fetch-sessions', 'Mental\MentalHealthController@fetchSession');
Route::post('/fetch-appointment-details', 'Mental\MentalHealthController@fetchApptDetails');
Route::post('/update-session', 'Mental\MentalHealthController@updateReadSession')->name('updateReadSession');
Route::post('/fetch-assessment-overview', 'Mental\MentalHealthController@fetchOverviewContent');
Route::get('/fetch-weekly-program', 'Mental\MentalHealthController@fetchWeeklyPrograms')->name('fetchWeeklyPrograms');
Route::get('/fetch-weekly-program-result', 'Mental\MentalHealthController@fetchWeeksResult')->name('fetchWeeksResult');
Route::post('/weeks-show', 'Mental\MentalHealthController@checkweekShow');
Route::post('/push-weekly-program-task', 'Mental\MentalHealthController@pushWeeklyProgram');
Route::post('/insert-mood', 'Mental\MentalHealthController@insertMood')->name('insertMood');
Route::get('/mood-history', 'Mental\MentalHealthController@moodHistory')->name('moodHistory');
Route::post('/insert-weekly-feedback', 'Mental\MentalHealthController@insertWeeklyFeedback')->name('insertFeedback');
Route::post('/insert-journal', 'Mental\MentalHealthController@insertJournal');
Route::post('/delete-journal', 'Mental\MentalHealthController@deleteJournal');
Route::post('/journal-history', 'Mental\MentalHealthController@journalHistory');
Route::post('/common-audios', 'Mental\MentalHealthController@commonAudio');
Route::post('/mental-dashboard', 'Mental\MentalHealthController@fetchMentalDashboard');
Route::post('/update-mh-tracker', 'Mental\MentalHealthController@updateMhTracker');
Route::post('/mh-trackers', 'Mental\MentalHealthController@mhTracker');
Route::post('/mh-common-sheets', 'Mental\MentalHealthController@commonSheet');
Route::post('/insert-sheet', 'Mental\MentalHealthController@insertSheetData');
Route::post('/update-audio-listeners', 'Mental\MentalHealthController@updateAudioListen');
Route::post('/random-prompt', 'Mental\MentalHealthController@randomPrompt');
Route::post('/getMentalHealthPlan', 'Mental\MentalHealthController@getMentalHealthPlan');
Route::post('/fetch-common-sheet', 'Mental\MentalHealthController@fetchCommonSheetsByUserId');
Route::post('/fetch-mh-slider', 'Mental\MentalHealthController@fetchMHSlider');
Route::post('/fetch-journal-url', 'Mental\MentalHealthController@fetchJournalUrl');
Route::post('/fetch-articles', 'Mental\MentalHealthController@fetchArticle');
Route::match(['get', 'post'], '/fetch-consult-data', 'Mental\MentalHealthController@fetchConsData')->name('mentalHealthPlan');
Route::match(['get', 'post'], '/institution', 'Mental\MentalHealthController@institute')->name('instituteStatic');
Route::match(['get', 'post'], '/view-mood-history', 'Mental\MentalHealthController@viewMoodHistory')->name('viewMoodHistory');
Route::match(['get', 'post'], '/symptom-list', 'Mental\MentalHealthController@symptomList')->name('symptomList');
Route::match(['get', 'post'], '/symptom-details/{symptom?}', 'Mental\MentalHealthController@symptomDetails')->name('symptomDetails');
Route::match(['get', 'post'], '/voice-assessment', 'Mental\MentalHealthController@voiceAssessment')->name('voiceAssessment');

Route::match(['get', 'post'], '/create-vht-Order', 'Mental\MentalHealthController@createVhtOrder')->name('createVhtOrder');

Route::match(['get', 'post'],'/vht-orders', 'Mental\MentalHealthController@vhtOrders')->name('vhtOrders');
Route::match(['get', 'post'],'/ai-privacy-policy', 'Mental\MentalHealthController@aiprivacyPolicy')->name('aiprivacyPolicy');

/**
 * Mental Health Routes END
 */

Route::any('/getPdf', "PdfManager@getPdf");
Route::any('/download-hg-lab-pdf', "Controller@changePdfHeader")->name('changePdfHeader');
Route::match(['get'], '/video-landing', "Controller@videoLanding");
Route::match(['get'], '/send-notification-admin', "Controller@sendNotificationAdmin");
Route::get('/app-guide', 'Controller@appGuide');
Route::match(['get', 'post'], '/free-consultation-plan', 'Controller@freePlan')->name('freePlan');
Route::match(['get', 'post'], '/select-plan', 'Controller@chooseFreePlan')->name('chooseFreePlan');
Route::match(['get', 'post'], '/festival-offer', 'Controller@buyFreePlan')->name('buyFreePlan');
Route::get('/updateUsersReferralCode', 'Controller@updateUsersReferralCode');
// Route::post('/helpIndPay', 'Controller@helpIndPay')->name('helpIndPay');
Route::post('/enquiryFromSubmit', 'Controller@enquiryFromSubmit')->name('enquiryFromSubmit');
Route::post('/loginUserByPaytm', 'Controller@loginUserByPaytmData')->name('loginUserByPaytm');
// Route::get('/help-india', 'Controller@helpIndiaLogin');
Route::any('/doit-landing', 'Controller@doitLandPage')->name('doitLandPage');
Route::any('/india-assist', 'Controller@indiaAssist')->name('indiaAssist');
Route::get('/sitemap.xml', 'Controller@sitemap')->name('sitemap');
Route::match(['get', 'post'], '/plan-details', 'Controller@planDetails')->name('planDetails');
Route::match(['post'], '/save-responce-whm', 'Controller@saveWHM')->name('saveWHM');
Route::match(['get', 'post'], '/subscription-receipt/{id}', 'Controller@downloadsubrecAdmin')->name('downloadsubrecadmin');
Route::match(['get', 'post'], '/notificationDoctorForAppointment', 'Controller@notificationDoctorForAppointment');
Route::match(['get', 'post'], '/AppointmentFileWriteByUrlPP/{city}/{appointment_id}/{fileName}', 'Controller@AppointmentFileWriteByUrlPP');
Route::match(['get', 'post'], '/checkNotify', 'Controller@checkNotify');
Route::match(['get', 'post'], '/checkFunc', 'Controller@checkFunc');
Route::match(['get', 'post'], '/login-feedback', 'Controller@loginFeedback')->name('loginFeedback');
Route::any('/plan/success', 'Controller@planOrderSuccess')->name('planOrderSuccess');
Route::any('/plan/cancel', 'Controller@planOrderCancel');
Route::any('/appointment/success', 'Controller@appointmentOrderSuccess')->name('appointmentOrderSuccess');
Route::any('/appointment/admin/success', 'Controller@appointmentAdminOrderSuccess')->name('appointmentAdminOrderSuccess');
Route::any('/appointment/cancel', 'Controller@appointmentOrderCancel');
Route::any('/medicine/success', 'Controller@medicineSuccess');
Route::any('/medicine/cancel', 'Controller@medicineCancel');
Route::match(['get', 'post'], '/hgelite', 'Controller@hgElite');
Route::match(['get', 'post'], '/doctor-consultation-plan', 'Controller@driveDashboard')->name('driveDashboard');
Route::match(['get', 'post'], '/hgoffer', 'Controller@offerdashboard')->name('offerdashboard');
Route::match(['get', 'post'], '/student', 'Controller@studentofferdashboard')->name('studentofferdashboard');
Route::match(['get', 'post'], '/offers', "Controller@unlimitedPlan")->name("unlimitedPlan");
Route::match(['get', 'post'], '/choosePlan', 'Controller@choosePlan')->name('choosePlan');
Route::any('/mental/success', 'Controller@mentalSuccess');
Route::post('/razorpay-subscription-plan', 'Controller@razorpaySubscriptionPlan')->name('razorpaySubscriptionPlan');
Route::any('/locality-search', 'Controller@getCurrentLocality')->name('getCurrentLocality');
Route::any('/search-doctor', 'Controller@searchDoctorsWeb')->name('searchDoctorsWeb');
Route::any('/spaciality-data', 'Controller@getSpecialityList')->name('getSpecialityList');
/* Cron for user subscription */
Route::match(['get', 'post'], '/autoUserSubscriptionExpired', 'Controller@autoUserSubscriptionExpired');
Route::match(['get', 'post'], '/reminderForUserSubscriptionExpired', 'Controller@reminderForUserSubscriptionExpired');
/* Cron for user Appoiment */
Route::match(['get', 'post'], '/notifyForCall', 'Controller@notifyForCall');
Route::match(['get', 'post'], '/wallet-history', "CommonController@userWalletHistory")->name("userWalletHistory");
Route::any('/myMedicineReminderNotification', 'Controller@myMedicineReminderNotification')->name('myMedicineReminderNotification');
Route::any('/get-clinics', 'Controller@getClinics')->name('getClinics');
Route::match(['get', 'post'], '/subcription-success', "Controller@successSubscripton")->name("successSubscripton");
Route::match(['get', 'post'], '/view-bill', "Controller@viewBill")->name("subscription.viewBill");
Route::any('/book-your-lab-test', 'Controller@subscriptionAds')->name('subscriptionAds');
Route::any('/search-lab', 'Controller@searchLabWeb')->name('searchLabWeb');
Route::any('/lab-list-data', 'Controller@getFavLabList')->name('getFavLabList');
Route::any('/lab-info', 'LabController@LabInfo')->name('LabInfo');
Route::any('/labCheckoutOrder', 'LabController@labCheckoutOrder')->name('labCheckoutOrder');
Route::any('/updateSlugForDoctor', 'Controller@updateSlugForDoctor');
Route::any('/checkAppointmentOrderStatus', 'Controller@checkAppointmentOrderStatus');
Route::any('/checkPaytmRewardStatus', 'Controller@checkPaytmRewardStatus');
Route::any('/checkSubscriptionStatus', 'Controller@checkSubscriptionStatus');
Route::get('/shp/{pid}', 'Controller@shp');
Route::get('/rad/{pid}', 'Controller@rad');
Route::get('/getPaytmOrders', 'Controller@getPaytmOrders');
Route::get('/createOrderFromAdmin', 'Controller@createOrderFromAdmin')->name('createOrderFromAdmin');
Route::match(['get', 'post'], '/get-labs', 'Controller@getLabs')->name('getLabs');
Route::match(['get', 'post'], '/get-uni-labs', 'Controller@getLabByCompany')->name('getLabByCompany');
Route::match(['get', 'post'], '/check-out', 'Controller@checkoutPlan')->name('checkoutPlan');
Route::match(['get', 'post'], '/paytmresponse', 'Controller@paytmResponse')->name('paytmResponse');
Route::match(['get', 'post'], '/razorpayresponse', 'Controller@razorpayResponse')->name('razorpayResponse');
Route::match(['get', 'post'], '/paymentresponse', 'Controller@paymentResponse')->name('paymentresponse');
Route::match(['get', 'post'], '/paymentcancel', 'Controller@paymentcancel')->name('paymentcancel');
Route::match(['get', 'post'], '/paymentcancelMiniProgram', 'Controller@paymentcancelMiniProgram')->name('paymentcancelMiniProgram');
Route::match(['get', 'post'], '/paytmResponseMIniApp', 'Controller@paytmResponseMIniApp')->name('paytmResponseMIniApp');
Route::match(['get', 'post'], '/paymentcancelMiniProgramPlan', 'Controller@paymentcancelMiniProgramPlan')->name('paymentcancelMiniProgramPlan');
Route::match(['get', 'post'], '/paytmResponseMIniAppPlan', 'Controller@paytmResponseMIniAppPlan')->name('paytmResponseMIniAppPlan');
Route::match(['get', 'post'], '/getStateList', 'Controller@getStateList')->name("getStateList");
Route::match(['get', 'post'], '/getCityList', 'Controller@getCityList')->name("getCityList");
Route::match(['get', 'post'], '/getLocalityList', 'Controller@getLocalityList')->name("getLocalityList");
Route::match(['get', 'post'], '/writeThyrocaredata', 'Controller@writeThyrocareData')->name("writeThyrocareData");
Route::match(['get', 'post'], '/insertThyrocarePackage', 'Controller@insertThyrocarePackage')->name("insertThyrocarePackage");

/*Doc ctrl Routes*/
Route::any('/razorpay/payment', 'DocController@razorpayPayment')->name('razorpayPayment');
Route::post('/verify-payment', 'DocController@verifyPayment')->name('verifyPayment');
Route::post('/payment-order-create', 'DocController@paymentOrderCreate')->name('paymentOrderCreate');
Route::post('/fetch-razorpay-payments', 'DocController@fetchRazorpayPayments')->name('fetchRazorpayPayments');
Route::match(['get', 'post'], '/walletPaytm', 'DocController@walletPaytm')->name('walletPaytm');
Route::match(['get', 'post'], '/tele-camp', 'DocController@teleCamp')->name('teleCamp');
Route::match(['get', 'post'], '/appointmentCheckout', 'DocController@appointmentCheckout')->name('appointmentCheckout');
Route::match(['get', 'post'], '/appointmentCheckoutPaytm', 'DocController@appointmentCheckoutPaytm')->name('appointmentCheckoutPaytm');
Route::match(['get', 'post'], '/claim-doctor', "DocController@addDoc")->name("addDoc");
Route::match(['get', 'post'], '/claim-doctor/verify-otp', "DocController@verifyClaimOtp")->name("verifyClaimOtp");
Route::match(['get', 'post'], '/claim-doctor/send-otp', "DocController@sendClaimOtp")->name("sendClaimOtp");
Route::any('/doctor-info', 'DocController@doctorInfo')->name('doctorInfo');
Route::any('/doctor-detail', 'DocController@getDoctorInfo')->name('getDoctorInfo');
Route::any('/hospital-detail', 'DocController@getHospitalInfo')->name('getHospitalInfo');
Route::any('/hospital-info', 'DocController@getHospitalInfoById')->name('getHospitalInfoById');
Route::any('/set-session', 'DocController@setSessionLocality')->name('setSessionLocality');
Route::any('/set-ids-city-states', 'DocController@setCityStateIds')->name('setCityStateIds');
Route::any('/verify-doc-info', 'DocController@verifyDocDetails')->name('verifyDocDetails');
Route::any('/verify-doc-info-edit', 'DocController@verifyDocDetailsEdit')->name('verifyDocDetailsEdit');
Route::any('/doctor/show-doctor-slot', 'DocController@showSlot')->name('doctor.showSlot');
Route::any('/doctor/show-doc-info', 'DocController@showDocInfo')->name('doctor.showDocInfo');
Route::any('/doctor/loadSlots', 'DocController@loadSlots')->name('doctor.loadSlots');
Route::any('/doctor/load-doc-slots', 'DocController@loadDocSlots')->name('doctor.loadDocSlots');
// Route::any('/doctor/appointment-book', 'DocController@bookSlot')->name('doctor.bookSlot');
Route::any('/doctor/consultation-book', 'DocController@bookSlot')->name('doctor.bookSlot');
Route::any('/online-consult', 'DocController@onlineConsult')->name('onlineConsult');
Route::any('/book-consult', 'DocController@bookConsult')->name('bookConsult');
Route::post('/searchUserByMobile', 'DocController@searchUserByMobile')->name('searchUserByMobile');
Route::any('/doctor/appointment-confirmation', 'DocController@bookSlotConfirm')->name('doctor.bookSlotConfirm');
Route::any('/doctor/appointment-process-queue', 'DocController@appointmentProcess')->name('doctor.appointmentProcess');
Route::match(['get', 'post'], '/usercheck', "UserController@checkUserUpdateNoEmail")->name("checkUserUpdateNoEmail");
Route::match(['get', 'post'], '/update-user/profile', "UserController@updateUserProfile")->name("updateUserProfile");
Route::any('/patient/show-feedback-form', 'DocController@showFeedbackForm')->name('patients.showFeedbackForm');
Route::any('/patient/save-feedback-form', 'DocController@saveFeedbackForm')->name('patients.saveFeedbackForm');
/*End Doc ctrl routes*/

/*Subscription Routes*/
Route::match(['get', 'post'], '/elite', 'SubscriptionController@drive')->name('drive');
Route::match(['get', 'post'], '/gennie-plan', 'SubscriptionController@subscriptionPlans')->name('subscriptionPlans');
Route::match(['get', 'post'], '/checkout-gennie-plan', 'SubscriptionController@checkOutUserPlan')->name('checkOutUserPlan');
Route::match(['get', 'post'], '/checkout-plan-paytm', 'SubscriptionController@checkOutUserPlanPaytm')->name('checkOutUserPlanPaytm');
Route::match(['get', 'post'], '/planCheckoutLive', 'SubscriptionController@planCheckoutLive')->name('planCheckoutLive');
Route::match(['get', 'post'], '/change-password', 'UserController@changePassword')->name('changePassword');
Route::match(['get', 'post'], '/forgot-password', 'UserController@forgotEmail')->name('forgotEmail');
Route::match(['get', 'post'], '/my-subscriptions', 'SubscriptionController@mySubscriptions')->name('mySubscriptions');
Route::match(['get', 'post'], '/view-subscription/{id}', 'SubscriptionController@viewSubscription')->name('viewSubscription');
Route::match(['get', 'post'], '/download-subscription/{id}', 'SubscriptionController@downloadsubrec')->name('downloadsubrec');
Route::any('/ApplyReferralCode', 'SubscriptionController@ApplyReferralCode')->name('ApplyReferralCode');
Route::any('/instant-subcription', 'SubscriptionController@instantSubs')->name('admin.instantSubs');
Route::any('/deposit-amt', 'SubscriptionController@depositAmt')->name('admin.depositAmt');
Route::any('/deposit-requests-admin', 'SubscriptionController@depositReqAdmin')->name('admin.depositReqAdmin');
Route::any('/update-requests-status', 'SubscriptionController@updateDepositReqSts')->name('admin.updateDepositReqSts');
/*Subscription Routes End*/

/*Agora Routes*/
Route::match(['get', 'post'], '/acceptVc', 'AgoraController@acceptVc')->name('acceptVc');
Route::match(['get', 'post'], '/acceptedCall', 'AgoraController@acceptedCall')->name('acceptedCall');
/*End*/

/*Home Routes*/
Route::match(['get', 'post'], '/', "HomeController@index")->name("index");
Route::match(['get', 'post'], '/check-Institute', 'HomeController@checkInstitute')->name('checkInstitute');
Route::match(['get', 'post'], '/students', 'HomeController@students')->name('students');
Route::match(['get', 'post'], '/enquiry/organization', 'HomeController@enquiryFromOrganization')->name('enquiryFromOrganization');
Route::match(['get', 'post'], '/videoLanding', 'HomeController@videoLanding')->name('videoLanding');
Route::match(['get', 'post'], '/work-sheet/success', 'HomeController@WKDone')->name('WKDone');
Route::match(['get', 'post'], '/worksheets/clear', 'HomeController@clearWorksheet')->name('worksheets.clear');
Route::get('/experts', 'HomeController@expertHg')->name('expertHg');
Route::match(['get', 'post'], '/work-sheets', 'HomeController@worksheets')->name('worksheets');
Route::get('/work-sheetsdd', 'HomeController@modifyHtml')->name('modifyHtml');
Route::match(['get', 'post'], '/partners', 'HomeController@partners')->name('partners');



Route::match(['get', 'post'], '/getCityListByName', 'HomeController@getCityListByName')->name('getCityListByName');
Route::match(['get', 'post'], '/corporate', 'HomeController@corporate')->name('corporate');
Route::match(['get', 'post'], '/setModalSession', 'HomeController@setModalSession')->name('setModalSession');
Route::match(['get', 'post'], '/checkModalSession', 'HomeController@checkModalSession')->name('checkModalSession');
Route::match(['get', 'post'], '/walletList', 'HomeController@walletList')->name('walletList');
Route::match(['get', 'post'], '/closeSession', 'HomeController@closeSession')->name('closeSession');
Route::match(['get', 'post'], '/userDetail', 'HomeController@userDetail')->name('userDetail');
//setting for qrCode
Route::any('/qr-code', 'HomeController@qrCodeApp')->name('qrcode.app');
Route::any('/doctor/book-appointment/{action}/{id}', 'HomeController@qrCodeIndex')->name('qrcode.index');
Route::any('/doctor/printqrCode', 'HomeController@printqrCode')->name('qrcode.print');
/** End section */
Route::get('/download', 'HomeController@healthgenniePatientApp')->name('healthgenniePatientApp');
Route::match(['get', 'post'], '/appointment-confirm', "HomeController@appointmentConfirm")->name("appointmentConfirm");
Route::match(['get', 'post'], '/appointment-cancel-doctor', "HomeController@appointmentCancel")->name("appointmentCancel");
Route::get('/pay/{orderId}', "Admin\HomeController@apptPayment")->name('apptPayment');
Route::get('/sub-pay/{orderId}', "Admin\HomeController@subsPayment")->name('subsPayment');
Route::get('/doctors/load-more/{city}/{type}', 'searchController@loadMore');
Route::post('/enquiryForm', 'HomeController@enqueryForm')->name('enqueryForm');
Route::any('/member', 'HomeController@generateCode')->name('generateCode');
Route::any('/generateCouponCode', 'HomeController@generateCouponCode')->name('generateCouponCode');
Route::any('/about-us', 'HomeController@aboutUs')->name('aboutUs');
Route::any('/contact-us', 'HomeController@contactUs')->name('contactUs');
Route::any('/mission', 'HomeController@Mission')->name('Mission');
Route::any('/career', 'HomeController@carrerUs')->name('carrerUs');
Route::any('/career-detail/{id?}', 'HomeController@carrerDetail')->name('carrerDetail');
Route::any('/job-apply/{id?}', 'HomeController@jobApply')->name('jobApply');
Route::any('/apply-for-job', 'HomeController@applyForJob')->name('applyForJob');
Route::get('/get-cities/{stateId}', 'HomeController@getCities')->name('getCities');
Route::get('/get-state/{countryId}', 'HomeController@getState')->name('getState');
Route::any('/support', 'HomeController@Support')->name('Support');
Route::any('/help', 'HomeController@helpMe')->name('helpMe');
Route::any('/privacy-policy', 'HomeController@privacyPolicy')->name('privacyPolicy');
Route::any('/cancelation-policy', 'HomeController@cancelationPolicy')->name('cancelationPolicy');
Route::any('/terms-conditions', 'HomeController@termsConditions')->name('termsConditions');
Route::any('/claim-terms-conditions', 'HomeController@claimTermsConditions')->name('claimTermsConditions');
Route::any('/subcribedEmail', 'HomeController@subcribedEmail')->name('subcribedEmail');
Route::any('/viewMailTemplate', 'HomeController@viewMailTemplate')->name('viewMailTemplate');
Route::any('/SendAppLink', 'HomeController@SendAppLink')->name('SendAppLink');
Route::any('/health-gennie-buy-medicine', 'HomeController@oneMgOpen')->name('oneMgOpen');

Route::any('/vaccination-drive', 'HomeController@vaccinationDrive')->name('vaccinationDrive');
Route::any('/runners', 'HomeController@runnersLead')->name('runnersLead');
Route::match(['get', 'post'], '/offers-old', "HomeController@hgOffers")->name("hgOffers");
Route::match(['get', 'post'], '/plans', "HomeController@hgOffersPlans")->name("hgOffersPlans");
Route::match(['get', 'post'], '/check-out-plan', "HomeController@checkOut")->name("checkOut");
Route::get('download-app', function () {
	return view('download-app');
});
/*End Home Routes*/

/*Blog routes*/
Route::any('/blog', 'BlogsController@blogList')->name('blogList');
Route::any('/blog/{slug}/', 'BlogsController@blogInfo')->name('blogInfo');
Route::any('/blog-search', 'BlogsController@blogSearch')->name('blogSearch');
Route::any('/blogLikeComment', 'BlogsController@blogLikeComment')->name('blogLikeComment');
Route::any('/clickComment', 'BlogsController@clickComment')->name('clickComment');
/*End blogs routes*/					

/*User ctrl routes*/
Route::match(['get', 'post'], '/user-profile', "UserController@profile")->name("profile");
Route::match(['get', 'post'], '/user-prescription', "UserController@myPriscription")->name("myPriscription");
Route::match(['get', 'post'], '/upload-priscription', "UserController@uploadPriscription")->name("uploadPriscription");
Route::match(['get', 'post'], '/delete-priscription', "UserController@deletePriscription")->name("deletePriscription");
Route::match(['get', 'post'], '/share-priscription', "UserController@sharePrescription")->name("sharePrescription");
Route::match(['get', 'post'], '/getNotePrintOfWeb', "UserController@getNotePrintOfWeb")->name("getNotePrintOfWeb");
Route::match(['get', 'post'], '/edit-user-send-otp', "UserController@sendEditUserOtp")->name("sendEditUserOtp");
Route::match(['get', 'post'], '/edit-user-confirm-otp', "UserController@confirmUserOtp")->name("confirmUserOtp");
Route::match(['get', 'post'], '/uploadDocument', "UserController@uploadDocument")->name("uploadDocument");
Route::match(['get', 'post'], '/deleteDocument', "UserController@deleteDocument")->name("deleteDocument");
/*End user ctrl routes*/
/*Appointment Routes*/
Route::match(['get', 'post'], '/user-consultation', "AppointmentController@userAppointment")->name("userAppointment");
Route::match(['get', 'post'], '/change-appointment', "AppointmentController@changeAppointment")->name("changeAppointment");
Route::match(['get', 'post'], '/change-slot', "AppointmentController@changeAppointmentSlot")->name("changeAppointmentSlot");
Route::match(['get', 'post'], '/cancel-appointment', "AppointmentController@cancelAppointment")->name("cancelAppointment");
Route::match(['get', 'post'], '/followup-appointment', "AppointmentController@followupAppointment")->name("followupAppointment");
Route::match(['get', 'post'], '/download-receipt', "AppointmentController@downloadReceipt")->name("downloadReceipt");
Route::match(['get', 'post'], '/consultation-details/{aPiD}', "AppointmentController@appointmentDetails")->name("appointmentDetails");
Route::match(['get', 'post'], '/payment-detail', 'AppointmentController@showAppointmentTxn')->name('showAppointmentTxn');
Route::match(['get', 'post'], '/download-appt-rec', 'AppointmentController@downloadApptReceipt')->name('downloadApptReceipt');
/*End Appt routes*/



/*Lab routes*/
Route::any('/lab-dashboard', 'LabController@LabDashboard')->name('LabDashboard');
Route::any('/lab-profile/{id?}', 'LabController@LabProfile')->name('LabProfile');
Route::any('/lab-details/{id?}/{type?}', 'LabController@LabDetails')->name('LabDetails');
Route::any('/avail-lab-pack-details/{code?}', 'LabController@availPackDetails')->name('availPackDetails');
Route::any('/lab-profile-details/{id?}', 'LabController@LabProfileDetails')->name('LabProfileDetails');
Route::any('/lab-cart', 'LabController@LabCart')->name('LabCart');
Route::any('/avail-lab-cart', 'LabController@AvailLabCart')->name('AvailLabCart');
Route::any('/CartUpdate', 'LabController@CartUpdate')->name('CartUpdate');
Route::any('/create-lab-order-addresses', 'LabController@createLaborderAddresses')->name('cart.createLaborderAddresses');
Route::any('/deletelaborderAddress', 'LabController@deletelaborderAddress')->name('deletelaborderAddress');
Route::any('/checkPincodeAvailability', 'LabController@checkPincodeAvailability')->name('checkPincodeAvailability');
Route::any('/GetAppointmentSlots', 'LabController@GetAppointmentSlots')->name('GetAppointmentSlots');
Route::any('/ApplyCoupon', 'LabController@ApplyCoupon')->name('ApplyCoupon');
Route::post('/apply-wallet-amt', 'CommonController@applyWalletAmt')->name('applyWalletAmt');
Route::any('/ViewCartAPI', 'LabController@ViewCartAPI')->name('ViewCartAPI');
Route::any('/AvailViewCartAPI', 'LabController@AvailViewCartAPI')->name('AvailViewCartAPI');
Route::any('/create-lab-order', 'LabController@createLabOrder')->name('cart.createLabOrder');
Route::any('/lab-order/success', 'LabController@orderSuccess')->name('orderSuccess');
Route::any('/lab-order/cancel', 'LabController@orderCancel')->name('orderCancel');
Route::any('/lab-order/cancel-order', 'LabController@cancelOrder')->name('cancelOrder');
Route::any('/lab-order/orders/{filter?}', 'LabController@labOrders')->name('labOrders');
Route::any('/lab-order/details/{orderid}', 'LabController@labOrderDetails')->name('labOrderDetails');
Route::any('/lab-order/all/{type}', 'LabController@allPackages')->name('allPackages');
Route::any('/lab-packages/{camp_id}', 'LabController@redirectToLab');
Route::any('/best-lab-test-packages', 'LabController@showLabsPackage')->name('showLabsPackage');
Route::any('/dhamaka-offer', 'LabController@dhamakaOffer')->name('dhamakaOffer');
Route::any('/getThyrocarePackageGroup', 'LabController@getThyrocarePackageGroup')->name('getThyrocarePackageGroup');
Route::match(['get', 'post'], '/upload-report', 'LabController@uploadPrescription')->name('uploadPrescription');
Route::match(['get', 'post'], '/get-prescriptions', 'LabController@getPrescriptions')->name('getPrescriptions');
Route::match(['post'], '/delete-prescription', 'LabController@removePrescription')->name('removePrescription');
/*End lab routes*/

// Ticket
Route::post('track-status', 'TicketController@trackStatus')->name('track-status');
Route::post('add-comment', 'TicketController@addComment')->name('add-comment');
Route::get('ticket-reply', 'TicketController@ticketReply');
Route::match(['get', 'post'], '/store',  'TicketController@store')->name('store');
//END Ticket routes

//Health Assessment Route
Route::any('/counciling/{slug?}', 'QuizController@counciling')->name('counciling');
Route::any('/councilingdone', 'QuizController@councilingdone')->name('councilingdone');
Route::any('/counseling-feedback', 'QuizController@Feedback');
Route::any('/counseling-dashboard/{slug}', 'QuizController@councelingdashboard')->name('counseling-dashboard');
Route::any('/screening-dashboard', 'QuizController@screeningdashboard')->name('screening-dashboard');
Route::any('/counseling-list/{slug}', 'QuizController@counselinglist')->name('counseling-list');
Route::any('/screencounseling-list', 'QuizController@screencounselinglist')->name('screencounseling-list');
Route::any('/dashboard/{slug}', 'QuizController@Dashboard')->name('orgDashboard');
Route::any('/student-list/{slug}', 'QuizController@stuList')->name('stuList');
Route::any('/counseling-addNote', 'QuizController@addNote')->name('addNote');
Route::any('/assessment-table-data/{slug}', 'QuizController@assessmentData');
Route::any('/programme/{slug}', 'QuizController@programme');
Route::any('/screening-report-card/{id?}/{slug?}', 'QuizController@screeningreportcard')->name('screeningreportcard');
Route::any('/student-report', 'QuizController@studentReport');
Route::any('/updateNextsessiondate/{id?}', 'QuizController@updateNextsessiondate')->name('updateNextsessiondate');
Route::any('/institute/slotBook', 'QuizController@slotBook')->name('slotBook');

Route::group(['middleware' => 'check.organization.session'], function () {
	Route::any('/institute/book-appointment/{slug}', 'QuizController@bookAppointment')->name('org.bookAppointment');
	Route::any('/institute/{slug}', 'QuizController@healthAssesAdmin')->name('healthAssesAdmin');
	Route::any('/institute/subscription/{slug}', 'QuizController@subscription')->name('org.subscription');
	Route::any('/institute/appointment/{slug}', 'QuizController@hgAppointmentsInstitute')->name('institute.hgAppointmentsInstitute');
	Route::any('/institute/screening-admin', 'QuizController@healthScreeningAssesAdmin')->name('healthScreeningAssesAdmin');
	Route::any('/institute/assessment-registration/{slug}', 'QuizController@QuizRegistration')->name('QuizRegistration');
	Route::any('/institute/screening', 'QuizController@QuizScreening')->name('QuizScreening');



	Route::any('/institute/health-assessment', 'QuizController@index')->name('quiz');
	Route::any('/institute/question-master', 'QuizController@quizquestionlist')->name('quizmaster');
	Route::any('/institute/saveQuestiondata', 'QuizController@saveQuestion');
	Route::any('/institute/assessment-admin-list', 'QuizController@assessmentListadmin')->name('assessmentListadmin');
	Route::any('/institute/assessment-admin-dashboard', 'QuizController@adminquizdashboard')->name('adminquizdashboard');
	Route::any('/institute/counseling-list-admin', 'QuizController@counselingListadminlist')->name('counselingListadmin');
	Route::any('/institute/assessment-feedback', 'QuizController@assessmentfeedback')->name('assessment-feedback');
	Route::any('/institute/assessment-list/{slug}', 'QuizController@assessmentList')->name('assessmentList');
	Route::any('/institute/screening-list/{slug?}', 'QuizController@screeningList')->name('screeningList');
	Route::any('/institute/question-answer/{slug?}', 'QuizController@questAns')->name('questAns');
	Route::any('/institute/report-card/{id?}/{slug?}', 'QuizController@reportcard')->name('reportcard');
	Route::any('/institute/student-list-all/{slug?}', 'QuizController@orgStudentListAll')->name('orgStudentListAll');
	Route::any('/institute/student-list/{slug?}', 'QuizController@orgStudentList')->name('orgStudentList');
	Route::match(['get', 'post'], '/institute/mental-records', 'QuizController@getMentalhealthList')->name('org.getMentalhealthList');
	Route::match(['get', 'post'], '/institute/mental-report/{slug}/{id?}', 'QuizController@getMentalhealthReport')->name('org.getMentalhealthReport');
	Route::match(['get', 'post'], '/institute/activity-station', 'QuizController@activityStation')->name('org.activityStation');
});

Route::any('/assessment-registration/{slug}', 'QuizController@QuizRegistration')->name('QuizRegistration');
Route::any('/screening', 'QuizController@QuizScreening')->name('QuizScreening');
// Route::any('/quiz-master-master', 'QuizController@storequizQuestion');
/* * ********************************** ADMIN ******************************************** */
//##############################    BEFOR_LOGIN    ##################################################
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', "middleware" => ["AdminBeforeLogin"]], function ($req) {
	Route::get('/', "HomeController@login");
	Route::any('/login', "HomeController@login")->name('admin.login');
	Route::any('/forgot-password', "HomeController@forgotPassword")->name('admin.reset.password');
	Route::match(['get', 'post'], '/update-password', "HomeController@updatePassword")->name('admin.updatePassword');
	Route::post('/credential-update', "HomeController@credentialUpdate")->name('admin.credentialUpdate');
});
//############################   AFTER-LOGIN     ###########################################################
Route::match(['get', 'post'], '/upload-doctor-documents', "Admin\HomeController@uploadDoctorDocuments")->name("admin.uploadDoctorDocuments");
Route::match(['get', 'post'], '/delete-file', "Admin\HomeController@deleteFile")->name("admin.deleteFile");

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', "middleware" => ["Admin"]], function ($req) {
	Route::any('/show-handle-queries', 'HomeController@showHandleQueries')->name('admin.showHandleQueries');
	Route::any('/manage-sprt', 'HomeController@manageSupportSystem')->name('admin.manageSupportSystem');
	Route::any('/show-slot', 'AppointmentController@showSlotAdmin')->name('showSlotAdmin');
	//HomeController
	Route::match(['get', 'post'], '/HgMap', 'HomeController@HgMap')->name('admin.HgMap');
	Route::match(['get', 'post'], '/updateYear', 'HomeController@updateYear')->name('admin.updateYear');
	Route::any('AssignNow', 'TicketController@AssignNow')->name('AssignNow');
	Route::post('comment/{comment}/reply', 'TicketController@replyToComment')->name('comment.reply.store');
	Route::get('/createTicket', 'TicketController@createTicket')->name('admin.createTicket');
	Route::get('/get-case-categories/{caseTypeId}', 'TicketController@getCaseCategories')->name('admin.getCaseCategories');
	Route::match(['get', 'post'], '/create-case', 'TicketController@createCase')->name('admin.createCase');
	Route::get('/unassignTicketList', 'TicketController@unassignTicketList')->name('admin.unassignTicketList');
	Route::get('/assignTicketList', 'TicketController@assignTicketList')->name('admin.assignTicketList');
	Route::get('/editTicket', 'TicketController@editTicket')->name('admin.editTicket');
	Route::get('/getReplyMessage', 'TicketController@getReplyMessage')->name('admin.getReplyMessage');
	Route::post('/updateTicket', 'TicketController@updateTicket')->name('update.ticket');
	Route::post('/fetch-users', 'TicketController@fetchUsers')->name('fetch-users');
	Route::get('/view-tickets', 'TicketController@viewTickets')->name('view.tickets');
	Route::get('/request-tickets', 'TicketController@requestTickets')->name('admin.requestTickets');
	Route::get('/doctor/start-chat', 'TicketController@startChat')->name('admin.startChat');
	Route::get('/create-user-ticket/{id}', 'TicketController@createUserTicket')->name('admin.createUserTicket');
	Route::post('/add-case', 'TicketController@addCase')->name('admin.addCase');
	Route::post('/get/Messages', 'TicketController@getMessages')->name('admin.getMessages');
	Route::post('/send/Messages', 'TicketController@sendMessage')->name('admin.sendMessage');


	

	Route::match(['get', 'post'], '/doctor-update-address', "HomeController@localityDoctorUpdateNotFOund")->name("admin.localityDoctorUpdateNotFOund");
	Route::match(['get', 'post'], '/locality-assign', "HomeController@localityAssign")->name("admin.localityAssign");
	Route::match(['get', 'post'], '/update-doc-address', "HomeController@updateDocAddress")->name("admin.updateDocAddress");
	Route::match(['get', 'post'], '/appliedQRCode', "HomeController@appliedQRCode")->name("admin.appliedQRCode");
	Route::match(['get', 'post'], '/setDocPosition', "HomeController@setDocPosition")->name("admin.setDocPosition");
	Route::match(['get', 'post'], '/varify-claim-doctors', "HomeController@varifyClaimDoctor")->name("admin.varifyClaimDoctor");
	Route::match(['get', 'post'], '/doctorSponsorship', "HomeController@doctorSponsorship")->name("admin.doctorSponsorship");
	Route::match(['get', 'post'], '/changeSponsorStatus', "HomeController@changeSponsorStatus")->name("admin.changeSponsorStatus");
	Route::match(['get', 'post'], '/enquiry-status', "HomeController@enquiryStatus")->name("admin.enquiryStatus");
	Route::match(['get', 'post'], '/loadHeaderData', "HomeController@loadHeaderData")->name("admin.loadHeaderData");
	Route::match(['get', 'post'], '/updateCallStatus', "HomeController@updateCallStatus")->name("admin.updateCallStatus");
	Route::match(['get', 'post'], '/sendUserBulkSms', "HomeController@sendUserBulkSms")->name("admin.sendUserBulkSms");
	Route::match(['get', 'post'], '/newNotification', "HomeController@newNotification")->name("admin.newNotification");

	Route::match(['get', 'post'], '/Getyear', "HomeController@Getyear")->name("admin.Getyear");
	Route::match(['get', 'post'], '/forgot-password', "HomeController@forgotPassword")->name("admin.password");

	Route::any('/health-process-pw', 'HomeController@healthProcessPw')->name('admin.healthProcessPw');
	Route::any('/view-symptoms-question-pw', 'HomeController@viewSymptomsQuestionPW')->name('admin.viewSymptomsQuestionPW');

	Route::match(['get', 'post'], '/addNote', "HomeController@addNote")->name("admin.addNote");
	Route::match(['get', 'post'], '/viewSubscription', 'SubscriptionController@viewSubscription')->name('subscription.viewSubscription');
	Route::match(['get', 'post'], '/newSubscription', 'SubscriptionController@newSubscription')->name('subscription.newSubscription');
	Route::match(['get', 'post'], '/viewPlan', 'SubscriptionController@viewPlan')->name('subscription.viewPlan');

	Route::match(['get', 'post'],'/update-ref-code', "SubscriptionController@updateRefCode")->name("admin.updateRefCode");
	Route::match(['get', 'post'],'/edit-Ref-code', "SubscriptionController@editRefCode")->name("admin.editRefCode");
	Route::match(['get', 'post'],'/total-institute-subs', 'SubscriptionController@totInsSubs')->name('subscription.totInsSubs');
	Route::any('/ApplyReferralCodeAdmin', 'SubscriptionController@ApplyReferralCodeAdmin')->name('ApplyReferralCodeAdmin');
	Route::post('/subcription-import', 'SubscriptionController@subcriptionImport')->name('admin.subcriptionImport');
	Route::any('/instant-subcription', 'SubscriptionController@instantSubs')->name('admin.instantSubs');
	Route::any('/instant-subcription-report', 'SubscriptionController@instantSubsReport')->name('admin.instantSubsReport');
	Route::any('/insert-subs-amount', 'SubscriptionController@insertSubsAmount')->name('admin.insertSubsAmount');
	Route::any('/deposit-requests', 'SubscriptionController@depositReq')->name('admin.depositReq');
	Route::match(['get', 'post'], '/get-referral-codes', 'SubscriptionController@getReferralCodes')->name('admin.getReferralCodes');
	Route::any('/checkPaymentStatusAdmin', 'SubscriptionController@checkPaymentStatusAdmin')->name('admin.checkPaymentStatusAdmin');
	Route::any('/checkPaymentStatusAdmin', 'SubscriptionController@checkPaymentStatusAdmin')->name('admin.checkPaymentStatusAdmin');
	Route::any('/instant-plan/success', 'SubscriptionController@instantPlanSuccess')->name('admin.instantPlanSuccess');
	Route::any('/logout', "HomeController@logout")->name("admin.logout");

	Route::any('/deposit-amt', 'SubscriptionController@depositAmt')->name('admin.depositAmt');
	Route::any('/deposit-requests', 'SubscriptionController@depositReq')->name('admin.depositReq');
	Route::any('/deposit-requests-admin', 'SubscriptionController@depositReqAdmin')->name('admin.depositReqAdmin');
	Route::any('/deposite-remark', 'SubscriptionController@depositeRemark')->name('admin.depositeRemark');
	Route::any('/deposit-ladger-admin', 'SubscriptionController@depositSubscriptionLadger')->name('admin.depositSubscriptionLadger');
	Route::any('/update-requests-status', 'SubscriptionController@updateDepositReqSts')->name('admin.updateDepositReqSts');
	Route::any('/daily-report', 'SubscriptionController@instantSubsReportAdmin')->name('admin.instantSubsReportAdmin');
	Route::match(['get', 'post'], '/jobApplication', 'HomeController@jobApplication')->name('admin.jobApplication');

	Route::group(["middleware" => ["check-module-access:corporate_users"]], function ($req) {
		Route::match(['get', 'post'], '/corporate-users', "HomeController@corporateUsers")->name("admin.corporateUsers");
		Route::match(['get', 'post'], '/patient-add', "HomeController@addPatients")->name("admin.addPatients");
		Route::match(['get', 'post'], '/viewSubscription', 'SubscriptionController@viewSubscription')->name('subscription.viewSubscription');
		Route::match(['get', 'post'], '/newSubscription', 'SubscriptionController@newSubscription')->name('subscription.newSubscription');
	});
	Route::match(['get', 'post'], '/add-user', "HomeController@addUser")->name("admin.addUser");
	Route::match(['get', 'post'], '/edit-user/{id}', "HomeController@editUser")->name("admin.editUser");
	Route::match(['get', 'post'], '/crtAppt', "HomeController@crtAppt")->name("admin.crtAppt");
	Route::match(['get', 'post'], '/loadLinks', "HomeController@loadLinks")->name("admin.loadLinks");
	/** Healthgennie Patients **/
	Route::group(["middleware" => ["check-module-access:users"]], function ($req) {
		Route::match(['get', 'post'], '/set-scheduler', "HomeController@setSchedular")->name("admin.setSchedular");
		Route::match(['get', 'post'], '/notification/status/update', "HomeController@notificationStatusUpdate")->name("admin.notificationStatusUpdate");

		Route::match(['get', 'post'], '/notification-click', "HomeController@notificationClick")->name("admin.notificationClick");

		Route::match(['get', 'post'], '/schedule-notification', "HomeController@scheduleNotification")->name("admin.scheduleNotification");
		Route::match(['get', 'post'], '/patients', "HomeController@patientList")->name("admin.patientList");
		Route::match(['get', 'post'], '/notification-click', "HomeController@notificationClick")->name("admin.notificationClick");
		Route::match(['get', 'post'], '/viewUsers', "HomeController@viewUsers")->name("admin.viewUsers");
		Route::get('/make-lab-order', "HomeController@makeLabOrder")->name("admin.makeLabOrder");

		Route::match(['get', 'post'],'/lab-calling', 'LabMasterController@labCalling')->name('admin.labCalling');
		Route::match(['get', 'post'],'/update-calling', 'LabMasterController@updatelabCalling')->name('admin.updatelabCalling');
		Route::match(['get', 'post'],'/follow-Up', "LabMasterController@followUp")->name("admin.followUp");

		Route::match(['get', 'post'],'/upate-follow-up', "LabMasterController@upateFollowUp")->name("admin.upateFollowUp");
		Route::match(['get', 'post'],'/update-cancel-reason', "LabMasterController@updateCancelReason")->name("admin.updateCancelReason");

		Route::get('/create-lab-order', "HomeController@newLabOrderFromAdmin")->name("admin.newLabOrderFromAdmin");
		Route::match(['get', 'post'], '/user-import', "HomeController@userExcelImport")->name("admin.userExcelImport");
		Route::match(['get', 'post'], '/crt-appt-lnk', "HomeController@createApptLnk")->name("admin.createApptLnk");
		Route::match(['get', 'post'], '/getAllChild', "HomeController@getAllChild")->name("admin.getAllChild");
		Route::match(['get', 'post'], '/addUserAddress', "HomeController@addUserAddress")->name("admin.addUserAddress");
	});
	// Route::group(["middleware" => ["check-module-access:dashboard"]], function ($req) {

	Route::match(['get', 'post'], '/home', 'HomeController@Home')->name('admin.home');

	// Route::match(['get','post'], '/home',  "HomeController@barGraph")->name("admin.barGraph");
	// });
	Route::group(["middleware" => ["check-module-access:doctor"]], function ($req) {
		Route::match(['get', 'post'], '/doctor-add', "HomeController@addDoctor")->name("admin.addDoctor");
		Route::match(['get', 'post'], '/edit-doctor', "HomeController@editDoctor")->name("admin.editDoctor");
		Route::match(['get', 'post'], '/update-doctor', "HomeController@updateDoctor")->name("admin.updateDoctor");
		Route::match(['get', 'post'], '/delete-doctor', "HomeController@deleteDoctorInfo")->name("admin.deleteDoctorInfo");
		Route::match(['get', 'post'], '/live-doctors', "HomeController@liveDoctorsList")->name("admin.liveDoctorsList");
		Route::match(['get', 'post'], '/doctors', "HomeController@doctorsList")->name("admin.doctorsList");
		Route::match(['get', 'post'], '/non-hg-doctors', "HomeController@nonHgDoctorsList")->name("admin.nonHgDoctorsList");
		Route::match(['get', 'post'], '/claim-doctors', "HomeController@claimDoctorsList")->name("admin.claimDoctorsList");
		Route::match(['get', 'post'], '/non-claim-doctors', "HomeController@nonClaimDoctorsList")->name("admin.nonClaimDoctorsList");
		Route::match(['get', 'post'], '/changeDoctorStatus', "HomeController@changeDoctorStatus")->name("admin.changeDoctorStatus");
	});
	Route::group(["middleware" => ["check-module-access:sponsor_suggest_doctors"]], function ($req) {
		Route::match(['get', 'post'], '/sponsored-doctors', "HomeController@sponsoredDoctor")->name("admin.sponsoredDoctor");
		Route::match(['get', 'post'], '/sponsor-doc/{action?}/{id?}', "HomeController@sponsorDoc")->name("admin.sponsorDoc");
	});
	Route::group(["middleware" => ["check-module-access:feedback_list"]], function ($req) {
		Route::match(['get', 'post'], '/healthgennie-feedbacks', "HomeController@feedbackPatAll")->name("admin.feedbackPatAll");
		Route::match(['get', 'post'], '/changeFeedbackStatus', "HomeController@changeFeedbackStatus")->name("admin.changeFeedbackStatus");
		Route::match(['get', 'post'], '/viewFeedback', "HomeController@viewFeedback")->name("admin.viewFeedback");
		Route::match(['get', 'post'], '/update-feedback', "HomeController@updateFeedback")->name("admin.updateFeedback");
	});
	Route::group(["middleware" => ["check-module-access:support_list"]], function ($req) {
		Route::match(['get', 'post'], '/healthgennie-supports', "HomeController@supportPatAll")->name("admin.supportPatAll");
		Route::match(['get', 'post'], '/viewSupport', "HomeController@viewSupport")->name("admin.viewSupport");
		Route::match(['get', 'post'], '/sendSupportReply', "HomeController@sendSupportReply")->name("admin.sendSupportReply");
	});
	Route::group(["middleware" => ["check-module-access:subscribe_list"]], function ($req) {
		Route::match(['get', 'post'], '/healthgennie-subcrriptions', "HomeController@subcribedAll")->name("admin.subcribedAll");
	});
	Route::group(["middleware" => ["check-module-access:contact_list"]], function ($req) {
		Route::match(['get', 'post'], '/healthgennie-contacts', "HomeController@contactQuery")->name("admin.contactQuery");
		Route::match(['get', 'post'], '/viewContact', "HomeController@viewContact")->name("admin.viewContact");
	});
	Route::group(["middleware" => ["check-module-access:enquiry_form_list"]], function ($req) {
		Route::match(['get', 'post'], '/enquiry-list', "HomeController@enquiryQuery")->name("admin.enquiryQuery");
	});
	Route::match(['get', 'post'],'/general-feedback-list', "HomeController@generalFeedbackList")->name("admin.generalFeedbackList");
	Route::group(["middleware" => ["check-module-access:corporateLeads_form_list"]], function ($req) {
		Route::match(['get', 'post'],'/corporateleads-list', "HomeController@corporateLeads")->name("admin.corporateLeads");
		Route::match(['get', 'post'],'/other/leads-list', "HomeController@otherCorporateLeads")->name("admin.otherLeads");
		Route::match(['get', 'post'],'/VHTlist', 'HomeController@VHTlist')->name('admin.VHTlist');
	});

	Route::match(['get', 'post'], '/corporate-status', "HomeController@corporateStatus")->name("admin.corporateStatus");
	Route::group(["middleware" => ["check-module-access:doctor_otp"]], function ($req) {
		Route::any('/doctor-one-time-pwd', 'HomeController@otpList')->name('admin.otpList');
	});
	Route::group(["middleware" => ["check-module-access:users_otp"]], function ($req) {
		Route::any('/user-otp', 'HomeController@userOtpList')->name('admin.userOtpList');
	});

	

	/** SpecialitySymptomsMaster  **/
	Route::group(["middleware" => ["check-module-access:symptoms"]], function ($req) {
		Route::match(['get', 'post'], '/symptoms', "SymptomController@SymptomsMaster")->name("symptoms.SymptomsMaster");
		Route::match(['get', 'post'], '/add-symptoms', "SymptomController@addSymptoms")->name("symptoms.addSymptoms");
		Route::match(['get', 'post'], '/edit-symptoms', "SymptomController@editSymptoms")->name("symptoms.editSymptoms");
		Route::match(['get', 'post'], '/update-symptoms', "SymptomController@updateSymptoms")->name("symptoms.updateSymptoms");
		Route::match(['get', 'post'], '/delete-symptoms', "SymptomController@deleteSymptoms")->name("symptoms.deleteSymptoms");
		Route::match(['get', 'post'], '/view-symptoms-ques', "SymptomController@viewSymptomsQuestion")->name("viewSymptomsQuestion");
		Route::match(['get', 'post'], '/quiz-questions', 'SymptomController@quizQuestions')->name('quizQuestions');
		Route::match(['get', 'post'], '/update-quiz-questions', 'SymptomController@updateQuizQuestions')->name('updateQuizQuestions');
		Route::match(['get', 'post'], '/add-quiz-questions', 'SymptomController@addQuizQuestions')->name('addQuizQuestions');
		Route::match(['get', 'post'], '/edit-quiz-questions/{id?}', 'SymptomController@editQuizQuestions')->name('editQuizQuestions');
		Route::match(['get', 'post'], '/quiz-question/update-status', 'SymptomController@updateStatus')->name('quizQuestion.updateStatus');
		Route::match(['get', 'post'], '/quiz-question/excelImport', 'SymptomController@symptomExcelImport')->name('symptomExcelImport');
		Route::match(['get', 'post'], '/assessment-challenges', 'SymptomController@getAssesmentChallenge')->name('getAssesmentChallenge');
		Route::match(['get', 'post'], '/assessment-ans-view', 'SymptomController@assesAnsView')->name('assesAnsView');
		Route::match(['get', 'post'], '/assessment-overview', 'SymptomController@assesOverview')->name('assesOverview');
		Route::match(['get', 'post'], '/update-status-overview', 'SymptomController@updateOverviewStatus')->name('updateOverviewStatus');
		Route::match(['get', 'post'], '/add-assessment-overview', 'SymptomController@addAssesOverview')->name('addAssesOverview');
		Route::match(['get', 'post'], '/edit-assessemt-program/{id?}', 'SymptomController@editAssesOverview')->name('editAssesOverview');
		Route::match(['get', 'post'], '/update-assessment-program', 'SymptomController@updateAssesProgram')->name('updateAssesProgram');
		Route::match(['get', 'post'], '/delete-assessment-program', 'SymptomController@deleteAssesOverview')->name('deleteAssesOverview');
		Route::match(['get', 'post'], '/assessment-program-view', 'SymptomController@viewAssessmentProgram')->name('viewAssessmentProgram');

		Route::match(['get', 'post'], '/sheet-display', 'SymptomController@sheetDisplay')->name('sheetDisplay');
		Route::match(['get', 'post'], '/edit-sheet', 'SymptomController@editSheet')->name('editSheet');
		Route::match(['get', 'post'], '/update-mentalHealth-sheet', 'SymptomController@updateMentalHealthSheet')->name('updateMentalHealthSheet');

		Route::match(['get', 'post'], '/insightful-tracker', "SymptomController@insightfulTracker")->name("insightfulTracker");
		Route::match(['get', 'post'], '/journling', "SymptomController@Journling")->name("Journling");
		Route::match(['get', 'post'], '/activity-sheet', "SymptomController@activitySheet")->name("activitySheet");
		Route::match(['get', 'post'], '/view-sessions', "SymptomController@viewSessions")->name("viewSessions");


		Route::match(['get', 'post'], '/mental-records', 'SymptomController@getMentalhealthList')->name('getMentalhealthList');
		Route::match(['get', 'post'], '/mental-report/{id?}', 'SymptomController@getMentalhealthReport')->name('getMentalhealthReport');


		Route::match(['get', 'post'], '/mental-health-ques/{id?}', 'SymptomController@mentalHealthQues')->name('mentalHealthQues');
		Route::match(['get', 'post'], '/mental-health-ques-edit/{id?}', "SymptomController@editMentalHealthQues")->name("editMentalHealthQues");
		Route::match(['get', 'post'], '/final-edit-MHQ', "SymptomController@finalEditMHQ")->name("finalEditMHQ");
		Route::match(['get', 'post'], '/update-Mental-Health-Ques', "SymptomController@updateMentalHealthQues")->name("updateMentalHealthQues");
		Route::match(['get', 'post'], '/view-Mental-Health-Ques', "SymptomController@viewMentalHealthQues")->name("viewMentalHealthQues");
	});
	Route::group(["middleware" => ["check-module-access:user_notification"]], function ($req) {
		Route::match(['get', 'post'], '/notifications', "HomeController@notificationMaster")->name("admin.notificationMaster");
	});
	/** Patient Portal App Blogs Master  **/
	Route::group(["middleware" => ["check-module-access:app_blog"]], function ($req) {
		Route::match(['get', 'post'], '/blogs', "BlogsController@blogMaster")->name("admin.blogMaster");
		Route::match(['get', 'post'], '/add-blogs', "BlogsController@addBlog")->name("admin.addBlog");
		Route::match(['get', 'post'], '/edit-blogs', "BlogsController@editBlog")->name("admin.editBlog");
		Route::match(['get', 'post'], '/view-blog/{id}', "BlogsController@viewBlog")->name("admin.viewBlog");
		Route::match(['get', 'post'], '/update-blogs', "BlogsController@updateBlog")->name("admin.updateBlog");
		Route::match(['get', 'post'], '/delete-blogs', "BlogsController@deleteBlog")->name("admin.deleteBlog");
		Route::match(['get', 'post'], '/blog-comments', "BlogsController@blogComments")->name("admin.blogComments");
		Route::match(['get', 'post'], '/blogCommentPublish', "BlogsController@blogCommentPublish")->name("admin.blogCommentPublish");
	});

	/** Patient Portal Localities manage  **/
	Route::group(["middleware" => ["check-module-access:city_locality"]], function ($req) {
		Route::match(['get', 'post'], '/locality-manage-doctor', "HomeController@doctorsListForLocality")->name("admin.doctorsListForLocality");
		Route::match(['get', 'post'], '/localities', "LocalityController@localityMaster")->name("admin.localityMaster");
		Route::match(['get', 'post'], '/add-locality', "LocalityController@addLocality")->name("admin.addLocality");
		Route::match(['get', 'post'], '/edit-locality', "LocalityController@editLocality")->name("admin.editLocality");
		Route::match(['get', 'post'], '/update-locality', "LocalityController@updateLocality")->name("admin.updateLocality");
		Route::match(['get', 'post'], '/update-locality-top-status', "LocalityController@updateLocalityStatusTop")->name("admin.updateLocalityStatusTop");
		Route::match(['get', 'post'], '/delete-locality', "LocalityController@deleteLocality")->name("admin.deleteLocality");
	});

	/** Patient Portal Offer Banners manage  **/
	Route::group(["middleware" => ["check-module-access:offer_banners"]], function ($req) {
		Route::match(['get', 'post'], '/offers-banner', "BannersController@offersBannerMaster")->name("admin.offersBannerMaster");
		Route::match(['get', 'post'], '/add-offers-banner', "BannersController@addOffersBanner")->name("admin.addOffersBanner");
		Route::match(['get', 'post'], '/edit-offers-banner', "BannersController@editOffersBanner")->name("admin.editOffersBanner");
		Route::match(['get', 'post'], '/update-offers-banner', "BannersController@updateOffersBanner")->name("admin.updateOffersBanner");
		Route::match(['get', 'post'], '/delete-offers-banner', "BannersController@deleteOffersBanner")->name("admin.deleteOffersBanner");
	});
	/** Patient Portal Ad Banner manage  **/
	Route::group(["middleware" => ["check-module-access:advertisement_banner"]], function ($req) {
		Route::match(['get', 'post'], '/ad-banner', "BannersController@adBannerMaster")->name("admin.adBannerMaster");
		Route::match(['get', 'post'], '/add-adv-banner', "BannersController@addAdBanner")->name("admin.addAdBanner");
		Route::match(['get', 'post'], '/edit-adv-banner', "BannersController@editAdBanner")->name("admin.editAdBanner");
		Route::match(['get', 'post'], '/update-adv-banner', "BannersController@updateAdBanner")->name("admin.updateAdBanner");
		Route::match(['get', 'post'], '/delete-adv-banner', "BannersController@deleteAdBanner")->name("admin.deleteAdBanner");
	});

	/** Patient Portal appointments  **/
	Route::group(["middleware" => ["check-module-access:appointment"]], function ($req) {
		Route::match(['get', 'post'], '/healthgennie-appointments', "AppointmentController@hgAppointments")->name("admin.hgAppointments");
		Route::match(['get', 'post'], '/update-appointment', "AppointmentController@updateAppointments")->name("admin.updateAppointments");
		Route::match(['get', 'post'], '/ChangeWorkingStatus', "AppointmentController@ChangeWorkingStatus")->name("admin.ChangeWorkingStatus");
		Route::match(['get', 'post'], '/RefundOrder', "AppointmentController@RefundOrder")->name("admin.RefundOrder");
		Route::match(['get', 'post'], '/switchAppointment', "AppointmentController@switchAppointment")->name("admin.switchAppointment");
		Route::match(['get', 'post'], '/appointmentRating', "AppointmentController@appointmentRating")->name("admin.appointmentRating");
		Route::match(['get', 'post'], '/showAppts', "AppointmentController@showAppts")->name("admin.showAppts");
		Route::match(['get', 'post'], '/sendPres', "AppointmentController@sendPres")->name("admin.sendPres");
		Route::match(['get', 'post'], '/sendPresToPharmacy', "AppointmentController@sendPresToPharmacy")->name("admin.sendPresToPharmacy");
		Route::post('/cahngeStatus', "AppointmentController@cahngeStatus")->name("admin.cahngeStatus");
		Route::post('/showPrescription', "AppointmentController@showPrescription")->name("admin.showPrescription");
		Route::post('/UploadedDocs', "AppointmentController@UploadedDocs")->name("admin.UploadedDocs");
		Route::post('/send-sms-due-appt', "AppointmentController@sendsmsAppt")->name("admin.sendsmsAppt");
		Route::post('/save-appt-info', "AppointmentController@saveApptInfo")->name("admin.saveApptInfo");
		Route::match(['get', 'post'], '/reopen/user-prescription', "AppointmentController@reopenPrescription")->name("admin.reopenPrescription");

		Route::match(['get', 'post'], '/bulk-export-csv', 'AppointmentController@bulkExportCsv')->name('admin.bulkExportCsv');
	});
	//coupon master settings
	Route::group(["middleware" => ["check-module-access:coupan_manager"]], function ($req) {
		Route::match(['get', 'post'], '/couponMaster', 'CouponController@couponMaster')->name('admin.couponMaster');
		Route::match(['get', 'post'], '/couponMasterAdd', 'CouponController@couponMasterAdd')->name('admin.couponMasterAdd');
		Route::match(['get', 'post'], '/addCouponMaster', 'CouponController@addCouponMaster')->name('admin.addCouponMaster');
		Route::match(['get', 'post'], '/updateCouponsMaster', 'CouponController@updateCouponsMaster')->name('admin.updateCouponsMaster');
		Route::match(['get', 'post'], '/editCoupons/{id?}', 'CouponController@editCoupons')->name('admin.editCoupons');
		Route::match(['get', 'post'], '/deleteCouponMaster/{id?}', 'CouponController@deleteCouponMaster')->name('admin.deleteCouponMaster');;
		Route::match(['get', 'post'], '/updateCouponStatus', 'CouponController@updateCouponStatus')->name('admin.updateCouponStatus');
	});
	//Order List
	Route::group(["middleware" => ["check-module-access:lab"]], function ($req) {
		Route::any('/ViewCartAPI', 'LabController@ViewCartAPI')->name('admin.ViewCartAPI');
		Route::any('/createLabOrder', 'LabController@createLabOrder')->name('admin.createLabOrder');
		Route::any('/createLabdefaultOrder', 'LabController@createLabdefaultOrder')->name('admin.createLabdefaultOrder');
		Route::any('/GetAppointmentSlots', 'LabController@GetAppointmentSlots')->name('admin.GetAppointmentSlots');
		Route::get('/getlab-packages/{cmp_id?}', "LabController@getLabsPackage")->name("admin.getLabsPackage");
		Route::any('/get-default-lab', "LabController@getDefLabs")->name("admin.getDefLabs");
		Route::any('/checkPincodeAvailability', 'LabController@checkPincodeAvailability')->name('admin.checkPincodeAvailability');
		Route::get('/lab-pay-order/{orderId}', "LabController@labPaymentforlink")->name('admin.labPayment');
		Route::post('/chnagepayStatus', "LabController@chnagepayStatus")->name("admin.chnagepayStatus");
		Route::match(['get', 'post'], '/changeCompanyStatus', 'LabController@changeCompanyStatus')->name('admin.changeCompanyStatus');
		Route::match(['get', 'post'], '/lab-orders', 'LabController@labOrders')->name('admin.labOrders');
		Route::match(['get', 'post'], '/laborder-import', "LabController@labOrderImport")->name("admin.labOrderImport");
		Route::match(['get', 'post'], '/orgIdupdate', "LabController@orgIdupdate")->name("admin.orgIdupdate");
		Route::match(['get', 'post'], '/update-lab-price', "LabController@updateLabCompanyPrice")->name("admin.updateLabCompanyPrice");
		Route::match(['get', 'post'], '/lab-company-price', "LabController@LabCompanyPrice")->name("admin.LabCompanyPrice");
		Route::match(['get', 'post'], '/lab-price-import', "LabController@uploadLabPrice")->name("admin.labPriceImport");
		Route::match(['get', 'post'], '/lab-price', "LabController@addNewLabPrice")->name("admin.addNewLabPrice");
		Route::match(['get', 'post'], '/viewOrderDetails', 'LabController@viewOrderDetails')->name('admin.viewOrderDetails');
		Route::match(['get', 'post'], '/deleteOrder', "LabController@deleteOrder")->name("admin.deleteOrder");
		Route::match(['get', 'post'], '/download-lab-bill/{id}', 'LabController@downloadLabBill')->name('downloadLabBill');
		Route::match(['get', 'post'], '/lab-requests', "LabMasterController@labRequests")->name("admin.labRequests.index");
		Route::match(['get', 'post'], '/update-note', "LabController@updateNote")->name("admin.updateNote");

		Route::match(['get', 'post'], '/default-labs', "LabMasterController@defaultLab")->name("admin.defaultLab.index");
		Route::match(['get', 'post'], '/default-labs/status-update', "LabMasterController@defaultLabupdateStatus")->name("defaultLab.status");
		Route::match(['get', 'post'], '/create-default-lab', "LabMasterController@create")->name("admin.defLab.create");
		Route::match(['get', 'post'], '/edit-default-lab', "LabMasterController@edit")->name("admin.defLab.edit");
		Route::match(['get', 'post'], '/delete-default-lab', "LabMasterController@delete")->name("admin.defLab.delete");
		Route::match(['get', 'post'], '/update-default-lab', 'LabMasterController@update')->name('admin.defLab.update');

		Route::match(['get', 'post'], '/labs-collection', "LabMasterController@labCollection")->name("admin.labCollection.index");
		Route::match(['get', 'post'], '/labs-collection/update-status', "LabMasterController@labCollectionstatus")->name("admin.labCollection.status");
		Route::match(['get', 'post'], '/create-lab-collection', "LabMasterController@createLabCollection")->name("admin.labCollection.create");
		Route::match(['get', 'post'], '/edit-lab-collection', "LabMasterController@editLabCollection")->name("admin.labCollection.edit");
		Route::match(['get', 'post'], '/delete-lab-collection', "LabMasterController@deleteLabCollection")->name("admin.labCollection.delete");
		Route::match(['get', 'post'], '/update-lab-collection', 'LabMasterController@updateLabCollection')->name('admin.labCollection.update');
		/** Patient Thyrocare Package manage  **/
		Route::match(['get', 'post'], '/thyrocare-packages', "ThyrocarePackageController@thyrocarePackageMaster")->name("admin.thyrocarePackageMaster");
		Route::match(['get', 'post'], '/add-thyrocare-package', "ThyrocarePackageController@addThyrocarePackage")->name("admin.addThyrocarePackage");
		Route::match(['get', 'post'], '/edit-thyrocare-package', "ThyrocarePackageController@editThyrocarePackage")->name("admin.editThyrocarePackage");
		Route::match(['get', 'post'], '/update-thyrocare-package', "ThyrocarePackageController@updateThyrocarePackage")->name("admin.updateThyrocarePackage");
		Route::match(['get', 'post'], '/delete-thyrocare-package', "ThyrocarePackageController@deleteThyrocarePackage")->name("admin.deleteThyrocarePackage");

		Route::match(['get', 'post'], '/lab-package', "LabMasterController@labPackage")->name("admin.labPackage.index");
		Route::match(['get', 'post'], '/update-lab-package-Status', "LabMasterController@updateLabPackSts")->name("lab.updateLabPackSts");
		Route::match(['get', 'post'], '/create-lab-package', "LabMasterController@createLabPackage")->name("admin.labPackage.create");
		Route::match(['get', 'post'], '/edit-lab-package', "LabMasterController@editLabPackage")->name("admin.labPackage.edit");
		Route::match(['get', 'post'], '/delete-lab-package', "LabMasterController@deleteLabPackage")->name("admin.labPackage.delete");
		Route::match(['get', 'post'], '/update-lab-package', 'LabMasterController@updateLabPackage')->name('admin.labPackage.update');
		Route::match(['get', 'post'], '/update-lab-status', 'LabMasterController@changeOrderStatus')->name('admin.changeOrderStatus');
		Route::match(['get', 'post'], '/update-lab-paymentMode', 'LabMasterController@changePaymentMode')->name('admin.changePaymentMode');
		Route::match(['get', 'post'], '/change-labReq-status', 'LabMasterController@changeLabReqSts')->name('admin.changeLabReqSts');
		Route::match(['get', 'post'], '/upload-report', 'LabMasterController@uploadReport')->name('admin.uploadReport');
		Route::match(['get', 'post'], '/upload-orgin-report', 'LabMasterController@uploadoriginReport')->name('admin.uploadoriginReport');

		//------------------------sher khan Route Start--------------------------------------//
		Route::match(['get', 'post'], '/lab/company', "LabMasterController@getlabcompany")->name("lab.company");
		Route::match(['get', 'post'], '/lab-company/update-status', "LabMasterController@updateLabcompanySts")->name("company.status.update");
		Route::match(['get', 'post'], '/add/company', "LabMasterController@createLabcompany")->name("admin.addCompany");
		Route::match(['get', 'post'], '/delete/company', "LabMasterController@deleteLabCompany")->name("admin.LabCompany.delete");
		Route::match(['get', 'post'], '/edit/company', "LabMasterController@editLabCompany")->name("admin.LabCompany.edit");
		Route::match(['get', 'post'], '/update/company', "LabMasterController@updateLabCompany")->name("admin.LabCompany.update");
		Route::match(['get', 'post'], '/lab/company/pin', "LabMasterController@getcomapnypin")->name("lab.company.pin");
		Route::match(['get', 'post'], '/create/company/pin', "LabMasterController@createLabcompanypin")->name("create.company.pin");
		Route::match(['get', 'post'], '/delete/company/pin', "LabMasterController@deleteLabCompanypin")->name("admin.LabCompany.delete.pin");
		Route::match(['get', 'post'], '/edit/company/pin', "LabMasterController@editLabCompanypin")->name("admin.LabCompanypin.edit");
		Route::match(['get', 'post'], '/update-lab-package/pin', 'LabMasterController@updateLabcompanypin')->name('admin.labPackage.pin.update');
		Route::match(['get', 'post'], '/update-lab-package/pin', 'LabMasterController@updateLabcompanypin')->name('admin.labPackage.pin.update');
	});

	/** Organization Master  **/
	Route::group(["middleware" => ["check-module-access:organization_master"]], function ($req) {
		Route::match(['get', 'post'], '/organization-master', "SettingsController@organizationMaster")->name("admin.organizationMaster");
		Route::match(['get', 'post'], '/add-organization', "SettingsController@addOrganization")->name("admin.addOrganization");
		Route::match(['get', 'post'], '/edit-organization', "SettingsController@editOrganization")->name("admin.editOrganization");
		Route::match(['get', 'post'], '/modifyOrganization', 'SettingsController@modifyOrganization')->name('admin.modifyOrganization');
		Route::match(['get', 'post'], '/viewOrgPay', 'SettingsController@viewOrgPay')->name('admin.viewOrgPay');
		Route::match(['get', 'post'], '/addNewPay', 'SettingsController@addNewPay')->name('admin.addNewPay');
	});
	/** slider Master  **/
	Route::group(["middleware" => ["check-module-access:slider_master"]], function ($req) {
		Route::match(['get', 'post'], '/slider-master', "SettingsController@sliderMaster")->name("admin.sliderMaster");
		Route::match(['get', 'post'], '/add-slider', "SettingsController@addSlider")->name("admin.addSlider");
		Route::match(['get', 'post'], '/edit-slider', "SettingsController@editSlider")->name("admin.editSlider");
		Route::match(['get', 'post'], '/modifySliderMaster', 'SettingsController@modifySliderMaster')->name('admin.modifySliderMaster');
	});
	/** Referral Master  **/
	Route::group(["middleware" => ["check-module-access:referral_code_master"]], function ($req) {
		Route::match(['get', 'post'], '/referral-master', "SettingsController@referralMaster")->name("admin.referralMaster");
		Route::match(['get', 'post'], '/add-referral', "SettingsController@addReferral")->name("admin.addReferral");
		Route::match(['get', 'post'], '/edit-referral/{id?}', "SettingsController@editReferral")->name("admin.editReferral");
		Route::match(['get', 'post'], '/modifyReferralMaster', 'SettingsController@modifyReferralMaster')->name('admin.modifyReferralMaster');
		Route::match(['get', 'post'], '/updateReferralsMaster', 'SettingsController@updateReferralsMaster')->name('admin.updateReferralsMaster');
		Route::match(['get', 'post'], '/deleteReferralMaster/{id?}', 'SettingsController@deleteReferralMaster')->name('admin.deleteReferralMaster');
		Route::match(['get', 'post'], '/updateReferralStatus', 'SettingsController@updateReferralStatus')->name('admin.updateReferralStatus');
	});
	Route::post('/accept-call', 'SettingsController@acceptCall');
	Route::post('/reject-call', 'SettingsController@rejectCall');
	Route::post('/initiate-call', 'SettingsController@initiateCall');

	Route::group(["middleware" => ["check-module-access:au_marathon_registrations"]], function ($req) {
		Route::match(['get', 'post'], '/au-marathon-registrations', 'SettingsController@AuMarathonReg')->name('admin.AuMarathonReg');
	});
	Route::group(["middleware" => ["check-module-access:vaccination_drive"]], function ($req) {
		Route::match(['get', 'post'], '/vaccination-drive', 'HomeController@vaccinationDrive')->name('admin.vaccinationDrive');
		Route::match(['get', 'post'], '/modifyVaccDriveStatus', 'HomeController@modifyVaccDriveStatus')->name('admin.modifyVaccDriveStatus');
	});
	Route::group(["middleware" => ["check-module-access:runners_lead"]], function ($req) {
		Route::match(['get', 'post'], '/runners-leads', 'HomeController@runnersLeads')->name('admin.runnersLeads');
	});
	Route::group(["middleware" => ["check-module-access:speciality_manager"]], function ($req) {
		Route::match(['get', 'post'], '/specialityGroupMaster', 'SettingsController@specialityGroupMaster')->name('admin.specialityGroupMaster');
		Route::match(['get', 'post'], '/updateGroupSpeciality', 'SettingsController@updateGroupSpeciality')->name('admin.updateGroupSpeciality');
		Route::match(['get', 'post'], '/addGroupSpeciality', 'SettingsController@addGroupSpeciality')->name('admin.addGroupSpeciality');
		Route::match(['get', 'post'], '/editGroupSpeciality/{id?}', 'SettingsController@editGroupSpeciality')->name('admin.editGroupSpeciality');
		Route::match(['get', 'post'], '/updateTshirtStatus', 'SettingsController@updateTshirtStatus')->name('admin.updateTshirtStatus');

		Route::match(['get', 'post'], '/doctor-speciality', "SettingsController@specialityAll")->name("admin.specialityAll");
		Route::match(['get', 'post'], '/updateSpeciality', 'SettingsController@updateSpeciality')->name('admin.updateSpeciality');
		Route::match(['get', 'post'], '/addSpeciality', 'SettingsController@addSpeciality')->name('admin.addSpeciality');
		Route::match(['get', 'post'], '/editSpeciality/{id?}', 'SettingsController@editSpeciality')->name('admin.editSpeciality');
	});
	//Dynamic Pages
	Route::group(["middleware" => ["check-module-access:pages_list"]], function ($req) {
		Route::match(['get', 'post'], '/AddDynamicPage', 'SettingsController@AddDynamicPage')->name('admin.AddDynamicPage');
		Route::match(['get', 'post'], '/pages-list', 'SettingsController@PagesList')->name('admin.PagesList');
		Route::match(['get', 'post'], '/editPageContent', 'SettingsController@editPageContent')->name('admin.editPageContent');
		Route::match(['get', 'post'], '/updatePageContent', 'SettingsController@updatePageContent')->name('admin.updatePageContent');
	});
	Route::group(["middleware" => ["check-module-access:hos_bed_availability"]], function ($req) {
		Route::match(['get', 'post'], '/AddHosPage', 'SettingsController@AddHosPage')->name('admin.AddHosPage');
		Route::match(['get', 'post'], '/hospital-list', 'SettingsController@HosBedList')->name('admin.HosBedList');
		Route::match(['get', 'post'], '/editHosBed', 'SettingsController@editHosBed')->name('admin.editHosBed');
		Route::match(['get', 'post'], '/updateHospitalContent', 'SettingsController@updateHospitalContent')->name('admin.updateHospitalContent');
	});
	//Settings
	Route::group(["middleware" => ["check-module-access:settings"]], function ($req) {
		Route::match(['get', 'post'], '/user-permission', 'SettingsController@UserPermission')->name('admin.UserPermission');
		Route::match(['get', 'post'], '/LoadUserPermission', 'SettingsController@LoadUserPermission')->name('admin.LoadUserPermission');
		Route::match(['get', 'post'], '/add-subadmin', 'SettingsController@addSubAdmin')->name('admin.addSubAdmin');
		Route::match(['get', 'post'], '/subadmin-list', 'SettingsController@subadminList')->name('admin.subadminList');
		Route::match(['get', 'post'], '/editSubAdmin', 'SettingsController@editSubAdmin')->name('admin.editSubAdmin');
		Route::match(['get', 'post'], '/modifySubAdmin', 'SettingsController@modifySubAdmin')->name('admin.modifySubAdmin');

		Route::match(['get', 'post'], '/addServicesMaster', "SettingsController@addServicesMaster")->name("admin.addServicesMaster");
		Route::match(['get', 'post'], '/viewServicesMaster/{id}', "SettingsController@viewServicesMaster")->name("admin.viewServicesMaster");
		Route::match(['get', 'post'], '/editServices', "SettingsController@editServices")->name("admin.editServices");
		Route::match(['get', 'post'], '/modifyServices', 'SettingsController@modifyServices')->name('admin.modifyServices');
	});
	Route::match(['get', 'post'], '/edit-profile', 'SettingsController@editProfile')->name('admin.editProfile');
	Route::match(['get', 'post'], '/update-profile', 'SettingsController@updateProfile')->name('admin.updateProfile');
	Route::group(["middleware" => ["check-module-access:service_master"]], function ($req) {
		Route::match(['get', 'post'], '/services-master', "SettingsController@servicesMaster")->name("admin.servicesMaster");
	});
	//plan master settings
	Route::group(["middleware" => ["check-module-access:plan_manager"]], function ($req) {
		Route::match(['get', 'post'], '/planMaster', 'SubscriptionController@planMaster')->name('plans.planMaster');
		Route::match(['get', 'post'], '/planMasterAdd', 'SubscriptionController@planMasterAdd')->name('plans.planMasterAdd');
		Route::match(['get', 'post'], '/updatePlansMaster', 'SubscriptionController@updatePlansMaster')->name('plans.updatePlansMaster');
		Route::match(['get', 'post'], '/editPlans', 'SubscriptionController@editPlans')->name('plans.editPlans');
		Route::match(['get', 'post'], '/deletePlanMaster', 'SubscriptionController@deletePlanMaster')->name('plans.deletePlanMaster');
		Route::match(['get', 'post'], '/updatePlanStatus', 'SubscriptionController@updatePlanStatus')->name('plans.updatePlanStatus');
	});

	//Subscription master settings
	Route::group(["middleware" => ["check-module-access:subscription_manager"]], function ($req) {
		Route::match(['get', 'post'], '/subscription-by-location', "SubscriptionController@subscriptionByLocation")->name("admin.subscriptionByLocation");
		Route::match(['get', 'post'], '/addSubNote', "SubscriptionController@addSubNote")->name("admin.addSubNote");

		Route::match(['get', 'post'], '/subscriptionMaster', 'SubscriptionController@subscriptionMaster')->name('subscription.subscriptionMaster');
		Route::match(['get', 'post'], '/updateSubscription', 'SubscriptionController@updateSubscription')->name('subscription.updateSubscription');
		Route::match(['get', 'post'], '/editSubscription', 'SubscriptionController@editSubscription')->name('subscription.editSubscription');
		Route::match(['get', 'post'], '/deleteSubscription', 'SubscriptionController@deleteSubscription')->name('subscription.deleteSubscription');
		Route::match(['get', 'post'], '/changePlanPeriodStatus', 'SubscriptionController@changePlanPeriodStatus')->name('subscription.changePlanPeriodStatus');
		Route::match(['get', 'post'], '/subscription-cashback', 'SubscriptionController@subscriptionCashback')->name('subscription.cashback');
		Route::match(['get', 'post'], '/subscription-cashback/status/update', 'SubscriptionController@subscriptionCashbackStatus')->name('admin.cashbackStatus');
		Route::post('/contact-vpa', 'SubscriptionController@createContact')->name('admin.contactVPA');
	});
	/** Camp Master  **/
	Route::group(["middleware" => ["check-module-access:camp_master"]], function ($req) {
		Route::match(['get', 'post'], '/camp-data', "SettingsController@campMaster")->name("admin.campMaster");
		Route::match(['get', 'post'], '/add-camp-data', "SettingsController@addCamp")->name("admin.addCamp");
		Route::match(['get', 'post'], '/edit-camp-data', "SettingsController@editCamp")->name("admin.editCamp");
		Route::match(['get', 'post'], '/update-camp-data', "SettingsController@updateCamp")->name("admin.updateCamp");
	});
	Route::group(["middleware" => ["check-module-access:health_question_master"]], function ($req) {
		Route::match(['get', 'post'], '/hq-master', "CommonController@hQMaster")->name("admin.hQMaster");
		Route::match(['get', 'post'], '/add-question', "CommonController@addQuestion")->name("admin.addQuestion");
		Route::match(['get', 'post'], '/edit-question', "CommonController@editQuestion")->name("admin.editQuestion");
		Route::match(['get', 'post'], '/update-question', "CommonController@updateQuestion")->name("admin.updateQuestion");
		Route::match(['get', 'post'], '/change-hQsts', 'CommonController@stsQuestion')->name('admin.stsQuestion');
		Route::match(['get', 'post'], '/deleteQuestion', 'CommonController@deleteQuestion')->name('admin.deleteQuestion');
		Route::match(['get', 'post'], '/getQuestions', 'CommonController@getQuestions')->name('admin.getQuestions');
		Route::match(['get', 'post'], '/assesment-master', 'CommonController@assesmentMaster')->name('admin.assesmentMaster');
		Route::match(['get', 'post'], '/wmh-master', 'CommonController@wmhMaster')->name('admin.wmhMaster');
	});
	Route::group(["middleware" => ["check-module-access:medicine_master"]], function ($req) {
		Route::match(['get', 'post'], '/medicine-master', "CommonController@medicineMaster")->name("admin.medicineMaster");
		Route::match(['get', 'post'], '/add-medicine', "CommonController@addMedicine")->name("admin.addMedicine");
		Route::match(['get', 'post'], '/createMedicine', "CommonController@createMedicine")->name("admin.createMedicine");
		Route::match(['get', 'post'], '/editMedicine/{id}', "CommonController@editMedicine")->name("admin.editMedicine");
		Route::match(['get', 'post'], '/modifyMedicine', 'CommonController@modifyMedicine')->name('admin.modifyMedicine');
		Route::match(['get', 'post'], '/searchMedicine', 'CommonController@searchMedicine')->name('admin.searchMedicine');
	});

	Route::group(["middleware" => ["check-module-access:paytm_orders"]], function ($req) {
		Route::match(['get', 'post'], '/ptm-order', "CommonController@paytmOrders")->name("admin.paytmOrders");
	});
	Route::group(["middleware" => ["check-module-access:user_data_list"]], function ($req) {
		Route::match(['get', 'post'], '/user-data-list', "HomeController@userDataList")->name("admin.userDataList");
		Route::match(['get', 'post'], '/reg-primary-user', "HomeController@regPrimaryUsers")->name("admin.regPrimaryUsers");
		Route::match(['get', 'post'], '/user-data-import', "HomeController@userDataExcelImport")->name("admin.userDataExcelImport");
		Route::match(['get', 'post'], '/new-user', "HomeController@newUserData")->name("admin.newUserData");
	});

	// Attandence Reports Route

	Route::group(["middleware" => ["check-module-access:attendance"]], function ($req) {
		Route::match(['get', 'post'], '/add-leave', "AttendanceController@addNoOfLeave")->name("admin.noOfLeave");
		Route::match(['get', 'post'], '/attendance-list', "AttendanceController@attendanceList")->name("admin.attendanceList");
		Route::match(['get', 'post'], '/attendance-admin-list', "AttendanceController@attendanceAdminList")->name("admin.attendanceAdminList");
		Route::match(['get', 'post'], '/dashboard-attendance-list', "AttendanceController@dashboardAttendanceList")->name("admin.dashboardAttendanceList");
		Route::match(['get', 'post'], '/leave-request-admin-list', "AttendanceController@leaveRequestAdminList")->name("admin.leaveRequestAdminList");
		Route::match(['get', 'post'], '/leave-request-list', "AttendanceController@leaveRequestList")->name("admin.leaveRequestList");
		Route::match(['get', 'post'], '/attendance-added', "AttendanceController@storeAttendance")->name("admin.storeAttendance");
		Route::match(['get', 'post'], '/leave-request-added', "AttendanceController@storeLeaveRequest")->name("admin.storeLeaveRequest");
		Route::match(['get', 'post'], '/attendance-edit', "AttendanceController@editAttendance")->name("admin.editAttendance");
		Route::match(['get', 'post'], '/leave-request-update-status', "AttendanceController@leaveUpdate")->name("admin.leaveUpdate");
	

		//career master settings
Route::match(['get', 'post'],'/career-management', 'CareerController@careerManage')->name('careerManage');
Route::match(['get', 'post'],'/add-category', 'CareerController@addCategory')->name('addCategory');
Route::match(['get', 'post'],'/editCategory/{id}', 'CareerController@editCategory')->name('editCategory');
Route::match(['get', 'post'],'/deleteCategory/{id}', 'CareerController@deleteCategory')->name('deleteCategory');
Route::match(['get', 'post'],'/updateCatStatus', 'CareerController@updateCatStatus')->name('updateCatStatus');

Route::match(['get', 'post'],'/job-management', 'CareerController@jobManage')->name('jobManage');
Route::match(['get', 'post'],'/add-new-job-details', 'CareerController@addNewJob')->name('addNewJob');
Route::match(['get', 'post'],'/editJob/{id}', 'CareerController@editJob')->name('editJob');
Route::match(['get', 'post'],'/deleteJob/{id}', 'CareerController@deleteJob')->name('deleteJob');

Route::match(['get', 'post'],'/Job-application', 'CareerController@JobApplications')->name('JobApplications');
Route::match(['get', 'post'],'/viewJobApplication/{id}', 'CareerController@viewJobApplication')->name('viewJobApplication');
Route::match(['get', 'post'],'/deleteJobApplication/{id}', 'CareerController@deleteJobApplication')->name('deleteJobApplication');

	
	});
});

//Search ctrl routes
Route::get('/{city}', 'searchController@getDataByCity')->name('getDataByCity');
Route::get('/{city}/doctors', 'searchController@findAllDoctorsByCity')->name('findAllDoctorsByCity');
Route::get('/{city}/clinics', 'searchController@findAllClinicsByCity')->name('findAllClinicsByCity');
Route::get('/{city}/hospitals', 'searchController@findAllHospitalsByCity')->name('findAllHospitalsByCity');
Route::get('/{city}/{type?}', 'searchController@findDoctorCityByType')->name('findDoctorCityByType');
Route::get('/{city}/{type?}/{name?}', 'searchController@findDoctorCityByType')->name('findDoctorLocalityByType');
