<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BarberController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BarberScheduleController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Маршруты для администратора
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/barbers', [BarberScheduleController::class, 'index'])->name('admin.barbers.index');
    Route::get('/barbers/{barber}/schedule', [BarberScheduleController::class, 'edit'])->name('admin.barbers.schedule.edit');
    Route::put('/barbers/{barber}/schedule', [BarberScheduleController::class, 'update'])->name('admin.barbers.schedule.update');
    Route::get('/barbers/{barber}/appointments', [BarberScheduleController::class, 'getAppointments']);
    
    // Маршруты для блокировки времени
    Route::post('/barbers/{barber}/block-time', [BarberScheduleController::class, 'blockTime'])->name('admin.barbers.block-time');
    Route::delete('/blocked-time/{id}', [BarberScheduleController::class, 'unblockTime'])->name('admin.barbers.unblock-time');
    Route::put('/blocked-time/{id}', [BarberScheduleController::class, 'updateBlockedTime'])->name('admin.barbers.update-blocked-time');
});

// Редирект с /dashboard на админ-панель для администраторов
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');
});

// Публичные маршруты
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/barbers', [BarberController::class, 'index'])->name('barbers.index');
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');

// Маршруты для авторизованных пользователей
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Маршрут для проверки доступного времени должен быть ДО ресурсного маршрута
    Route::get('/appointments/available-times', [AppointmentController::class, 'getAvailableTimes'])
        ->name('appointments.available-times');
    
    Route::resource('appointments', AppointmentController::class);
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])
        ->name('appointments.update-status');
    Route::resource('reviews', ReviewController::class)->except(['index', 'show']);
});

require __DIR__.'/auth.php';
