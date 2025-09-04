<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CaseController;
use App\Http\Controllers\API\LogoController;
use App\Http\Controllers\API\ExecutiveController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post("/login", [AuthController::class, "login"]);

Route::middleware(['auth:api', 'check.token', 'checkLoginRequest', 'checkUserStatus'])->group(function () {

    //Case List And Submit Case
    Route::get("/all-cases", [CaseController::class, "allCaseList"])->name("all.case.list");

    //Odometer
    Route::post('/check-in/{id}', [ExecutiveController::class, 'checkIn'])->name('executive.check.in');
    Route::post('/check-out/{id}', [ExecutiveController::class, 'checkOut'])->name('executive.check.out');
    Route::get('/check-in-data', [ExecutiveController::class, 'checkInData'])->name('check.in.data');

    //Password Reset Request
    Route::post('/password-rest-request', [ExecutiveController::class, 'passwordResetRequest'])->name('password.rest.request.save');

    //Timeline
    Route::get('/time-line', [ExecutiveController::class, 'timeline'])->name('executive.timeline');

    //Chart - Report
    Route::get('/weekly-chart', [ExecutiveController::class, 'weeklyChart'])->name('executive.weekly.chart');
    Route::get('/monthly-chart', [ExecutiveController::class, 'monthlyChart'])->name('executive.monthly.chart');
    
    //Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/save-questiondata', [CaseController::class, 'storequestion']);

    //Logo 
    Route::get('/get-logo', [LogoController::class, 'index'])->name('get-logo');
    Route::post('/post-logo', [LogoController::class, 'store'])->name('post-logo');
    Route::get('/assign-work-data/{assign_id}',[CaseController::class, 'getAssignedWorks'])->name('assign-work-data');

    //Special cases
    Route::get("/all-special-cases", [CaseController::class, "specialCaseList"])->name("all-specialcase-list");

    Route::get('/insurance-company/{id}/questionnaire', [CaseController::class, 'getQuestionnaire']);

    Route::get('/case-assignments/{executive_id}/{period}', [CaseController::class, 'getAssignments']);

    
});


