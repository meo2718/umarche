<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComponentTestController;
use App\Http\Controllers\LifeCycleTestController;
use App\Http\Controllers\User\ItemController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\LoginWithGoogleController;
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
require __DIR__.'/auth.php';

Route::get('/', function () {
    //prefix設定
    return view('user.welcome');
});

//userのルート 
Route::middleware('auth:users')->group(function(){
    //'/'で一覧画面遷移→ログアウト後'/'に遷移するようにRouteServiceProviderに記載
    Route::get('/', [ItemController::class, 'index'])->name('items.index');
    Route::get('show/{item}', [ItemController::class, 'show'])->name('items.show');
    Route::match(['get','post'],'edit/{shop}', [ItemController::class, 'edit'])->name('items.edit');
});

//cartのルート
Route::prefix('cart')-> 
    middleware('auth:users')->group(function(){
        Route::post('add', [CartController::class, 'add'])->name('cart.add');
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('delete/{item}', [CartController::class, 'delete'])->name('cart.delete');
        //決済処理のルート
        Route::get('checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});

//googleログインのルート
Route::get("auth/google", [LoginWithGoogleController::class,"redirectToGoogle",]);
Route::get("auth/google/callback", [LoginWithGoogleController::class,"googleCallback",]);