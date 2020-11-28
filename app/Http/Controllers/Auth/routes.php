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
// API Routes.

Route::group(['prefix' => 'api'], function () {

    Route::get('validate-username/{q}', 'Auth\OnboardingController@checkUsername');
    Route::get('validate-email/{q}', 'Auth\OnboardingController@checkEmail');
    Route::get('validate-phone/{q}', 'Auth\OnboardingController@checkPhonenumber');
    Route::post('reset-password', 'Auth\OnboardingController@ResetPassword');
    Route::get('get-token', 'Auth\OnboardingController@token');
    Route::get('refresh-token', 'Auth\OnboardingController@refreshToken');
    //end sms verification routes

    //start email verification routes
    Route::post('send-email-verification-code', 'Auth\OnboardingController@sendEmailVerificationCode');
    Route::post('resend-email-verification-code', 'Auth\OnboardingController@resendEmailVerificationCode');
    Route::post('verify-email-code', 'Auth\OnboardingController@verifyEmailCode');
    //end email verification routes

    //start email verification routes
    Route::post('resend-email-verification-code', 'Auth\OnboardingController@resendEmailVerificationCode');
    Route::post('verify-email-code', 'Auth\OnboardingController@verifyEmailCode');
    //end email verification routes
    //start user registration routes
    Route::post('signup', 'Auth\OnboardingController@registerUser');
    Route::post('login', 'Auth\OnboardingController@LoginUser');
    Route::post('get-police', 'Auth\OnboardingController@getPolice');
    Route::post('get-village', 'Auth\OnboardingController@getVillage');
    Route::post('get-officer', 'Auth\OnboardingController@getOfficer');
    Route::post('get-villages','Auth\OnboardingController@getVillages');
    //end user registration routes

    //start membership payment verification routes
    Route::post('set-target', 'Auth\OnboardingController@setTarget');
    //end membership payment verification routes
    //END API ROUTES FOR ONBOARDING
    //complaints routes
    Route::post('add-complaint', 'App\Http\Controllers\ComplaintsController@addComplaint');
    Route::post('get-civilian-complaints', 'App\Http\Controllers\ComplaintsController@getCivilianComplaints');
    Route::post('get-all-complaints', 'App\Http\Controllers\ComplaintsController@getAllComplaints');
    Route::post('get-village-complaints', 'App\Http\Controllers\ComplaintsController@getVillageComplaints');
    Route::post('get-complaint', 'App\Http\Controller\ComplaintsController@getComplaint');
    Route::post('get-civilian-complaint', 'App\Http\Controllers\ComplaintsController@getCivilianComplaint');

    //Crimes routes
    Route::post('add-crime', 'App\Http\Controllers\CrimesController@addCrime');
    Route::post('get-civilian-crimes', 'App\Http\Controllers\CrimesController@getCivilianCrimes');
    Route::post('get-all-crimes', 'App\Http\Controllers\CrimesController@getAllCrimes');
    Route::post('get-village-crimes', 'App\Http\Controllers\CrimesController@getVillageCrimes');
    Route::post('get-crime', 'App\Http\Controllers\CrimesController@getCrime');
    Route::post('get-civilian-crime', 'App\Http\Controllers\CrimesController@getCivilianCrime');

    //Staff Response to Complaints 
    Route::post('add-staff-response-complaint', 'App\Http\Controllers\StaffResponseComplaintController@addStaffResponseComplaint');
    Route::post('get-response-complaint', 'App\Http\Controllers\StaffResponseComplaintController@getResponseComplaint');
    Route::post('get-staff-response-complaint', 'App\Http\Controllers\StaffResponseComplaintController@getStaffResponseComplaints');
    Route::post('get-complaint-response-staff', 'App\Http\Controllers\StaffResponseComplaintController@getComplaintResponseStaff');

    //Staff Response to Crime 
    Route::post('add-staff-response-crime', 'App\Http\Controllers\StaffResponseCrimeController@addStaffResponseCrime');
    Route::post('get-response-crime', 'App\Http\Controllers\StaffResponseCrimeController@getCrimeResponse');
    Route::post('get-staff-response-crime', 'App\Http\Controllers\StaffResponseCrimeController@getStaffResponseCrimes');
    Route::post('get-crime-response-staff', 'App\Http\Controllers\StaffResponseCrimeController@getCrimeResponseStaff');

    //profile routes
    Route::post('update-profile', 'Auth\OnboardingController@updateProfile');
    Route::post('update-profile-photo', 'Auth\OnboardingController@updateProfilePhoto');
   
    //password routes
    Route::post('change-password', 'Auth\AuthController@ChangePassword');

    // Password reset link request routes...
    Route::post('password/email', 'Auth\PasswordController@postEmail');

    // Password reset routes...
    Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
    Route::post('password/reset', 'Auth\PasswordController@postReset');

    //token routes
    Route::post('add-token', 'Auth\AuthController@addToken');

    // activation routes
    Route::get('activate/{id}', 'Auth\OnboardingController@activate');
    Route::get('activate-code/{id}', 'Auth\OnboardingController@activateCode');
    //END INAPP ROUTES

});




Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');
Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function () {
    return view('home');
});
