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

Route::group(['prefix' => 'parent'], function () {

    Route::post('/login', [ParentAPIController::class,"parentLogin"]);
    
    // Route::post('/parent-register', [ParentAPIController::class, 'appParentRegister'])->name('appParentRegister');
    
    Route::post('/update-parent-profile', [ParentAPIController::class, 'updateParentProfile'])->name('updateParentProfile');
    
    Route::get('/students', [ParentAPIController::class, 'students'])->name('students');
    
    Route::get('/parent-students/{token}', [ParentAPIController::class, 'parentStudents'])->name('parentStudents');
    
    Route::get('/student-details/{student_id}', [ParentAPIController::class, 'studentsDetails'])->name('studentsDetails');
    
    Route::get('/parent-details/{token}', [ParentAPIController::class, 'getParentDetailByID'])->name('getParentDetailByID');
    
    Route::get('/getJobTicketDetails/{ticket_id}', [ParentAPIController::class, 'jobTicketDetails'])->name('ticketAPI');
    
    // Route::post('/code-verify', [ParentAPIController::class, 'verificationCode'])->name('verificationCode');
    
    Route::post('/verification-code', [ParentAPIController::class, 'verificationCode'])->name('verificationCode');
    
    Route::post('/store-student', [ParentAPIController::class, 'storeStudent'])->name('storeStudent');
    
    Route::post('/submit-ticket', [ParentAPIController::class, 'submitTicket'])->name('submitTicket');
    
    Route::post('/get-job-ticket-price', [ParentAPIController::class, 'getJobTicketEstimation'])->name('getJobTicketEstimation');
    
    Route::get('/tutor-attendance/{token}', [ParentAPIController::class, 'tutorAttendance'])->name('tutorAttendance');
    
    Route::get('/tutor-requests/{parent_token}', [ParentAPIController::class, 'tutorRequests'])->name('tutorRequests');
    
    Route::get('/tutor-request-details/{id}', [ParentAPIController::class, 'tutorRequestDetails'])->name('tutorRequestDetails');
    
    Route::get('/getStates/', [ParentAPIController::class, 'getStates'])->name('getStates');
    
    Route::get('/getCitiesByState/{state_id}', [ParentAPIController::class, 'getCities'])->name('getCities');
    
    Route::get('/class-schedules/{token}', [ParentAPIController::class, 'getClassSchedules'])->name('getClassSchedules');
    
    Route::get('/due-invoices/{token}', [ParentAPIController::class, 'getDueInvoices'])->name('getDueInvoices');
    
    Route::get('/get-upcoming-classes/{token}', [ParentAPIController::class, 'getUpcomingClasses'])->name('getUpcomingClasses');
    
    Route::get('/get-today-classes/{parent_id}', [ParentAPIController::class, 'getTodayClasses'])->name('getTodayClasses');
    
    Route::get('/approve-attendance/{id}', [ParentAPIController::class, 'approveAttendance'])->name('approveAttendance');
    
    Route::get('/reject-attendance/{id}', [ParentAPIController::class, 'rejectAttendance'])->name('rejectAttendance');
    
    Route::get('/reject-attendance/{id}', [ParentAPIController::class, 'rejectAttendance'])->name('rejectAttendance');
    
    Route::get('/news', [ParentAPIController::class, 'news'])->name('news');
    
    Route::post('/pay-commitment-fee', [ParentAPIController::class, 'payCommitmentFee'])->name('payCommitmentFee');
    
    Route::get('/faqs', [ParentAPIController::class, 'faqs']);
    
    Route::post('/submit-class-schedule', [ParentAPIController::class, 'submitClassSchedulesAdmin']);
    
    Route::post('/save-payment-info', [ParentAPIController::class, 'savePaymentInfo']);
    
    Route::get('/get-payment-info/{id}', [ParentAPIController::class, 'paymentCards']);

    //Evaluation Report
    Route::get('/evaluation-reports/{token}', [ParentAPIController::class, 'evaluationReportListing']);
    Route::post('/submit-evaluation-report', [ParentAPIController::class, 'submitEvaluationReport']);
    Route::get('/evaluation-report-view/{id}', [ParentAPIController::class, 'evaluationReportView']);

    //Progress Report
    Route::get('/progress-reports/{token}', [ParentAPIController::class, 'progressReportListing']);
    Route::get('/progress-reports', [ParentAPIController::class, 'progressReportListing']);
    Route::post('/submit-progress-report', [ParentAPIController::class, 'submitProgressReport']);

    //Blogs
    Route::get('/blogs', [ParentAPIController::class, 'blogs']);
    Route::get('/blog-details/{id}', [ParentAPIController::class, 'blogsDetails']);
    
    
    
    Route::get('/getSubjects', [ParentAPIController::class, 'getSubjects'])->name('getSubjects');

    Route::get('/getCategories', [ParentAPIController::class, 'getCategories'])->name('getCategories');

    Route::get('/getSubjectsByLevel/{id}', [ParentAPIController::class, 'getSubjectsByLevel'])->name('getSubjectsByLevel');

});

