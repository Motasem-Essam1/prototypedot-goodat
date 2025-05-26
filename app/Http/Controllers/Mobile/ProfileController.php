<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Mobile\Profile\UpdateProfileRequest;
use App\Http\Requests\Mobile\Services\GetServicesTaskRequest;
use App\Http\Requests\Mobile\Subscriptions\SubscriptionRequest;
use App\Http\Resources\ConfigurationResource;
use App\Http\Resources\Mobile\CustomerReviewResource;
use App\Http\Resources\Mobile\ServiceResource;
use App\Http\Resources\Mobile\TaskResource;
use App\Http\Resources\ServiceTaskResource;
use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\UpdateUserResource;
use App\Models\Configuration;
use App\Services\CustomerReviewsService;
use App\Services\ProviderContactService;
use App\Services\SubCategoryService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Models\CustomerReview;
use App\Models\Favorite;
use App\Models\Services;
use App\Models\Task;
use App\Models\User;
use App\Models\UserData;
use App\Models\UserSubCategories;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Validation\Rules;
use App\Utils\FileUtil;
use Illuminate\Support\Facades\Validator;


class ProfileController extends BaseController
{
    private $userService;
    private $fileUtil;

    public function __construct(UserService $userService,
                                FileUtil $fileUtil,
                                private readonly SubCategoryService $subCategoryService,
                                private readonly ProviderContactService $providerContactService,
                                private readonly CustomerReviewsService $customerReviewsService)
    {
        $this->userService = $userService;
        $this->fileUtil = $fileUtil;
    }

    /**
     * updateProfile.
     *
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        if ($request->user()->currentAccessToken()) {
            $data = [
                'id' => auth('sanctum')->id(),
                'full_name' => $request['fullname'],
                'email' => $request['email'],
                'country_code' => $request['phone_code'],
                'phone_number' => $request['phone_number'],
                'user_sub_categories' => $request['user_sub_categories']
            ];

            foreach ($data['user_sub_categories'] as $user_sub_category)
            {
                if(!$this->subCategoryService->checkCategoryIsActiveBySubCategoryId($user_sub_category))
                {
                    return $this->sendError('category not found have subcategory '. $user_sub_category, "something went wrong");
                }
                else if(!$this->subCategoryService->checkSubCategoryIsActiveBySubCategoryId($user_sub_category))
                {
                    return $this->sendError('SubCategory ' . $user_sub_category . ' not found', "something went wrong");
                }
            }

            $uploadedData = $this->userService->uploadUserData($data);
            if ($uploadedData) {
                $user_data = UpdateUserResource::make(User::query()->where('id', auth('sanctum')->id())->with('user_data')->first());
                return $this->sendResponse($user_data, 'success');
            } else {
                return $this->sendError('failed.', 'something went wrong');
            }
        } else {
            return $this->sendError('failed Cannot updateProfile no token.', 'something went wrong');

        }

    }


    /**
     * serviceTask.
     *
     * @param GetServicesTaskRequest $request
     * @return JsonResponse
     */
    public function serviceTask(GetServicesTaskRequest $request): JsonResponse
    {

        if ($request->user()->currentAccessToken()) {

            $user = User::query()->where('id', auth('sanctum')->id())->first();
            $result = ServiceTaskResource::make($user);
            if ($result) {
                return $this->sendResponse($result, 'success');
            } else {
                return $this->sendError('failed.', ['something went wrong']);
            }
        } else {
            return $this->sendError('failed cannot get serviceTask no token.', 'something went wrong');
        }
    }

    /**
     * updatePassword.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePassword(Request $request): JsonResponse
    {
        if ($request->user()->currentAccessToken()) {
            $validator = Validator::make($request->all(), [
                'old_password' => ['required', 'string'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            if ($validator->fails()) {
                return $this->sendError('something went wrong', $validator->messages()->all());
            }

            $request['id'] = auth('sanctum')->id();
            $data = $request->only('id', 'old_password', 'password');

            $response = $this->userService->updatePassword($data);
            if ($response['status']) {
                return $this->sendResponse([], 'success');
            } else {
                return $this->sendError('failed', 'something went wrong');
            }
        } else {
            return $this->sendError('failed cannot updatePassword no token.', 'something went wrong');
        }

    }

    /**
     * uploadAvatar.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        $image_path = "";
        if ($request->user()->currentAccessToken()) {
            $name = auth('sanctum')->id() . "-" . time();
            $path = "user-profile";
            $user_data = UserData::query()->where('user_id', auth('sanctum')->id())->first();
            if (isset($request->avatar)) {

                //delete old avatar
                if ($user_data->avatar != "assets/img/ninja.svg") {
                    $this->fileUtil->deleteFileByPath($user_data->avatar);
                }

                $image_path = $this->fileUtil->uploadFile($request->avatar, $name, $path);
                $user_data->avatar = $image_path;
            }
            $user_data->save();

            $user_data = UpdateUserResource::make(User::query()->where('id', auth('sanctum')->id())->with('user_data')->first());
            if ($image_path) {
                return $this->sendResponse($user_data, 'uploaded successfully');
            } else {
                return $this->sendError('failed.', ['something went wrong']);
            }
        } else {
            return $this->sendError('failed cannot uploadAvatar no token.', 'something went wrong');
        }

    }

    /**
     * subscription.
     *
     * @param SubscriptionRequest $request
     * @return JsonResponse
     */
    public function subscription(SubscriptionRequest $request): JsonResponse
    {
        if ($request->user()->currentAccessToken()) {
            $user = User::query()->where('id', auth('sanctum')->id())->with('user_data')->first();
            $result = SubscriptionResource::make($user);
            if ($result) {
                return $this->sendResponse($result, 'success');
            } else {
                return $this->sendError('failed.', ['something went wrong']);
            }
        } else {
            return $this->sendError('failed get subscription no token.', 'something went wrong');
        }

    }

