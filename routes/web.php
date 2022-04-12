<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BackEnd\BranchController;
use App\Http\Controllers\BackEnd\ContactRequestController;
use App\Http\Controllers\BackEnd\CustomerController;
use App\Http\Controllers\BackEnd\FaqController;
use App\Http\Controllers\BackEnd\FileController;
use App\Http\Controllers\BackEnd\GalleryController;
use App\Http\Controllers\BackEnd\HomePageBlockController;
use App\Http\Controllers\BackEnd\LanguageController;
use App\Http\Controllers\BackEnd\MainController;
use App\Http\Controllers\BackEnd\MenuController;
use App\Http\Controllers\BackEnd\PropertyController;
use App\Http\Controllers\BackEnd\SettingController;
use App\Http\Controllers\BackEnd\SliderController;
use App\Http\Controllers\BackEnd\TranslationController;
use App\Http\Controllers\BackEnd\UserController;
use App\Models\Language;
use App\Models\SystemPage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

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


Route::get('/logout', [AuthenticatedSessionController::class, 'destroy']);

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('/change-language/{lang}', [LanguageController::class, 'change_language'])->name('change_language');

Route::group(['middleware' => 'auth'], function () {

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [MainController::class, 'index'])->name('index');
        Route::get('/change-status/{page}/{id}/{column_to_update}', [MainController::class, 'change_status']);
        Route::resource('/sliders', SliderController::class);
        Route::resource('/libraries', FileController::class);
        Route::resource('/branches', BranchController::class);
        Route::resource('/properties', PropertyController::class);
        Route::resource('/customers', CustomerController::class);
        Route::resource('/galleries', GalleryController::class);
        Route::resource('/faqs', FaqController::class);
        Route::resource('/contact-requests', ContactRequestController::class);
        Route::resource('/homepage-blocks', HomePageBlockController::class);
        Route::resource('/settings', SettingController::class);
        Route::resource('users', UserController::class);
        Route::resource('/translations', TranslationController::class);
        Route::resource('/menus', MenuController::class);
        Route::post('menus/order-update', [MenuController::class, 'order_update'])->name('menus.order_update');
        Route::post('menus/dynamic-nestable', [MenuController::class, 'dynamic_nestable'])->name('menus.dynamic_nestable');
        
    });

    Route::post('/file-upload/{path}', [FileController::class, 'store'])->name('file_upload');
    Route::delete('/file-delete', [FileController::class, 'destroy'])->name('file_delete');
    Route::get('/file-fetch/{file}', [FileController::class, 'show'])->name('file_fetch');

});

Route::name('system.')->group(function () {
    if (Schema::hasTable('languages')) {
        foreach (Language::all() as  $language) {
            $code = $language->code;
            Route::group(['prefix' => $code], function () use ($code) {
                if (Schema::hasTable('system_pages')) {
                    foreach (SystemPage::all() as $system_page) {
                        $route_name = $system_page->route_name;
                        Route::get(__('/' . $route_name . '', [], $code), [$system_page->controller, 'show'])->name("{$code}.{$route_name}");
                    }
                }
            });
        }
    }
});