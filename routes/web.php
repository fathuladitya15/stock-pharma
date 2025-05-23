<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\POQController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;

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

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth::routes();
Route::get('/login',[LoginController::class,'showLoginForm'])->name('login');
Route::post('/login',[LoginController::class,'login'])->name('login.process');
Route::post('/logout',[LoginController::class,'logout'])->name('logout');

Route::middleware(['auth'])->group(function ()  {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::middleware(['role:admin'])->group(function() {
        Route::prefix('users')->group(function() {
            Route::get('/', [UserController::class, 'index'])->name('users');
            Route::get('/data', [UserController::class, 'fetchUsers'])->name('users.data');
            Route::get('/show/{id}', [UserController::class, 'show'])->name('users.show');
            Route::post('/save', [UserController::class, 'store'])->name('users.save');
            Route::put('/update/{id}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('users.delete');
        });
    });
    Route::middleware(['role:admin|staff_gudang'])->group(function() {
        Route::prefix('products')->group(function ()  {
            Route::get('/', [ProductController::class, 'index'])->name('product');
            Route::get('data', [ProductController::class, 'data'])->name('product.data');
            Route::post('save',[ProductController::class,'store'])->name('product.save');
            Route::get('show/{id}',[ProductController::class,'show'])->name('product.show');
            Route::post('edit/{id}',[ProductController::class,'edit'])->name('product.edit');
            Route::delete('delete/{id}',[ProductController::class,'delete'])->name('product.delete');

            Route::get('search', [ProductController::class, 'search'])->name('product.search');

            Route::prefix('category')->group(function() {
                Route::get('/', [ProductController::class, 'category'])->name('category.product');
                Route::get('/data',[ProductController::class,'category_data'])->name('category.data');
                Route::post('save', [ProductController::class, 'category_store'])->name('category.product.store');
                Route::get('show/{id}',[ProductController::class, 'category_show'])->name('category.product.show');
                Route::post('update/{id}',[ProductController::class, 'category_update'])->name('category.product.update');
                Route::delete('delete/{id}',[ProductController::class,'category_delete'])->name('category.product.delete');
            });
        });
        Route::prefix('suppliers')->group(function() {
            Route::get('/',[SuppliersController::class,'index'])->name('suppliers');
            Route::get('/data',[SuppliersController::class,'data'])->name('suppliers.data');
            Route::post('/save',[SuppliersController::class,'store'])->name('suppliers.save');
            Route::get('/show/{id}',[SuppliersController::class,'show'])->name('suppliers.show');
            Route::post('/update/{id}',[SuppliersController::class,'update'])->name('suppliers.update');
            Route::delete('/delete/{id}',[SuppliersController::class,'delete'])->name('suppliers.delete');
        });
        Route::prefix('sales')->group(function() {
            Route::get('/',[SalesController::class, 'index'])->name('sales');
            Route::get('data',[SalesController::class, 'data'])->name('sales.data');
            Route::get('show/{id}',[SalesController::class, 'show'])->name('sales.show');
            Route::post('export',[SalesController::class, 'export'])->name('sales.export');
            Route::post('save',[SalesController::class, 'store'])->name('sales.store');
            Route::get('print/{id}',[SalesController::class, 'print'])->name('sales.print');
        });
    });
    Route::middleware(['role:admin|staff_gudang|supplier'])->group(function ()  {
        Route::prefix('purchase-order')->group(function() {
            Route::get('/',[PurchaseOrderController::class,'index'])->name('purchase.order');
            Route::get('/data',[PurchaseOrderController::class,'data'])->name('purchase.order.data');
            Route::post('/save',[PurchaseOrderController::class,'store'])->name('purchase.order.save');
            Route::get('/show/{id}',[PurchaseOrderController::class,'show'])->name('purchase.order.show');
            Route::post('export',[PurchaseOrderController::class, 'export'])->name('purchase.order.export');
            Route::post('/update/{id}',[PurchaseOrderController::class,'update'])->name('purchase.order.update');
            Route::delete('/delete/{id}',[PurchaseOrderController::class,'delete'])->name('purchase.order.delete');
            Route::post('update-status/{id}',[PurchaseOrderController::class, 'update_status'])->name('purchase.order.status');
            Route::get('detail/{id}',[PurchaseOrderController::class, 'detail'])->name('purchase.order.detail');
        });
    });
    Route::middleware(['role:admin|manager'])->group(function() {
        Route::prefix('POQ')->group(function () {
           Route::get('/',[POQController::class, 'index'])->name('poq.index');
           Route::get('data',[POQController::class, 'data'])->name('poq.data');
           Route::post('calculate/{id}',[POQController::class, 'calculatePOQ'])->name('poq.calcuate');
           Route::delete('delete/{id}',[POQController::class, 'delete'])->name('poq.delete');
           Route::post('export',[POQController::class, 'export'])->name('poq.export');
        });
    });
    Route::middleware(['role:manager'])->group(function () {
        Route::prefix('report')->group(function ()  {
            Route::get('sales',[SalesController::class, 'report'])->name('sales.report');
            Route::post('search',[SalesController::class, 'report_search'])->name('sales.report.search');
            Route::get('report-excel',[SalesController::class, 'excel'])->name('sales.report.excel');

            Route::get('purchase-order',[PurchaseOrderController::class, 'report'])->name('purchase.order.report');
            Route::post('purchase-order/search',[PurchaseOrderController::class, 'report_search'])->name('purchase.order.report.search');
            Route::get('purchase-order/report-excel',[PurchaseOrderController::class, 'excel'])->name('purchase.order.report.excel');
        });
    });
    Route::prefix('profile')->group(function() {
        Route::get('/',[ProfileController::class,'index'])->name('profile');
        Route::post('update',[ProfileController::class, 'update'])->name('profile.update');
    });
});