Route::group(['prefix' => 'tutor'], function () {
    
     //Tutor Routes
    Route::get('/getTutorDetailByID/{id}', [TutorAPIController::class, 'getTutorDetailByID'])->name('getTutorDetailByID');
    
    Route::get('/loginAPI/{phone}', [TutorAPIController::class, 'loginAPI'])->name('loginAPI');
    
    Route::post('/verificationCode', [TutorAPIController::class, 'verificationCode'])->name('verificationCode');
    
    Route::post('/getTutorDeviceToken', [TutorAPIController::class, 'getTutorDeviceToken'])->name('getTutorDeviceToken');
    
    Route::post('/appTutorRegister', [TutorAPIController::class, 'appTutorRegister'])->name('appTutorRegister');
    
    Route::get('/tutorPayments/{tutorID}', [TutorAPIController::class, 'tutorPayments'])->name('tutorPayments');
    
    Route::get('/notifications/{id}', [TutorAPIController::class, 'notifications']);
    
    Route::get('/getJobTicketDetails/{ticket_id}', [TutorAPIController::class, 'jobTicketDetails'])->name('ticketAPI');

    
    Route::get('/getCategories', [TutorAPIController::class, 'getCategories'])->name('getCategories');
    
    Route::get('/getStates', [TutorAPIController::class, 'getStates'])->name('getStates');

    Route::get('/getCities', [TutorAPIController::class, 'getCities'])->name('getCities');
    
    Route::get('/getSubjects', [TutorAPIController::class, 'getSubjects'])->name('getSubjects');
    
    Route::get('/tutorFirstReportListing/{tutorID}', [TutorAPIController::class, 'tutorFirstReportListing']);

    Route::post('/progressReport', [TutorAPIController::class, 'progressReport']);;
    
    Route::get('/progressReportListing', [TutorAPIController::class, 'progressReportListing']);
    
    Route::get('/classScheduleStatusNotifications/{tutorID}', [TutorAPIController::class, 'classScheduleStatusNotifications']);

    Route::get('/getCommulativeCommission/{tutorID}', [TutorAPIController::class, 'getCommulativeCommission'])->name('getCommulativeCommission');
    
    Route::get('/getTutorStudents/{tutorID}', [TutorAPIController::class, 'getTutorStudents'])->name('getTutorStudents');
    
    Route::get('/getTutorSubjects/{tutorID}', [TutorAPIController::class, 'getTutorSubjects'])->name('getTutorSubjects');
    
    Route::get('/getUpcomingClassesByTutorID/{tutorID}', [TutorAPIController::class, 'getUpcomingClassesByTutorID'])->name('getUpcomingClassesByTutorID');
    
    Route::get('/news', [TutorAPIController::class, 'newsAPI']);
    
    Route::get('/faqs', [TutorAPIController::class, 'faqsAPI']);

    Route::get('/bannerAds', [TutorAPIController::class, 'bannerAds'])->name('bannerAds');
    
    Route::get('/get_tutor_dashboard_data/{contact}/{date}', [TutorAPIController::class, 'getDashboardValues'])->name('getDashboardValues'); 

    Route::get('/update_dashboard_status/{contact}', [App\Http\Controllers\APIController::class, 'updateDashboardStatus'])->name('updateDashboardStatus'); 

    Route::get('/paymentHistory', [App\Http\Controllers\PaymentHistoryController::class, 'paymentHistory'])->name('paymentHistory');
    
    Route::get('/showPaymentHistory', [App\Http\Controllers\PaymentHistoryController::class, 'showPaymentHistory'])->name('showPaymentHistory');
    
    Route::post('/service-preferences', [TutorAPIController::class, 'storeServicePreferences'])->name('storeServicePreferences');
    
    Route::post('/bio-details', [TutorAPIController::class, 'storeBioDetails'])->name('storeBioDetails');
    
    Route::post('/emergency-contact', [TutorAPIController::class, 'storeEmergencyContact'])->name('storeEmergencyContact');
    
    Route::post('/education', [TutorAPIController::class, 'storeEducation'])->name('storeEducation');
    
    Route::post('/documents', [TutorAPIController::class, 'storeDocuments'])->name('storeDocuments');
    
    Route::post('/declaration', [TutorAPIController::class, 'storeDeclaration'])->name('storeDeclaration');
    
    Route::get('/getCancelledHours/{tutorID}', [TutorAPIController::class, 'getCancelledHours'])->name('getCancelledHours');

    
    //End Tutor Routes
   

    Route::post('/forgotPassword', [TutorAPIController::class, 'forgotPassword'])->name('forgotPassword');
    Route::post('/verifyResetCode', [TutorAPIController::class, 'verifyResetCode'])->name('verifyResetCode');
    Route::post('/resetPassword', [TutorAPIController::class, 'resetPassword'])->name('resetPassword');

    Route::post('/editProfile', [TutorAPIController::class, 'editProfile'])->name('editProfile');
    Route::post('/viewProfile', [TutorAPIController::class, 'viewProfile'])->name('viewProfile');

   

    // Check Tutor Data API
    Route::post('/checkTutorData', [TutorAPIController::class, 'checkTutorData'])->name('checkTutorData');


    //Mix API's
    Route::get('/ticketsAPI/{tutorID}', [TutorAPIController::class, 'ticketsAPI'])->name('ticketsAPI');

    Route::get('/newsAPI', [TutorAPIController::class, 'newsAPI'])->name('newsAPI');

    Route::get('/statistics/{tutorID}', [TutorAPIController::class, 'getTutorStatistics'])->name('getTutorStatistics');

    Route::get('/getTutorStudents/{tutorID}', [TutorAPIController::class, 'getTutorStudents'])->name('getTutorStudents');

    Route::get('/getUpcomingClassesByTutorID/{tutorID}', [TutorAPIController::class, 'getUpcomingClassesByTutorID'])->name('getUpcomingClassesByTutorID');

    Route::get('/tutorFirstReportListing/{tutorID}', [TutorAPIController::class, 'tutorFirstReportListing']);

    Route::get('/progressReportView/{id}', [TutorAPIController::class, 'progressReportView']);

    Route::get('/tutorFirstReportView/{id}', [TutorAPIController::class, 'tutorFirstReportView']);

    Route::post('/addMultipleClasses', [TutorAPIController::class, 'addMultipleClasses'])->name('addMultipleClasses');

    Route::post('/attendedClassClockInTwo', [TutorAPIController::class, 'attendedClassClockInTwo']);

    Route::post('/attendedClassClockOutTwo', [TutorAPIController::class, 'attendedClassClockOutTwo']);

    Route::post('/tutorFirstReport', [TutorAPIController::class, 'tutorFirstReport']);

    Route::post('/progressReport', [TutorAPIController::class, 'progressReport']);
    
    Route::get('/offerSendByTutor/{subjectID}/{tutorID}/{ticket_id}/{comment}', [TutorAPIController::class, 'offerSendByTutor'])->name('offerSendByTutor');
    
    Route::get('/getTutorOffers/{id}', [TutorAPIController::class, 'getTutorOffers'])->name('getTutorOffers');
    
    Route::get('/getClassSchedulesTime/{id}', [TutorAPIController::class, 'getClassSchedulesTime'])->name('getClassSchedulesTime');
    
    Route::post('/addMultipleClasses', [TutorAPIController::class, 'addMultipleClasses'])->name('addMultipleClasses');
    
    Route::get('/attendedClassStatus/{id}/{status}/{statusReason}', [TutorAPIController::class, 'attendedClassStatus']);
    
    Route::get('/getStudentSubjects/{studentID}', [TutorAPIController::class, 'getStudentSubjects'])->name('getStudentSubjects');
    
    Route::post('/attendedClassClockInTwo', [TutorAPIController::class, 'attendedClassClockInTwo']);
    
    Route::post('/attendedClassClockOutTwo', [TutorAPIController::class, 'attendedClassClockOutTwo']);
    
    Route::get('/newsStatusUpdate/{id}/{status}/{tutorID}', [TutorAPIController::class, 'newsStatusUpdate']);
    
    Route::get('/tutorNewsStatusList/{tutorID}', [TutorAPIController::class, 'tutorNewsStatusList']);
    
    Route::get('/detailedNews/{id}', [TutorAPIController::class, 'detailedNews']);
    
    Route::post('/editTutorProfile', [TutorAPIController::class, 'editTutorProfile'])->name('editTutorProfile');
    
    Route::get('/bannerAds', [TutorAPIController::class, 'bannerAds'])->name('bannerAds');
    
    Route::post('/editStatus', [TutorAPIController::class, 'editStatus'])->name('editStatus');
    
    Route::get('/getClassAttendedTime/{id}', [TutorAPIController::class, 'getClassAttendedTime'])->name('getClassAttendedTime');

});

