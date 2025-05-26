<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\BaseController;
use App\Http\Resources\ConfigurationResource;
use App\Http\Resources\Mobile\CategoryActiveResource;
use App\Http\Resources\Mobile\CustomerReviewResource;
use App\Http\Resources\Mobile\CustomerReviewsResource;
use App\Http\Resources\Mobile\UserSubCategoriesResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\ProviderResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\TaskResource;
use App\Models\Category;
use App\Models\Configuration;
use App\Models\CustomerReview;
use App\Models\Package;
use App\Models\Services;
use App\Models\Task;
use App\Models\User;
use App\Models\UserSubCategories;
use App\Services\CustomerReviewsRatingService;
use App\Services\CustomerReviewsService;
use App\Services\ServicesService;
use App\Services\SubCategoryService;
use App\Services\TaskService;
use App\Services\UserService;
use App\Services\UserSubCategoriesService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends BaseController
{
    public function __construct(private readonly TaskService $task_service,
                                private readonly ServicesService $services_Service,
                                private readonly UserSubCategoriesService $usersubcategories_Service,
                                private readonly CustomerReviewsService $customerReviewsService,
                                private readonly CustomerReviewsRatingService $customerReviewsRatingService,
                                private readonly UserService $userService,
                                private readonly SubCategoryService $subCategoryService)
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function getProviders(): JsonResponse
    {
        $providers = User::query()->with('user_data')->where('status', 1)->with('CustomerReview', function ($query){
            $query->where('review_type', 'provider_average')->first();
        })->get();

        foreach($providers as $user)
        {
            if($user->CustomerReview()->where('review_type', 'provider_average')->get()->first())
            {
                $user['rate'] = $user->CustomerReview()->where('review_type', 'provider_average')->get()->first()->rate;
            }
            else{
                $user['rate'] = "0";
            }
        }

        $providers = ProviderResource::collection($providers);
        $statisticCollection = collect($providers);
        $providers = $statisticCollection->sortByDesc('rate')->take(10);
        return $this->sendResponse(array_values($providers->all()), 'success');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function getCategories(): JsonResponse
    {
        $categories = Category::where('is_active', 1)->with('subCategoriesActive')->with('subCategoriesActive.parentCategory')->get();
        $response   = CategoryActiveResource::collection($categories);
        return $this->sendResponse($response, 'Categories data fetched successfully');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function getCustomerReviews(): JsonResponse
    {
        //for CustomerReview section
        $CustomerReviews = CustomerReview::select('id', 'description', 'rate', 'review_id', 'customer_id', 'review_type')->orderBy('rate','DESC')->limit(10)
        ->with(['user' => function ($query) {
            $query->select('id', 'name', 'status')
                ->with(['user_data' => function ($query2) {
                    $query2->select('id', 'user_id', 'avatar');
                }]);
        }])
        ->with(['customer' => function ($query) {
            $query->select('id', 'name', 'status')
                ->with(['user_data' => function ($query2) {
                    $query2->select('id', 'user_id', 'avatar');
                }]);
        }])
        ->where('review_type', '=', "provider")->where('approvel', '1')->where('status', 1)->get();
        //->with('customer.user_data')




        //start get UserSubCategories
        foreach($CustomerReviews as $CustomerReview)
        {
            $user_sub_categories = UserSubCategories::with('SubCategory')->where('user_id', $CustomerReview['review_id'])->get()->toArray();
            $user_sub_categories_text = implode(", ",  array_map(function ($item) {
                return $item['sub_category']['sub_category_name'];
            }, $user_sub_categories)
            );
            $CustomerReview['user_sub_categories_text'] = $user_sub_categories_text;
            $CustomerReview->user->likes_count = (int)$CustomerReview->user->likes_count;
        }

        //end get UserSubCategories
        return $this->sendResponse(CustomerReviewsResource::collection($CustomerReviews), 'success');
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param int $id ,
     * @return JsonResponse
     */
    public function getServiceByCategory(Request  $request, int $id): JsonResponse
    {

        if(empty($request['latitude']))
        {
            $request['latitude'] = 0;
        }

        if(empty($request['longitude']))
        {
            $request['longitude'] = 0;
        }

        ServiceResource::$latitude =$request['latitude'];
        ServiceResource::$longitude =$request['longitude'];

        $services =  $this->services_Service->getServicesByCategory($id);
        $services = ServiceResource::collection($services);
        $statisticCollection = collect($services);
        $statisticCollection = $statisticCollection ->filter(function ($value, $key){
            return $value['is_active'] == 1;
        });

        $statisticCollection = $statisticCollection ->filter(function ($value, $key){
            return $value['user_status'] == 1;
        });

        if($request['latitude'] == 0 && $request['longitude'] == 0)
        {
            $sorted = $statisticCollection->sortByDesc('user_average')->sortByDesc('average')->take(20);

        }
        else{
            $sorted = $statisticCollection->sortByDesc('user_average')->sortByDesc('average')->sortBy('location')->take(20);
        }

        return $this->sendResponse($sorted->values()->all(), 'success');
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param int $id ,
     * @return JsonResponse
     */
    public function getServiceBySubCategory(Request  $request, int $id): JsonResponse
    {
        if(empty($request['latitude']))
        {
            $request['latitude'] = 0;
        }

        if(empty($request['longitude']))
        {
            $request['longitude'] = 0;
        }

        ServiceResource::$latitude =$request['latitude'];
        ServiceResource::$longitude =$request['longitude'];


        $services =  $this->services_Service->getServiceBySubCategory($id);
        $services = ServiceResource::collection($services);
        $statisticCollection = collect($services);
        $statisticCollection = $statisticCollection ->filter(function ($value, $key){
            return $value['is_active'] == 1;
        });

        $statisticCollection = $statisticCollection ->filter(function ($value, $key){
            return $value['user_status'] == 1;
        });

        if($request['latitude'] == 0 && $request['longitude'] == 0)
        {
            $sorted = $statisticCollection->sortByDesc('user_average')->sortByDesc('average')->take(20);

        }
        else{
            $sorted = $statisticCollection->sortByDesc('user_average')->sortByDesc('average')->sortBy('location')->take(20);

        }

        return $this->sendResponse($sorted->values()->all(), 'success');
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param int $id ,
     * @return JsonResponse
     */
    public function getTasksByCategory(Request  $request, int $id): JsonResponse
    {
        if(empty($request['latitude']))
        {
            $request['latitude'] = 0;
        }

        if(empty($request['longitude']))
        {
            $request['longitude'] = 0;
        }

        TaskResource::$latitude =$request['latitude'];
        TaskResource::$longitude =$request['longitude'];

        $tasks =  $this->task_service->getTasksByCategory($id);
        $tasks = TaskResource::collection($tasks);
        $statisticCollection = collect($tasks);
        $statisticCollection = $statisticCollection ->filter(function ($value, $key){
            return $value['is_active'] == 1;
        });

        $statisticCollection = $statisticCollection ->filter(function ($value, $key){
            return $value['user_status'] == 1;
        });

        if($request['latitude'] == 0 && $request['longitude'] == 0)
        {
            $sorted = $statisticCollection->sortByDesc('user_average')->sortByDesc('average')->take(20);
        }
        else{
            $sorted = $statisticCollection->sortByDesc('user_average')->sortByDesc('average')->sortBy('location')->take(20);
        }

        return $this->sendResponse($sorted->values()->all(), 'success');
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param int $id ,
     * @return JsonResponse
     */
    public function getTaskBySubCategory(Request  $request, int $id): JsonResponse
    {
        if(empty($request['latitude']))
        {
            $request['latitude'] = 0;
        }

        if(empty($request['longitude']))
        {
            $request['longitude'] = 0;
        }

        TaskResource::$latitude =$request['latitude'];
        TaskResource::$longitude =$request['longitude'];

        $tasks =  $this->task_service->getTaskBySubCategory($id);
        $tasks = TaskResource::collection($tasks);
        $statisticCollection = collect($tasks);
        $statisticCollection = $statisticCollection ->filter(function ($value, $key){
            return $value['is_active'] == 1;
        });

        $statisticCollection = $statisticCollection ->filter(function ($value, $key){
            return $value['user_status'] == 1;
        });

        if($request->latitude == 0 && $request->longitude == 0)
        {
            $sorted = $statisticCollection->sortByDesc('user_average')->sortByDesc('average')->take(20);
        }
        else{
            $sorted = $statisticCollection->sortByDesc('user_average')->sortByDesc('average')->sortBy('location')->take(20);
        }

        return $this->sendResponse($sorted->values()->all(), 'success');
    }


    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
	public function search(Request $request): JsonResponse
    {
        $categories = Category::where('is_active', 1)->get();
        //search query
        $q = $request->searchText;
        $c = $request->c;

        if($request->category == "")
        {
            $request->category = null;
        }
        else{
            $categories_text = str_replace('-', ' ', $request->category);
            $search_category = explode('|', $categories_text);
        }

        if($request->rating == "")
        {
            $request->rating = null;
        }
        $rating = $request->rating;

        if($request->price_start == "")
        {
            $request->price_start = null;
        }


        if(strtoupper($request->order_search) == "DESC")
        {
            $request->order_search = "desc";
        }
        else{
            $request->order_search = "asc";
        }
        $order = $request->order_search;

        if(empty($q))
        {
            $response = "please fill search text";
            return $this->sendResponse($response, 'something went wrong');
        }

        if(!is_null($request->rating)){

            $service = Services::orderBy('service_name', $order)->where('is_active', 1)->Where(function ($query) use ($q){
                $query->where('service_name', 'LIKE', "%{$q}%")
                    ->orWhere('service_description', 'LIKE', "%{$q}%")
                    ->orWhere('starting_price', 'LIKE', "%{$q}%")
                    ->orWhereHas('category', function($query) use ($q) {
                        $query->where('sub_category_name', 'LIKE', "%{$q}%");
                    })->orWhereHas('parentCategory', function($query) use ($q) {
                        $query->where('category_name', 'LIKE', "%{$q}%");
                    })->orWhereHas('user', function($query) use ($q) {
                        $query->where('name', 'LIKE', "%{$q}%");
                    });
            })->WhereHas('CustomerReview', function($q) use ($rating)
            {
                $q->whereBetween('rate', [$rating, $rating+1 -0.01]);

            });

            $tasks = Task::orderBy('task_name', $order)->where('is_active', 1)->Where(function ($query) use ($q){
                $query->where('task_name', 'LIKE', "%{$q}%")
                    ->orWhere('task_description', 'LIKE', "%{$q}%")
                    ->orWhere('starting_price', 'LIKE', "%{$q}%")
                    ->orWhere('ending_price', 'LIKE', "%{$q}%")
                    ->orWhereHas('category', function($query) use ($q) {
                        $query->where('sub_category_name', 'LIKE', "%{$q}%");
                    })->orWhereHas('parentCategory', function($query) use ($q) {
                        $query->where('category_name', 'LIKE', "%{$q}%");
                    })->orWhereHas('user', function($query) use ($q) {
                        $query->where('name', 'LIKE', "%{$q}%");
                    });
            })->WhereHas('CustomerReview', function($q) use ($rating)
            {
                $q->whereBetween('rate', [$rating, $rating+1 -0.01]);
            });
        }
        else{
            //return $request->rating;

            $service = Services::orderBy('service_name', $order)->with('CustomerReview')->where('is_active', 1)->Where(function ($query) use ($q){
                $query->where('service_name', 'LIKE', "%{$q}%")
                    ->orWhere('service_description', 'LIKE', "%{$q}%")
                    ->orWhere('starting_price', 'LIKE', "%{$q}%")
                    ->orWhereHas('category', function($query) use ($q) {
                        $query->where('sub_category_name', 'LIKE', "%{$q}%");
                    })->orWhereHas('parentCategory', function($query) use ($q) {
                        $query->where('category_name', 'LIKE', "%{$q}%");
                    })->orWhereHas('user', function($query) use ($q) {
                        $query->where('name', 'LIKE', "%{$q}%");
                    });
            });

            $tasks = Task::orderBy('task_name', $order)->with('CustomerReview')->where('is_active', 1)->Where(function ($query) use ($q){
                $query->where('task_name', 'LIKE', "%{$q}%")
                    ->orWhere('task_description', 'LIKE', "%{$q}%")
                    ->orWhere('starting_price', 'LIKE', "%{$q}%")
                    ->orWhere('ending_price', 'LIKE', "%{$q}%")
                    ->orWhereHas('category', function($query) use ($q) {
                        $query->where('sub_category_name', 'LIKE', "%{$q}%");
                    })->orWhereHas('parentCategory', function($query) use ($q) {
                        $query->where('category_name', 'LIKE', "%{$q}%");
                    })->orWhereHas('user', function($query) use ($q) {
                        $query->where('name', 'LIKE', "%{$q}%");
                    });
            });
        }

        //get max price
        $max_price =300;
        $last_price =0;
        $last_price = Task::max('ending_price');
        if(!empty($last_price))
        {
            if($last_price > $max_price)
                $max_price = $last_price;
        }

        $last_price = Services::max('ending_price');
        if(!empty($last_price))
        {
            if($last_price > $max_price)
                $max_price = $last_price;
        }

        if(empty($request->price_start))
        {
            $request->price_start = 0;
        }

        if(empty($request->price_end))
        {
            $request->price_end = $max_price;
        }


            //dd($tasks);
        if (!is_null($request->category) && !is_null($request->price_start)){
            $service = $service->whereBetween('starting_price', [$request->price_start, $request->price_end])
                ->whereBetween('ending_price', [$request->price_start, $request->price_end])
                ->WhereHas('category', function($query) use ($q, $search_category) {
                    $query->whereIn('sub_category_name', $search_category);
                })->get();
            $tasks = $tasks->whereBetween('starting_price', [$request->price_start, $request->price_end])
                ->whereBetween('ending_price', [$request->price_start, $request->price_end])
                ->WhereHas('category', function($query) use ($q, $search_category) {
                    $query->whereIn('sub_category_name', $search_category);
                })->get();
            //dd($search_category);

        }else if(!is_null($request->category)){
            $service = $service->WhereHas('category', function($query) use ($q, $search_category) {
                $query->whereIn('sub_category_name', $search_category);
            })->get();
            $tasks = $tasks->WhereHas('category', function($query) use ($q, $search_category) {
                $query->whereIn('sub_category_name', $search_category);
            })->get();
        }else if(!is_null($request->price_start)){
            $service = $service->whereBetween('starting_price', [$request->price_start, $request->price_end])
                ->whereBetween('ending_price', [$request->price_start, $request->price_end])
                ->get();
            $tasks = $tasks->whereBetween('starting_price', [$request->price_start, $request->price_end])
                ->whereBetween('ending_price', [$request->price_start, $request->price_end])
                ->get();
        }
        else{
            $service = $service->get();
            $tasks = $tasks->get();
        }

        $tasks = TaskResource::collection($tasks);
        $statisticCollectionTasks = collect($tasks);
        $statisticCollectionTasks = $statisticCollectionTasks ->filter(function ($value, $key){
            return $value['user_status'] == 1;
        });

        $service = ServiceResource::collection($service);
        $statisticCollectionServices = collect($service);
        $statisticCollectionServices = $statisticCollectionServices ->filter(function ($value, $key){
            return $value['user_status'] == 1;
        });

        if($order == "asc")
        {
            $tasks = $statisticCollectionTasks->sortBy('average')->values()->all();
            $service = $statisticCollectionServices->sortBy('average')->values()->all();
        }
        else{
            $tasks = $statisticCollectionTasks->sortByDesc('average')->values()->all();
            $service = $statisticCollectionServices->sortByDesc('average')->values()->all();
        }

        $configurations = Configuration::query()->where("key", "currency_symbol")->first();

        $data = [
            'categories' => $categories,
            'q' => $q,
            'c' => $c,
            'rating' => $request->rating,
            'service' => $service,
            'tasks' =>$tasks,
            'max_price' => $max_price,
            'order' => $order,
            'configurations' => ConfigurationResource::make($configurations),
        ];

        return $this->sendResponse($data, 'success');
    }


    public function searchByKey(String $key){
        $myArray = [];


        //search user by key and take 3 of them
        $users = User::where('name', 'LIKE', "%{$key}%")->where('status', 1)->select('name', 'id')->take(3)->get()->toArray();
        foreach($users as $user)
        {
            $itemkey =
                [
                    "type" => "Provider",
                    "provider_name" => $user['name'],
                    "id" => $user['id']

                ];
            array_push($myArray, $itemkey);
        }


        //search serivce by key and take 3 of them
        $services = Services::where('is_active', '1')->where('service_name', 'LIKE', "%{$key}%")->take(9)->get();
        $services = ServiceResource::collection($services);
        $services = collect($services);
        $services = $services ->filter(function ($value, $key){
            return $value['user_status'] == 1;
        });

        foreach($services as $service)
        {
            $itemkey =
                [
                    "type" => "Service",
                    "service_name" => $service['service_name'],
                    "service_slug" => $service['service_slug'],
                    "id" => $service['id']

                ];
            array_push($myArray, $itemkey);
        }


        //search task by key  and take 3 of them
        $tasks = Task::where('is_active', '1')->where('task_name', 'LIKE', "%{$key}%")->take(3)->get();
        $tasks = TaskResource::collection($tasks);
        $tasks = collect($tasks);
        $tasks = $tasks ->filter(function ($value, $key){
            return $value['user_status'] == 1;
        });

        foreach($tasks as $task)
        {
            $itemkey =
                [
                    "type" => "Task",
                    "task_name" => $task['task_name'],
                    "task_slug" => $task['task_slug'],
                    "id" => $task['id']
                ];
            array_push($myArray, $itemkey);
        }

        return $this->sendResponse($myArray, 'success');
    }

    //provider by name
    /**
     * Display a listing of the resource.
     * @param  int  $name,
     * @return JsonResponse
     */
    public function getProvider(int $id): JsonResponse
    {
        //set average and percentage value equal zero
        $this->customerReviewsRatingService->setRatingZero();

        $provider = $this->userService->getUser($id);

        if (!isset($provider)){
            return $this->sendError('failed user not found.', 'something went wrong');
        }

        //start get rate of current user for this provider
        if (auth('sanctum')->check())
        {
            if ($provider['status'] == 0 && $provider['id'] != auth('sanctum')->id()) {
                return $this->sendError('failed user not found.', 'something went wrong');
            }

            $auth_id = auth('sanctum')->user()['id'];

            //get CustomerReview average for task by task id
            $auth_customer_reviews = $this->customerReviewsService->getCustomerReviewByUserId($id, 'provider', $auth_id);

            $this->customerReviewsRatingService->CalculateAuthRating($auth_customer_reviews);
        }
        else {
            if ($provider['status'] == 0) {
                return $this->sendError('failed user not found.', 'something went wrong');
            }
        }
        //end get rate of current user for this provider

        //start get UserSubCategories
        $user_sub_categories_text =  $this->usersubcategories_Service->getUserSubCategoriesText($id);
        //end get UserSubCategories

        //get average CustomerReview for this provider
        $customer_review_average = $this->customerReviewsService->getCustomerReview($id, 'provider_average');

        //Calculate rating average, quality, time, accuracy, communication for task
        if(!$customer_review_average->isEmpty()){
            $this->customerReviewsRatingService->CalculateRating($customer_review_average[0]);
        }

        //get all CustomerReview for this provider
        $customer_reviews = $this->customerReviewsService->getCustomerReviewProvider($id);

        $provider['user_data']['avatar'] = asset($provider->user_data->avatar);

        $configurations = Configuration::query()->where("key", "currency_symbol")->first();

        $data = [
            'provider' => ProviderResource::make($provider),
            'services' => ServiceResource::collection($provider['servicesActive']),
            'reviews' => CustomerReviewsResource::collection($customer_reviews),
            'rating_average' => $this->customerReviewsRatingService->getCustomerReviewsRating(),
            'auth_rating' => $this->customerReviewsRatingService->getAuthRating(),
            'user_sub_categories' => $user_sub_categories_text,
            'configurations' => ConfigurationResource::make($configurations),
        ];

        return $this->sendResponse($data, 'success');

    }

    public function getProviderByCategory(int $id): JsonResponse
    {
        $provider_id_Array = $this->userService->getUserIdsArrayByCategoryId($id);

        $users = $this->userService->getUserWithRateSubCategoriesByIdArray($provider_id_Array);

        $users = ProviderResource::collection($users);
        $statisticCollection = collect($users);
        $users = $statisticCollection->sortByDesc('rate')->take(20);

        return $this->sendResponse($users->values()->all(), 'success get provider by category');

    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function GetPackages(): JsonResponse
    {
        $package = Package::where('is_public', 1)->get();
        return $this->sendResponse(PackageResource::collection($package), 'success');
    }

    /**
     * Display a listing of the resource.
     * @param  string  $package,
     * @return JsonResponse
     */
    public function ShowPackages(string $package): JsonResponse
    {
       try
       {


        if(auth('sanctum')->check())
        {
            $package = Package::where('is_public', 1)->where('package_name', $package)->first();

            if (empty($package)){
                return $this->sendError('failed package not found.', 'something went wrong');
            }

            //if user has no package
            $user_id = auth('sanctum')->id();
            if(is_null(User::query()->findOrFail($user_id)->user_data->package_id))
            {
                return $this->sendResponse($package, 'success');
            }
            else{
                //has package
                $current_package = Package::where('id',User::query()->findOrFail($user_id)->user_data->package_id)->first();

                //has same package
                if($current_package['id'] == $package['id'])
                {
                    return $this->sendError('failed you have already this package', 'something went wrong');
                }
                else if($current_package['price'] > $package['price'])
                {
                    return $this->sendError('failed you have package higher than this package', 'something went wrong');
                }
                else{
                    return $this->sendResponse($package, 'success');
                }
            }

        }
        else{
            return $this->sendError('unauthorized', 'something went wrong');
        }
        }
        catch(Exception $e)
        {
            return $this->sendError($e, 'user not exist');
        }


    }



    /**
     * Display account
     * @return JsonResponse
     */
    public function account(): JsonResponse
    {
        $categories = Category::with('subCategoriesActive')->where('is_active', true)->get();
        $user_sub_categories = UserSubCategories::with('SubCategory')->where('user_id', auth('sanctum')->user()['id'])->get();


        $data = [
            'avatar' => asset(auth('sanctum')->user()['user_data']['avatar']),
            'full_name' => auth('sanctum')->user()['name'],
            'email' => auth('sanctum')->user()['email'],
            'phoned_Signed' => auth('sanctum')->user()['phoned_Signed'],
            'phone_number' => '+'. auth('sanctum')->user()['country_code'] . auth('sanctum')->user()['phone_number'],
            'phone' => auth('sanctum')->user()['phone_number'],
            'country_code' => auth('sanctum')->user()['country_code'],
            'categories' => CategoryActiveResource::collection($categories),
            'user_sub_categories' => UserSubCategoriesResource::collection($user_sub_categories)
        ];

        return $this->sendResponse($data, 'success');
    }
}
