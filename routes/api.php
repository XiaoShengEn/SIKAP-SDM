<?php

use App\Http\Controllers\AgendaDataController;
use Illuminate\Support\Facades\Route;

Route::middleware(['agenda.api', 'throttle:60,1'])
    ->get('/agenda', [AgendaDataController::class, 'apiIndex']);
