<?php

use App\Http\Controllers\DomainController;
use App\Http\Controllers\DomainTransferController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterDomainController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('registration')->middleware('auth')->group(function () {
    Route::get('/', [RegisterDomainController::class, 'index'])->name('register.index');
    Route::post('/{domain}/store', [RegisterDomainController::class, 'store'])->name('register.domain');
});
Route::prefix('domains')->group(function () {
    Route::get('/', [DomainController::class, 'index'])->name('domains.index');
    Route::post('/search', [DomainController::class, 'search'])->name('domains.search');
    Route::get('/transfer', [DomainTransferController::class, 'index'])->name('domains.transfer');
    Route::post('/transfer/initiate', [DomainTransferController::class, 'initiateTransfer'])->name('domains.transfer.initiate');
    Route::get('/transfer/status/{domainName}', [DomainTransferController::class, 'checkStatus'])->name('domains.transfer.status');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
