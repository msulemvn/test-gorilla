<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizInstanceController;
use App\Http\Controllers\QuizRecordController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SupervisorController;
use App\Models\QuizRecord;

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

/**
  ____        _     _ _        ____             _            
 |  _ \ _   _| |__ | (_) ___  |  _ \ ___  _   _| |_ ___  ___ 
 | |_) | | | | '_ \| | |/ __| | |_) / _ \| | | | __/ _ \/ __|
 |  __/| |_| | |_) | | | (__  |  _ < (_) | |_| | ||  __/\__ \
 |_|    \__,_|_.__/|_|_|\___| |_| \_\___/ \__,_|\__\___||___/
                                                             
 */

Route::post('/apply', [ApplicationController::class, 'store']);
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('forgot-password', 'forgotPassword');
    Route::post('reset-password', 'resetPassword')->name('password.reset');
    Route::post('verify-token', 'verifyToken');
});

/**
     _         _   _                _   _           _           _   ____             _            
    / \  _   _| |_| |__   ___ _ __ | |_(_) ___ __ _| |_ ___  __| | |  _ \ ___  _   _| |_ ___  ___ 
   / _ \| | | | __| '_ \ / _ \ '_ \| __| |/ __/ _` | __/ _ \/ _` | | |_) / _ \| | | | __/ _ \/ __|
  / ___ \ |_| | |_| | | |  __/ | | | |_| | (_| (_| | ||  __/ (_| | |  _ < (_) | |_| | ||  __/\__ \
 /_/   \_\__,_|\__|_| |_|\___|_| |_|\__|_|\___\__,_|\__\___|\__,_| |_| \_\___/ \__,_|\__\___||___/
                                                                                                  
 */


Route::middleware('auth:api')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
        Route::post('change-password', 'changePassword');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('profile', 'showUser');
    });

    Route::controller(UserController::class)->middleware('role:admin')->group(function () {
        Route::get('users', 'show');
    });

    Route::controller(ApplicationController::class)->middleware('role:admin,manager')->group(function () {
        Route::get('applications', 'show');
        Route::post('accept-application/{applicationId}', 'acceptApplication');
        Route::post('reject-application/{applicationId}', 'rejectApplication');
        Route::get('attachment', 'url');
    });

    Route::controller(StudentController::class)->middleware('role:admin,manager')->group(function () {
        Route::post('delete-student/{student}', 'destroy');
        Route::get('students', 'show');
    });

    Route::controller(QuizController::class)->group(function () {
        Route::get('quizzes', 'show');
    });

    Route::controller(QuizInstanceController::class)->middleware('role:admin,manager')->group(function () {
        Route::post('assign-quiz', 'store');
        Route::get('assigned-quizzes', 'show');
    });

    Route::controller(QuizRecordController::class)->middleware('role:admin,manager')->group(function () {
        Route::get('view-results', 'show');
        Route::get('recording', 'url');
    });

    Route::controller(QuizRecordController::class)->middleware('role:student')->group(function () {
        Route::get('view-results/{id}', 'show');
    });

    Route::controller(QuizInstanceController::class)->middleware('role:student')->group(function () {
        Route::get('assigned-quizzes/{studentId}', 'show');
    });

    Route::controller(ManagerController::class)->middleware('role:admin')->group(function () {
        Route::post('add-manager', 'store');
        Route::post('delete-manager/{manager}', 'destroy');
        Route::get('managers', 'show');
    });

    Route::controller(SupervisorController::class)->middleware('role:admin')->group(function () {
        Route::post('add-supervisor', 'store');
        Route::post('delete-supervisor/{supervisor}', 'destroy');
        Route::get('supervisors', 'show');
    });

    Route::controller(QuizRecordController::class)->middleware('role:student')->group(function () {
        Route::post('submit-quiz', 'store');
    });
});
