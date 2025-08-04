<?php

use App\Http\Controllers\AnfisController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AnfisController::class, 'index']);
