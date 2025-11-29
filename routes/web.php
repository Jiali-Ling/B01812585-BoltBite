<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\BoltBite\RestaurantController as BoltRestaurantController;
use App\Http\Controllers\BoltBite\MenuController;
use App\Http\Controllers\BoltBite\CartController;
use App\Http\Controllers\BoltBite\OrderController;
use App\Http\Controllers\BoltBite\CheckoutController;
use App\Http\Controllers\ReviewController as CustomerReviewController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Merchant\MenuItemController as MerchantMenuItemController;
use App\Http\Controllers\Merchant\OrderController as MerchantOrderController;
use App\Http\Controllers\Merchant\ReviewController as MerchantReviewController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\Admin\AdminDashboardController;
use Illuminate\Support\Facades\Route;
use App\Models\Restaurant;

Route::get('/', function () {
    if (view()->exists('welcome')) {
        $featuredRestaurants = Restaurant::whereHas('menuItems', function ($query) {
                $query->where('status', 'on_shelf')->where('is_available', true);
            })
            ->inRandomOrder()
            ->take(3)
            ->get();
        return view('welcome', compact('featuredRestaurants'));
    }
    return redirect()->route('restaurants.index');
});

Route::get('/restaurants', [BoltRestaurantController::class, 'index'])->name('restaurants.index');
Route::get('/restaurants/{id}', [BoltRestaurantController::class, 'show'])->name('restaurants.show');
Route::get('/menu-items/{menuItem}', [BoltRestaurantController::class, 'showMenuItem'])->name('menu-items.show');
Route::get('/menu-items/{menuItem}/file/{filename}', [\App\Http\Controllers\BoltBite\MenuItemFileController::class, 'file'])->name('menu-items.file');

Route::resource('posts', PostController::class);
Route::get('/uploads/{id}/file/{filename}', [UploadController::class, 'file'])->name('uploads.file');
Route::resource('uploads', UploadController::class);
Route::get('/cart', [CartController::class, 'show'])->name('cart.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->middleware('auth')->name('checkout.process');

Route::get('posts/trashed', [PostController::class, 'trashed'])->name('posts.trashed');
Route::post('posts/{id}/restore', [PostController::class, 'restore'])->name('posts.restore');
Route::delete('posts/{id}/force', [PostController::class, 'forceDelete'])->name('posts.forceDelete');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::post('/menu-items/{menuItem}/reviews', [CustomerReviewController::class, 'store'])
        ->middleware('can:create,App\Models\Review')
        ->name('menu-items.reviews.store');
    
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->middleware('can:create,App\Models\BoltBite\Order')->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->middleware('can:view,order')->name('orders.show');
    Route::patch('/orders/{order}', [OrderController::class, 'update'])->middleware('can:update,order')->name('orders.update');
    
    Route::post('/orders/{order}/comments', [CommentController::class, 'store'])
        ->middleware('can:createForOrder,App\Models\Comment,order')
        ->name('orders.comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->middleware('can:delete,comment')
        ->name('comments.destroy');
    
    Route::get('/uploads/restaurant/{id}/edit', [UploadController::class, 'editRestaurantImage'])->name('uploads.restaurant.edit');
    Route::post('/uploads/restaurant/{id}/update', [UploadController::class, 'updateRestaurantImage'])->name('uploads.restaurant.update');
    
    Route::prefix('merchant')->name('merchant.')->middleware('role:merchant,admin')->group(function () {
        Route::get('/restaurants/{restaurant}/menu', [MenuController::class, 'index'])->name('menu.index');
        Route::post('/restaurants/{restaurant}/menu-items', [MerchantMenuItemController::class, 'store'])
            ->middleware('can:create,App\Models\MenuItem')
            ->name('menu-items.store');
        Route::put('/restaurants/{restaurant}/menu-items/{menuItem}', [MerchantMenuItemController::class, 'update'])
            ->middleware('can:update,menuItem')
            ->name('menu-items.update');
        Route::delete('/restaurants/{restaurant}/menu-items/{menuItem}', [MerchantMenuItemController::class, 'destroy'])
            ->middleware('can:delete,menuItem')
            ->name('menu-items.destroy');
        Route::post('/restaurants/{restaurant}/menu-items/{menuItem}/toggle', [MerchantMenuItemController::class, 'toggleStatus'])
            ->middleware('can:update,menuItem')
            ->name('menu-items.toggle');
        Route::get('/restaurants/{restaurant}/reviews', [MerchantReviewController::class, 'index'])->name('reviews.index');
        Route::post('/restaurants/{restaurant}/reviews/{review}/reply', [MerchantReviewController::class, 'reply'])->name('reviews.reply');
        Route::get('/orders', [MerchantOrderController::class, 'index'])->name('orders.index');
        Route::get('/stats', function () { return view('merchant.stats'); })->name('stats.index');
    });

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
    });
});

require __DIR__.'/auth.php';
