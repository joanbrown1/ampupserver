<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChargeController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MeterController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\FailedController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\DiscoController;
use Illuminate\Support\Facades\Route;

Route::get('welcome', [UserController::class, 'index']);

Route::group([], function() {  // Pass an empty array as the first argument
    Route::post('api/authUser', [UserController::class, 'login']);
    Route::post('api/users', [UserController::class, 'register']);
    Route::get('users', [UserController::class, 'allUsers'])->name('user.all');
    Route::post('users/email', [UserController::class, 'UsersByEmail'])->name('user.byEmail');
    Route::put('update/user', [UserController::class, 'update'])->name('user.update');
    Route::put('updatemeter', [UserController::class, 'update']);
    Route::post('password', [UserController::class, 'sendPasswordResetEmail']);
});

Route::group([], function() {
    Route::post('api/authAdmin', [AdminController::class, 'login']);
    Route::post('admins', [AdminController::class, 'register']);
    Route::get('admins', [AdminController::class, 'allAdmins'])->name('admin.all');
    Route::post('admins/email', [AdminController::class, 'AdminsByEmail'])->name('admin.byEmail');
    Route::put('update/admin', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('delete/admin/{email}', [AdminController::class, 'deleteByEmail'])->name('admin.delete');
});

Route::group([], function() {
    Route::post('charge', [ChargeController::class, 'create']);
    Route::get('charges', [ChargeController::class, 'allCharges'])->name('charge.all');
});

Route::group([], function() {
    Route::get('/discos', [DiscoController::class, 'index']);
    Route::put('/disco/{id}', [DiscoController::class, 'update']);
    Route::put('/discos/id', [DiscoController::class, 'DiscosById']);
});

Route::group([], function() {
    Route::post('conversation', [ConversationController::class, 'create']);
    Route::get('conversations', [ConversationController::class, 'getAll'])->name('conversation.all');
    Route::post('conversation/sender', [ConversationController::class, 'getBySenderEmail'])->name('conversation.sender');
    Route::delete('conversation/{id}', [ConversationController::class, 'delete'])->name('conversation.delete');
});

Route::group([], function() {
    Route::post('message', [MessageController::class, 'create']);
    Route::get('messages', [MessageController::class, 'getAll'])->name('message.all');
    Route::post('messages/convoid', [MessageController::class, 'getByConvoId'])->name('message.sender');
    Route::delete('messages/{convoid}', [MessageController::class, 'deleteByConvoId'])->name('message.delete');
});

Route::group([], function() {
    Route::post('discount', [DiscountController::class, 'create']);
    Route::get('discounts', [DiscountController::class, 'getAll'])->name('discount.all');
    Route::post('discounts/code', [DiscountController::class, 'searchByCode'])->name('discount.code');
});

Route::group([], function() {
    Route::post('faq', [FaqController::class, 'create']);
    Route::get('faqs', [FaqController::class, 'allFaqs'])->name('faq.all');
});

Route::group([], function() {
    Route::post('meter', [MeterController::class, 'create']);
    Route::get('meters', [MeterController::class, 'getAll'])->name('meter.all');
    Route::post('meters/email', [MeterController::class, 'searchByEmail'])->name('meter.email');
});

Route::group([], function() {
    Route::post('transaction', [TransactionController::class, 'create']);
    Route::get('transactions', [TransactionController::class, 'getAll'])->name('transaction.all');
    Route::post('transactions/email', [TransactionController::class, 'searchByEmail'])->name('message.sender');
    Route::post('transactions/ppid', [TransactionController::class, 'searchByPpid'])->name('message.sender');
    Route::post('transactions/date', [TransactionController::class, 'searchByDate'])->name('message.sender');
});

Route::group([], function() {
    Route::post('failed', [FailedController::class, 'create']);
    Route::get('faileds', [FailedController::class, 'getAll'])->name('failed.all');
    Route::post('faileds/email', [FailedController::class, 'searchByEmail'])->name('message.sender');
    Route::post('faileds/ppid', [FailedController::class, 'searchByPpid'])->name('message.sender');
    Route::post('faileds/date', [FailedController::class, 'searchByDate'])->name('message.sender');
});


Route::post('/support', [SupportController::class, 'sendEmail']);
