<?php

use App\Http\Controllers\Dashboard\Auth\AuthController;
use App\Http\Controllers\Dashboard\{AdminController,
    CategoryController,
    ConfigurationController,
    PackageController,
    ServiceController,
    StatisticsController,
    SubCategoryController,
    TaskController,
    UserController,
    PaymentController,
    PackageDurationsController,
    ProviderContactController,
    QuestionAnswersController,
    ContactUsController,
    AttributesController};
use App\Http\Controllers\MapsSearchController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->group(function (){
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::post('/maps-search', [MapsSearchController::class, 'searchByLocation']);
    Route::post('/maps-place-id', [MapsSearchController::class, 'searchByPlaceId']);

    Route::middleware('auth:sanctum')->group(function (){
        Route::middleware('admin.token')->group(function()
        {
            Route::apiResources([
                'categories'    => CategoryController::class,
                'subCategories' => SubCategoryController::class,
                'admins' => AdminController::class,
                'users' => UserController::class,
                'tasks' => TaskController::class,
                'services' => ServiceController::class,
                'packages' => PackageController::class,
                'transactions'  => PaymentController::class,
                'configurations' => ConfigurationController::class,
                'packageDurations' => PackageDurationsController::class,
                'providerContacts' => ProviderContactController::class,
                'questionAnswers' => QuestionAnswersController::class,
                'contactUs' => ContactUsController::class,
                'attributes' => AttributesController::class,
            ]);
            Route::prefix('admins')->group(function () {
                Route::post('/updatePassword', [AdminController::class, 'updatePassword']);
                Route::post('/getAdminByToken', [AdminController::class, 'getAdminByToken']);
                Route::post('/status', [AdminController::class, 'status']);
            });
            Route::prefix('users')->group(function () {
                Route::post('/updatePassword', [UserController::class, 'updatePassword']);
                Route::post('/uploadAvatar', [UserController::class, 'uploadAvatar']);
                Route::post('/status', [UserController::class, 'status']);
                Route::post('/verify', [UserController::class, 'verify']);
            });
            Route::prefix('categories')->group(function () {
                Route::post('/uploadImage', [CategoryController::class, 'uploadImage']);
            });
            Route::prefix('subCategories')->group(function () {
                Route::post('/uploadImage', [SubCategoryController::class, 'uploadImage']);
            });
            Route::prefix('tasks')->group(function () {
                Route::post('/addTaskImages', [TaskController::class, 'addTaskImages']);
                Route::delete('/deleteImage/{id}', [TaskController::class, 'deleteImage']);
                Route::post('/status', [TaskController::class, 'status']);
            });
            Route::prefix('services')->group(function () {
                Route::post('/addServiceImages', [ServiceController::class, 'addServiceImages']);
                Route::delete('/deleteImage/{id}', [ServiceController::class, 'deleteImage']);
                Route::post('/status', [ServiceController::class, 'status']);
            });
            Route::prefix('packages')->group(function () {
                Route::post('/uploadImage', [PackageController::class, 'uploadImage']);
                Route::post('/isPublic', [PackageController::class, 'isPublic']);
                Route::post('/assignPackageToUser', [PackageController::class, 'assignPackageToUser']);
            });
            Route::prefix('statistics')->group(function () {
                Route::get('/entity', [StatisticsController::class, 'getEntityStatistics']);
                Route::get('/visitors-per-months', [StatisticsController::class, 'postViewsMonthly']);
                Route::get('/country', [StatisticsController::class, 'getStatisticsByCountry']);
            });

            Route::prefix('reviews')->group(function () {
                Route::get('/all', [UserController::class, 'getAllCustomerReview']);
                Route::get('/all/{id}', [UserController::class, 'getCustomerReview']);
                Route::put('/approval', [UserController::class, 'CustomerReviewUpdateApprovel']);
            });

            Route::prefix('configurations')->group(function () {
                Route::post('/updateByKey', [ConfigurationController::class, 'updateByKey']);
                Route::get('/GetByKey/{key}', [ConfigurationController::class, 'getByKey']);
                Route::post('/DeleteByKey/{key}', [ConfigurationController::class, 'deleteByKey']);
            });

            Route::prefix('questionAnswers')->group(function () {
                Route::post('/status', [QuestionAnswersController::class, 'status']);
            });

            Route::prefix('attributes')->group(function () {
                Route::post('/status', [AttributesController::class, 'status']);
            });
        });
    });
});
