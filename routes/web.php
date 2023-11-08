<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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

// Route::get('/', function () {
//     return view('tempProductList');
// });
Route::get('/',[ProductController::class,'index'])->name('product.form');

Route::post('product/change',[ProductController::class,'productChange'])->name('product.change');

Route::post('product/qty-change',[ProductController::class,'qtyChange'])->name('product.qtyChange');

Route::post('product/add',[ProductController::class,'productAdd'])->name('product.add');
Route::post('product/delete',[ProductController::class,'productDelete'])->name('product.delete');

Route::get('products',[ProductController::class,'products'])->name('product');

Route::get('product/data-store',[ProductController::class,'dataStore'])->name('product.dataSave');


