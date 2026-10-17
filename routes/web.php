<?php

use App\Http\Controllers\BugController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BugController::class, 'signupPage']);
Route::post('/signup', [BugController::class, 'handleSignup']);
Route::get('/login', [BugController::class, 'loginPage'])->name('login');
Route::post('/login', [BugController::class, 'handleLogin']);

Route::middleware('auth')->group(function () {
    Route::get('/create-project', [BugController::class, 'createProjectPage']);
    Route::post('/create-project', [BugController::class, 'handleCreateProject']);

    Route::get('/add-developers', [BugController::class, 'addDevelopersPage']);
    Route::post('/team-members', [BugController::class, 'addTeamMember']);
    Route::post('/add-developers', [BugController::class, 'storeDeveloper']);
    Route::put('/developers/{id}', [BugController::class, 'updateDeveloper'])->whereNumber('id');
    Route::delete('/developers/{id}', [BugController::class, 'deleteDeveloper'])->whereNumber('id');

    Route::get('/dashboard', [BugController::class, 'dashboardPage']);
    Route::get('/workspace', [BugController::class, 'workspacePage']);
    Route::get('/ai-agent', [BugController::class, 'aiAgentPage']);
    Route::post('/api/bugs/store', [BugController::class, 'store']);
    Route::post('/api/bugs/override', [BugController::class, 'override']);
    Route::get('/logout', [BugController::class, 'logout']);
    Route::post('/logout', [BugController::class, 'logout']);
});