<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\User\UserMLMController;
use App\Http\Controllers\User\UserBankDetailController;
use App\Http\Controllers\User\FundSummaryController;
use App\Http\Controllers\User\FundRequestController;
use App\Http\Controllers\User\FundRequestStatusController;
use App\Http\Controllers\User\FundTransferController;
use App\Http\Controllers\User\FundHistoryController;

// 1. Login routes (GET shows form, POST processes login)
Route::match(['get', 'post'], '/', [LoginController::class, 'handleLogin'])->name('login');

// Dashboard (protected)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// 3. Logout route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



// Buy Now Routes
Route::get('/buy-now', [ProductController::class, 'buyNow'])->name('buy-now');
Route::post('/purchase', [ProductController::class, 'purchase'])->name('purchase');




Route::get('/my-profile', [UserController::class, 'editProfile'])->name('user.profile');
Route::post('/my-profile/update', [UserController::class, 'updateProfile'])->name('user.profile.update');

 Route::get('/profile-image', [UserController::class, 'editProfileImage'])->name('user.profile.image');
    Route::post('/profile-image/upload', [UserController::class, 'uploadProfileImage'])->name('user.profile.image.upload');

    Route::get('/change-password', [UserController::class, 'showChangePasswordForm'])->name('user.change-password');
    Route::post('/change-password', [UserController::class, 'changePassword'])->name('user.change-password.update');

      // Change transaction password
    Route::get('/change-transaction-password', [UserController::class, 'showChangeTransactionPasswordForm'])->name('user.change-transaction-password');
    Route::post('/change-transaction-password', [UserController::class, 'changeTransactionPassword'])->name('user.change-transaction-password.update');
    
    // Forgot transaction password
    Route::get('/forgot-transaction-password', [UserController::class, 'showForgotTransactionPasswordForm'])->name('user.forgot-transaction-password');
    Route::post('/forgot-transaction-password', [UserController::class, 'forgotTransactionPassword'])->name('user.forgot-transaction-password.submit');


     Route::get('/welcome-letter', [UserController::class, 'welcomeLetter'])->name('user.welcome-letter');

      Route::get('/visiting-card', [UserController::class, 'visitingCard'])->name('user.visiting-card');
    Route::post('/visiting-card/download', [UserController::class, 'downloadVisitingCard'])->name('user.visiting-card.download');
     Route::get('/signup-acknowledgement', [UserController::class, 'signupAcknowledgement'])->name('user.signup-acknowledgement');

Route::get('/direct-business', [UserMLMController::class, 'directBusiness'])
    ->name('user.direct-business');

Route::get('/downline-business', [UserMLMController::class, 'downlineBusiness'])
    ->name('user.downline-business');

Route::get('/genealogy', [UserMLMController::class, 'genealogy'])
    ->name('user.genealogy');

   
 Route::get('/user-profile/{userId}/modal', [UserMLMController::class, 'getUserProfileModal'])
        ->name('user.profile.modal');

        Route::get('/user-tree/{userId}/html', [UserMLMController::class, 'getUserTreeHtml'])
    ->name('user.tree.html');

    Route::get('/admin-bank-detail', [UserBankDetailController::class, 'index'])
    ->name('user.admin-bank-detail');

    Route::get('/fund-summary', [FundSummaryController::class, 'index'])
    ->name('user.fund-summary');

    
Route::get('/fund-request', [FundRequestController::class, 'index'])
    ->name('user.fund-request');

Route::get('/api/fund-request/bank-details', [FundRequestController::class, 'getBankDetails'])
    ->name('user.fund-request.bank-details');

Route::post('/fund-request/submit', [FundRequestController::class, 'submit'])
    ->name('user.fund-request.submit');
    // Fund Request Status
Route::get('/fund-request-status', [FundRequestStatusController::class, 'index'])
    ->name('user.fund-request-status');

// Fund Transfer
Route::get('/fund-transfer', [FundTransferController::class, 'index'])
    ->name('user.fund-transfer');
Route::post('/fund-transfer/transfer', [FundTransferController::class, 'transfer'])
    ->name('user.fund-transfer.transfer');
Route::get('/fund-list', [FundTransferController::class, 'getSentTransfers'])
    ->name('user.fund-list');
Route::get('/fund-receive-list', [FundTransferController::class, 'getReceivedTransfers'])
    ->name('user.fund-receive-list');
Route::get('/api/wallet-balance', [FundTransferController::class, 'getWalletBalance'])
    ->name('user.wallet-balance');

// Fund History
Route::get('/fund-history', [FundHistoryController::class, 'index'])
    ->name('user.fund-history');