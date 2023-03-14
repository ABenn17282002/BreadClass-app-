<?php

use Illuminate\Support\Facades\Route;
// 管理者・講師用コントローラーの使用
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\MultiAuthController;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// マルチ認証
// ログイン
Route::get('multi_login', [\App\Http\Controllers\MultiAuthController::class, 'showLoginForm']);
Route::post('multi_login', [\App\Http\Controllers\MultiAuthController::class, 'login']);

// ログアウト
Route::get('multi_login/logout', [\App\Http\Controllers\MultiAuthController::class, 'logout'])->name('multi_login.logout');

// 管理者用DashBoard
Route::prefix('administrators')->middleware('auth:administrators')->group(function(){
    Route::middleware('auth:administrators')->group(function () {
        Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        // 管理者一覧の表示
        Route::get('show', [AdminController::class, 'AdminShow'])->name('admin.show');

        // 管理者:新規作成画面
        Route::get('create', [AdminController::class, 'AdminCreate'])->name('admin.show.create');

        // 管理者:新規追加
        Route::post('store', [AdminController::class, 'AdminStore'])->name('admin.show.store');

        // 講師一覧の表示
        Route::get('teacher', [AdminController::class, 'TeacherShow'])->name('admin.teacher');
    });
});

// 講師用DashBoard
Route::prefix('teachers')->middleware('auth:teachers')->group(function(){
    Route::middleware('auth:teachers')->group(function () {
        Route::get('dashboard', [TeacherController::class, 'index'])->name('teacher.dashboard');

        // 講師一覧
        Route::get('show', [TeacherController::class, 'show'])->name('teacher.show');
    });
});
