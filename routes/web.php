<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Chatcontroller;

Route::get('/chat', [ChatController::class, 'index']);
Route::post('/chat/send', [ChatController::class, 'send']);
