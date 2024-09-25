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

Route::middleware(['auth'])->group(function () {

    // Route vers le tableau de bord après la connexion
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/no-company', function () {
    return view('errors.no-company');
})->name('no-company');

Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});


// Afficher la liste des sociétés
Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
// Formulaire de création de société
Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
// Enregistrer une nouvelle société
Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
// Route pour afficher le formulaire de modification d'une société
Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
// Route pour soumettre les modifications d'une société
Route::put('/companies/{company}', [CompanyController::class, 'update'])->name('companies.update');

// Afficher la liste des utilisateurs
Route::get('/users', [UserController::class, 'index'])->name('users.index');
// Formulaire de création d'un nouvel utilisateur
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
// Enregistrer un nouvel utilisateur
Route::post('/users', [UserController::class, 'store'])->name('users.store');
// Afficher les détails d'un utilisateur spécifique
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
// Éditer un utilisateur
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
// Mettre à jour un utilisateur
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
// Route pour supprimer un utilisateur
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

// Routes pour la gestion des catégories de matériels
Route::resource('categories', MaterialCategoryController::class)->middleware('auth');

// Routes pour la gestion des matériels
Route::resource('materials', MaterialController::class)->middleware('auth');
Route::get('/materials/create', [MaterialController::class, 'create'])->name('materials.create');
Route::post('/materials', [MaterialController::class, 'store'])->name('materials.store');
Route::get('/materials/{material}/edit', [MaterialController::class, 'edit'])->name('materials.edit');
Route::put('/materials/{material}', [MaterialController::class, 'update'])->name('materials.update');
Route::put('materials/{material}/remove', [MaterialController::class, 'removeFromStock'])->name('materials.remove');
Route::put('/materials/{id}', [MaterialController::class, 'update'])->name('materials.update');

Route::get('materials/{materialId}/history', [MaterialController::class, 'showHistory'])->name('materials.history');
Route::delete('histories/{id}', [HistoryController::class, 'destroy'])->name('history.destroy');
Route::resource('history', HistoryController::class);

Route::resource('device_identifiers', DeviceIdentifierController::class);
Route::resource('suppliers', SupplierController::class);

Route::get('company/settings', [CompanyController::class, 'settings'])->name('company.settings');
Route::put('company/{company}', [CompanyController::class, 'update'])->name('company.update');
Route::put('company/{id}', [CompanyController::class, 'update'])->name('company.update');

Route::resource('employees', EmployeeController::class);

Route::get('/stocks', [DeviceIdentifierController::class, 'stockOverview'])->name('stocks.index');

Route::resource('clients', ClientController::class);

Route::resource('quotes', QuoteController::class);
Route::get('/quotes/{id}/download', [QuoteController::class, 'downloadPDF'])->name('quotes.downloadPDF');

Route::resource('invoices', InvoiceController::class);
Route::post('/quotes/{id}/convert-to-invoice', [QuoteController::class, 'convertToInvoice'])->name('quotes.convertToInvoice');
Route::get('/invoices/{id}/download', [InvoiceController::class, 'downloadPDF'])->name('invoices.downloadPDF');

Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');

Route::get('/calendar', [AppointmentController::class, 'index'])->name('calendar.index');
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
