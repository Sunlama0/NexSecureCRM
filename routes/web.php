<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MaterialCategoryController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\DeviceIdentifierController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AppointmentController;

// Route pour la page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Routes d'authentification fournies par Laravel Breeze
require __DIR__.'/auth.php';

// Routes pour les utilisateurs connectés
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gestion du profil
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Gestion des utilisateurs
    Route::resource('users', UserController::class);

    // Gestion des sociétés
    Route::resource('companies', CompanyController::class)->except(['show']);
    Route::get('company/settings', [CompanyController::class, 'settings'])->name('company.settings');
    Route::put('company/{company}', [CompanyController::class, 'update'])->name('company.update');

    // Gestion des catégories de matériels
    Route::resource('categories', MaterialCategoryController::class);

    // Gestion des matériels
    Route::resource('materials', MaterialController::class);
    Route::put('materials/{material}/remove', [MaterialController::class, 'removeFromStock'])->name('materials.remove');
    Route::get('materials/{materialId}/history', [MaterialController::class, 'showHistory'])->name('materials.history');

    // Gestion des identifiants d'appareils et des fournisseurs
    Route::resource('device_identifiers', DeviceIdentifierController::class);
    Route::resource('suppliers', SupplierController::class);

    // Gestion des employés
    Route::resource('employees', EmployeeController::class);

    // Gestion des stocks
    Route::get('/stocks', [DeviceIdentifierController::class, 'stockOverview'])->name('stocks.index');

    // Gestion des clients
    Route::resource('clients', ClientController::class);

    // Gestion des devis
    Route::resource('quotes', QuoteController::class);
    Route::get('/quotes/{id}/download', [QuoteController::class, 'downloadPDF'])->name('quotes.downloadPDF');
    Route::post('/quotes/{id}/convert-to-invoice', [QuoteController::class, 'convertToInvoice'])->name('quotes.convertToInvoice');

    // Gestion des factures
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices/{id}/download', [InvoiceController::class, 'downloadPDF'])->name('invoices.downloadPDF');

    // Gestion des paiements
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');

    // Gestion du calendrier et des rendez-vous
    Route::get('/calendar', [AppointmentController::class, 'index'])->name('calendar.index');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
});

// Routes pour la gestion des erreurs
Route::get('/no-company', function () {
    return view('errors.no-company');
})->name('no-company');

// Déconnexion
Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
