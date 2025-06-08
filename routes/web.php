<?php

use App\Http\Controllers\BorderCrossingController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EntranceFeeController;
use App\Http\Controllers\EntryStatementController;
use App\Http\Controllers\EntryStatementLogController;
use App\Http\Controllers\ExitStatementController;
use App\Http\Controllers\LateFeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserLogController;
use App\Http\Controllers\ViolationController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', [EntryStatementController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::get('/users', function () {
    return view('users.index');
})->middleware('auth')->name('users.index');
Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');


Route::resource('roles', RoleController::class);
Route::resource('users', UserController::class);
Route::get('user-create', [UserController::class, 'create_user']);


Route::middleware(['auth'])
    ->group(function () {
        Route::get('/user/{user}/logs', [UserLogController::class, 'userLogs'])->name('user.logs');
        Route::resource('/entry_statements', EntryStatementController::class);
        Route::get('/entry-statements/{entry}/logs', [EntryStatementLogController::class, 'showLogs'])->name('entry.logs');
        Route::post('/entry-statements/checkout/{id}', [EntryStatementController::class, 'checkout'])->name('entry_statements.checkout');
        Route::post('/finance-exit/checkout/{id}', [EntryStatementController::class, 'FinanceExit'])->name('entry_statements.FinanceExit');
        Route::post('/entry-statements/{entry}/add-violation', [EntryStatementController::class, 'addViolation'])->name('entry_statements.addviolation');
        Route::resource('/exit-statements', ExitStatementController::class);
        Route::resource('/violations', ViolationController::class);
        Route::resource('/late-fees', LateFeeController::class);
        Route::resource('/entrance-fees', EntranceFeeController::class);
        Route::resource('/border_crossing', BorderCrossingController::class);
    });

require __DIR__ . '/auth.php';
