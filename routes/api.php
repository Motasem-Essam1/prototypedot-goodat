<?php

use App\Http\Controllers\Dashboard\ConfigurationController;
use App\Http\Controllers\MapsSearchController;
use App\Http\Controllers\Mobile\{Auth\AuthController,
    ProfileController,
    ServiceController,
    SubscriptionController,
    TaskController,
    HomeController,
    FavoritesController,
    NotificationsController};
use App\Http\Controllers\Web\ServiceController as WebServ;
use App\Http\Controllers\Dashboard\ContactUsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/login-social', [AuthController::class, 'loginSocial']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::get('/maps-search', [MapsSearchController::class, 'searchByLocation'])->name('maps.search');
Route::get('/maps-place-id', [MapsSearchController::class, 'searchByPlaceId'])->name('maps.searchByPlaceId');

Route::prefix('home')->group(function () {
    Route::get('providers', [HomeController::class, 'getProviders']);
    Route::get('categories', [HomeController::class, 'getCategories']);
    Route::get('customer_reviews', [HomeController::class, 'getCustomerReviews']);
    Route::get('search', [HomeController::class, 'search']);
    Route::get('search-by-key/{key}', [HomeController::class, 'searchByKey']);
    Route::get('service/all/{id}', [HomeController::class, 'getServiceByCategory']);
    Route::get('service/all/sub/{id}', [HomeController::class, 'getServiceBySubCategory']);
    Route::get('task/all/{id}', [HomeController::class, 'getTasksByCategory']);
    Route::get('task/all/sub/{id}', [HomeController::class, 'getTaskBySubCategory']);
    Route::get('provider/all/{id}', [HomeController::class, 'getProviderByCategory']);
    Route::get('view-provider/{id}', [HomeController::class, 'getProvider']);
    Route::get('packages', [HomeController::class, 'GetPackages']);
    Route::get('packages/{package}', [HomeController::class, 'ShowPackages']);
    Route::get('view-task/{slug}', [TaskController::class, 'view']);
    Route::get('view-service/{slug}', [ServiceController::class, 'view']);

});

Route::get('tasks/view-task/{slug}', [TaskController::class, 'view']);
Route::get('services/view-service/{slug}', [ServiceController::class, 'view']);

Route::get('tasks/{id}', [TaskController::class, 'show']);
Route::get('services/{id}', [ServiceController::class, 'show']);

Route::middleware('auth:sanctum')->group(function (){
    Route::prefix('auth')->group(function () {
        Route::post('/verify-phone', [AuthController::class, 'verifyNumber']);
    });

    Route::prefix('subscription')->middleware('phone-verify')->group(function () {
        Route::get('checkout/{package}', [SubscriptionController::class, 'subscribe']);
        Route::get('cancel', [SubscriptionController::class, 'cancel']);
        Route::get('payment', [SubscriptionController::class, 'payment']);
    });

    Route::prefix('profile')->group(function () {
        Route::post('/update-profile', [ProfileController::class, 'updateProfile']);
        Route::get('/service-task', [ProfileController::class, 'serviceTask']);
        Route::post('/upload-avatar', [ProfileController::class, 'uploadAvatar']);
        Route::post('/update-password', [ProfileController::class, 'updatePassword']);
        Route::get('/subscription', [ProfileController::class, 'subscription']);
        Route::get('/favorites', [ProfileController::class, 'favorites']);
        Route::get('/get-user', [ProfileController::class, 'getUser']);
        Route::get('/reviews', [ProfileController::class, 'reviews']);

    });

    Route::get('account', [HomeController::class, 'account']);
    Route::post('provider-contact', [ProfileController::class, 'providerContact']);
    Route::post('provider-update-rate', [ProfileController::class, 'providerUpdateRate']);

    Route::apiResources([
        'tasks' => TaskController::class,
        'services' => ServiceController::class,
    ]);

    Route::prefix('services')->group(function () {
        Route::post('/add-service-images', [ServiceController::class, 'addServiceImages']);
        Route::post('/delete-service-images', [ServiceController::class, 'deleteServiceImages']);
        Route::post('service-update-rate/', [ServiceController::class, 'serviceUpdateRate']);
    });

    Route::prefix('tasks')->group(function () {
        Route::post('/add-task-images', [TaskController::class, 'addTaskImages']);
        Route::post('/delete-task-images', [TaskController::class, 'deleteTaskImages']);
        Route::post('/task-update-rate', [TaskController::class, 'taskUpdateRate']);
    });

    Route::prefix('favorites')->group(function () {
        Route::post('/', [FavoritesController::class, 'favorites']);
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationsController::class, 'fetchNotifications']);
        Route::post('/save-token', [NotificationsController::class, 'saveToken']);
        Route::post('/is-read', [NotificationsController::class, 'isRead']);
    });


    Route::prefix('configurations')->group(function () {
        Route::get('/GetByKey/{key}', [ConfigurationController::class, 'getByKey']);
    });
});

Route::post('/contact-us', [ContactUsController::class, 'store'])->name('contactUs');

//this route for non-web developers has been consuming api and need to reset user data for testing
//Route::post('/reset-users-data', function (){
//   if (getenv('APP_ENV') == 'local') {
//       DB::table('user_data')->delete();
//       DB::table('users')->delete();
//       return "User Data has been reset";
//   }
//   return "Can't reset users data";
//});
