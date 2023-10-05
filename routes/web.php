<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\TempImagesController;
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
    return view('welcome');
});
Route::get('/admin/login',[AdminLoginController::class,'index'])->name('admin.login');
Route::group(['prefix' => 'admin'], function(){

    Route::group(['middleware' => 'admin.guest'], function(){
        
        Route::get('/login',[AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate',[AdminLoginController::class, 'authenticate'])->name('admin.authenticate');

    });

    Route::group(['middleware' => 'admin.auth'], function(){

        Route::get('/dashboard',[HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout',[HomeController::class, 'logout'])->name('admin.logout');


        // Product Routes
        Route::get('/products',[ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create',[ProductController::class, 'create'])->name('products.create');
        Route::post('/products',[ProductController::class, 'store'])->name('products.store');
        Route::get('/products/edit/{id}',[ProductController::class, 'edit'])->name('products.edit');
        Route::post('/products/update/{id}',[ProductController::class, 'update'])->name('products.update');
        Route::get('/products/status/{id}',[ProductController::class, 'status'])->name('products.status');
        Route::get('/products/delete/{id}',[ProductController::class, 'destroy'])->name('products.delete');

    // temp-images.create
        Route::post('/upload-temp-image',[TempImagesController::class, 'create'])->name('temp-images.create');


        Route::get('/getSlug', function(Request $request){
            $slug = '';
            if (!empty(request()->input('title'))) {
                $slug = Str::slug(request()->input('title'));
            }

            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('getSlug');
    });
});
