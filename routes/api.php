<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchemeOfStudyController;

Route::post('/schemes', [SchemeOfStudyController::class, 'store'])->name('scheme.store');
