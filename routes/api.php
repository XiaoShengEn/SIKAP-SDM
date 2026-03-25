<?php

use App\Http\Controllers\AgendaDataController;
use Illuminate\Support\Facades\Route;

Route::get('/agenda', [AgendaDataController::class, 'apiIndex']);

