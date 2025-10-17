<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookDetailController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        return redirect()->route($role . '.dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    # Book Management
    Route::get('admin/book', [BookController::class, 'index'])->name('admin.book');
    Route::get('admin/book/create', [BookController::class, 'create'])->name('admin.book.create');
    Route::post('admin/book/store', [BookController::class, 'store'])->name('admin.book.store');
    Route::get('admin/book/get-types/{categoryId}', [BookController::class, 'getBookTypes'])->name('admin.book.getTypes');
    Route::get('admin/book/generate-code/{categoryId}/{typeId}', [BookController::class, 'generateCode'])->name('admin.book.generateCode');
    Route::get('admin/book/edit/{id}', [BookController::class, 'edit'])->name('admin.book.edit');
    Route::put('admin/book/update/{id}', [BookController::class, 'update'])->name('admin.book.update');
    Route::delete('admin/book/delete/{id}', [BookController::class, 'destroy'])->name('admin.book.destroy');

    # Category Management
    Route::get('admin/category', [CategoryController::class, 'index'])->name('admin.category');
    Route::get('admin/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
    Route::post('admin/category/store', [CategoryController::class, 'store'])->name('admin.category.store');
    Route::get('admin/category/edit/{id}', [CategoryController::class, 'edit'])->name('admin.category.edit');
    Route::put('admin/category/update/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('admin/category/destroy/{id}', [CategoryController::class, 'destroy'])->name('admin.category.destroy');

    # Book Detail Management
    Route::get('admin/book-detail', [BookDetailController::class, 'index'])->name('admin.book_detail');
    Route::get('admin/book-detail/edit-stock/{id}', [BookDetailController::class, 'editStock'])->name('admin.book_detail.edit.stock');
    Route::get('admin/book-detail/edit-price/{id}', [BookDetailController::class, 'editPrice'])->name('admin.book_detail.edit.price');
    Route::put('admin/book-detail/update-stock/{id}', [BookDetailController::class, 'updateStock'])->name('admin.book_detail.update.stock');
    Route::put('admin/book-detail/update-price/{id}', [BookDetailController::class, 'updatePrice'])->name('admin.book_detail.update.price');

    # Transaction History
    Route::get('admin/transaction', [TransactionController::class, 'index'])->name('admin.transaction');
    Route::get('admin/transaction/detail/{id}', [TransactionController::class, 'show'])->name('admin.transaction.detail');

    # Cashier (Employee) Management
    Route::get('admin/employee', [UserController::class, 'index'])->name('admin.employee');
    Route::get('admin/employee/create', [UserController::class, 'createCashier'])->name('admin.employee.create');
    Route::post('admin/employee/store', [UserController::class, 'storeCashier'])->name('admin.employee.store');
    Route::get('admin/employee/edit/{id}', [UserController::class, 'editCashier'])->name('admin.employee.edit');
    Route::put('admin/employee/update/{id}', [UserController::class, 'updateCashier'])->name('admin.employee.update');
    Route::delete('admin/employee/destroy/{id}', [UserController::class, 'destroyCashier'])->name('admin.employee.destroy');

    # Cashier (Employee) Management
    Route::get('admin/logs', [LogController::class, 'index'])->name('admin.log');
});

Route::middleware(['auth', 'role:cashier'])->group(function () {
    Route::get('/cashier/dashboard', [DashboardController::class, 'index'])->name('cashier.dashboard');

    # Shop
    // Halaman utama shop
    Route::get('/shop', [ShopController::class, 'index'])->name('cashier.shop');
    Route::post('/shop/cart/add', [ShopController::class, 'addToCart'])->name('cashier.addToCart');
    Route::post('/shop/cart/update', [ShopController::class, 'updateCart'])->name('cashier.updateCart');
    Route::delete('/shop/cart/{id}', [ShopController::class, 'removeFromCart'])->name('cashier.removeFromCart');
    Route::get('/cashier/checkout', [ShopController::class, 'showCheckoutForm'])->name('cashier.checkout.form');
    Route::post('/cashier/checkout/process', [ShopController::class, 'checkout'])->name('cashier.checkout.process');
});

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/dashboard', [DashboardController::class, 'index'])->name('owner.dashboard');
});
