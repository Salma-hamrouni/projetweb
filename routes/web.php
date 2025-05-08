<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ObjectifController;
use App\Http\Controllers\EtapeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProgressionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\MindmapController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\TimelineController;

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Page dashboard protégée par l'authentification
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// Routes sécurisées par middleware 'auth' (authentification)
Route::middleware(['auth'])->group(function () {

    // Objectifs
    Route::get('/objectifs', [ObjectifController::class, 'index'])->name('objectifs.index');
    Route::get('/objectifs/create', [ObjectifController::class, 'create'])->name('objectifs.create');
    Route::post('/objectifs', [ObjectifController::class, 'store'])->name('objectifs.store');
    Route::get('/objectifs/{objectif}', [ObjectifController::class, 'show'])->name('objectifs.show');
    Route::get('/objectifs/{objectif}/edit', [ObjectifController::class, 'edit'])->name('objectifs.edit');
    Route::put('/objectifs/{objectif}', [ObjectifController::class, 'update'])->name('objectifs.update');
    Route::delete('/objectifs/{objectif}', [ObjectifController::class, 'destroy'])->name('objectifs.destroy');
    
    // Étapes
    Route::get('/etapes', [EtapeController::class, 'index'])->name('etapes.index');
    Route::get('/etapes/create', [EtapeController::class, 'create'])->name('etapes.create');
    Route::post('/etapes', [EtapeController::class, 'store'])->name('etapes.store');
    Route::get('/etapes/{etape}', [EtapeController::class, 'show'])->name('etapes.show');
    Route::get('/etapes/{etape}/edit', [EtapeController::class, 'edit'])->name('etapes.edit');
    Route::put('/etapes/{etape}', [EtapeController::class, 'update'])->name('etapes.update');
    Route::delete('/etapes/{etape}', [EtapeController::class, 'destroy'])->name('etapes.destroy');

    Route::get('/progressions', [ProgressionController::class, 'index'])->name('progressions.index');

    Route::get('/timeline', [TimelineController::class, 'index'])->name('timeline.index');

    Route::get('/profile/index', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/photo', [ProfileController::class, 'uploadPhoto'])->name('profile.photo.upload');

Route::get('/map', [MapController::class, 'index'])->name('map.index');

Route::get('/mindmap', [MindmapController::class, 'index'])->name('mindmap.index');
Route::get('/objectifs-partages', [DashboardController::class, 'showSharedObjectifs'])->name('shared_objectifs');

// Dans routes/web.php
Route::get('/ask', [MindmapController::class, 'ask']);

Route::get('/calendrier', [CalendarController::class, 'index'])->name('calendrier.index');




 
});

// Routes d'authentification pour la connexion, l'inscription, etc.
require __DIR__.'/auth.php';

// Routes de Home
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
