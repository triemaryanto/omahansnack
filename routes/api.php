<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Mobile\UserController as MobileUserController;
use App\Http\Controllers\Api\Mobile\CategoryController as MobileCategoryController;
use App\Http\Controllers\Api\Mobile\PostController as MobilePostController;
use App\Http\Controllers\Api\Mobile\ProductController as MobileProductController;
use App\Http\Controllers\Api\Mobile\CommentController as MobileCommentController;
use App\Http\Controllers\Api\Dashboard\PostController;
use App\Http\Controllers\Api\Dashboard\UserController;
use App\Http\Controllers\Api\Dashboard\LoginController;
use App\Http\Controllers\Api\Dashboard\CommentController;
use App\Http\Controllers\Api\Dashboard\ProductController;
use App\Http\Controllers\Api\Dashboard\CategoryController;
use App\Http\Controllers\Api\Dashboard\MidtransController;
use App\Http\Controllers\Api\Dashboard\DashboardController;
use App\Http\Controllers\Api\Dashboard\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', [LoginController::class, 'index']);
Route::post('/payment-notif', [MidtransController::class, 'callback']);
Route::prefix('dashboard')->middleware(['auth:api', 'admin'])->group(function () {
    Route::get('/user', [LoginController::class, 'getUser']);
    Route::get('/refresh', [LoginController::class, 'refreshToken']);
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/count', [DashboardController::class, 'index']);
    Route::get('/post', [DashboardController::class, 'singlePost']);
    Route::get('/product', [DashboardController::class, 'singleProduct']);
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/products', ProductController::class);
    Route::apiResource('/posts', PostController::class);
    Route::apiResource('/transactions', TransactionController::class);
    Route::apiResource('/users', UserController::class);
    Route::apiResource('/comments', CommentController::class);
});

Route::post('/register', [MobileUserController::class, 'store']);
Route::prefix('mobile')->middleware(['auth:api', 'user'])->group(function () {
    Route::get('/user', [MobileUserController::class, 'getUser']);
    Route::get('/user/{id}', [MobileUserController::class, 'show']);
    Route::post('/checkout', [MobileUserController::class, 'checkout']);
    Route::get('/categories', [MobileCategoryController::class, 'index']);
    Route::get('/allcategories', [MobileCategoryController::class, 'all']);
    Route::get('/categoriesSecond', [MobileCategoryController::class, 'indexSecond']);
    Route::get('/category/{slug}', [MobileCategoryController::class, 'show']);
    Route::get('/categoryproduct/{slug}', [MobileCategoryController::class, 'showProduct']);
    Route::get('/post', [MobilePostController::class, 'index']);
    Route::get('/singlepost', [MobilePostController::class, 'singlePost']);
    Route::get('/post/{slug}', [MobilePostController::class, 'show']);
    Route::post('/comment', [MobilePostController::class, 'storeComment']);
    Route::get('/product', [MobileProductController::class, 'all']);
    Route::get('/product/{slug}', [MobileProductController::class, 'show']);
    Route::get('/comments', [MobileCommentController::class, 'index']);
});
