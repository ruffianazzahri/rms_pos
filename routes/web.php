<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\Dashboard\CustomerVipController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\SupplierController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\PaySalaryController;
use App\Http\Controllers\Dashboard\AttendenceController;
use App\Http\Controllers\Dashboard\AdvanceSalaryController;
use App\Http\Controllers\Dashboard\DatabaseBackupController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\PosController;
use App\Http\Controllers\Dashboard\POSKafeController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\CashierController;
use App\Http\Controllers\Dashboard\ChartController;
use App\Http\Controllers\Dashboard\OmzetController;
use App\Http\Controllers\Dashboard\FinancialReportController;
use App\Http\Controllers\Dashboard\GeneralJournalController;
use App\Http\Controllers\Dashboard\MasterChargeController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Menampilkan form "Lupa Password"
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')->name('password.request');

// Mengirim email reset
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')->name('password.email');

// Menampilkan form "Password Baru"
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')->name('password.reset');

// Simpan password baru
Route::put('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')->name('password.updatepassword');

Route::view('/backend/privacy-policy', 'backend.privacy-policy')->name('privacy-policy');
Route::view('/backend/terms-of-service', 'backend.terms-of-service')->name('terms-of-service');

Route::get('/', function () {
    return view('welcome');
});


// DEFAULT DASHBOARD & PROFILE
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
});

// ====== USERS ======
Route::middleware(['permission:user.menu'])->group(function () {
    Route::resource('/users', UserController::class)->except(['show']);
});

// ====== CUSTOMERS ======
Route::middleware(['permission:customer.menu'])->group(function () {
    Route::resource('/customers', CustomerController::class);
});

// ====== CUSTOMERS VIP (MEMBER CARD)======
Route::middleware(['permission:customer.menu'])->group(function () {
Route::get('/customers_vip/scan', [CustomerVipController::class, 'scan'])->name('customers_vip.scan');

    Route::resource('/customers_vip', CustomerVipController::class)->names('customers_vip');
});


// ====== SUPPLIERS ======
Route::middleware(['permission:supplier.menu'])->group(function () {
    Route::resource('/suppliers', SupplierController::class);
});

// ====== EMPLOYEES ======
Route::middleware(['permission:employee.menu'])->group(function () {
    Route::resource('/employees', EmployeeController::class);
});

// ====== EMPLOYEE ATTENDENCE ======
Route::middleware(['permission:attendence.menu'])->group(function () {
    Route::resource('/employee/attendence', AttendenceController::class)->except(['show', 'update', 'destroy']);
});

// ====== SALARY EMPLOYEE ======
Route::middleware(['permission:salary.menu'])->group(function () {
    // PaySalary
    Route::resource('/pay-salary', PaySalaryController::class)->except(['show', 'create', 'edit', 'update']);
    Route::get('/pay-salary/history', [PaySalaryController::class, 'payHistory'])->name('pay-salary.payHistory');
    Route::get('/pay-salary/history/{id}', [PaySalaryController::class, 'payHistoryDetail'])->name('pay-salary.payHistoryDetail');
    Route::get('/pay-salary/{id}', [PaySalaryController::class, 'paySalary'])->name('pay-salary.paySalary');

    // Advance Salary
    Route::resource('/advance-salary', AdvanceSalaryController::class)->except(['show']);
});

// ====== PRODUCTS ======
Route::middleware(['permission:product.menu'])->group(function () {
    Route::get('/products/import', [ProductController::class, 'importView'])->name('products.importView');
    Route::post('/products/import', [ProductController::class, 'importStore'])->name('products.importStore');
    Route::get('/products/export', [ProductController::class, 'exportData'])->name('products.exportData');
    Route::resource('/products', ProductController::class);
});

// ====== CATEGORY PRODUCTS ======
Route::middleware(['permission:category.menu'])->group(function () {
    Route::resource('/categories', CategoryController::class);
});

// ====== POS ======
Route::middleware(['permission:pos.menu'])->group(function () {
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/add', [PosController::class, 'addCart'])->name('pos.addCart');
    Route::post('/pos/update/{rowId}', [PosController::class, 'updateCart'])->name('pos.updateCart');
    Route::get('/pos/delete/{rowId}', [PosController::class, 'deleteCart'])->name('pos.deleteCart');
    Route::post('/pos/invoice/create', [PosController::class, 'createInvoice'])->name('pos.createInvoice');
    Route::post('/pos/invoice/print', [PosController::class, 'printInvoice'])->name('pos.printInvoice');

    // Create Order
    Route::post('/pos/order', [OrderController::class, 'storeOrder'])->name('pos.storeOrder');
});

// POS KHUSUS KAFE
Route::middleware('auth')->group(function () {
    Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.index');
    Route::post('/cashier/transaksi', [CashierController::class, 'store'])->name('cashier.transaksi');
});

