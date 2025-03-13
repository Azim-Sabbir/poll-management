<?php

use App\Http\Controllers\PollController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(["prefix" => "admin", "middleware" => ["auth", "verified"]], function() {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get("polls", [PollController::class, "index"])->name("polls.index");
    Route::post("polls", [PollController::class, "store"])->name("polls.store");

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
