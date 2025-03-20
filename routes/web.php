<?php

use App\Http\Controllers\Api\PollController as ApiPollController;
use App\Http\Controllers\Api\VoteController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(["prefix" => "admin", "middleware" => ["auth", "verified", "admin"]], function() {
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

Route::prefix('poll')->group(function () {
    Route::view('/', 'poll');
    Route::view('/{slug}', 'poll');
});

/*api routes*/
Route::group(["prefix" => "polls"], function() {
    Route::get("/", [ApiPollController::class, "index"]);
    Route::get('/{slug}', [VoteController::class, 'show']);
    Route::post('/{pollId}/vote', [VoteController::class, 'vote']);
});


require __DIR__.'/auth.php';
