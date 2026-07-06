<?php
// use App\Http\Controllers\TicketAttachmentController;
// use App\Http\Controllers\TicketExecutorAttachmentController;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\dashboardController;
// use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\TicketController;
// use App\Http\Controllers\UserController;
// use App\Http\Controllers\ResolveTicketController;

// Route::middleware('throttle:15,1')->group(function () {
//     Route::get('/', [AuthController::class, 'loginPage'])->name('login')->middleware('guest');
//     Route::get('/lang/{lang}', function ($lang) {
//         session(['applocale' => $lang]);
//         return back();
//     })->name('lang.switch');
//     Route::post('/login', [AuthController::class, 'login'])->name('login.post');
// });

// Route::middleware(['auth', 'role:admin|human|executor'])->group(function () {
//     Route::get('/dashboard', [dashboardController::class, 'dashboardPage'])->name('dashboard');
//     Route::post('/tickets/{ticketId}/attachments', [TicketAttachmentController::class, 'store'])->name('attachments.store');
//     Route::delete('/tickets/{ticketId}/attachments/{attachmentId}', [TicketAttachmentController::class, 'destroy'])->name('attachments.destroy');
 
//     // routes/web.php
// Route::get('/dashboard/filteropen', [dashboardController::class, 'dashboardPage'])
//     ->name('dashboard.filteropen');
// Route::get('/dashboard/filterprogress', [dashboardController::class, 'dashboardPage'])
//     ->name('dashboard.filterprogress');
// Route::get('/dashboard/filteroverdue', [dashboardController::class, 'dashboardPage'])
//     ->name('dashboard.filteroverdue');
// Route::get('/dashboard/filterclosed', [dashboardController::class, 'dashboardPage'])
//     ->name('dashboard.filterclosed');
// Route::get('/dashboard/filteronprogress', [dashboardController::class, 'dashboardPage'])
//     ->name('dashboard.filteronprogress');

//     Route::get('/editopenticket/{hash}', [dashboardController::class, 'edit'])->name('editopenticket');
//     Route::get('/showopenticket/{hash}', [dashboardController::class, 'show'])->name('showopenticket');
//     Route::post('/logout', [AuthController::class, 'logout'])
//         ->name('logout.post');
//     Route::post('/updateroles', [ProfileController::class, 'updateActiveRole'])
//         ->name('profile.update-active-role');
//     Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
//     Route::get('/editopenticketforadmin/{hash}', [dashboardController::class, 'edit'])->name('editopenticketforadmin');
//     Route::get('/showopenticketforadmin/{hash}', [dashboardController::class, 'show'])->name('showopenticketforadmin');
//     Route::get('/reviewtickets/{hash}', [TicketController::class, 'reviewticket'])->name('reviewtickets');

// });

// Route::middleware(['auth', 'role:admin|executor'])->group(function () {
//     Route::match(['GET', 'POST'], '/allticketforadmins/allticketforadmins', [dashboardController::class, 'getAllticketforadmins'])->name('allticketforadmins.allticketforadmins');
//     Route::post('/tickets/{ticketId}/executor-attachments', [TicketExecutorAttachmentController::class, 'store'])->name('executor.attachments.store');
//     Route::delete('/tickets/{ticketId}/executor-attachments/{attachmentId}', [TicketExecutorAttachmentController::class, 'destroy'])->name('executor.attachments.destroy');
//     Route::get('/resolvetickets', [ResolveTicketController::class, 'allReview'])->name('resolvetickets');
//     Route::match(['GET', 'POST'], '/resolveticket/resolveticket', [ResolveTicketController::class, 'getReviewtickets'])->name('resolveticket.resolveticket');
//     Route::get('/showtickets/{hash}', [dashboardController::class, 'show'])->name('showtickets');

