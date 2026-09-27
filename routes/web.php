<?php

use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Beforbim Public Website Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [PublicPageController::class, 'home'])->name('home');

// About Us
Route::get('/about', [PublicPageController::class, 'about'])->name('about');

// Contact Us & Inquiries
Route::get('/contact', [PublicPageController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicPageController::class, 'submitContact'])->name('contact.submit');

// Blog & Knowledge Base
Route::get('/blog', [PublicPageController::class, 'blog'])->name('blog.index');
Route::get('/blog/{slug}', [PublicPageController::class, 'blogShow'])->name('blog.show');

// Instructors
Route::get('/instructors', [PublicPageController::class, 'instructors'])->name('instructors.index');
Route::get('/instructors/{user}', [PublicPageController::class, 'instructorShow'])->name('instructors.show');
