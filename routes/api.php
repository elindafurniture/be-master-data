<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Master\BranchController;
use App\Http\Middleware\VerifyCoreToken;

Route::middleware([VerifyCoreToken::class])->group(function () {
    Route::prefix('master')->group(function () {
        // Branch routes
        Route::get('branch', [BranchController::class, 'index'])->name('master.branch.index');
        Route::get('branch/{id}', [BranchController::class, 'show'])->name('master.branch.show');
        Route::post('branch', [BranchController::class, 'store'])->name('master.branch.store');
        Route::put('branch/{id}', [BranchController::class, 'update'])->name('master.branch.update');
        Route::delete('branch/{id}', [BranchController::class, 'destroy'])->name('master.branch.destroy');
    });
});