//     Route::put(
//         'updateopenticketforadmin/{hash}',
//         [dashboardController::class, 'update']
//     )->middleware('throttle:15,1')->name('updateopenticketforadmin');
// });
// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/users', [UserController::class, 'users'])->name('users');
//     Route::match(['GET', 'POST'], '/users/users', [UserController::class, 'getUsers'])->name('users.users');
//     Route::get('/editusers/{hash}', [UserController::class, 'edit'])->name('editusers');
//     Route::post('/updateusers/{hash}/update', [UserController::class, 'update'])->name('updateusers');
//     Route::post('/users/bulk-update-role', [UserController::class, 'bulkUpdateRole'])
//         ->name('users.bulkUpdateRole');
        
// });
// Route::middleware(['auth', 'role:human'])->group(function () {
//     Route::get('/openticket', [TicketController::class, 'openTicket'])->name('openticket');
//     Route::get('/mytickets', [TicketController::class, 'myTicket'])->name('mytickets');
//     Route::match(['GET', 'POST'], '/allmytickets/allmytickets', [TicketController::class, 'getAllmytickets'])->name('allmytickets.allmytickets');
//     Route::post('/ticketreq', [TicketController::class, 'store'])->middleware('throttle:15,1')->name('ticketreq');
//     Route::get('/showmytickets/{hash}', [TicketController::class, 'show'])->name('showmytickets');
//     Route::get('/editmytickets/{hash}', [TicketController::class, 'editmyticket'])->name('editmytickets');
//     Route::put(
//         'updatemytickets/{hash}',
//         [TicketController::class, 'updatemytickets']
//     )->middleware('throttle:15,1')->name('updatemytickets');
//     Route::put(
//         'reviewticketsfromhuman/{hash}',
//         [TicketController::class, 'storeReview']
//     )->middleware('throttle:15,1')->name('reviewticketsfromhuman');
// });
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketAttachmentController;
use App\Http\Controllers\TicketExecutorAttachmentController;
use App\Http\Controllers\ResolveTicketController;


Route::middleware('throttle:15,1')->group(function () {
    Route::get('/', [AuthController::class, 'loginPage'])->name('login')->middleware('guest');
    Route::get('/lang/{lang}', function ($lang) {
        session(['applocale' => $lang]);
        return back();
    })->name('lang.switch');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/about', [dashboardController::class, 'aboutUs'])->name('about');

});
Route::middleware(['auth', 'role:admin|human|executor'])->group(function () {
    Route::get('/dashboard', [dashboardController::class, 'dashboardPage'])->name('dashboard');
    // Route::post('/tickets/{ticketId}/attachments', [TicketAttachmentController::class, 'store'])->name('attachments.store');
    // Route::delete('/tickets/{ticketId}/attachments/{attachmentId}', [TicketAttachmentController::class, 'destroy'])->name('attachments.destroy');
     Route::post('/attachments/{ticket}', [TicketAttachmentController::class, 'store'])->name('attachments.store');
    Route::delete('/attachments/{attachment}', [TicketAttachmentController::class, 'destroy'])->name('attachments.destroy');
    Route::get('/attachments/{attachment}/signed-url', [TicketAttachmentController::class, 'signedUrl'])->name('attachments.signed-url');
 
     Route::post('/attachmentsforexecutor/{ticket}', [TicketExecutorAttachmentController::class, 'store'])->name('attachmentsforexecutor.store');
    Route::delete('/attachmentsforexecutor/{attachment}', [TicketExecutorAttachmentController::class, 'destroy'])->name('attachmentsforexecutor.destroy');
    Route::get('/attachmentsforexecutor/{attachment}/signed-url-for-executor', [TicketExecutorAttachmentController::class, 'signedUrlForExecutor'])->name('attachmentsforexecutor.signed-url-for-executor');

    // routes/web.php
Route::get('/dashboard/filteropen', [dashboardController::class, 'dashboardPage'])
    ->name('dashboard.filteropen');
Route::get('/dashboard/filterprogress', [dashboardController::class, 'dashboardPage'])
    ->name('dashboard.filterprogress');
Route::get('/dashboard/filteroverdue', [dashboardController::class, 'dashboardPage'])
    ->name('dashboard.filteroverdue');
Route::get('/dashboard/filterclosed', [dashboardController::class, 'dashboardPage'])
    ->name('dashboard.filterclosed');
Route::get('/dashboard/filteronprogress', [dashboardController::class, 'dashboardPage'])
    ->name('dashboard.filteronprogress');

    Route::get('/editopenticket/{hash}', [dashboardController::class, 'edit'])->name('editopenticket');
    Route::get('/showopenticket/{hash}', [dashboardController::class, 'show'])->name('showopenticket');
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout.post');
    Route::post('/updateroles', [ProfileController::class, 'updateActiveRole'])
        ->name('profile.update-active-role');
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::get('/editopenticketforadmin/{hash}', [dashboardController::class, 'edit'])->name('editopenticketforadmin');
    Route::get('/showopenticketforadmin/{hash}', [dashboardController::class, 'show'])->name('showopenticketforadmin');
    Route::get('/reviewtickets/{hash}', [TicketController::class, 'reviewticket'])->name('reviewtickets');
});