// ====== ORDERS ======
Route::middleware(['permission:orders.menu'])->group(function () {
    Route::get('/orders/pending', [OrderController::class, 'pendingOrders'])->name('order.pendingOrders');
    Route::get('/orders/complete', [OrderController::class, 'completeOrders'])->name('order.completeOrders');
    Route::get('/orders/details/{order_id}', [OrderController::class, 'orderDetails'])->name('order.orderDetails');
    Route::put('/orders/update/status', [OrderController::class, 'updateStatus'])->name('order.updateStatus');
    Route::get('/orders/invoice/download/{order_id}', [OrderController::class, 'invoiceDownload'])->name('order.invoiceDownload');

    // Pending Due
    Route::get('/pending/due', [OrderController::class, 'pendingDue'])->name('order.pendingDue');
    Route::get('/order/due/{id}', [OrderController::class, 'orderDueAjax'])->name('order.orderDueAjax');
    Route::post('/update/due', [OrderController::class, 'updateDue'])->name('order.updateDue');

    // Stock Management
    Route::get('/stock', [OrderController::class, 'stockManage'])->name('order.stockManage');
});

Route::get('/print-nota/{id}', [CashierController::class, 'printNota'])->name('nota.print');


// ====== DATABASE BACKUP ======
Route::middleware(['permission:database.menu'])->group(function () {
    Route::get('/database/backup', [DatabaseBackupController::class, 'index'])->name('backup.index');
    Route::get('/database/backup/now', [DatabaseBackupController::class, 'create'])->name('backup.create');
    Route::get('/database/backup/download/{getFileName}', [DatabaseBackupController::class, 'download'])->name('backup.download');
    Route::get('/database/backup/delete/{getFileName}', [DatabaseBackupController::class, 'delete'])->name('backup.delete');
});

// ====== ROLE CONTROLLER ======
Route::middleware(['permission:roles.menu'])->group(function () {
    // Permissions
    Route::get('/permission', [RoleController::class, 'permissionIndex'])->name('permission.index');
    Route::get('/permission/create', [RoleController::class, 'permissionCreate'])->name('permission.create');
    Route::post('/permission', [RoleController::class, 'permissionStore'])->name('permission.store');
    Route::get('/permission/edit/{id}', [RoleController::class, 'permissionEdit'])->name('permission.edit');
    Route::put('/permission/{id}', [RoleController::class, 'permissionUpdate'])->name('permission.update');
    Route::delete('/permission/{id}', [RoleController::class, 'permissionDestroy'])->name('permission.destroy');

    // Roles
    Route::get('/role', [RoleController::class, 'roleIndex'])->name('role.index');
    Route::get('/role/create', [RoleController::class, 'roleCreate'])->name('role.create');
    Route::post('/role', [RoleController::class, 'roleStore'])->name('role.store');
    Route::get('/role/edit/{id}', [RoleController::class, 'roleEdit'])->name('role.edit');
    Route::put('/role/{id}', [RoleController::class, 'roleUpdate'])->name('role.update');
    Route::delete('/role/{id}', [RoleController::class, 'roleDestroy'])->name('role.destroy');

    // Role Permissions
    Route::get('/role/permission', [RoleController::class, 'rolePermissionIndex'])->name('rolePermission.index');
    Route::get('/role/permission/create', [RoleController::class, 'rolePermissionCreate'])->name('rolePermission.create');
    Route::post('/role/permission', [RoleController::class, 'rolePermissionStore'])->name('rolePermission.store');
    Route::get('/role/permission/{id}', [RoleController::class, 'rolePermissionEdit'])->name('rolePermission.edit');
    Route::put('/role/permission/{id}', [RoleController::class, 'rolePermissionUpdate'])->name('rolePermission.update');
    Route::delete('/role/permission/{id}', [RoleController::class, 'rolePermissionDestroy'])->name('rolePermission.destroy');
});

//chart
Route::get('/chart/orders', [ChartController::class, 'getOrdersChartData']);

//omzet
Route::get('/omzet/print/form', [OmzetController::class, 'print'])->name('omzet.print.form');

//financial report
Route::resource('financial_report', FinancialReportController::class);
Route::get('/products-sale', [FinancialReportController::class, 'detailByProduk']);
Route::get('/laporan-keuangan', [FinancialReportController::class, 'laporanKeuangan'])->name('laporan.keuangan');
Route::get('/laporan-keuangan/export', [FinancialReportController::class, 'exportLaporan'])->name('laporan.keuangan.export');


//general journal
Route::resource('general_journal', GeneralJournalController::class);
// routes/web.php
Route::post('/general-journal/print', [GeneralJournalController::class, 'print'])->name('general_journal.print');



// master pajak dan diskon

Route::resource('master-charges', MasterChargeController::class);

//scan member
Route::get('/check-member/{uid}', [CustomerVipController::class, 'scan']);

require __DIR__ . '/auth.php';
