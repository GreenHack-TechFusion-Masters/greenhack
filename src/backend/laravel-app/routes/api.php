<?php

// third party libs...
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// controllers
use App\Http\Controllers\API\EleveController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\ParentsController;
use App\Http\Controllers\API\ProfesseurController;
use App\Http\Controllers\API\PersonnelController;
use App\Http\Controllers\API\UploadController;
use App\Http\Controllers\API\MembreConseilController;
use App\Http\Controllers\API\OtherController;

/*
|--------------------------------------------------------------------------
| api Routes
|--------------------------------------------------------------------------
|Closes #215 - convocation,fautes,professeurs
| Here is where you can register api routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



/**
 * publics routes
 */
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::middleware('jwt.verify')->post('logout', [AuthController::class, 'logout']);
    Route::middleware('jwt.verify')->post('user', [AuthController::class, 'user']);
});

Route::prefix('files')->group(function () {
    // file download
    Route::prefix('download')->group(function () {
        Route::get('/', [UploadController::class, 'downloadUserAvatar']);
    });
});

/**
 * secured routes
 */
Route::middleware('jwt.verify')->group(function () {

    Route::middleware('role:' . ADMIN_ROLE['name'])->group(function () {

        // users
        Route::prefix('users')->group(function () {
            Route::get('/findAll', [UserController::class, 'index']);
            Route::get('/findOne/{userId}', [UserController::class, 'show']);
            Route::post('/create', [UserController::class, 'store']);
            Route::put('/update/{userId}', [UserController::class, 'update']);
            Route::delete('/delete/{userId}', [UserController::class, 'destroy']);
        });

        // permissions
        Route::prefix('permissions')->group(function () {
            Route::get('/findAll', [PermissionController::class, 'index']);
            Route::get('/findOne/{permissionId}', [PermissionController::class, 'show']);
            Route::post('/create', [PermissionController::class, 'store']);
            Route::put('/update/{permissionId}', [PermissionController::class, 'update']);
            Route::put('/update/status/{permisionId}', [PermissionController::class, 'updateStatus']);
            Route::delete('/delete/{permissionId}', [PermissionController::class, 'destroy']);
        });

        // roles
        Route::prefix('roles')->group(function () {
            Route::get('/findAll', [RoleController::class, 'index']);
            Route::get('/findOne/{roleId}', [RoleController::class, 'show']);
            Route::post('/create', [RoleController::class, 'store']);
            Route::put('/update/{roleId}', [RoleController::class, 'update']);
            Route::put('/update/status/{roleId}', [RoleController::class, 'updateStatus']);
            Route::delete('/delete/{roleId}', [RoleController::class, 'destroy']);
        });
    });

    // eleves
    Route::prefix('eleves')->group(function () {
        Route::get('findOne/{eleveId}', [EleveController::class, 'view']);
        Route::get('findAll', [EleveController::class, 'index']);
        Route::get('records/{keyword}', [EleveController::class, 'records']);

        Route::middleware('permission:modifier_eleve')->post('update/{eleveId}', [EleveController::class, 'update']);
        Route::middleware('permission:supprimer_eleve')->delete('delete/{eleveId}', [EleveController::class, 'delete']);
        Route::middleware('permission:creer_eleve')->post('create', [EleveController::class, 'store']);
    });

    // membreconseils
    Route::prefix('membreconseils')->group(function () {
        Route::get('findOne/{membreconseilId}', [MembreConseilController::class, 'view']);
        Route::get('findAll/', [MembreConseilController::class, 'index']);

        Route::middleware('permission:modifier_conseil')->post('update/{eleveId}', [MembreConseilController::class, 'update']);
        Route::middleware('permission:supprimer_conseil')->delete('delete/{eleveId}', [MembreConseilController::class, 'delete']);
        Route::middleware('permission:creer_conseil')->post('create/', [MembreConseilController::class, 'store']);
    });

    // notifications
    Route::prefix('notification')->group(function () {
        Route::get('findOne/{notificationId}', [NotificationController::class, 'view']);
        Route::get('findAll/', [NotificationController::class, 'index']);

        Route::middleware('permission:modifier_notification')->put('update/{notificationId}', [NotificationController::class, 'update']);
        Route::middleware('permission:supprimer_notification')->delete('delete/{notificationId}', [NotificationController::class, 'delete']);
        Route::middleware('permission:creer_notification')->post('create/', [NotificationController::class, 'store']);
    });

    // parents
    Route::prefix('parents')->group(function () {
        Route::get('findAll', [ParentsController::class, 'index']);
        Route::get('findOne/{parentId}', [ParentsController::class, 'view']);

        Route::get('parentNotif/{eleveId}', [ParentsController::class, 'parentNotification']);

        Route::middleware('permission:modifier_parent')->post('update/{parentId}', [ParentsController::class, 'update']);
        Route::middleware('permission:creer_parent')->post('create', [ParentsController::class, 'store']);
        Route::middleware('permission:supprimer_parent')->delete('delete/{parentId}', [ParentsController::class, 'delete']);
    });

    // personnel
    Route::prefix('personnel')->group(function () {
        Route::get('findAll', [PersonnelController::class, 'index']);
        Route::get('findOne/{personnelId}', [PersonnelController::class, 'view']);

        Route::middleware('permission:creer_personnel')->post('create', [PersonnelController::class, 'store']);
        Route::middleware('permission:modifier_personnel')->post('update/{personnelId}', [PersonnelController::class, 'update']);
        Route::middleware('permission:supprimer_personnel')->delete('delete/{personnelId}', [PersonnelController::class, 'delete']);
    });

    //professeurs
    Route::prefix('professeurs')->group(function () {
        Route::middleware('permission:creer_professeur')->post('create', [ProfesseurController::class, 'store']);
        Route::middleware('permission:modifier_professeur')->post('update/{professeurId}', [ProfesseurController::class, 'update']);
        Route::middleware('permission:supprimer_professeur')->delete('delete/{professeurId}', [ProfesseurController::class, 'delete']);
        Route::get('findOne/{professeurId}', [ProfesseurController::class, 'view']);
        Route::get('findAll', [ProfesseurController::class, 'index']);
    });
});

Route::get('eleves/mostDisciplines/', [EleveController::class, 'mostDisciplines']);

Route::get('prof/findAll', [ProfesseurController::class, 'index']);

Route::post('contact', [OtherController::class, 'contact']);
