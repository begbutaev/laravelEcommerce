<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Person\OrderController as PersonController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


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


Auth::routes([
    'reset' => false,
    'confirm' => false,
    'verify' => false,
]);

Route::middleware(['auth'])->group(function () {

    Route::group([
        'prefix' => 'person',
        'namespace' => 'Person',
        'as' => 'person.',
    ], function () {
        Route::get('/orders', [PersonController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [PersonController::class, 'show'])->name('orders.show');
    });

    Route::group([
        'prefix' => 'admin',
    ], function () {
        Route::group([
            'middleware' => 'is_admin',
            'namespace' => 'Admin',
        ], function () {
            Route::get('/orders', [OrderController::class, 'index'])->name('orders');
            Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        });

        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);

    });

});


Route::get('/logout', [LoginController::class, 'logout'])->name('get-logout');

Route::get('/', [MainController::class, 'index'])->name('index');


Route::group(['prefix' => 'basket'], function () {
    Route::post('/add/{product}', [BasketController::class, 'basketAdd'])->name('basket-add');

    Route::group([
        'middleware' => 'basket_not_empty',
    ], function () {
        Route::get('', [BasketController::class, 'basket'])->name('basket');
        Route::get('/place', [BasketController::class, 'basketPlace'])->name('basket-place');
        Route::post('/remove/{product}', [BasketController::class, 'basketRemove'])->name('basket-remove');
        Route::post('/place', [BasketController::class, 'basketConfirm'])->name('basket-confirm');
    });


});


Route::get('/categories', [MainController::class, 'categories'])->name('categories');
Route::get('/{category}', [MainController::class, 'category'])->name('category');
Route::get('/{category}/{product?}', [MainController::class, 'product'])->name('product');



Auth::routes();

