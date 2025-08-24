<?php

use App\Http\Controllers\BorderCrossingController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EntranceFeeController;
use App\Http\Controllers\EntryCardController;
use App\Http\Controllers\EntryStatementAdditionalFeeController;
use App\Http\Controllers\EntryStatementController;
use App\Http\Controllers\EntryStatementLogController;
use App\Http\Controllers\ExitStatementController;
use App\Http\Controllers\FinanceBoxController;
use App\Http\Controllers\FinanceTransactionController;
use App\Http\Controllers\FinancialReceiptController;
use App\Http\Controllers\LateFeeController;
use App\Http\Controllers\PrintController;
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
    if (auth()->user()->hasRole('Admin')) {
        return redirect('/dashboard');
    } else {
        return redirect('/entry-search');
    }
})->middleware('auth');

Route::get('/dashboard', [EntryStatementController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
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

Route::get('/barcode/{code}', function ($code) {
    $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
    return response($generator->getBarcode($code, $generator::TYPE_CODE_128))
        ->header('Content-type', 'image/png');
});

Route::middleware(['auth'])
    ->group(function () {
        Route::prefix('entry-statements/{entry}')->group(function () {
            Route::post('/additional-fees', [EntryStatementAdditionalFeeController::class, 'store'])->name('additional_fees.store');
            Route::patch('/additional-fees/{fee}', [EntryStatementAdditionalFeeController::class, 'update'])->name('additional_fees.update');
            Route::delete('/additional-fees/{fee}', [EntryStatementAdditionalFeeController::class, 'destroy'])->name('additional_fees.destroy');
        });
        Route::get('/entry-cards/{id}/print', [EntryCardController::class, 'print'])->name('entry-cards.print');
        Route::get('/finance/transactions', [FinanceTransactionController::class, 'index'])->name('finance.transactions.index');
        Route::get('/finance-boxes', [FinanceBoxController::class, 'index'])->name('finance.boxes.index');
        Route::get('/finance-receipts/transactions', [FinancialReceiptController::class, 'index'])->name('finance.receipts.index');

        Route::get('/finance/boxes/{box}/transactions', [FinanceTransactionController::class, 'boxTransactions'])->name('finance.box.transactions');
        Route::get('/print-card/{id}', [PrintController::class, 'printCard'])->name('print.card');
        Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
        Route::post('/profile/update-password', [UserController::class, 'updatePassword'])->name('profile.update_password');
        Route::get('/entry-search', [EntryStatementController::class, 'entrySearch'])->name('entrySearch');
        Route::get('/complete-enrty', [EntryStatementController::class, 'CompleteEnrty'])->name('CompleteEnrty');
        Route::get('/complete-exit', [EntryStatementController::class, 'CompleteExit'])->name('CompleteExit');
        Route::get('/entry-search-show', [EntryStatementController::class, 'entrySearch_show'])->name('entrySearch.show');
        Route::get('/user/{user}/logs', [UserLogController::class, 'userLogs'])->name('user.logs');
        Route::get('/entry-statements/{entry}/logs', [EntryStatementLogController::class, 'showLogs'])->name('entry.logs');
        Route::post('/entry-statements/checkout/{id}', [EntryStatementController::class, 'checkout'])->name('entry_statements.checkout');
        Route::post('/finance-exit/checkout/{id}', [EntryStatementController::class, 'FinanceExit'])->name('entry_statements.FinanceExit');
        Route::post('/finance-entry/checkout/{id}', [EntryStatementController::class, 'FinanceEntry'])->name('entry_statements.FinanceEntry');
        Route::post('/entry-statements/{entry}/add-violation', [EntryStatementController::class, 'addViolation'])->name('entry_statements.addviolation');
        Route::post('/entry-statements/{entry}/add-time', [EntryStatementController::class, 'addTime'])->name('entry_statements.addTime');

        Route::get('/exit-statements/create', [ExitStatementController::class, 'create'])->name('exit_statements.create');
        Route::post('/exit-statements/store', [ExitStatementController::class, 'store'])->name('exit_statements.store');
        Route::get('/exit-statements-book/create', [ExitStatementController::class, 'createByBook'])->name('exit_statements.searchBook');
        Route::get('/exit-statements-book/search-book', [ExitStatementController::class, 'searchByBook'])->name('exit_statements.searchBook');
        Route::post('/exit-statements-book/store-from-book', [ExitStatementController::class, 'storeFromBook'])->name('exit_statements.storeFromBook');


        Route::get('/entry-statements-book/create', [EntryStatementController::class, 'createByBook'])->name('entry_statements.searchBook');
        Route::get('/entry-statements-book/search-book', [EntryStatementController::class, 'searchByBook'])->name('entry_statements.searchBook');
        Route::post('/entry-statements-book/store-from-book', [EntryStatementController::class, 'storeFromBook'])->name('entry_statements.storeFromBook');

        Route::resource('/entry_statements', EntryStatementController::class);
        Route::resource('/exit-statements', ExitStatementController::class);
        Route::resource('/violations', ViolationController::class);
        Route::resource('/late-fees', LateFeeController::class);
        Route::resource('/entrance-fees', EntranceFeeController::class);
        Route::resource('/border_crossing', BorderCrossingController::class);
    });

require __DIR__ . '/auth.php';
