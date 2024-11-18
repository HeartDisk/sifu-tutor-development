<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Route::get('/truncate-tables', function () {
    $tables = [
        'assigned_classes', 'attendances', 'bank_vouchers', 'bank_voucher_items', 'bannerads', 'bio_details', 'certificates',
        'class_attendeds', 'class_schedules', 'creditorinvoices', 'creditorPayments', 'customers', 'customer_commitment_fees',
        'customer_vouchers', 'customer_voucher_items', 'declarations', 'documents', 'educations', 'emergency_contacts',
        'expenditures', 'expense_vouchers', 'expense_voucher_items', 'invoicePayments', 'invoices', 'invoice_deductions',
        'invoice_items', 'job_tickets', 'job_ticket_students', 'loggedInUsers', 'parent_device_tokens', 'payments',
        'payment_vouchers', 'progressReport', 'reports_notifications', 'self_push_notifications', 'service_preferences',
        'studentinvoices', 'studentinvoice_items', 'students', 'student_subjects', 'telescope_entries', 'telescope_entries_tags',
        'text_messages', 'tutorFirstSubmittedReportFromApps', 'tutorinvoices', 'tutorinvoice_items', 'tutoroffers', 'tutors',
        'tutorVerificationCode', 'tutor_commitment_fees', 'tutor_device_tokens', 'tutor_news_status', 'tutor_subjects',
        'user_activities', 'verificationCode' , 'tutorpayments', 'staff_payments', 'staffs', 'api_logs', 'notifications', 'telescope_entries'
    ];

    // Disable foreign key checks
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    // Truncate each table
    foreach ($tables as $table) {
        DB::table($table)->truncate();
    }

    // Re-enable foreign key checks
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    return 'Tables truncated successfully!';
});


Route::post('/pusher_auth', function () {
    return true; // yaa koi bhi logic jo aapka user ko authenticate kare
});
// Route::post('/pusher_auth', [\App\Http\Controllers\PusherAuthController::class, 'authenticate']);

Route::get('/get-google-access-token', [App\Http\Controllers\HomeController::class, 'getAccessToken']);


Route::get("/eventTtest", [App\Http\Controllers\HomeController::class, 'eventTest']);


Route::get("/Notificationtest", [App\Http\Controllers\HomeController::class, 'Notificationtest']);

Route::get("/Emailtest", [App\Http\Controllers\HomeController::class, 'Emailtest']);

Route::get("/Whatsapptest", [App\Http\Controllers\HomeController::class, 'Whatsapptest']);

