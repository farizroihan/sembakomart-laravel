<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{HomeController,AuthController,CartController,OrderController};
use App\Http\Controllers\Admin\{ProductController,OrderController as AdminOrderController};
Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/produk/{product}',[HomeController::class,'show'])->name('products.show');
Route::get('/kategori/{category}',[HomeController::class,'category'])->name('categories.show');
Route::get('/daftar',[AuthController::class,'registerForm'])->name('register');
Route::post('/daftar',[AuthController::class,'register'])->name('register.store');
Route::get('/masuk',[AuthController::class,'loginForm'])->name('login');
Route::post('/masuk',[AuthController::class,'login'])->name('login.store');
Route::post('/keluar',[AuthController::class,'logout'])->middleware('auth')->name('logout');
Route::get('/keranjang',[CartController::class,'index'])->name('cart');
Route::post('/keranjang/{product}',[CartController::class,'add'])->name('cart.add');
Route::patch('/keranjang/{product}',[CartController::class,'update'])->name('cart.update');
Route::delete('/keranjang/{product}',[CartController::class,'remove'])->name('cart.remove');
Route::middleware('auth')->group(function(){
 Route::get('/checkout',[OrderController::class,'checkout'])->name('checkout');
 Route::post('/checkout',[OrderController::class,'store'])->name('checkout.store');
 Route::get('/pesanan-saya',[OrderController::class,'mine'])->name('orders.mine');
});
Route::prefix('admin')->name('admin.')->middleware(['auth','admin'])->group(function(){
 Route::get('/',[ProductController::class,'dashboard'])->name('dashboard');
 Route::resource('produk',ProductController::class)->except('show');
 Route::get('/pesanan',[AdminOrderController::class,'index'])->name('orders');
 Route::patch('/pesanan/{order}',[AdminOrderController::class,'status'])->name('orders.status');
});