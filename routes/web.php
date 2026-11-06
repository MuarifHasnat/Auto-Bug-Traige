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
    Route::post('/project/switch', [BugController::class, 'switchProject'])->name('projects.switch');

    Route::get('/add-developers', [BugController::class, 'addDevelopersPage']);
    Route::post('/team-members', [BugController::class, 'addTeamMember']);
    Route::post('/add-developers', [BugController::class, 'storeDeveloper']);
    Route::put('/developers/{id}', [BugController::class, 'updateDeveloper'])->whereNumber('id');
    Route::delete('/developers/{id}', [BugController::class, 'deleteDeveloper'])->whereNumber('id');

    Route::get('/dashboard', [BugController::class, 'dashboardPage']);
    Route::get('/workspace', [BugController::class, 'workspacePage']);
    Route::get('/ai-agent', [BugController::class, 'aiAgentPage']);
    Route::get('/notifications', [BugController::class, 'notificationsPage'])->name('notifications.index');
    Route::post('/notifications/{id}/open', [BugController::class, 'openNotification'])->name('notifications.open');
    Route::post('/notifications/{id}/read', [BugController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [BugController::class, 'markAllNotificationsRead'])->name('notifications.read-all');
    Route::post('/api/bugs/store', [BugController::class, 'store']);
    Route::post('/api/bugs/override', [BugController::class, 'override']);
    Route::post('/api/bugs/reassign', [BugController::class, 'reassign'])->name('bugs.reassign');
    Route::post('/api/bugs/comments', [BugController::class, 'storeComment'])->name('bugs.comments');
    Route::post('/api/bugs/watch', [BugController::class, 'toggleWatch'])->name('bugs.watch');
    Route::post('/api/bug-filters', [BugController::class, 'saveBugFilter'])->name('bug-filters.store');
    Route::delete('/api/bug-filters/{id}', [BugController::class, 'deleteBugFilter'])->whereNumber('id')->name('bug-filters.destroy');
    Route::get('/logout', [BugController::class, 'logout']);
    Route::post('/logout', [BugController::class, 'logout']);
});
