<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\POQController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Auth::routes();

Route::middleware(['auth'])->group(function ()  {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::middleware(['role:admin|staff_gudang'])->group(function() {
        Route::prefix('products')->group(function ()  {
            Route::get('/', [ProductController::class, 'index'])->name('product');
            Route::get('data', [ProductController::class, 'data'])->name('product.data');
            Route::post('save',[ProductController::class,'store'])->name('product.save');
            Route::get('show/{id}',[ProductController::class,'show'])->name('product.show');
            Route::post('edit/{id}',[ProductController::class,'edit'])->name('product.edit');
            Route::delete('delete/{id}',[ProductController::class,'delete'])->name('product.delete');

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
    });
    Route::middleware(['role:admin|staff_gudang|supplier'])->group(function ()  {
        Route::prefix('purchase-order')->group(function() {
            Route::get('/',[PurchaseOrderController::class,'index'])->name('purchase.order');
            Route::get('/data',[PurchaseOrderController::class,'data'])->name('purchase.order.data');
            Route::post('/save',[PurchaseOrderController::class,'store'])->name('purchase.order.save');
            Route::get('/show/{id}',[PurchaseOrderController::class,'show'])->name('purchase.order.show');
            Route::post('/update/{id}',[PurchaseOrderController::class,'update'])->name('purchase.order.update');
            Route::delete('/delete/{id}',[PurchaseOrderController::class,'delete'])->name('purchase.order.delete');
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

    Route::prefix('profile')->group(function() {
        Route::get('/',[ProfileController::class,'index'])->name('profile');
        Route::post('update',[ProfileController::class, 'update'])->name('profile.update');
    });
});
