<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContractorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MigrateController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RazorpayWebhookController;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/migrate', MigrateController::class)
    ->withoutMiddleware([
        StartSession::class,
        ShareErrorsFromSession::class,
        PreventRequestForgery::class,
    ])
    ->name('migrate');
Route::get('/clear-cache-xyz123', function () {
    $out = [];
    foreach (['config:clear', 'config:cache','route:clear', 'view:clear', 'cache:clear'] as $cmd) {
        try {
            Artisan::call($cmd);
            $out[] = "$cmd OK";
        } catch (Throwable $e) {
            $out[] = "$cmd FAILED: ".$e->getMessage();
        }
    }

    return '<pre>'.implode("\n", $out).'</pre>';
});
Route::get('/migrate-xyz123', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);

        return '<pre>'.e(Artisan::output()).'</pre>';
    } catch (Throwable $e) {
        return '<pre>'.e($e->getMessage()).'</pre>';
    }
})->withoutMiddleware([StartSession::class, ShareErrorsFromSession::class, VerifyCsrfToken::class]);
// Route::get('/migrate-xyz123', function () {
//     Artisan::call('migrate', ['--force' => true]);
//     return '<pre>' . e(Artisan::output()) . '</pre>';
// });
Route::get('/show-log-xyz123', function () {
    $file = storage_path('logs/laravel.log');
    if (! file_exists($file)) {
        return 'No log file';
    }
    $lines = file($file);

    return '<pre>'.e(implode('', array_slice($lines, -60))).'</pre>';
});
// Route::get('/clear-cache-xyz123', function () {
//     Artisan::call('config:clear');
//     Artisan::call('cache:clear');
//     Artisan::call('route:clear');
//     Artisan::call('view:clear');

//     return 'Caches cleared';
// });
Route::post('webhooks/razorpay', RazorpayWebhookController::class)->name('webhooks.razorpay');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::get('/billing/payments', [BillingController::class, 'payments'])->name('billing.payments');
    Route::get('/billing/status', [BillingController::class, 'status'])->name('billing.status');
    Route::put('/billing', [BillingController::class, 'update'])->name('billing.update');
    Route::post('/billing/order', [BillingController::class, 'order'])->name('billing.order');
    Route::post('/billing/verify', [BillingController::class, 'verify'])->name('billing.verify');

    Route::get('documents/export', [DocumentController::class, 'export'])->name('documents.export');

    Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::resource('documents', DocumentController::class)->except(['show']);
    Route::resource('contractors', ContractorController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('employees', EmployeeController::class)->except(['show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
