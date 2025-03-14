<?php

use App\Http\Controllers\Api\VoteController;
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
    Route::get("polls/{poll}", [PollController::class, "show"])->name("polls.show");

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('poll/{poll}', function () {
    return view('poll');
});

/*api routes*/
Route::get('polls/{poll}', [VoteController::class, 'show']);
Route::post('polls/{poll}/vote', [VoteController::class, 'vote']);

require __DIR__.'/auth.php';
