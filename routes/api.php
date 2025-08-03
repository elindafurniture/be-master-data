<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Master\BranchController;
use App\Http\Middleware\VerifyCoreToken;

Route::middleware([VerifyCoreToken::class])->group(function () {
    Route::prefix('master')->group(function () {
        Route::get('branch', [BranchController::class, 'index'])->name('master.branch.index');
        Route::post('branch', [BranchController::class, 'store']);
    });
});
