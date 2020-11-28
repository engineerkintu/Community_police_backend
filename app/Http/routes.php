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
    Route::post('add-complaint', 'ComplaintsController@addComplaint');
    Route::post('get-civilian-complaints', 'ComplaintsController@getCivilianComplaints');
    Route::post('get-all-complaints', 'ComplaintsController@getAllComplaints');
    Route::post('get-village-complaints', 'ComplaintsController@getVillageComplaints');
    Route::post('get-complaint', 'ComplaintsController@getComplaint');
    Route::post('get-civilian-complaint', 'ComplaintsController@getCivilianComplaint');

    //Crimes routes
    Route::post('add-crime', 'CrimesController@addCrime');
    Route::post('get-civilian-crimes', 'CrimesController@getCivilianCrimes');
    Route::post('get-all-crimes', 'CrimesController@getAllCrimes');
    Route::post('get-village-crimes', 'CrimesController@getVillageCrimes');
    Route::post('get-crime', 'CrimesController@getCrime');
    Route::post('get-civilian-crime', 'CrimesController@getCivilianCrime');

    //Staff Response to Complaints 
    Route::post('add-staff-response-complaint', 'StaffResponseComplaintController@addStaffResponseComplaint');
    Route::post('get-response-complaint', 'StaffResponseComplaintController@getResponseComplaint');
    Route::post('get-staff-response-complaint', 'StaffResponseComplaintController@getStaffResponseComplaints');
    Route::post('get-complaint-response-staff', 'StaffResponseComplaintController@getComplaintResponseStaff');

    //Police
    Route::post('get-all-police', 'Auth\OnboardingController@getAllPolice');
    Route::post('get-officers', 'Auth\OnboardingController@getOfficers');
    Route::post('activate-user', 'Auth\OnboardingController@activateUser');
    Route::post('get-user', 'Auth\OnboardingController@getUser');


    //Staff Response to Crime 
    Route::post('add-staff-response-crime', 'StaffResponseCrimeController@addStaffResponseCrime');
    Route::post('get-response-crime', 'StaffResponseCrimeController@getCrimeResponse');
    Route::post('get-staff-response-crime', 'StaffResponseCrimeController@getStaffResponseCrimes');
    Route::post('get-crime-response-staff', 'StaffResponseCrimeController@getCrimeResponseStaff');

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
