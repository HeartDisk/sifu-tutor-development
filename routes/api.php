<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TutorAPIController;
use App\Http\Controllers\ParentAPIController;


Route::get('/get-token/{id}', [App\Http\Controllers\APIController::class, 'getParentToken'])->name('getParentToken');

Route::get('/getSubjects', [App\Http\Controllers\APIController::class, 'getSubjects'])->name('getSubjects');

Route::get('/getCategories', [App\Http\Controllers\APIController::class, 'getCategories'])->name('getCategories');

Route::get('/getStates', [App\Http\Controllers\APIController::class, 'getStates'])->name('getStates');

Route::get('/getCities', [App\Http\Controllers\APIController::class, 'getCities'])->name('getCities');

Route::get('/getCitiesByState/{id}', [App\Http\Controllers\APIController::class, 'citiesByState'])->name('citiesByState');

#Parent API's Routes
Route::group(['prefix' => 'parent'], function () {

    Route::post('/verify-payment', [ParentAPIController::class,"paymentVerification"]);
    Route::post('/login', [ParentAPIController::class,"parentLogin"]);
    Route::post('/register-parent-profile', [ParentAPIController::class, 'registerParentProfile'])->name('registerParentProfile');
    Route::post('/update-parent-profile', [ParentAPIController::class, 'updateParentProfile'])->name('updateParentProfile');
    Route::get('/parent-students', [ParentAPIController::class, 'parentStudents'])->name('parentStudents');
    Route::get('/student-details', [ParentAPIController::class, 'studentsDetails'])->name('studentsDetails');
    Route::get('/speical-needs', [ParentAPIController::class, 'specialNeeds'])->name('specialNeeds');
    Route::get('/parent-details', [ParentAPIController::class, 'getParentDetailByID'])->name('getParentDetailByID');
    Route::get('/getJobTicketDetails', [ParentAPIController::class, 'jobTicketDetails'])->name('ticketAPI');
    Route::post('/verification-code', [ParentAPIController::class, 'verificationCode'])->name('verificationCode');
    Route::post('/store-devicetoken', [ParentAPIController::class, 'StoreDeviceToken'])->name('StoreDeviceToken');
    Route::post('/store-student', [ParentAPIController::class, 'storeStudent'])->name('storeStudent');
    Route::post('/submit-ticket', [ParentAPIController::class, 'submitTicket'])->name('submitTicket');
    Route::post('/get-job-ticket-price', [ParentAPIController::class, 'getJobTicketEstimation'])->name('getJobTicketEstimation');
    Route::post('/getTicketSummary', [ParentAPIController::class, 'getTicketSummary'])->name('getTicketSummary');
    Route::get('/tutor-attendance', [ParentAPIController::class, 'tutorAttendance'])->name('tutorAttendance');
    Route::get('/tutor-requests', [ParentAPIController::class, 'tutorRequests'])->name('tutorRequests');
    Route::get('/tutor-request-details', [ParentAPIController::class, 'tutorRequestDetails'])->name('tutorRequestDetails');
    Route::get('/getStates', [ParentAPIController::class, 'getStates'])->name('getStates');
    Route::get('/getCitiesByState', [ParentAPIController::class, 'getCities'])->name('getCities');
    Route::get('/class-schedules', [ParentAPIController::class, 'getClassSchedules'])->name('getClassSchedules');
    Route::get('/due-invoices', [ParentAPIController::class, 'getDueInvoices'])->name('getDueInvoices');
    Route::get('/get-upcoming-classes', [ParentAPIController::class, 'getUpcomingClasses'])->name('getUpcomingClasses');
    Route::get('/get-today-classes', [ParentAPIController::class, 'getTodayClasses'])->name('getTodayClasses');
    Route::get('/approve-attendance', [ParentAPIController::class, 'approveAttendance'])->name('approveAttendance');
    Route::get('/reject-attendance', [ParentAPIController::class, 'rejectAttendance'])->name('rejectAttendance');
    Route::get('/news', [ParentAPIController::class, 'news'])->name('news');
    Route::get('/detailedNews', [ParentAPIController::class, 'detailedNews']);
    Route::post('/newsStatusUpdate', [ParentAPIController::class, 'newsStatusUpdate']);
    Route::get('/notifications', [ParentAPIController::class, 'notifications'])->name('notifications');
    Route::get('/detailedNotifications', [ParentAPIController::class, 'detailedNotifications']);
    Route::post('/notificationsStatusUpdate', [ParentAPIController::class, 'notificationsStatusUpdate']);
    Route::post('/pay-commitment-fee', [ParentAPIController::class, 'payCommitmentFee'])->name('payCommitmentFee');
    Route::get('/faqs', [ParentAPIController::class, 'faqs']);
    Route::get('/policies', [ParentAPIController::class, 'policies']);
    Route::post('/submit-class-schedule', [ParentAPIController::class, 'submitClassSchedulesAdmin']);
    Route::post('/save-payment-info', [ParentAPIController::class, 'savePaymentInfo']);
    Route::get('/get-payment-info', [ParentAPIController::class, 'paymentCards']);


    //Evaluation & Progress Reports
    Route::get('/get-student-reports', [ParentAPIController::class, 'GetStudentReportsListing']);

    //Evaluation Report
    Route::get('/evaluation-reports', [ParentAPIController::class, 'evaluationReportListing']);
    Route::post('/submit-evaluation-report', [ParentAPIController::class, 'submitEvaluationReport']);
    Route::get('/evaluation-report-view', [ParentAPIController::class, 'evaluationReportView']);

    //Progress Report
    Route::get('/progress-reports', [ParentAPIController::class, 'progressReportListing']);
    Route::post('/submit-progress-report', [ParentAPIController::class, 'submitProgressReport']);

    //Blogs
    Route::get('/blogs', [ParentAPIController::class, 'blogs']);
    Route::get('/blog-details', [ParentAPIController::class, 'blogsDetails']);
    
    Route::get('/getSubjects', [ParentAPIController::class, 'getSubjects'])->name('getSubjects');
    Route::get('/getCategories', [ParentAPIController::class, 'getCategories'])->name('getCategories');
    Route::get('/getCategoriesByMode', [ParentAPIController::class, 'getCategoriesByMode'])->name('getCategoriesByMode');
    Route::get('/getSubjectsByLevel', [ParentAPIController::class, 'getSubjectsByLevel'])->name('getSubjectsByLevel');
    
    // Merge All API to Home API's
    Route::get('/getHomeData', [ParentAPIController::class, 'getHomeData']);
    // Route::get('/parent-details', [ParentAPIController::class, 'getParentDetailByID'])->name('getParentDetailByID');
    // Route::get('/blogs', [ParentAPIController::class, 'blogs']);
    // Route::get('/due-invoices', [ParentAPIController::class, 'getDueInvoices'])->name('getDueInvoices');
    // Route::get('/get-today-classes', [ParentAPIController::class, 'getTodayClasses'])->name('getTodayClasses');
    // Route::get('/tutor-attendance', [ParentAPIController::class, 'tutorAttendance'])->name('tutorAttendance');
    // Route::get('/news', [ParentAPIController::class, 'news'])->name('news');
    // Route::get('/notifications', [ParentAPIController::class, 'notifications'])->name('notifications');
    // Route::get('/getSubjectsByLevel', [ParentAPIController::class, 'getSubjectsByLevel'])->name('getSubjectsByLevel');
    // Route::get('/getCategoriesByMode', [ParentAPIController::class, 'getCategoriesByMode'])->name('getCategoriesByMode');

});