    /**
     * favorites.
     *
     * @return JsonResponse
     */
    public function favorites(): JsonResponse
    {

        if (auth('sanctum')->check()) {
            // Services
            $services_ids = Favorite::query()->where('item_type', 'service')->where('user_id', auth('sanctum')->id())->get()->pluck('favorite_id')->toArray();
            $services = Services::query()->where('is_active', '1')->whereIn("id", $services_ids)->with('images')->with('user.user_data')->paginate(15, ['*'], 'services');

            // Tasks
            $tasks_ids = Favorite::query()->where('item_type', 'task')->where('user_id', auth('sanctum')->id())->get()->pluck('favorite_id')->toArray();
            $tasks = Task::query()->where('is_active', '1')->whereIn("id", $tasks_ids)->with('images')->with('user.user_data')->paginate(15, ['*'], 'tasks');

            // Providers
            $providers_ids = Favorite::query()->where('item_type', 'provider')->where('user_id', auth('sanctum')->id())->get()->pluck('favorite_id')->toArray();
            $providers = User::query()->whereIn("id", $providers_ids)->with('user_data')->with('UserSubCategories')->paginate(15, ['*'], 'providers');
            foreach ($providers as $user) {
                $user_sub_categories = UserSubCategories::with('SubCategory')->where('user_id', auth('sanctum')->id())->get()->toArray();
                $user_sub_categories_text = implode(", ", array_map(function ($item) {
                        return $item['sub_category']['sub_category_name'];
                    }, $user_sub_categories)
                );
                $user['user_sub_categories_text'] = $user_sub_categories_text;

                //get rate
                if ($user->CustomerReview()->where('review_type', 'provider_average')->get()->first()) {
                    $user['rate'] = $user->CustomerReview()->where('review_type', 'provider_average')->get()->first()->rate;
                } else {
                    $user['rate'] = 0;
                }

                //get  number of customer review
                if ($user->CustomerReview()->where('review_type', 'provider')->get()) {
                    $user['customer_review_number'] = $user->CustomerReview()->where('review_type', 'provider')->get()->count();
                } else {
                    $user['customer_review_number'] = 0;
                }
            }

            $data = [
                'services' => ServiceResource::collection($services),
                'providers' => $providers,
                'tasks' => TaskResource::collection($tasks),
                'user' => auth('sanctum')->user()
            ];

            if ($data) {
                return $this->sendResponse($data, 'Favorites data fetched successfully');
            } else {
                return $this->sendError('failed.', ['something went wrong']);
            }
        } else {
            return $this->sendError('failed cannot get favorites.', 'something went wrong');
        }
    }


    /**
     * getUser.
     *
     * @return JsonResponse
     */
    public function getUser(): JsonResponse
    {
        $user = UserResource::make(User::query()->where('id' , auth('sanctum')->id())->with('user_data')->first());
        return $this->sendResponse($user, 'success');
    }

    public function providerUpdateRate(Request $request): JsonResponse
    {
        //update customer review of task
        $flag = $this->customerReviewsService->UpdateCustomerReview($request, 'provider');

        if($flag)
        {
            return $this->sendResponse([], 'provider Customer Review element updated successfully');
        }
        else{
            return $this->sendResponse([], 'provider Customer Review element not updated');
        }
    }

    public function providerContact(Request $request): JsonResponse
    {
        $flag = $this->providerContactService->createProviderContact($request);

        if($flag)
        {
            return $this->sendResponse([], 'success create provider contact ');
        }
        else{
            return $this->sendResponse([], 'provider can not contact himself');
        }
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function reviews(): JsonResponse
    {
        if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->id();
            $service_CustomerReviews = CustomerReview::query()->where('customer_id',  $user_id)->with('Service')->where('review_type', "service")->where('status', 1)->get();
            $provider_CustomerReviews = CustomerReview::query()->where('customer_id', $user_id)->with('user.user_data')->where('review_type', "provider")->where('status', 1)->get();
            $configurations = Configuration::query()->where("key", "currency_symbol")->first();

            $data = [
                'service_CustomerReviews' => CustomerReviewResource::collection($service_CustomerReviews),
                'provider_CustomerReviews' => CustomerReviewResource::collection($provider_CustomerReviews),
                'configurations' => ConfigurationResource::make($configurations),

            ];
            return $this->sendResponse($data, 'success');

        }
        else {
            # code...
            return $this->sendError('unauthorized', 'something went wrong');

        }
    }
}
