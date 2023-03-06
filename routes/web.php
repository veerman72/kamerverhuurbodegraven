<?php

use App\Http\Controllers\BuildingController;
use App\Http\Controllers\BuildingUnitController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\OwnerBuildingController;
use App\Http\Controllers\OwnerBuildingUnitController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantContractController;
use App\Http\Controllers\TenantController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')
    ->scopeBindings()
    ->group(function () {
        Route::resource('buildings', BuildingController::class);
        Route::resource('buildings.units', BuildingUnitController::class);
        Route::resource('contracts', ContractController::class);
        Route::resource('owners', OwnerController::class);
        Route::resource('owners.buildings', OwnerBuildingController::class);
        Route::resource('owners.buildings.units', OwnerBuildingUnitController::class);
        Route::resource('tenants', TenantController::class);
        Route::resource('tenants.contracts', TenantContractController::class);
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/contracts/{contract}/pdf', function (App\Models\Contract $contract) {
    $pdf = new \App\Actions\Contracts\GenerateContractRoz2017($contract);
    $pdf->generate();
});

require __DIR__ . '/auth.php';
