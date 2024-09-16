<?php

use App\Http\Controllers\Auth\MyLoginController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('register', function () {
    return view('register');
})->name('register');


Route::get('addContanct', function () {
    return view('addContact');
})->name('addContanct');

Route::get('thanks', function () {
    return view('thankyou');
})->name('thanks');

Route::get('/home', [ContactController::class, 'index'])->name('contacts.index');

Route::post('login', [MyLoginController::class, 'login'])->name('login.post');
Route::post('register', [RegistrationController::class, 'store'])->name('register.post');
Route::post('contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('contact/{id}/edit', [ContactController::class, 'edit'])->name('contact.edit');
Route::post('contact/{id}', [ContactController::class, 'destroy'])->name('contact.destroy');
Route::put('contact/{id}', [ContactController::class, 'update'])->name('contact.update');
Route::get('search', [ContactController::class, 'search'])->name('contact.search');

Route::post('/logout', [MyLoginController::class, 'logout'])->name('logout');
