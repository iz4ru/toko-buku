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
    Route::get('admin/transaction', [TransactionController::class, 'indexAdmin'])->name('admin.transaction');
    Route::get('admin/transaction/detail/{id}', [TransactionController::class, 'show'])->name('admin.transaction.detail');

    # Cashier (Employee) Management
    Route::get('admin/employee', [UserController::class, 'index'])->name('admin.employee');
    Route::get('admin/employee/create', [UserController::class, 'create'])->name('admin.employee.create');
    Route::post('admin/employee/store', [UserController::class, 'store'])->name('admin.employee.store');
    Route::get('admin/employee/edit/{id}', [UserController::class, 'edit'])->name('admin.employee.edit');
    Route::put('admin/employee/update/{id}', [UserController::class, 'update'])->name('admin.employee.update');
    Route::delete('admin/employee/destroy/{id}', [UserController::class, 'destroy'])->name('admin.employee.destroy');

    # Logs
    Route::get('admin/logs', [LogController::class, 'index'])->name('admin.log');
});

Route::middleware(['auth', 'role:cashier'])->group(function () {
    Route::get('/cashier/dashboard', [DashboardController::class, 'index'])->name('cashier.dashboard');

    # Shop
    Route::get('cashier/shop', [ShopController::class, 'index'])->name('cashier.shop');
    Route::get('cashier/checkout', [ShopController::class, 'showCheckoutForm'])->name('cashier.checkout.form');
    Route::post('cashier/cart/add', [ShopController::class, 'addToCart'])->name('cashier.cart.add');
    Route::post('cashier/cart/update', [ShopController::class, 'updateCart'])->name('cashier.cart.update');
    Route::get('cashier/cart/remove/{id}', [ShopController::class, 'removeFromCart'])->name('cashier.cart.remove');
    Route::post('cashier/checkout/process', [ShopController::class, 'checkout'])->name('cashier.checkout.process');

    # Transaction
    Route::get('cashier/transaction', [TransactionController::class, 'indexCashier'])->name('cashier.transaction');
    Route::get('cashier/transaction/edit/{id}', [TransactionController::class, 'edit'])->name('cashier.transaction.edit');
    Route::put('/cashier/transaction/update/{id}', [TransactionController::class, 'update'])->name('cashier.transaction.update');
    Route::get('cashier/transaction/receipt/{id}', [TransactionController::class, 'receipt'])->name('cashier.transaction.receipt');

    # Logs
    Route::get('cashier/logs', [LogController::class, 'index'])->name('cashier.log');
});

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/dashboard', [DashboardController::class, 'index'])->name('owner.dashboard');

    # Book Management
    Route::get('owner/book', [BookController::class, 'index'])->name('owner.book');
    Route::get('owner/book/create', [BookController::class, 'create'])->name('owner.book.create');
    Route::post('owner/book/store', [BookController::class, 'store'])->name('owner.book.store');
    Route::get('owner/book/get-types/{categoryId}', [BookController::class, 'getBookTypes'])->name('owner.book.getTypes');
    Route::get('owner/book/generate-code/{categoryId}/{typeId}', [BookController::class, 'generateCode'])->name('owner.book.generateCode');
    Route::get('owner/book/edit/{id}', [BookController::class, 'edit'])->name('owner.book.edit');
    Route::put('owner/book/update/{id}', [BookController::class, 'update'])->name('owner.book.update');
    Route::delete('owner/book/delete/{id}', [BookController::class, 'destroy'])->name('owner.book.destroy');

    # Category Management
    Route::get('owner/category', [CategoryController::class, 'index'])->name('owner.category');
    Route::get('owner/category/create', [CategoryController::class, 'create'])->name('owner.category.create');
    Route::post('owner/category/store', [CategoryController::class, 'store'])->name('owner.category.store');
    Route::get('owner/category/edit/{id}', [CategoryController::class, 'edit'])->name('owner.category.edit');
    Route::put('owner/category/update/{id}', [CategoryController::class, 'update'])->name('owner.category.update');
    Route::delete('owner/category/destroy/{id}', [CategoryController::class, 'destroy'])->name('owner.category.destroy');

    # Book Detail Management
    Route::get('owner/book-detail', [BookDetailController::class, 'index'])->name('owner.book_detail');
    Route::get('owner/book-detail/edit-stock/{id}', [BookDetailController::class, 'editStock'])->name('owner.book_detail.edit.stock');
    Route::get('owner/book-detail/edit-price/{id}', [BookDetailController::class, 'editPrice'])->name('owner.book_detail.edit.price');
    Route::put('owner/book-detail/update-stock/{id}', [BookDetailController::class, 'updateStock'])->name('owner.book_detail.update.stock');
    Route::put('owner/book-detail/update-price/{id}', [BookDetailController::class, 'updatePrice'])->name('owner.book_detail.update.price');

    # Transaction
    Route::get('owner/transaction', [TransactionController::class, 'indexOwner'])->name('owner.transaction');
    Route::get('owner/transaction/edit/{id}', [TransactionController::class, 'edit'])->name('owner.transaction.edit');
    Route::put('/owner/transaction/update/{id}', [TransactionController::class, 'update'])->name('owner.transaction.update');
    Route::get('owner/transaction/receipt/{id}', [TransactionController::class, 'receipt'])->name('owner.transaction.receipt');

    # Sale Report
    Route::get('owner/report', [TransactionController::class, 'reportIndex'])->name('owner.report');
    Route::get('owner/report-print', [TransactionController::class, 'reportPrint'])->name('owner.report.print');
    Route::get('owner/report-export', [TransactionController::class, 'exportExcel'])->name('owner.report.export');

    # Cashier & Admin (Employee) Management
    Route::get('owner/employee', [UserController::class, 'index'])->name('owner.employee');
    Route::get('owner/employee/create', [UserController::class, 'create'])->name('owner.employee.create');
    Route::post('owner/employee/store', [UserController::class, 'store'])->name('owner.employee.store');
    Route::get('owner/employee/edit/{id}', [UserController::class, 'edit'])->name('owner.employee.edit');
    Route::put('owner/employee/update/{id}', [UserController::class, 'update'])->name('owner.employee.update');
    Route::delete('owner/employee/destroy/{id}', [UserController::class, 'destroy'])->name('owner.employee.destroy');

    # Logs
    Route::get('owner/logs', [LogController::class, 'index'])->name('owner.log');
});