#Api Post Array(store multiple records)









Route::post('/classScheduleAttendedStatusWithImage', [App\Http\Controllers\APIController::class, 'classScheduleAttendedStatusWithImage']);
Route::post('/token', [App\Http\Controllers\APIController::class, 'token']);


Route::get('/sendAttendanceToParent/{ticketID}/{tutorID}', [App\Http\Controllers\APIController::class, 'sendAttendanceToParent']);

Route::get('/ticketAPI/{ticket_id}', [App\Http\Controllers\APIController::class, 'ticketAPI'])->name('ticketAPI');

// Route::get('/news', [App\Http\Controllers\APIController::class, 'newsAPI']);
// Route::get('/faqs', [App\Http\Controllers\APIController::class, 'faqsAPI']);
// Route::get('/newsStatusUpdate/{id}/{status}/{tutorID}', [App\Http\Controllers\APIController::class, 'newsStatusUpdate']);
// Route::get('/tutorNewsStatusList/{tutorID}', [App\Http\Controllers\APIController::class, 'tutorNewsStatusList']);




// Route::get('/notifications/{id}', [App\Http\Controllers\APIController::class, 'notifications']);
Route::post('/SendNotification', [App\Http\Controllers\APIController::class, 'sendnotification']);
Route::get('/classScheduleNotifications/{id}', [App\Http\Controllers\APIController::class, 'classScheduleNotifications']);
// Route::get('/classScheduleStatusNotifications/{tutorID}', [App\Http\Controllers\APIController::class, 'classScheduleStatusNotifications']);


Route::get('/updateNotificationStatus/{id}/{status}', [App\Http\Controllers\APIController::class, 'updateNotificationStatus']);
Route::get('/detailedNotification/{id}', [App\Http\Controllers\APIController::class, 'detailedNotification']);
Route::get('/searchJobTickets/{categoryID}/{subjectID}/{mode}/', [App\Http\Controllers\APIController::class, 'searchJobTickets']);

Route::post('/tutorFirstReport', [App\Http\Controllers\APIController::class, 'tutorFirstReport']);

Route::get('/tutorFirstReportListing/{tutorID}', [App\Http\Controllers\APIController::class, 'tutorFirstReportListing']);
Route::get('/tutorFirstReportView/{id}', [App\Http\Controllers\APIController::class, 'tutorFirstReportView']);


// Route::post('/progressReport', [App\Http\Controllers\APIController::class, 'progressReport']);

// Route::get('/progressReportListing', [App\Http\Controllers\APIController::class, 'progressReportListing']);
// Route::get('/progressReportView/{id}', [App\Http\Controllers\APIController::class, 'progressReportView']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
