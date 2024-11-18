<?php

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



Route::post('/verificationCode', [App\Http\Controllers\APIController::class, 'verificationCode'])->name('verificationCode');

Route::get('/update_dashboard_status/{contact}', [App\Http\Controllers\APIController::class, 'updateDashboardStatus'])->name('updateDashboardStatus'); 

Route::post('/register', 'App\Http\Controllers\API\RegisterController@register');
Route::post('/login', 'App\Http\Controllers\API\RegisterController@login');


#Api Post Array(store multiple records)
Route::post('/appTutorRegister', [App\Http\Controllers\APIController::class, 'appTutorRegister'])->name('appTutorRegister');
Route::post('/getTutorDeviceToken', [App\Http\Controllers\APIController::class, 'getTutorDeviceToken'])->name('getTutorDeviceToken');

Route::post('/addMultipleClasses', [App\Http\Controllers\APIController::class, 'addMultipleClasses'])->name('addMultipleClasses');
Route::get('/getStudentSubjects/{studentID}', [App\Http\Controllers\APIController::class, 'getStudentSubjects'])->name('getStudentSubjects');
Route::post('/editStatus', [App\Http\Controllers\APIController::class, 'editStatus'])->name('editStatus');
Route::post('/editTutorProfile', [App\Http\Controllers\APIController::class, 'editTutorProfile'])->name('editTutorProfile');
Route::get('/bannerAds', [App\Http\Controllers\APIController::class, 'bannerAds'])->name('bannerAds');
Route::post('/attendedClassClockInTwo', [App\Http\Controllers\APIController::class, 'attendedClassClockInTwo']);
Route::post('/attendedClassClockOutTwo', [App\Http\Controllers\APIController::class, 'attendedClassClockOutTwo']);

Route::post('/classScheduleAttendedStatusWithImage', [App\Http\Controllers\APIController::class, 'classScheduleAttendedStatusWithImage']);
Route::post('/token', [App\Http\Controllers\APIController::class, 'token']);


Route::get('/sendAttendanceToParent/{ticketID}/{tutorID}', [App\Http\Controllers\APIController::class, 'sendAttendanceToParent']);


Route::get('/news', [App\Http\Controllers\APIController::class, 'newsAPI']);
Route::get('/faqs', [App\Http\Controllers\APIController::class, 'faqsAPI']);
Route::get('/newsStatusUpdate/{id}/{status}/{tutorID}', [App\Http\Controllers\APIController::class, 'newsStatusUpdate']);
Route::get('/tutorNewsStatusList/{tutorID}', [App\Http\Controllers\APIController::class, 'tutorNewsStatusList']);


Route::get('/detailedNews/{id}', [App\Http\Controllers\APIController::class, 'detailedNews']);

Route::get('/notifications/{id}', [App\Http\Controllers\APIController::class, 'notifications']);
Route::post('/SendNotification', [App\Http\Controllers\APIController::class, 'sendnotification']);
Route::get('/classScheduleNotifications/{id}', [App\Http\Controllers\APIController::class, 'classScheduleNotifications']);
Route::get('/classScheduleStatusNotifications/{tutorID}', [App\Http\Controllers\APIController::class, 'classScheduleStatusNotifications']);


Route::get('/updateNotificationStatus/{id}/{status}', [App\Http\Controllers\APIController::class, 'updateNotificationStatus']);
Route::get('/detailedNotification/{id}', [App\Http\Controllers\APIController::class, 'detailedNotification']);
Route::get('/searchJobTickets/{categoryID}/{subjectID}/{mode}/', [App\Http\Controllers\APIController::class, 'searchJobTickets']);

Route::post('/tutorFirstReport', [App\Http\Controllers\APIController::class, 'tutorFirstReport']);

Route::get('/tutorFirstReportListing/{tutorID}', [App\Http\Controllers\APIController::class, 'tutorFirstReportListing']);
Route::get('/tutorFirstReportView/{id}', [App\Http\Controllers\APIController::class, 'tutorFirstReportView']);


Route::post('/progressReport', [App\Http\Controllers\APIController::class, 'progressReport']);

Route::get('/progressReportListing', [App\Http\Controllers\APIController::class, 'progressReportListing']);
Route::get('/progressReportView/{id}', [App\Http\Controllers\APIController::class, 'progressReportView']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
