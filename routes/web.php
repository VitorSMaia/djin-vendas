<?php

use Illuminate\Support\Facades\Route;
use Opcodes\LogViewer\LogViewer;

Route::view('/', 'home')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::view('vendas', 'vendas')->name('vendas');

    Route::get('logs', fn() => LogViewer::showRoute())
        ->name('logs');


    Route::get('/dashboard', \App\Livewire\Reports\SalesDashboard::class)->name('dashboard');
    Route::get('/estoque', \App\Livewire\Products\InventoryManager::class)->name('estoque');
    Route::view('profile', 'profile')->name('profile');
});

require __DIR__ . '/auth.php';
