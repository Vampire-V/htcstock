<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes(['verify' => true, 'register' => false]);
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('optimize-clear', function () {
        Artisan::call('optimize:clear');
        return redirect()->back();
    })->name('optimize-clear');

    Route::get('language/{locale}', 'LocalizationController@language')->name('switch.language')->where('locale','[a-zA-Z]{2}');
    Route::get('/', 'HomeController@index')->name('welcome');
    Route::get('/systemset/{name}', 'HomeController@systemset')->name('system-set');

    Route::namespace('Auth')->prefix('me')->name('me.')->group(function () {
        Route::resource('user', 'UsersController', ['only' => ['edit', 'update']]);
    });
});


// Directory Admin   middleware('can:for-superadmin-admin') เรียกมาจาก AuthServiceProvider for-superadmin-admin 'can:for-superadmin-admin',
Route::namespace('Admin')->prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('updateusers', 'UsersController@updateusers')->name('users.updateusers');
    Route::get('updatevendors', 'VendorController@updatevendor')->name('vendor.updatevendor');
    Route::resources([
        'users' => 'UsersController',
        'permissions' => 'PermissionsController',
        'roles' => 'RoleController'
    ]);
    Route::post('{id}/addrole', 'UsersController@addrole')->name('users.addrole');
    Route::delete('{user}/removerole', 'UsersController@removerole')->name('users.removerole');
    Route::post('{id}/addsystem', 'UsersController@addsystem')->name('users.addsystem');
    Route::delete('{user}/removesystem', 'UsersController@removesystem')->name('users.removesystem');
    Route::post('uploadfileequipment', 'AccessoriesController@uploadfileequipment')->name('uploadfileequipment');
});
Route::get('/operations','Admin\UsersController@operations');
Route::post('upload', 'UploadController@store');
Route::delete('upload', 'UploadController@destroy');
require __DIR__ . '/itstock.php';
require __DIR__ . '/contractlegal.php';
require __DIR__ . '/kpi.php';



// Account
Route::namespace('Accounts')->prefix('accounts')->name('accounts.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', 'HomeController@index')->name('dashboard');
});
