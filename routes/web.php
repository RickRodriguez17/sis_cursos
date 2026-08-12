<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WebhookController;
use App\Models\Course;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::get('/cursos', [CourseController::class, 'index'])->name('courses.index');
Route::get('/cursos/{course:slug}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/cursos/{course:slug}/lecciones/{lesson}', [CourseController::class, 'video'])->name('lessons.video');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
Route::post('/registro', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
Route::post('/pagos/webhook/{gateway}', [WebhookController::class, 'handle']);
Route::middleware('auth')->group(function () {
    Route::get('/carrito', [ShopController::class, 'cart'])->name('cart');
    Route::post('/carrito/{course}', [ShopController::class, 'add'])->name('cart.add');
    Route::delete('/carrito/{course}', [ShopController::class, 'remove'])->name('cart.remove');
    Route::post('/checkout', [ShopController::class, 'checkout'])->name('checkout');
    Route::get('/ordenes/{order}/esperando', [ShopController::class, 'waiting'])->name('orders.waiting');
    Route::get('/ordenes/{order}/estado', [ShopController::class, 'status'])->name('orders.status');
    Route::post('/ordenes/{order}/simular-pago', [ShopController::class, 'fakeConfirm'])->name('orders.fake');
    Route::get('/mis-cursos', [ShopController::class, 'myCourses'])->name('my.courses');
    Route::get('/mis-ordenes', [ShopController::class, 'myOrders'])->name('my.orders');
});
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', fn () => view('admin.index', ['courses' => Course::withCount('lessons')->latest()->get(), 'orders' => Order::with('user')->latest()->limit(20)->get()]))->name('admin.index');
});
