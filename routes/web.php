<?php

use App\Http\Controllers\Web\AccountController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ProvidersController;
use App\Http\Controllers\Web\ServiceController;
use App\Http\Controllers\Web\SubscriptionController;
use App\Http\Controllers\Web\TaskController;
use App\Http\Controllers\Web\FavoriteController;
use App\Http\Controllers\Web\NotificationSendController;
use App\Http\Controllers\Web\ContactUsController;
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

Route::get('/', [HomeController::class, 'home'] )->name('home');
Route::get('service-provider/{id}', [ProvidersController::class, 'view'])->name('provider.profile');
Route::get('service-view/{slug}', [ServiceController::class, 'view'])->name('service.view');
Route::get('task-view/{slug}', [TaskController::class, 'view'])->name('task.view');

Route::middleware('auth')->group(function () {

    Route::post('/save-token', [NotificationSendController::class, 'saveToken'])->name('save-token'); // Push
    Route::post('/is-read', [NotificationSendController::class, 'isRead'] )->name('isRead');
    Route::get('/fetch-notifications', [NotificationSendController::class, 'fetchNotifications'] )->name('fetchNotifications');
    Route::get('/remove-tokens', [NotificationSendController::class, 'removeTokens'] )->name('removeTokens');
    Route::get('/notifications', [NotificationSendController::class, 'notificationsPage'] )->name('notifications');

    Route::post('/provider-contact', [ProvidersController::class, 'providerContact'])->name('providerContact');

    Route::prefix('account')->group(function (){
        Route::get('/', [AccountController::class, 'account'])->name('account.account');
        Route::get('/change-password', [AccountController::class, 'changePassword'])->name('account.password');
        Route::get('/my-favorites', [AccountController::class, 'myFavorites'])->name('account.my-favorites');
        Route::get('/service-task', [AccountController::class, 'serviceTask'])->name('account.service-task');
        Route::get('/subscription', [AccountController::class, 'subscription'])->name('account.subscription');
        Route::get('/reviews', [AccountController::class, 'reviews'])->name('account.reviews');
        Route::post('/upload-user-profile', [AccountController::class, 'uploadUserProfileImage'])->name('account.account.upload-profile');
        Route::post('/update-password', [AccountController::class, 'updatePassword'])->name('account.account.update-password');
        Route::post('/delete-task/{id}', [TaskController::class, 'deleteTask'])->name('delete.task');
        Route::get('/update-task/{id}', [TaskController::class, 'updateTask'])->name('update.task');
        Route::post('/update-task-data', [TaskController::class, 'updateTaskData'])->name('update.task.data');

        Route::post('/delete-service/{id}', [ServiceController::class, 'deleteService'])->name('delete.service');
        Route::get('/update-service/{id}', [ServiceController::class, 'updateService'])->name('update.service');
        Route::post('/update-service-data', [ServiceController::class, 'updateServiceData'])->name('update.service.data');

        Route::post('/update-service/account/delete-service-images', [ServiceController::class, 'deleteServiceImages']);
        Route::post('/update-task/account/delete-task-images', [TaskController::class, 'deleteTaskImages']);
    });

    Route::prefix('subscription')->middleware('phone-verify')->group(function (){
        Route::get('checkout/{package}', [SubscriptionController::class, 'subscribe'])->name('subscription.subscription');
        Route::get('cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');

        Route::get('payment', [SubscriptionController::class, 'payment'])->name('subscription.payment');
        Route::get('payment/callback/success', [SubscriptionController::class, 'paymentSuccess'])->name('subscription.payment.success');
        Route::get('payment/callback/fail', [SubscriptionController::class, 'paymentFail'])->name('subscription.payment.fail');
    });


    Route::prefix('services')->middleware(['phone-verify', 'is-service-provider'])->group(function (){
        Route::get('add-new-service', [ServiceController::class, 'create'])->name('service.add.view');
        Route::post('add-new-service', [ServiceController::class, 'store'])->name('service.add');
    });

    Route::prefix('task')->middleware(['phone-verify'])->group(function (){
        Route::get('add-new-task', [TaskController::class, 'create'])->name('task.add.view');
        Route::post('add-new-task', [TaskController::class, 'store'])->name('task.add');
    });

    Route::post('toggle-favorite', [FavoriteController::class, 'toggleFavorite'])->name('toggle_favorite');
//    Route::get('favorite', [FavoriteController::class, 'store'])->name('favorite.favorite');

});

//Route::post('toggle-favorite', [FavoriteController::class, 'toggle_favorite'])->name('favorite');

Route::get('action/status', function (){
    return view('congratulations');
})->name('status');

Route::get('service/all/{id}', [ServiceController::class, 'getServiceByCategory']);
Route::get('service/all/sub/{id}', [ServiceController::class, 'getServiceBySubCategory']);

Route::get('task/all/{id}', [TaskController::class, 'getTasksByCategory']);
Route::get('task/all/sub/{id}', [TaskController::class, 'getTaskBySubCategory']);

Route::get('search', [HomeController::class, 'search'])->name('search');

Route::get('contact-us', [ContactUsController::class, 'contact'])->name('contact');

Route::get('about', [HomeController::class, 'about'])->name('about');

Route::get('privacy-policy', [HomeController::class, 'privacy'])->name('privacy');

Route::post('provider-update-rate', [ProvidersController::class, 'providerUpdateRate']);

Route::post('service-update-rate', [ServiceController::class, 'serviceUpdateRate']);

Route::post('task-update-rate', [TaskController::class, 'taskUpdateRate']);

Route::get('search-by-key/{key}', [HomeController::class, 'searchByKey']);

Route::get('provider/all/{id}', [ProvidersController::class, 'getProviderByCategory']);

Route::get('pricing', [SubscriptionController::class, 'pricingList'])->name('pricing');

require __DIR__.'/auth.php';