Route::get('/hosted-checkout', [\App\Http\Controllers\PaymentController::class, 'payment'])->name('hosted.checkout');
Route::post('/payment/success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
Route::post('/payment/cancel', [\App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');



Route::get('/api-logs', [\App\Http\Controllers\HomeController::class, 'logsIndex'])->name('api.logs');
 
Route::get('/invoice-email-test', [\App\Http\Controllers\TestingController::class, 'sendInvoice'])->name('sendInvoice');

Route::get('/create-checkout-session', [\App\Http\Controllers\StripeController::class, 'createCheckoutSession'])->name('checkout.session');
Route::get('/checkout-success', [\App\Http\Controllers\StripeController::class, 'checkoutSuccess'])->name('checkout.success');
Route::get('/checkout-cancel', [\App\Http\Controllers\StripeController::class, 'checkoutCancel'])->name('checkout.cancel');


Route::get('/mark-attendance', [App\Http\Controllers\TestingController::class, 'markAttendance'])->name('markAttendance');

Route::post('/assignTutor/{subjectID}/{ticket_id}', [App\Http\Controllers\TicketController::class, 'assignTutor'])->name('assignTutor');

Route::post('/assignAdminInCharge', [App\Http\Controllers\TicketController::class, 'assignAdminInCharge'])->name('assignAdminInCharge');

Route::post('/assignAdminInChargeStudent', [App\Http\Controllers\StudentsController::class, 'assignAdminInChargeStudent'])->name('assignAdminInChargeStudent');

Route::post('/assignAdminInChargeCustomer', [App\Http\Controllers\StudentsController::class, 'assignAdminInChargeCustomer'])->name('assignAdminInChargeCustomer');

Route::get('/upcoming', [App\Http\Controllers\HomeController::class, 'upcomingClasses'])->name('upcomingClasses');

Route::get('/analytics/getTutorDropOutRate', [App\Http\Controllers\AnalyticsController::class, 'getTutorDropOutRate'])->name('getTutorDropOutRate');

Route::get("/eventTtest", [App\Http\Controllers\HomeController::class, 'eventTest']);

Route::get('/assignAllPermissions', [App\Http\Controllers\SettingsController::class, 'giveAllPermissionsToUser'])->name('giveAllPermissionsToUser');


//Check duplicate NRIC
Route::post('/checkDuplicateNric', [App\Http\Controllers\TutorController::class, 'checkDuplicateNric'])->name('checkDuplicateNric');


//Job ticket
Route::post('/checkCustomerDuplicateEmailJobTicket', [App\Http\Controllers\CustomersController::class, 'checkCustomerDuplicateEmailJobTicket'])->name('checkCustomerDuplicateEmailJobTicket');

//Tutor
Route::post('/checkTutorDuplicateEmail', [App\Http\Controllers\TutorController::class, 'checkTutorDuplicateEmail'])->name('checkTutorDuplicateEmail');
Route::post('/checkTutorDuplicatePhone', [App\Http\Controllers\TutorController::class, 'checkTutorDuplicatePhone'])->name('checkTutorDuplicatePhone');

//Customer
Route::post('/checkCustomerDuplicateEmail', [App\Http\Controllers\CustomersController::class, 'checkCustomerDuplicateEmail'])->name('checkCustomerDuplicateEmail');
Route::post('/checkCustomerDuplicatePhone', [App\Http\Controllers\CustomersController::class, 'checkCustomerDuplicatePhone'])->name('checkCustomerDuplicatePhone');

//Staff
Route::post('/checkStaffDuplicateEmail', [App\Http\Controllers\StaffController::class, 'checkStaffDuplicateEmail'])->name('checkStaffDuplicateEmail');
Route::post('/checkStaffDuplicatePhone', [App\Http\Controllers\StaffController::class, 'checkStaffDuplicatePhone'])->name('checkStaffDuplicatePhone');


//chart of accounts category
Route::get('/chart-of-accounts-category', [App\Http\Controllers\ChartOfAccountCategoryController::class, 'index']);
Route::get('/create-chart-of-accounts-category', [App\Http\Controllers\ChartOfAccountCategoryController::class, 'create']);
Route::post('/submit-chart-of-accounts-category', [App\Http\Controllers\ChartOfAccountCategoryController::class, 'store']);
Route::get('/edit-chart-of-accounts-category/{id}', [App\Http\Controllers\ChartOfAccountCategoryController::class, 'edit']);
Route::post('/submit-edit-chart-of-accounts-category/{id}', [App\Http\Controllers\ChartOfAccountCategoryController::class, 'update']);
Route::get('/delete-chart-of-accounts-category/{id}', [App\Http\Controllers\ChartOfAccountCategoryController::class, 'destroy']);

//chart of accounts sub category
Route::get('/chart-of-accounts-subcategory', [App\Http\Controllers\ChartOfAccountSubCategoryController::class, 'index']);
Route::get('/create-chart-of-accounts-subcategory', [App\Http\Controllers\ChartOfAccountSubCategoryController::class, 'create']);
Route::post('/submit-chart-of-accounts-subcategory', [App\Http\Controllers\ChartOfAccountSubCategoryController::class, 'store']);
Route::get('/edit-chart-of-accounts-subcategory/{id}', [App\Http\Controllers\ChartOfAccountSubCategoryController::class, 'edit']);
Route::post('/submit-edit-chart-of-accounts-subcategory/{id}', [App\Http\Controllers\ChartOfAccountSubCategoryController::class, 'update']);
Route::get('/delete-chart-of-accounts-subcategory/{id}', [App\Http\Controllers\ChartOfAccountSubCategoryController::class, 'destroy']);
Route::get('/get-chart-of-accounts-subcategory-by-category/{id}', [App\Http\Controllers\ChartOfAccountSubCategoryController::class, 'getCategoriesById']);


Route::get('/emailTest', [App\Http\Controllers\HomeController::class, 'emailTest'])->name('emailTest');

Route::get('/test-email', function () {
    $details = [
        'title' => 'Test Email',
        'body' => 'This is a test email.'
    ];

    \Mail::to('aasim.creative@gmail.com')->send(new \App\Mail\MyTestMail($details));

    return 'Email sent!';
});


Auth::routes();

Route::get('/token', function () {
        return csrf_token();
    });




Route::get('tutor_automation_reminder', function () {
\Artisan::call('reminder:send-make-class-schedule');

 return 'Scheduler Run Sucessfully';
});
Route::get('class_reminder_automation', function () {
\Artisan::call('send:class-reminders');

 return 'Scheduler Run Sucessfully';
});

Route::get('run_schedule', function () {
    \Artisan::call('schedule:run');

     return 'Scheduler Run Sucessfully';
});


Route::get('update_class_schedule', function () {
    \Artisan::call('reminder:send-update-class-schedule');

     return 'Scheduler Run Sucessfully';
});

Route::get('clear', function () {
    \Artisan::call('route:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('config:clear');

     return 'Route and Cache Cleared';
});

Route::get('/automation', [App\Http\Controllers\HomeController::class, 'automation'])->name('automation');

Route::get('/automated', [App\Http\Controllers\HomeController::class, 'automated'])->name('automated');

Route::get('/sendTutorInvoice', [App\Http\Controllers\HomeController::class, 'sendTutorInvoice'])->name('sendTutorInvoice');

Route::post('/checkSchedule', [App\Http\Controllers\ClassSchedulesController::class, 'checkSchedule'])->name('checkSchedule');


Route::post('/CodeVerify', [App\Http\Controllers\APIController::class, 'verificationCodeNew'])->name('verificationCodeNew');

Route::get('/getParentDetails/{id}', [App\Http\Controllers\StudentsController::class, 'getParentDetails'])->name('getParentDetails');


Route::get('/getStudents', [App\Http\Controllers\HomeController::class, 'getStudents'])->name('getStudents');
Route::get('/getStudentByID/{id}', [App\Http\Controllers\HomeController::class, 'getStudentByID'])->name('getStudentByID');
Route::get('/getStudentsByParentID/{id}', [App\Http\Controllers\HomeController::class, 'getStudentsByParentID'])->name('getStudentsByParentID');
Route::post('/getSearchStudents', [App\Http\Controllers\HomeController::class, 'getSearchStudents'])->name('getSearchStudents');
Route::post('/sendMobileNotification', [App\Http\Controllers\HomeController::class, 'sendMobileNotification'])->name('sendMobileNotification');
Route::get('/mobileNotificationForm', [App\Http\Controllers\HomeController::class, 'mobileNotificationForm'])->name('mobileNotificationForm');
Route::patch('/fcm-token', [App\Http\Controllers\HomeController::class, 'updateToken'])->name('fcmToken');
Route::post('/send-notification',[App\Http\Controllers\HomeController::class,'notification'])->name('notification');

Route::get('/agreeAttendance/{id}', [App\Http\Controllers\APIController::class, 'agreeAttendance']);
Route::get('/disputeAttendance/{id}', [App\Http\Controllers\APIController::class, 'disputeAttendance']);


Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('index');

Route::get('/welcomeMessage', [App\Http\Controllers\WelcomeController::class, 'welcomeMessage'])->name('welcomeMessage');
Route::get('/disputeMessage', [App\Http\Controllers\WelcomeController::class, 'disputeMessage'])->name('disputeMessage');

// Route::get('/loginAPI/{phone}', [App\Http\Controllers\APIController::class, 'loginAPI'])->name('loginAPI');
Route::get('/loginAPISMSTest', [App\Http\Controllers\APIController::class, 'loginAPISMSTest'])->name('loginAPISMSTest');






// Route::get('/verificationCode/{code}', [App\Http\Controllers\APIController::class, 'verificationCode'])->name('verificationCode');


Route::get('/APIclear', [App\Http\Controllers\APIController::class, 'APIclear'])->name('APIclear');

Route::post('/ajaxCall', [App\Http\Controllers\APIController::class, 'ajaxCall'])->name('ajaxCall');

Route::get('/getClassSchedulesForTutors/{tutorID}', [App\Http\Controllers\APIController::class, 'getClassSchedulesForTutors'])->name('getClassSchedulesForTutors');
// Route::get('/getTutorDetailByID/{id}', [App\Http\Controllers\APIController::class, 'getTutorDetailByID'])->name('getTutorDetailByID');
// Route::get('/getTutorSubjects/{tutorID}', [App\Http\Controllers\APIController::class, 'getTutorSubjects'])->name('getTutorSubjects');
Route::get('/getAttendedHours/{tutorID}', [App\Http\Controllers\APIController::class, 'getAttendedHours'])->name('getAttendedHours');
Route::get('/getScheduledHours/{tutorID}', [App\Http\Controllers\APIController::class, 'getScheduledHours'])->name('getScheduledHours');
Route::get('/getCancelledHours/{tutorID}', [App\Http\Controllers\APIController::class, 'getCancelledHours'])->name('getCancelledHours');


Route::get('/getAssignedTickets/{tutorID}', [App\Http\Controllers\APIController::class, 'getAssignedTickets'])->name('getAssignedTickets');


// Route::get('/getCommulativeCommission/{tutorID}', [App\Http\Controllers\APIController::class, 'getCommulativeCommission'])->name('getCommulativeCommission');

Route::get('/ticketsAPI/{tutorID}', [App\Http\Controllers\APIController::class, 'ticketsAPI'])->name('ticketsAPI');

Route::get('/ticketAPI/{ticket_id}', [App\Http\Controllers\APIController::class, 'ticketAPI'])->name('ticketAPI');

Route::get('/assignedTicketsAPI/{tutorID}', [App\Http\Controllers\APIController::class, 'assignedTicketsAPI'])->name('assignedTicketsAPI');
Route::get('/finishedTicketsAPI/{tutorID}', [App\Http\Controllers\APIController::class, 'finishedTicketsAPI'])->name('finishedTicketsAPI');

// Route::get('/tutorPayments/{tutorID}', [App\Http\Controllers\APIController::class, 'tutorPayments'])->name('tutorPayments');
Route::get('/viewTutorPayment/{tutorID}', [App\Http\Controllers\APIController::class, 'viewTutorPayment'])->name('viewTutorPayment');
// Route::get('/editTutorPayment/{tutorID}', [App\Http\Controllers\APIController::class, 'editTutorPayment'])->name('editTutorPayment');

// Route::get('/getStates', [App\Http\Controllers\APIController::class, 'getStates'])->name('getStates');

// Route::get('/getCities', [App\Http\Controllers\APIController::class, 'getCities'])->name('getCities');
// Route::get('/getSubjects', [App\Http\Controllers\APIController::class, 'getSubjects'])->name('getSubjects');
// Route::get('/getCategories', [App\Http\Controllers\APIController::class, 'getCategories'])->name('getCategories');
Route::get('/getTutors', [App\Http\Controllers\APIController::class, 'getTutors'])->name('getTutors');
Route::post('/addClassSchedule', [App\Http\Controllers\APIController::class, 'addClassSchedule'])->name('addClassSchedule');
Route::get('/getClassSchedules', [App\Http\Controllers\APIController::class, 'getClassSchedules'])->name('getClassSchedules');
Route::get('/getClassSchedulesByID/{id}', [App\Http\Controllers\APIController::class, 'getClassSchedulesByID'])->name('getClassSchedulesByID');
// Route::get('/getUpcomingClassesByTutorID/{tutorID}', [App\Http\Controllers\APIController::class, 'getUpcomingClassesByTutorID'])->name('getUpcomingClassesByTutorID');

Route::get('/getClassScheduleHoursByID/{id}', [App\Http\Controllers\APIController::class, 'getClassScheduleHoursByID'])->name('getClassScheduleHoursByID');
Route::get('/getClassAttendedHoursByID/{id}', [App\Http\Controllers\APIController::class, 'getClassAttendedHoursByID'])->name('getClassAttendedHoursByID');

Route::get('/getClassSchedulesTime/{id}', [App\Http\Controllers\APIController::class, 'getClassSchedulesTime'])->name('getClassSchedulesTime');
Route::get('/getClassAttendedTime/{id}', [App\Http\Controllers\APIController::class, 'getClassAttendedTime'])->name('getClassAttendedTime');

Route::get('/submitClassSchedulesTime/{tutorID}/{class_schedule_id}/{studentID}/{subjectID}/{ticketID}/{date}/{startTime}/{endTime}/{hasIncentive}', [App\Http\Controllers\APIController::class, 'submitClassSchedulesTime'])->name('submitClassSchedulesTime');
Route::get('/getTutorOffers/{id}', [App\Http\Controllers\APIController::class, 'getTutorOffers'])->name('getTutorOffers');
Route::get('/offerSendByTutor/{subjectID}/{tutorID}/{ticket_id}/{comment}', [App\Http\Controllers\APIController::class, 'offerSendByTutor'])->name('offerSendByTutor');

Route::get('/attendedClassClockIn/{id}/{class_schedule_id}/{min}/{sec}/{hasIncentive}', [App\Http\Controllers\APIController::class, 'attendedClassClockIn']);
Route::get('/attendedClassClockOut/{id}/{class_schedule_id}/{min}/{sec}/{hasIncentive}', [App\Http\Controllers\APIController::class, 'attendedClassClockOut']);
Route::get('/attendedClassStatus/{id}/{status}/{statusReason}', [App\Http\Controllers\APIController::class, 'attendedClassStatus']);

// Route::get('/getTutorStudents/{tutorID}', [App\Http\Controllers\APIController::class, 'getTutorStudents'])->name('getTutorStudents');

#Api Post Array(store multiple records)

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/followup/StudentInvoiceReadyConfirmation', [App\Http\Controllers\FollowupController::class, 'StudentInvoiceReadyConfirmation'])->name('StudentInvoiceReadyConfirmation');
Route::get('/viweStudentInvoiceReadyConfirmation/{id}', [App\Http\Controllers\FollowupController::class, 'viweStudentInvoiceReadyConfirmation'])->name('viweStudentInvoiceReadyConfirmation');
Route::get('/editStudentInvoiceReadyConfirmation/{id}', [App\Http\Controllers\FollowupController::class, 'editStudentInvoiceReadyConfirmation'])->name('editStudentInvoiceReadyConfirmation');

Route::get('/followup/TutorNotUpdateClassSchedule', [App\Http\Controllers\FollowupController::class, 'TutorNotUpdateClassSchedule'])->name('TutorNotUpdateClassSchedule');
Route::get('/followup/TutorNotSubmitReport', [App\Http\Controllers\FollowupController::class, 'TutorNotSubmitReport'])->name('TutorNotSubmitReport');
Route::get('/followup/TutorNeverLogIn', [App\Http\Controllers\FollowupController::class, 'TutorNeverLogIn'])->name('TutorNeverLogIn');
Route::get('/followup/TutorNeverScheduleClass', [App\Http\Controllers\FollowupController::class, 'TutorNeverScheduleClass'])->name('TutorNeverScheduleClass');

Route::get('/ClassSchedules', [App\Http\Controllers\ClassSchedulesController::class, 'index'])->name('ClassSchedules');
Route::get('/viewClassSchedules/{id}', [App\Http\Controllers\ClassSchedulesController::class, 'viewClassSchedules'])->name('viewClassSchedules');

Route::post('/submitClassSchedules', [App\Http\Controllers\ClassSchedulesController::class, 'submitClassSchedules'])->name('submitClassSchedules');
Route::post('/submitClassSchedulesAdmin', [App\Http\Controllers\ClassSchedulesController::class, 'submitClassSchedulesAdmin'])->name('submitClassSchedulesAdmin');

Route::post('/submitEditClassSchedules', [App\Http\Controllers\ClassSchedulesController::class, 'submitEditClassSchedules'])->name('submitEditClassSchedules');

Route::post('/submitAttendClassSchedules', [App\Http\Controllers\ClassSchedulesController::class, 'submitAttendClassSchedules'])->name('submitAttendClassSchedules');

Route::post('/submitCheckIn', [App\Http\Controllers\ClassSchedulesController::class, 'submitCheckIn'])->name('submitCheckIn');
Route::post('/submitCheckOut', [App\Http\Controllers\ClassSchedulesController::class, 'submitCheckOut'])->name('submitCheckOut');
Route::post('/submitStatus', [App\Http\Controllers\ClassSchedulesController::class, 'submitStatus'])->name('submitStatus');
Route::get('/deleteClassSchedule/{id}', [App\Http\Controllers\ClassSchedulesController::class, 'deleteClassSchedule'])->name('deleteClassSchedule');


Route::get('/fetchClassSchedules', [App\Http\Controllers\ClassSchedulesController::class, 'fetchClassSchedules'])->name('fetchClassSchedules');

Route::get('/Customers', [App\Http\Controllers\StudentsController::class, 'Customers'])->name('Customers');
Route::get('/addCustomer', [App\Http\Controllers\StudentsController::class, 'addCustomer'])->name('addCustomer');
Route::get('/viewCustomer/{id}', [App\Http\Controllers\StudentsController::class, 'viewCustomer'])->name('viewCustomer');
Route::get('/editCustomer/{id}', [App\Http\Controllers\StudentsController::class, 'editCustomer'])->name('editCustomer');
Route::get('/deleteCustomer/{id}', [App\Http\Controllers\StudentsController::class, 'deleteCustomer'])->name('deleteCustomer');

Route::get('/customerDashboard/{id}', [App\Http\Controllers\StudentsController::class, 'customerDashboard'])->name('customerDashboard');
Route::get('/customerTicket/{id}', [App\Http\Controllers\StudentsController::class, 'customerTicket'])->name('customerTicket');
Route::get('/customerInvoices/{id}', [App\Http\Controllers\StudentsController::class, 'customerInvoices'])->name('customerInvoices');
Route::get('/customerCommentmentFees/{id}', [App\Http\Controllers\StudentsController::class, 'customerCommentmentFees'])->name('customerCommentmentFees');




Route::post('/submitEditCustomer', [App\Http\Controllers\StudentsController::class, 'submitEditCustomer'])->name('submitEditCustomer');
Route::post('/submitCustomer', [App\Http\Controllers\StudentsController::class, 'submitCustomer'])->name('submitCustomer');

Route::get('/Students', [App\Http\Controllers\StudentsController::class, 'index'])->name('Students');
Route::get('/addStudent', [App\Http\Controllers\StudentsController::class, 'addStudent'])->name('addStudent');
Route::get('/editStudent/{id}', [App\Http\Controllers\StudentsController::class, 'editStudent'])->name('editStudent');

Route::get('/deleteStudent/{id}', [App\Http\Controllers\StudentsController::class, 'deleteStudent'])->name('deleteStudent');

Route::get('/viewStudent/{id}', [App\Http\Controllers\StudentsController::class, 'viewStudent'])->name('viewStudent');
Route::get('/studentDashboard/{id}', [App\Http\Controllers\StudentsController::class, 'studentDashboard'])->name('studentDashboard');
Route::get('/studentDashboardTickets/{id}', [App\Http\Controllers\StudentsController::class, 'studentDashboardTickets'])->name('studentDashboardTickets');
Route::get('/studentDashboardClassSchedules/{id}', [App\Http\Controllers\StudentsController::class, 'studentDashboardClassSchedules'])->name('studentDashboardClassSchedules');
Route::get('/studentDashboardInvoices/{id}', [App\Http\Controllers\StudentsController::class, 'studentDashboardInvoices'])->name('studentDashboardInvoices');



Route::get('/deleteStudent/{id}', [App\Http\Controllers\StudentsController::class, 'deleteStudent'])->name('deleteStudent');

Route::get('/studentSchedule', [App\Http\Controllers\StudentsController::class, 'studentSchedule'])->name('studentSchedule');
Route::get('/studentAssignments', [App\Http\Controllers\StudentsController::class, 'studentAssignments'])->name('studentAssignments');

Route::post('/submitStudent', [App\Http\Controllers\StudentsController::class, 'submitStudent'])->name('submitStudent');
Route::post('/submitEditStudent', [App\Http\Controllers\StudentsController::class, 'submitEditStudent'])->name('submitEditStudent');

Route::get('/students/StudentInvoices', [App\Http\Controllers\StudentsController::class, 'StudentInvoices'])->name('StudentInvoices');

Route::post('submitStudentInvoice', [App\Http\Controllers\StudentsController::class, 'submitStudentInvoice'])->name('submitStudentInvoice');

Route::get('/students/studentPaymentLists', [App\Http\Controllers\StudentsController::class, 'StudentPaymentLists'])->name('StudentPaymentLists');
Route::get('/students/addStudentInvoice', [App\Http\Controllers\StudentsController::class, 'addStudentInvoice'])->name('addStudentInvoice');
Route::get('/students/viewStudentInvoice/{id}', [App\Http\Controllers\StudentsController::class, 'viewStudentInvoice'])->name('viewStudentInvoice');

Route::get('/students/sendWhatsapp/{id}', [App\Http\Controllers\StudentsController::class, 'sendWhatsapp'])->name('sendWhatsapp');

Route::get('/pdfFile/{id}', [App\Http\Controllers\StudentsController::class, 'pdfFile'])->name('pdfFile');

Route::get('/students/viewStudentInvoiceById/{id}', [App\Http\Controllers\StudentsController::class, 'viewStudentInvoiceById'])->name('viewStudentInvoiceById');
Route::get('/invoicePublicLink/{id}', [App\Http\Controllers\WelcomeController::class, 'invoicePublicLink'])->name('invoicePublicLink');
Route::get('/invoicePublicLink3Months/{id}', [App\Http\Controllers\WelcomeController::class, 'invoicePublicLink3Months'])->name('invoicePublicLink3Months');
Route::get('/sendEmailInvoice/{id}', [App\Http\Controllers\StudentsController::class, 'sendEmailInvoice'])->name('sendEmailInvoice');

Route::get('/students/viewStudentPayment/{id}', [App\Http\Controllers\StudentsController::class, 'viewStudentPayment'])->name('viewStudentPayment');
Route::get('/students/editStudentInvoice/{id}', [App\Http\Controllers\StudentsController::class, 'editStudentInvoice'])->name('editStudentInvoice');
Route::get('/students/deleteStudentInvoice/{id}', [App\Http\Controllers\StudentsController::class, 'deleteStudentInvoice'])->name('deleteStudentInvoice');

Route::post('/students/submitAddInvoice', [App\Http\Controllers\StudentsController::class, 'submitAddInvoice'])->name('submitAddInvoice');

Route::post('/students/submitEditInvoice', [App\Http\Controllers\StudentsController::class, 'submitEditInvoice'])->name('submitEditInvoice');

Route::get('/getStudent/{id}', [App\Http\Controllers\StudentsController::class, 'getStudent'])->name('getStudent');

Route::get('/getSubjectById/{id}', [App\Http\Controllers\StudentsController::class, 'getSubjectById'])->name('getSubjectById');

Route::get('/TutorList', [App\Http\Controllers\TutorController::class, 'index'])->name('TutorList');
Route::get('/addTutor', [App\Http\Controllers\TutorController::class, 'addTutor'])->name('addTutor');
Route::get('/tutorLogin/{id}', [App\Http\Controllers\TutorController::class, 'tutorLogin'])->name('tutorLogin');

Route::get('/TutorScheduleCalendar', [App\Http\Controllers\TutorController::class, 'TutorScheduleCalendar'])->name('TutorScheduleCalendar');

Route::get('/viewTutor/{id}', [App\Http\Controllers\TutorController::class, 'viewTutor'])->name('viewTutor');
Route::get('/editTutor/{id}', [App\Http\Controllers\TutorController::class, 'editTutor'])->name('editTutor');
Route::get('/deleteTutor/{id}', [App\Http\Controllers\TutorController::class, 'deleteTutor'])->name('deleteTutor');
Route::get('/makeTutorPayment/{id}', [App\Http\Controllers\TutorController::class, 'makeTutorPayment'])->name('makeTutorPayment');
Route::post('/submitTutorPayment', [App\Http\Controllers\TutorController::class, 'submitTutorPayment'])->name('submitTutorPayment');

Route::get('/allTickets/{id}', [App\Http\Controllers\TutorController::class, 'allTickets'])->name('allTickets');
Route::get('/scheduledClasses/{id}', [App\Http\Controllers\TutorController::class, 'scheduledClasses'])->name('scheduledClasses');
Route::get('/assignedClasses/{id}', [App\Http\Controllers\TutorController::class, 'assignedClasses'])->name('assignedClasses');

Route::post('/submitTutor', [App\Http\Controllers\TutorController::class, 'submitTutor'])->name('submitTutor');
Route::post('/submitEditTutor', [App\Http\Controllers\TutorController::class, 'submitEditTutor'])->name('submitEditTutor');


Route::get('/tutorOffer/{subjectID}/{tutorID}/{ticket_id}', [App\Http\Controllers\TutorController::class, 'tutorOffer'])->name('tutorOffer');

Route::get('/tutoOfferActionApprove/{id}', [App\Http\Controllers\TutorController::class, 'tutoOfferActionApprove'])->name('tutoOfferActionApprove');
Route::get('/tutoOfferActionReject/{id}', [App\Http\Controllers\TutorController::class, 'tutoOfferActionReject'])->name('tutoOfferActionReject');


Route::post('submitAttendance', [App\Http\Controllers\TutorController::class, 'submitAttendance'])->name('submitAttendance');
Route::post('updateAttendance', [App\Http\Controllers\TutorController::class, 'updateAttendance'])->name('updateAttendance');

Route::get('/getAttendance/{id}/{subject}/{tutor_id}/{ticket_id}', [App\Http\Controllers\TutorController::class, 'getAttendance'])->name('getAttendance');
Route::get('/getCheckINN/{id}/{subject}/{tutor_id}/{ticket_id}', [App\Http\Controllers\TutorController::class, 'getCheckINN'])->name('getCheckINN');
Route::get('/getCheckOUT/{id}/{subject}/{tutor_id}/{ticket_id}', [App\Http\Controllers\TutorController::class, 'getCheckOUT'])->name('getCheckOUT');

Route::get('/TutorReports', [App\Http\Controllers\TutorController::class, 'TutorReports'])->name('TutorReports');
Route::get('/addTutorReport', [App\Http\Controllers\TutorController::class, 'addTutorReport'])->name('addTutorReport');
Route::post('/submitTutorReport', [App\Http\Controllers\TutorController::class, 'submitTutorReport'])->name('submitTutorReport');
Route::post('/submitEditTutorReport', [App\Http\Controllers\TutorController::class, 'submitEditTutorReport'])->name('submitEditTutorReport');


Route::get('/TutorReportsV2', [App\Http\Controllers\TutorController::class, 'TutorReportsV2'])->name('TutorReportsV2');
Route::get('/progressReports', [App\Http\Controllers\TutorController::class, 'progressReports'])->name('progressReports');
Route::get('/progressReports/{id}', [App\Http\Controllers\TutorController::class, 'progressReportsView'])->name('progressReportsView');
Route::get('/TutorReportsV2View/{id}', [App\Http\Controllers\TutorController::class, 'TutorReportsV2View'])->name('TutorReportsV2View');

Route::get('/profile/{id}', [App\Http\Controllers\TutorController::class, 'profile'])->name('profile');

Route::post('/submitEditProfile', [App\Http\Controllers\TutorController::class, 'submitEditProfile'])->name('submitEditProfile');

Route::get('/changePassword/{id}', [App\Http\Controllers\TutorController::class, 'changePassword'])->name('changePassword');
Route::post('/submitChangePassword', [App\Http\Controllers\TutorController::class, 'submitChangePassword'])->name('submitChangePassword');

Route::get('/TutorAssignments', [App\Http\Controllers\TutorController::class, 'TutorAssignments'])->name('TutorAssignments');

Route::get('/TutorPayments', [App\Http\Controllers\TutorController::class, 'TutorPayments'])->name('TutorPayments');
Route::get('/tutorPaymentSlip/{id}', [App\Http\Controllers\TutorController::class, 'tutorPaymentSlip'])->name('tutorPaymentSlip');
Route::get('/downloadTutorPaymentSlip/{id}', [App\Http\Controllers\TutorController::class, 'downloadTutorPaymentSlip'])->name('downloadTutorPaymentSlip');
Route::get('/sendTutorPaymentSlip/{id}', [App\Http\Controllers\TutorController::class, 'sendTutorPaymentSlip'])->name('sendTutorPaymentSlip');




Route::get('/viewTutorPaymentJournalBreakdown/{tutorID}', [App\Http\Controllers\TutorController::class, 'viewTutorPaymentJournalBreakdown'])->name('viewTutorPaymentJournalBreakdown');
Route::get('/TutorPaymentJournal', [App\Http\Controllers\TutorController::class, 'TutorPaymentJournal'])->name('TutorPaymentJournal');


Route::get('/TutorFinder', [App\Http\Controllers\TutorController::class, 'TutorFinder'])->name('TutorFinder');
Route::get('/tutorDetail/{id}', [App\Http\Controllers\TutorController::class, 'tutorDetail'])->name('tutorDetail');


Route::get('/StaffPayments/ViewCommissions', [App\Http\Controllers\StaffController::class, 'staffPaymentsViewCommissions'])->name('staffPaymentsViewCommissions');
Route::post('/StaffPayments/ViewCommissionsByMonth', [App\Http\Controllers\StaffController::class, 'staffPaymentsViewCommissionsByMonth'])->name('staffPaymentsViewCommissionsByMonth');
Route::get('/ViewCommissionsBreakDown/{staff_id}', [App\Http\Controllers\StaffController::class, 'ViewCommissionsBreakDown'])->name('ViewCommissionsBreakDown');



Route::get('/ViewPaymentSlip/{id}', [App\Http\Controllers\StaffController::class, 'ViewPaymentSlip'])->name('ViewPaymentSlip');

Route::get('/downloadStaffPaymentSlip/{id}', [App\Http\Controllers\StaffController::class, 'downloadStaffPaymentSlip'])->name('downloadStaffPaymentSlip');
Route::get('/sendStaffPaymentSlip/{id}', [App\Http\Controllers\StaffController::class, 'sendStaffPaymentSlip'])->name('sendStaffPaymentSlip');



Route::get('/getStaffCommissionById/{id}', [App\Http\Controllers\StaffController::class, 'getStaffCommissionById'])->name('getStaffCommissionById');

Route::get('/StaffList', [App\Http\Controllers\StaffController::class, 'StaffList'])->name('StaffList');
Route::get('/addStaff', [App\Http\Controllers\StaffController::class, 'addStaff'])->name('addStaff');
Route::post('/submitStaff', [App\Http\Controllers\StaffController::class, 'submitStaff'])->name('submitStaff');
Route::get('/StaffPayments', [App\Http\Controllers\StaffController::class, 'StaffPayments'])->name('StaffPayments');
Route::post('/submitEditStaff', [App\Http\Controllers\StaffController::class, 'submitEditStaff'])->name('submitEditStaff');
Route::get('/viewStaff/{id}', [App\Http\Controllers\StaffController::class, 'viewStaff'])->name('viewStaff');
Route::get('/editStaff/{id}', [App\Http\Controllers\StaffController::class, 'editStaff'])->name('editStaff');
Route::get('/deleteStaff/{id}', [App\Http\Controllers\StaffController::class, 'deleteStaff'])->name('deleteStaff');

Route::get('/staffPayment/{id}', [App\Http\Controllers\StaffController::class, 'staffPayment'])->name('staffPayment');

Route::get('/makeStaffPayment', [App\Http\Controllers\StaffController::class, 'makeStaffPayment'])->name('makeStaffPayment');

Route::get('/StaffPayments/MakePayment', [App\Http\Controllers\StaffController::class, 'StaffMakePayment'])->name('StaffMakePayment');

Route::post('/submitPaymentStaff', [App\Http\Controllers\StaffController::class, 'submitPaymentStaff'])->name('submitPaymentStaff');

Route::get('/subjectList', [App\Http\Controllers\ProductController::class, 'ProductList'])->name('subjectList');
Route::get('/addProduct', [App\Http\Controllers\ProductController::class, 'addProduct'])->name('addProduct');
Route::post('/submitProduct', [App\Http\Controllers\ProductController::class, 'submitProduct'])->name('submitProduct');
Route::get('/editProduct/{id}', [App\Http\Controllers\ProductController::class, 'editProduct'])->name('editProduct');
Route::get('/deleteProduct/{id}', [App\Http\Controllers\ProductController::class, 'deleteProduct'])->name('deleteProduct');
Route::post('/submitEditProduct', [App\Http\Controllers\ProductController::class, 'submitEditProduct'])->name('submitEditProduct');
Route::get('/viewProduct/{id}', [App\Http\Controllers\ProductController::class, 'viewProduct'])->name('viewProduct');

Route::post('/getProductsByAjax', [App\Http\Controllers\ProductController::class, 'getProductsByAjax'])->name('getProductsByAjax');



Route::get('/services', [App\Http\Controllers\ProductController::class, 'services'])->name('services');

Route::post('/submitService', [App\Http\Controllers\ProductController::class, 'submitService'])->name('submitService');
Route::get('/deleteService/{id}', [App\Http\Controllers\ProductController::class, 'deleteService'])->name('deleteService');



Route::get('/CategoryList', [App\Http\Controllers\ProductController::class, 'CategoryList'])->name('CategoryList');
Route::get('/addCategory', [App\Http\Controllers\ProductController::class, 'addCategory'])->name('addCategory');
Route::post('/submitCategory', [App\Http\Controllers\ProductController::class, 'submitCategory'])->name('submitCategory');
Route::get('/editCategory/{id}', [App\Http\Controllers\ProductController::class, 'editCategory'])->name('editCategory');
Route::get('/deleteCategory/{id}', [App\Http\Controllers\ProductController::class, 'deleteCategory'])->name('deleteCategory');
Route::get('/selectCategoryPriceByAjax/{id}', [App\Http\Controllers\ProductController::class, 'selectCategoryPriceByAjax'])->name('selectCategoryPriceByAjax');
Route::post('/submitEditCategory', [App\Http\Controllers\ProductController::class, 'submitEditCategory'])->name('submitEditCategory');

Route::get('/TutorApplicationSummary', [App\Http\Controllers\TicketController::class, 'TutorApplicationSummary'])->name('TutorApplicationSummary');

Route::get('/TicketList', [App\Http\Controllers\TicketController::class, 'TicketList'])->name('TicketList');
Route::post('/addExtraStudents', [App\Http\Controllers\TicketController::class, 'addExtraStudents'])->name('addExtraStudents');

Route::get('/addTicket', [App\Http\Controllers\TicketController::class, 'addTicket'])->name('addTicket');
Route::post('/submitJobTicket', [App\Http\Controllers\TicketController::class, 'submitTicket'])->name('submitJobTicket');
Route::post('/submitTDuplicateicket', [App\Http\Controllers\TicketController::class, 'submitTDuplicateicket'])->name('submitTDuplicateicket');
Route::get('/sendnotification', [App\Http\Controllers\TicketController::class, 'sendnotification'])->name('sendnotification');
Route::post('/submitEditJobTicket', [App\Http\Controllers\TicketController::class, 'submitEditJobTicket'])->name('submitEditJobTicket');
Route::get('/addTicket/{id}', [App\Http\Controllers\TicketController::class, 'addTicketGetAjaxCall'])->name('addTicketGetAjaxCall');
Route::get('/addTicketAjaxCallParrent/{parrentID}', [App\Http\Controllers\TicketController::class, 'addTicketAjaxCallParrent'])->name('addTicketAjaxCallParrent');
Route::post('/addTicketAjaxPOSTcustomerState', [App\Http\Controllers\TicketController::class, 'addTicketAjaxPOSTcustomerState'])->name('addTicketAjaxPOSTcustomerState');
Route::post('/addTicketAjaxPOSTclassState', [App\Http\Controllers\TicketController::class, 'addTicketAjaxPOSTclassState'])->name('addTicketAjaxPOSTclassState');
Route::post('/getParentStudents', [App\Http\Controllers\TicketController::class, 'getParentStudents'])->name('getParentStudents');

Route::get('/viewTicket/{id}', [App\Http\Controllers\TicketController::class, 'viewTicket'])->name('viewTicket');
Route::get('/duplicateTicket/{id}', [App\Http\Controllers\TicketController::class, 'duplicateTicket'])->name('duplicateTicket');



Route::get('/acceptTicket/{ssid}/{subject_id}/{ticket_id}/{tutorID}', [App\Http\Controllers\TicketController::class, 'acceptTicket'])->name('acceptTicket');

Route::get('/editTicket/{id}', [App\Http\Controllers\TicketController::class, 'editTicket'])->name('editTicket');
Route::get('/deleteTicket/{id}', [App\Http\Controllers\TicketController::class, 'deleteTicket'])->name('deleteTicket');



Route::get('/editTicketTutor/{id}', [App\Http\Controllers\TicketController::class, 'editTicketTutor'])->name('editTicketTutor');
Route::post('/updateTicketTutor', [App\Http\Controllers\TicketController::class, 'updateTicketTutor'])->name('updateTicketTutor');


Route::get('/ChartOfAccounts', [App\Http\Controllers\ChartOfAccountsController::class, 'ChartOfAccounts'])->name('ChartOfAccounts');
Route::get('/addChartOfAccounts', [App\Http\Controllers\ChartOfAccountsController::class, 'addChartOfAccounts'])->name('addChartOfAccounts');
Route::post('/submitChartOfAccounts', [App\Http\Controllers\ChartOfAccountsController::class, 'submitChartOfAccounts'])->name('submitChartOfAccounts');
Route::get('/viewChartOfAccounts/{id}', [App\Http\Controllers\ChartOfAccountsController::class, 'viewChartOfAccounts'])->name('viewChartOfAccounts');
Route::get('/editChartOfAccounts/{id}', [App\Http\Controllers\ChartOfAccountsController::class, 'editChartOfAccounts'])->name('editChartOfAccounts');

//Mobile App News
Route::get('/MobileAppNews', [App\Http\Controllers\MobileAppController::class, 'MobileAppNews'])->name('MobileAppNews');
Route::get('/addNews', [App\Http\Controllers\MobileAppController::class, 'addNews'])->name('addNews');
Route::post('/submitNews', [App\Http\Controllers\MobileAppController::class, 'submitNews'])->name('submitNews');
Route::get('/editNews/{id}', [App\Http\Controllers\MobileAppController::class, 'editNews'])->name('editNews');
Route::post('/submitEditNews', [App\Http\Controllers\MobileAppController::class, 'submitEditNews'])->name('submitEditNews');
Route::get('/deleteNews/{id}', [App\Http\Controllers\MobileAppController::class, 'deleteNews'])->name('deleteNews');
Route::get('/singleMobileAppNews/{id}', [App\Http\Controllers\MobileAppController::class, 'singleMobileAppNews'])->name('singleMobileAppNews');

//Mobile App Blogs
Route::get('/MobileAppBlog', [App\Http\Controllers\MobileAppBlogController::class, 'MobileAppBlog'])->name('MobileAppBlog');
Route::get('/addBlog', [App\Http\Controllers\MobileAppBlogController::class, 'addBlog'])->name('addBlog');
Route::post('/submitBlog', [App\Http\Controllers\MobileAppBlogController::class, 'submitBlog'])->name('submitBlog');
Route::get('/editBlog/{id}', [App\Http\Controllers\MobileAppBlogController::class, 'editBlog'])->name('editBlog');
Route::post('/submitEditBlog', [App\Http\Controllers\MobileAppBlogController::class, 'submitEditBlog'])->name('submitEditBlog');
Route::get('/deleteBlog/{id}', [App\Http\Controllers\MobileAppBlogController::class, 'deleteBlog'])->name('deleteBlog');
Route::get('/singleMobileAppBlog/{id}', [App\Http\Controllers\MobileAppBlogController::class, 'singleMobileAppBlog'])->name('singleMobileAppBlog');


Route::resource('faq', 'App\Http\Controllers\FaqController');
Route::get('faq/delete/{id}', [App\Http\Controllers\FaqController::class, 'destroy']);

Route::resource('policies', 'App\Http\Controllers\PolicyController');
Route::get('policies/delete/{id}', [App\Http\Controllers\PolicyController::class, 'destroy']);

// Route::get('/policies/{role}/{type}', [PolicyController::class, 'showPolicy']);
// Route::post('/policies', [PolicyController::class, 'store']);
// Route::put('/policies/{policy}', [PolicyController::class, 'update']);
// Route::delete('/policies/{policy}', [PolicyController::class, 'destroy']);

// Route::get('/MobileAppFAQ', [App\Http\Controllers\MobileAppController::class, 'MobileAppFAQ'])->name('MobileAppFAQ');
// Route::get('/addFAQ', [App\Http\Controllers\MobileAppController::class, 'addFAQ'])->name('addFAQ');
// Route::post('/submitFAQ', [App\Http\Controllers\MobileAppController::class, 'submitFAQ'])->name('submitFAQ');
// Route::get('/editFAQ/{id}', [App\Http\Controllers\MobileAppController::class, 'editFAQ'])->name('editFAQ');
// Route::post('/submitEditFAQ', [App\Http\Controllers\MobileAppController::class, 'submitEditFAQ'])->name('submitEditFAQ');
// Route::get('/deleteFAQ/{id}', [App\Http\Controllers\MobileAppController::class, 'deleteFAQ'])->name('deleteFAQ');
// Route::get('/singleMobileAppFAQ/{id}', [App\Http\Controllers\MobileAppController::class, 'singleMobileAppFAQ'])->name('singleMobileAppFAQ');

Route::get('/notification', [App\Http\Controllers\MobileAppController::class, 'notification'])->name('notification');
Route::get('/addNotification', [App\Http\Controllers\MobileAppController::class, 'addNotification'])->name('addNotification');
Route::post('/get-student-subjects', [App\Http\Controllers\MobileAppController::class, 'getStudentSubjects'])->name('getStudentSubjects');
Route::post('/get-tutor-students', [App\Http\Controllers\MobileAppController::class, 'getTutorStudents'])->name('getTutorStudents');
Route::post('/submitNotification', [App\Http\Controllers\MobileAppController::class, 'submitNotification'])->name('submitNotification');

Route::get('/deleteNotification/{id}', [App\Http\Controllers\MobileAppController::class, 'deleteNotification'])->name('deleteNotification');

Route::resource('selfpushnotification', App\Http\Controllers\SelfPushNotificationController::class);
Route::get('/selfpushnotification/delete/{id}', [App\Http\Controllers\SelfPushNotificationController::class, 'destroy']);


Route::get('/selfPushNotification', [App\Http\Controllers\MobileAppController::class, 'selfPushNotification'])->name('selfPushNotification');
Route::get('/addSelfPushNotification', [App\Http\Controllers\MobileAppController::class, 'addSelfPushNotification'])->name('addSelfPushNotification');
Route::get('/submitSelfPushNotification', [App\Http\Controllers\MobileAppController::class, 'submitSelfPushNotification'])->name('submitSelfPushNotification');


Route::get('/bannerAds', [App\Http\Controllers\MobileAppController::class, 'bannerAds'])->name('bannerAds');
Route::get('/addBannerAds', [App\Http\Controllers\MobileAppController::class, 'addBannerAds'])->name('addBannerAds');
Route::get('/deleteBannerAds/{id}', [App\Http\Controllers\MobileAppController::class, 'deleteBannerAds'])->name('deleteBannerAds');


Route::post('/submitBannerAds', [App\Http\Controllers\MobileAppController::class, 'submitBannerAds'])->name('submitBannerAds');

Route::get('/extraStudentCharges', [App\Http\Controllers\SettingsController::class, 'extraStudentCharges'])->name('extraStudentCharges');
Route::post('/submitExtraStudentCharges', [App\Http\Controllers\SettingsController::class, 'submitExtraStudentCharges'])->name('submitExtraStudentCharges');
Route::get('/users', [App\Http\Controllers\SettingsController::class, 'users'])->name('users');
Route::get('/deleteUser/{id}', [App\Http\Controllers\SettingsController::class, 'deleteUser'])->name('deleteUser');

Route::get('/addUser', [App\Http\Controllers\SettingsController::class, 'addUser'])->name('addUser');
Route::post('/submitUser', [App\Http\Controllers\SettingsController::class, 'submitUser'])->name('submitUser');
Route::post('/editSubmitUser', [App\Http\Controllers\SettingsController::class, 'editSubmitUser'])->name('editSubmitUser');

Route::get('/userRoles', [App\Http\Controllers\SettingsController::class, 'userRoles'])->name('userRoles');
Route::get('/userRoles/addRole', [App\Http\Controllers\SettingsController::class, 'addUserRole'])->name('addRole');
Route::post('/userRoles/addRoleAndPermission', [App\Http\Controllers\SettingsController::class, 'addRolesAndPermission'])->name('addRolesPermission');
Route::get('/Role/{role?}', [App\Http\Controllers\SettingsController::class, 'showRole'])->name('showRole');
Route::post('/editRole', [App\Http\Controllers\SettingsController::class, 'editRole'])->name('editRole');
Route::get('/deleteRole/{id}', [App\Http\Controllers\SettingsController::class, 'deleteRole'])->name('deleteRole');


Route::get('/StateCities', [App\Http\Controllers\SettingsController::class, 'StateCities'])->name('StateCities');
Route::post('/submitState', [App\Http\Controllers\SettingsController::class, 'submitState'])->name('submitState');
Route::get('/editState/{id}', [App\Http\Controllers\SettingsController::class, 'editState'])->name('editState');
Route::get('/editCity/{id}', [App\Http\Controllers\SettingsController::class, 'editCity'])->name('editCity');


Route::post('/submitEditState', [App\Http\Controllers\SettingsController::class, 'submitEditState'])->name('submitEditState');
Route::post('/submitEditCity', [App\Http\Controllers\SettingsController::class, 'submitEditCity'])->name('submitEditCity');


Route::post('/submitCity', [App\Http\Controllers\SettingsController::class, 'submitCity'])->name('submitCity');


Route::get('/accountInformation', [App\Http\Controllers\SettingsController::class, 'accountInformation'])->name('accountInformation');
Route::get('/appleRedemptionCode', [App\Http\Controllers\SettingsController::class, 'appleRedemptionCode'])->name('appleRedemptionCode');
Route::get('/system', [App\Http\Controllers\SettingsController::class, 'system'])->name('system');
Route::get('/addRedemptionCode', [App\Http\Controllers\SettingsController::class, 'addRedemptionCode'])->name('addRedemptionCode');
Route::get('/MessageTemplates', [App\Http\Controllers\SettingsController::class, 'MessageTemplates'])->name('MessageTemplates');

Route::get('/tutorBonus', [App\Http\Controllers\SettingsController::class, 'tutorBonus'])->name('tutorBonus');
Route::post('/submitTtuorBonus', [App\Http\Controllers\SettingsController::class, 'submitTtuorBonus'])->name('submitTtuorBonus');
Route::post('/submitEditTutorBonuses', [App\Http\Controllers\SettingsController::class, 'submitEditTutorBonuses'])->name('submitEditTutorBonuses');

Route::get('/viewTutorBonuses/{id}', [App\Http\Controllers\SettingsController::class, 'viewTutorBonuses'])->name('viewTutorBonuses');
Route::get('/editTutorBonuses/{id}', [App\Http\Controllers\SettingsController::class, 'editTutorBonuses'])->name('editTutorBonuses');

Route::get('/addTutorBonus', [App\Http\Controllers\SettingsController::class, 'addTutorBonus'])->name('addTutorBonus');
Route::get('/StudentPicBonuses', [App\Http\Controllers\SettingsController::class, 'StudentPicBonuses'])->name('StudentPicBonuses');

Route::get('/addStudentPicBonuses', [App\Http\Controllers\SettingsController::class, 'addStudentPicBonuses'])->name('addStudentPicBonuses');
Route::post('/submitAddStudentPicBonuses', [App\Http\Controllers\SettingsController::class, 'submitAddStudentPicBonuses'])->name('submitAddStudentPicBonuses');
Route::get('/editStudentPICBonuses/{id}', [App\Http\Controllers\SettingsController::class, 'editStudentPICBonuses'])->name('editStudentPICBonuses');
Route::get('/viewStudentPICBonuses/{id}', [App\Http\Controllers\SettingsController::class, 'viewStudentPICBonuses'])->name('viewStudentPICBonuses');
Route::post('/submitEditStudentPicBonuses', [App\Http\Controllers\SettingsController::class, 'submitEditStudentPicBonuses'])->name('submitEditStudentPicBonuses');



Route::get('/addComission', [App\Http\Controllers\SettingsController::class, 'addComission'])->name('addComission');
Route::post('/submitAddComission', [App\Http\Controllers\SettingsController::class, 'submitAddComission'])->name('submitAddComission');
Route::get('/editComission/{id}', [App\Http\Controllers\SettingsController::class, 'editComission'])->name('editComission');
Route::get('/viewComission/{id}', [App\Http\Controllers\SettingsController::class, 'viewComission'])->name('viewComission');
Route::post('/submitEditComission', [App\Http\Controllers\SettingsController::class, 'submitEditComission'])->name('submitEditComission');


Route::get('/StudentPicCommissions', [App\Http\Controllers\SettingsController::class, 'StudentPicCommissions'])->name('StudentPicCommissions');

Route::get('/journalLedger', [App\Http\Controllers\JournalLedgerController::class, 'journalLedger'])->name('JournalLedger');
Route::get('/addJournalLedger', [App\Http\Controllers\JournalLedgerController::class, 'addJournalLedger'])->name('addJournalLedger');
Route::post('/submitJournalLedger', [App\Http\Controllers\JournalLedgerController::class, 'submitJournalLedger'])->name('submitJournalLedger');
Route::get('/viewJournalLedger/{id}', [App\Http\Controllers\JournalLedgerController::class, 'viewJournalLedger'])->name('viewJournalLedger');
Route::get('/editJournalLedger/{id}', [App\Http\Controllers\JournalLedgerController::class, 'editJournalLedger'])->name('editJournalLedger');
Route::post('/updateJournalLedger/{id}', [App\Http\Controllers\JournalLedgerController::class, 'updateJournalLedger'])->name('updateJournalLedger');
Route::get('/deleteJournalLedger/{id}', [App\Http\Controllers\JournalLedgerController::class, 'deleteJournalLedger'])->name('deleteJournalLedger');
Route::get('/reportGeneralLedger', [App\Http\Controllers\JournalLedgerController::class, 'reportGeneralLedger'])->name('reportGeneralLedger');

Route::get('/expenditures', [App\Http\Controllers\ExpenditureController::class, 'expenditures'])->name('expenditures');
Route::get('/addExpenditure', [App\Http\Controllers\ExpenditureController::class, 'addExpenditure'])->name('addExpenditure');
Route::post('/submitExpenditures', [App\Http\Controllers\ExpenditureController::class, 'submitExpenditures'])->name('submitExpenditures');
Route::get('/viewExpenditures/{id}', [App\Http\Controllers\ExpenditureController::class, 'viewExpenditures'])->name('viewExpenditures');
Route::get('/editExpenditures/{id}', [App\Http\Controllers\ExpenditureController::class, 'editExpenditures'])->name('editExpenditures');
Route::get('/deleteExpenditures/{id}', [App\Http\Controllers\ExpenditureController::class, 'deleteExpenditures'])->name('deleteExpenditures');

Route::get('/saleInvoice', [App\Http\Controllers\SaleInvoiceController::class, 'saleInvoice'])->name('saleInvoice');
Route::get('/paymentList', [App\Http\Controllers\SaleInvoiceController::class, 'paymentList'])->name('paymentList');

Route::get('/addSaleInvoice', [App\Http\Controllers\SaleInvoiceController::class, 'addSaleInvoice'])->name('addSaleInvoice');
Route::post('/submitSaleInvoice', [App\Http\Controllers\SaleInvoiceController::class, 'submitSaleInvoice'])->name('submitSaleInvoice');
Route::post('/updateSaleInvoice', [App\Http\Controllers\SaleInvoiceController::class, 'updateSaleInvoice'])->name('updateSaleInvoice');
Route::get('/viewSaleInvoice/{id}', [App\Http\Controllers\SaleInvoiceController::class, 'viewSaleInvoice'])->name('viewSaleInvoice');
Route::get('/editSaleInvoice/{id}', [App\Http\Controllers\SaleInvoiceController::class, 'editSaleInvoice'])->name('editSaleInvoice');
Route::get('/deleteSaleInvoice/{id}', [App\Http\Controllers\SaleInvoiceController::class, 'deleteSaleInvoice'])->name('deleteSaleInvoice');

Route::get('/CreditorInvoices', [App\Http\Controllers\CreditorInvoiceController::class, 'CreditorInvoices'])->name('CreditorInvoices');
Route::get('/addCreditorInvoice', [App\Http\Controllers\CreditorInvoiceController::class, 'addCreditorInvoice'])->name('addCreditorInvoice');
Route::post('/submitCreditorInvoice', [App\Http\Controllers\CreditorInvoiceController::class, 'submitCreditorInvoice'])->name('submitCreditorInvoice');
Route::post('/UpdateCreditorInvoice', [App\Http\Controllers\CreditorInvoiceController::class, 'UpdateCreditorInvoice'])->name('UpdateCreditorInvoice');
Route::get('/viewCreditorInvoice/{id}', [App\Http\Controllers\CreditorInvoiceController::class, 'viewCreditorInvoice'])->name('viewCreditorInvoice');
Route::get('/editCreditorInvoice/{id}', [App\Http\Controllers\CreditorInvoiceController::class, 'editCreditorInvoice'])->name('editCreditorInvoice');
Route::get('/CreditorpaymentList', [App\Http\Controllers\CreditorInvoiceController::class, 'CreditorpaymentList'])->name('CreditorpaymentList');


Route::get('/addCreditorPayment', [App\Http\Controllers\CreditorInvoiceController::class, 'addCreditorPayment'])->name('addCreditorPayment');
Route::post('/submitCreditorPayment', [App\Http\Controllers\CreditorInvoiceController::class, 'submitCreditorPayment'])->name('submitCreditorPayment');
Route::get('/ViewCreditorPayment/{id}', [App\Http\Controllers\CreditorInvoiceController::class, 'ViewCreditorPayment'])->name('ViewCreditorPayment');
Route::get('/deleteCreditorPayment/{id}', [App\Http\Controllers\CreditorInvoiceController::class, 'deleteCreditorPayment'])->name('deleteCreditorPayment');


Route::get('/paymentHistory', [App\Http\Controllers\PaymentHistoryController::class, 'paymentHistory'])->name('paymentHistory');
Route::get('/showPaymentHistory', [App\Http\Controllers\PaymentHistoryController::class, 'showPaymentHistory'])->name('showPaymentHistory');


Route::get('/dailyTicketApplication', [App\Http\Controllers\OperationReportController::class, 'dailyTicketApplication'])->name('dailyTicketApplication');
Route::get('/monthlyInvoiceChargeStatus', [App\Http\Controllers\OperationReportController::class, 'monthlyInvoiceChargeStatus'])->name('monthlyInvoiceChargeStatus');
Route::get('/monthlyProductVsComission', [App\Http\Controllers\OperationReportController::class, 'monthlyProductVsComission'])->name('monthlyProductVsComission');


Route::get('/analytics/overview', [App\Http\Controllers\AnalyticsController::class, 'overview'])->name('analytics/overview');
Route::get('/analytics/tutorVsSubject', [App\Http\Controllers\AnalyticsController::class, 'tutorVsSubject'])->name('analytics/tutorVsSubject');
Route::get('/analytics/studentVsSubject', [App\Http\Controllers\AnalyticsController::class, 'studentVsSubject'])->name('analytics/studentVsSubject');
Route::get('/analytics/customerVsSubject', [App\Http\Controllers\AnalyticsController::class, 'customerVsSubject'])->name('analytics/customerVsSubject');
Route::get('/analytics/classesByDayType', [App\Http\Controllers\AnalyticsController::class, 'classesByDayType'])->name('analytics/classesByDayType');
Route::get('/analytics/ticketStatus', [App\Http\Controllers\AnalyticsController::class, 'ticketStatus'])->name('analytics/ticketStatus');
Route::get('/analytics/classesByWeekday', [App\Http\Controllers\AnalyticsController::class, 'classesByWeekday'])->name('analytics/classesByWeekday');

Route::get('/analytics/studentInvoices', [App\Http\Controllers\AnalyticsController::class, 'studentInvoices'])->name('analytics/studentInvoices');
Route::get('/analytics/picSalesPerformance', [App\Http\Controllers\AnalyticsController::class, 'picSalesPerformance'])->name('analytics/picSalesPerformance');
Route::get('/analytics/platformUsage', [App\Http\Controllers\AnalyticsController::class, 'platformUsage'])->name('analytics/platformUsage');
Route::get('/analytics/tutorsuccessreport', [App\Http\Controllers\AnalyticsController::class, 'tutorsuccessreport'])->name('analytics/platformUsage');


// accounting Section
Route::get('/financialReport/customer_statement', [App\Http\Controllers\FinancialReportController::class, 'customer_statement'])->name('financialReport/customer_statement');

Route::get('/financialReport/bank_statement', [App\Http\Controllers\FinancialReportController::class, 'bank_statement'])->name('financialReport/bank_statement');

Route::get('/financialReport/expense_balance/{id}', [App\Http\Controllers\FinancialReportController::class, 'expense_balance'])->name('financialReport/expense_balance');
Route::get('/financialReport/bank_balance/{id}', [App\Http\Controllers\FinancialReportController::class, 'bank_balance'])->name('financialReport/bank_balance');

Route::get('/financialReport/accounts', [App\Http\Controllers\FinancialReportController::class, 'accounts'])->name('financialReport/accounts');


Route::post('financialReport/submitAccount', [App\Http\Controllers\FinancialReportController::class, 'submitAccount'])->name('financialReport/submitAccount');
Route::get('edit_account/{id}', [App\Http\Controllers\FinancialReportController::class, 'edit_account'])->name('edit_account');
Route::post('update_account', [App\Http\Controllers\FinancialReportController::class, 'update_account'])->name('update_account');
Route::get('delete_account/{id}', [App\Http\Controllers\FinancialReportController::class, 'delete_account'])->name('delete_account');



Route::get('/account_type/{id}', [App\Http\Controllers\FinancialReportController::class, 'account_type'])->name('account_type');


Route::get('/financialReport/getRecords/{id}', [App\Http\Controllers\FinancialReportController::class, 'getRecords']);

Route::post('/financialReport/getTutorRecords', [App\Http\Controllers\FinancialReportController::class, 'getTutorRecords']);

Route::get('/financialReport/getExpenseRecords/{id}', [App\Http\Controllers\FinancialReportController::class, 'getExpenseRecords']);

Route::get('/financialReport/getBankRecords/{id}', [App\Http\Controllers\FinancialReportController::class, 'getBankRecords']);

Route::get('/financialReport/{id}/customer_balance', [App\Http\Controllers\FinancialReportController::class, 'customer_balance']);
Route::get('/financialReport/{id}/tutor_balance', [App\Http\Controllers\FinancialReportController::class, 'tutor_balance']);

Route::get('/financialReport/tutor_statement', [App\Http\Controllers\FinancialReportController::class, 'tutor_statement'])->name('financialReport/tutor_statement');
Route::get('/financialReport/class_statement', [App\Http\Controllers\FinancialReportController::class, 'class_statement'])->name('financialReport/class_statement');

Route::get('/financialReport/expense_statement', [App\Http\Controllers\FinancialReportController::class, 'expense_statement'])->name('financialReport/expense_statement');
Route::get('/financialReport/bank_statement', [App\Http\Controllers\FinancialReportController::class, 'bank_statement'])->name('financialReport/bank_statement');

Route::get('/financialReport/general_journal', [App\Http\Controllers\FinancialReportController::class, 'general_journal'])->name('financialReport/general_journal');
Route::get('/financialReport/general_journal23', [App\Http\Controllers\FinancialReportController::class, 'general_journal23'])->name('financialReport/general_journal23');
Route::get('/financialReport/cash_in_hand', [App\Http\Controllers\FinancialReportController::class, 'cash_in_hand'])->name('financialReport/cash_in_hand');

Route::get('/customer-voucher-list', [App\Http\Controllers\FinancialReportController::class, 'customer_voucher_list'])->name('customer_voucher_list');
Route::get('/customer-voucher', [App\Http\Controllers\FinancialReportController::class, 'customer_voucher'])->name('customer_voucher');
Route::get('/customer-voucher-view/{id}', [App\Http\Controllers\FinancialReportController::class, 'view_customer_voucher'])->name('view_customer_voucher');
Route::get('/customer-voucher-delete/{id}', [App\Http\Controllers\FinancialReportController::class, 'delete_customer_voucher'])->name('delete_customer_voucher');
Route::post('/submit-customer-voucher', [App\Http\Controllers\FinancialReportController::class, 'submitCustomerVoucher'])->name('submitCustomerReceiveVoucher');
//Route::get('/customer-receive-voucher', [App\Http\Controllers\FinancialReportController::class, 'customer_receive_voucher'])->name('customer_receive_voucher');
//Route::get('/customer-payment-voucher', [App\Http\Controllers\FinancialReportController::class, 'customer_payment_voucher'])->name('customer_payment_voucher');

Route::get('/tutor-voucher', [App\Http\Controllers\FinancialReportController::class, 'tutor_voucher'])->name('tutor_voucher');
Route::post('/submit-tutor-voucher', [App\Http\Controllers\FinancialReportController::class, 'submitTutorVoucher'])->name('submitTutorVoucher');
Route::get('/tutor-payment-voucher', [App\Http\Controllers\FinancialReportController::class, 'tutor_payment_voucher'])->name('tutor_payment_voucher');
Route::get('/tutor-voucher-list', [App\Http\Controllers\FinancialReportController::class, 'tutor_receiving_voucher_list'])->name('tutor_receiving_voucher_list');
Route::get('/tutor-voucher-view/{id}', [App\Http\Controllers\FinancialReportController::class, 'view_tutor_voucher'])->name('view_tutor_voucher');
Route::get('/tutor-voucher-delete/{id}', [App\Http\Controllers\FinancialReportController::class, 'delete_tutor_voucher'])->name('delete_tutor_voucher');


Route::get('/expense-voucher', [App\Http\Controllers\FinancialReportController::class, 'expense_voucher'])->name('expense_voucher');
Route::get('/expense-voucher-list', [App\Http\Controllers\FinancialReportController::class, 'expense_voucher_list'])->name('expense_voucher_list');
Route::post('/submit-expense-voucher', [App\Http\Controllers\FinancialReportController::class, 'submitExpenseVoucher'])->name('submitExpenseVoucher');
Route::get('/expense-voucher-view/{id}', [App\Http\Controllers\FinancialReportController::class, 'view_expense_voucher'])->name('view_expense_voucher');
Route::get('/expense-voucher-delete/{id}', [App\Http\Controllers\FinancialReportController::class, 'delete_expense_voucher'])->name('delete_expense_voucher');



// Route::post('/submit_bank_voucher_credit', [App\Http\Controllers\FinancialReportController::class, 'submit_bank_voucher_credit'])->name('submit_bank_voucher_credit');

// Route::get('/expense-payment-voucher', [App\Http\Controllers\FinancialReportController::class, 'expense_payment_voucher'])->name('expense_payment_voucher');
// Route::get('/expense-voucher-list', [App\Http\Controllers\FinancialReportController::class, 'expense_voucher_list'])->name('expense_voucher_list');


Route::get('/bank-voucher', [App\Http\Controllers\FinancialReportController::class, 'bank_voucher'])->name('bank_voucher');
Route::post('/submit_bank_voucher', [App\Http\Controllers\FinancialReportController::class, 'submit_bank_voucher_debit'])->name('submit_bank_voucher_debit');
Route::get('/bank-voucher-view/{id}', [App\Http\Controllers\FinancialReportController::class, 'view_bank_voucher'])->name('view_bank_voucher');
// Route::get('/bank-payment-voucher', [App\Http\Controllers\FinancialReportController::class, 'bank_payment_voucher'])->name('bank_payment_voucher');
Route::get('/bank-voucher-list', [App\Http\Controllers\FinancialReportController::class, 'bank_voucher_list'])->name('bank_voucher_list');
Route::get('/bank-voucher-delete/{id}', [App\Http\Controllers\FinancialReportController::class, 'delete_bank_voucher'])->name('delete_bank_voucher');

Route::get('/approveDispute/{id}', [App\Http\Controllers\ClassSchedulesController::class, 'approveDispute']);
Route::get('/rejectDispute/{id}', [App\Http\Controllers\ClassSchedulesController::class, 'rejectDispute']);



// accounting Section

Route::get('/financialReport/cashFlow', [App\Http\Controllers\FinancialReportController::class, 'cashFlow'])->name('financialReport/cashFlow');
Route::get('/financialReport/balanceSheet', [App\Http\Controllers\FinancialReportController::class, 'balanceSheet'])->name('financialReport/balanceSheet');
Route::get('/financialReport/trialBalance', [App\Http\Controllers\FinancialReportController::class, 'trialBalance'])->name('financialReport/trialBalance');
Route::get('/financialReport/incomeStatement', [App\Http\Controllers\FinancialReportController::class, 'incomeStatement'])->name('financialReport/incomeStatement');
Route::get('/financialReport/incomeByProduct', [App\Http\Controllers\FinancialReportController::class, 'incomeByProduct'])->name('financialReport/incomeByProduct');

Route::get('/fullcalender', [App\Http\Controllers\FullCalenderController::class, 'index']);
Route::post('/fullcalenderAjax', [App\Http\Controllers\FullCalenderController::class, 'ajax']);

Route::get('/billing/invoices', [App\Http\Controllers\BillingController::class, 'invoices'])->name('invoices');

Route::get('/userActivities', [App\Http\Controllers\SystemLogsController::class, 'userActivities'])->name('userActivities');
Route::get('/textMessages', [App\Http\Controllers\SystemLogsController::class, 'textMessages'])->name('textMessages');

Route::get('/export_excel', [App\Http\Controllers\ExportExcelController::class, 'index']);
Route::get('/export_excel/excel', [App\Http\Controllers\ExportExcelController::class, 'excel'])->name('export_excel.excel');
Route::get('/export_excel/StudentInvoiceReadyForConfirmationList', [App\Http\Controllers\ExportExcelController::class, 'StudentInvoiceReadyForConfirmationList'])->name('export_excel.StudentInvoiceReadyForConfirmationList');
Route::get('/export_excel/tutorNotUpdateClassSchedule', [App\Http\Controllers\ExportExcelController::class, 'tutorNotUpdateClassSchedule'])->name('export_excel.tutorNotUpdateClassSchedule');

Route::post('/changeTutorID', [App\Http\Controllers\TicketController::class, 'changeTutorID']);