Route::middleware(['auth', 'role:admin|executor'])->group(function () {
    Route::match(['GET', 'POST'], '/allticketforadmins/allticketforadmins', [dashboardController::class, 'getAllticketforadmins'])->name('allticketforadmins.allticketforadmins');
    Route::post('/tickets/{ticketId}/executor-attachments', [TicketExecutorAttachmentController::class, 'store'])->name('executor.attachments.store');
    Route::delete('/tickets/{ticketId}/executor-attachments/{attachmentId}', [TicketExecutorAttachmentController::class, 'destroy'])->name('executor.attachments.destroy');
  Route::get('/resolvetickets', [ResolveTicketController::class, 'allReview'])->name('resolvetickets');
    Route::match(['GET', 'POST'], '/resolveticket/resolveticket', [ResolveTicketController::class, 'getReviewtickets'])->name('resolveticket.resolveticket');
    Route::get('/showtickets/{hash}', [dashboardController::class, 'show'])->name('showtickets');

    Route::put(
        'updateopenticketforadmin/{hash}',
        [dashboardController::class, 'update']
    )->middleware('throttle:15,1')->name('updateopenticketforadmin');
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'users'])->name('users');
    Route::match(['GET', 'POST'], '/users/users', [UserController::class, 'getUsers'])->name('users.users');
    Route::get('/editusers/{hash}', [UserController::class, 'edit'])->name('editusers');
    Route::post('/updateusers/{hash}/update', [UserController::class, 'update'])->name('updateusers');
    Route::post('/users/bulk-update-role', [UserController::class, 'bulkUpdateRole'])
        ->name('users.bulkUpdateRole');
        
});
Route::middleware(['auth', 'role:human'])->group(function () {
    Route::get('/openticket', [TicketController::class, 'openTicket'])->name('openticket');
    Route::get('/mytickets', [TicketController::class, 'myTicket'])->name('mytickets');
    Route::match(['GET', 'POST'], '/allmytickets/allmytickets', [TicketController::class, 'getAllmytickets'])->name('allmytickets.allmytickets');
    Route::post('/ticketreq', [TicketController::class, 'store'])->middleware('throttle:15,1')->name('ticketreq');
    Route::get('/showmytickets/{hash}', [TicketController::class, 'show'])->name('showmytickets');
    Route::get('/editmytickets/{hash}', [TicketController::class, 'editmyticket'])->name('editmytickets');
    Route::put(
        'updatemytickets/{hash}',
        [TicketController::class, 'updatemytickets']
    )->middleware('throttle:15,1')->name('updatemytickets');
    Route::put(
        'reviewticketsfromhuman/{hash}',
        [TicketController::class, 'storeReview']
    )->middleware('throttle:15,1')->name('reviewticketsfromhuman');
});