Route::group(['prefix' => 'tutor'], function () {
    
    Route::post('/login', [TutorAPIController::class, 'loginAPI'])->name('loginAPI');
    Route::post('/tutor-register', [TutorAPIController::class, 'appTutorRegister'])->name('appTutorRegister');
    Route::get('/getTutorDetailByID', [TutorAPIController::class, 'getTutorDetailByID'])->name('getTutorDetailByID');
    Route::post('/verification-code', [TutorAPIController::class, 'verificationCode'])->name('verificationCode');
    Route::post('/store-devicetoken', [TutorAPIController::class, 'StoreDeviceToken'])->name('StoreDeviceToken');
    Route::get('/getCategories', [TutorAPIController::class, 'getCategories'])->name('getCategories');
    Route::get('/getStates', [TutorAPIController::class, 'getStates'])->name('getStates');
    Route::get('/getCities', [TutorAPIController::class, 'getCities'])->name('getCities');
    Route::get('/getSubjects', [TutorAPIController::class, 'getSubjects'])->name('getSubjects');
    Route::get('/tutorPayments', [TutorAPIController::class, 'tutorPayments'])->name('tutorPayments');
    Route::get('/report-notifications', [TutorAPIController::class, 'Reportnotifications']);
    Route::get('/getJobTicketDetails', [TutorAPIController::class, 'jobTicketDetails'])->name('ticketAPI');
    Route::get('/classScheduleStatusNotifications', [TutorAPIController::class, 'classScheduleStatusNotifications']);
    Route::get('/getTutorStudents', [TutorAPIController::class, 'getTutorStudents'])->name('getTutorStudents');
    Route::get('/getTutorSubjects', [TutorAPIController::class, 'getTutorSubjects'])->name('getTutorSubjects');
    Route::get('/getUpcomingClassesByTutorID', [TutorAPIController::class, 'getUpcomingClassesByTutorID'])->name('getUpcomingClassesByTutorID');
    Route::get('/news', [TutorAPIController::class, 'newsAPI']);
    Route::get('/faqs', [TutorAPIController::class, 'faqsAPI']);
    Route::get('/policies', [TutorAPIController::class, 'policiesAPI']);
    Route::get('/bannerAds', [TutorAPIController::class, 'bannerAds'])->name('bannerAds');
    Route::get('/get_tutor_dashboard_data', [TutorAPIController::class, 'getDashboardValues'])->name('getDashboardValues'); 
    Route::post('/service-preferences', [TutorAPIController::class, 'storeServicePreferences'])->name('storeServicePreferences');
    Route::get('/getServicePreferences', [TutorAPIController::class, 'getServicePreferences'])->name('getServicePreferences');
    Route::post('/bio-details', [TutorAPIController::class, 'storeBioDetails'])->name('storeBioDetails');
    Route::get('/getBioDetails', [TutorAPIController::class, 'getBioDetails'])->name('getBioDetails');
    Route::post('/emergency-contact', [TutorAPIController::class, 'storeEmergencyContact'])->name('storeEmergencyContact');
    Route::get('/getEmergencyContact', [TutorAPIController::class, 'getEmergencyContact'])->name('getEmergencyContact');
    Route::post('/education', [TutorAPIController::class, 'storeEducation'])->name('storeEducation');
    Route::get('/getEducationDetails', [TutorAPIController::class, 'getEducationDetails'])->name('getEducationDetails');
    Route::post('/documents', [TutorAPIController::class, 'storeDocuments'])->name('storeDocuments');
    Route::get('/getDocuments', [TutorAPIController::class, 'getDocuments'])->name('getDocuments');
    Route::post('/declaration', [TutorAPIController::class, 'storeDeclaration'])->name('storeDeclaration');
    Route::get('/getDeclaration', [TutorAPIController::class, 'getDeclaration'])->name('getDeclaration');
    Route::get('/checkTutorData', [TutorAPIController::class, 'checkTutorData'])->name('checkTutorData');
    Route::get('/getSubjectsByLevel', [TutorAPIController::class, 'getSubjectsByLevel'])->name('getSubjectsByLevel');
    
    //Notifications
    Route::get('/notifications', [TutorAPIController::class, 'notifications'])->name('notifications');
    Route::get('/detailedNotifications', [TutorAPIController::class, 'detailedNotifications']);
    Route::post('/notificationsStatusUpdate', [TutorAPIController::class, 'notificationsStatusUpdate']);

    //Combine Notifications
    Route::get('/combinedNotifications', [TutorAPIController::class, 'combinedNotifications'])->name('combinedNotifications');
    Route::post('/combinedNotificationsStatusUpdate', [TutorAPIController::class, 'combinedNotificationsStatusUpdate']);

    //Evaluation & Progress Reports
    Route::get('/get-student-reports', [TutorAPIController::class, 'GetStudentReportsListing']);

    //Progress Report
    Route::post('/progressReport', [TutorAPIController::class, 'submitProgressReport']);;
    Route::get('/progressReportListing', [TutorAPIController::class, 'progressReportListing']);

    //Evaluation Report
    Route::post('/tutorFirstReport', [TutorAPIController::class, 'tutorFirstReport']);
    Route::get('/tutorFirstReportView', [TutorAPIController::class, 'tutorFirstReportView']);
    Route::get('/tutorFirstReportListing', [TutorAPIController::class, 'tutorFirstReportListing']);

    //Mix API's
    Route::get('/ticketsAPI', [TutorAPIController::class, 'ticketsAPI'])->name('ticketsAPI');
    Route::post('/addMultipleClasses', [TutorAPIController::class, 'addMultipleClasses'])->name('addMultipleClasses');
    Route::post('/attendedClassClockInTwo', [TutorAPIController::class, 'attendedClassClockInTwo']);
    Route::post('/attendedClassClockOutTwo', [TutorAPIController::class, 'attendedClassClockOutTwo']);
    Route::post('/apply_job_ticket', [TutorAPIController::class, 'offerSendByTutor'])->name('offerSendByTutor');
    Route::get('/getTutorOffers', [TutorAPIController::class, 'getTutorOffers'])->name('getTutorOffers');
    Route::get('/getClassSchedulesTime', [TutorAPIController::class, 'getClassSchedulesTime'])->name('getClassSchedulesTime');
    Route::get('/attendedClassStatus', [TutorAPIController::class, 'attendedClassStatus']);
    Route::get('/getStudentSubjects', [TutorAPIController::class, 'getStudentSubjects'])->name('getStudentSubjects');
    Route::post('/newsStatusUpdate', [TutorAPIController::class, 'newsStatusUpdate']);
    Route::get('/tutorNewsStatusList', [TutorAPIController::class, 'tutorNewsStatusList']);
    Route::get('/detailedNews', [TutorAPIController::class, 'detailedNews']);
    Route::post('/editTutorProfile', [TutorAPIController::class, 'editTutorProfile'])->name('editTutorProfile');
    Route::post('/editStatus', [TutorAPIController::class, 'editStatus'])->name('editStatus');
    Route::get('/getClassAttendedTime', [TutorAPIController::class, 'getClassAttendedTime'])->name('getClassAttendedTime');
    
    //Ghost APIS
      Route::get('/getCancelledHours/{tutorID}', [TutorAPIController::class, 'getCancelledHours'])->name('getCancelledHours');
});


Route::post('/classScheduleAttendedStatusWithImage', [App\Http\Controllers\APIController::class, 'classScheduleAttendedStatusWithImage']);

Route::get('/sendAttendanceToParent/{ticketID}/{tutorID}', [App\Http\Controllers\APIController::class, 'sendAttendanceToParent']);

Route::post('/SendNotification', [App\Http\Controllers\APIController::class, 'sendnotification']);
Route::get('/classScheduleNotifications/{id}', [App\Http\Controllers\APIController::class, 'classScheduleNotifications']);
// Route::get('/classScheduleStatusNotifications/{tutorID}', [App\Http\Controllers\APIController::class, 'classScheduleStatusNotifications']);


Route::get('/updateNotificationStatus/{id}/{status}', [App\Http\Controllers\APIController::class, 'updateNotificationStatus']);
Route::get('/detailedNotification/{id}', [App\Http\Controllers\APIController::class, 'detailedNotification']);
Route::get('/searchJobTickets/{categoryID}/{subjectID}/{mode}/', [App\Http\Controllers\APIController::class, 'searchJobTickets']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
