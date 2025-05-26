<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\BaseController;
use App\Http\Resources\ServiceResource;

use App\Http\Requests\Web\Services\ServiceRequest;
use App\Http\Requests\Mobile\Services\UpdateServiceRequest;

use App\Models\Configuration;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Services\ServicesService;
use App\Services\CustomerReviewsRatingService;
use App\Services\CustomerReviewsService;
use App\Services\CategoryService;
use App\Services\NotificationService;

class ServiceController extends BaseController
{
    public function __construct(private readonly ServicesService $services_Service,
                                private readonly CustomerReviewsRatingService $customerReviewsRatingService,
                                private readonly CustomerReviewsService $customerReviewsService,
                                private readonly CategoryService $categoryService,
                                private readonly NotificationService $notificationService)
    {

    }

    /**
     * view service
     *
     * @param string $slug
     * @return View
     */
    //for view service string
    public function view(string $slug)
    {
        //set average and percentage value equal zero
        $this->customerReviewsRatingService->setRatingZero();

        //get service by slug (service name)
        $data = $this->services_Service->getServiceBySlug($slug);

        if($data['service-found'])
        {
            $service = $data['service'];

            //start get rate of current user for this service
                if (Auth::check())
                {
                    if($service['is_active'] == 0 && $service['user_id'] != Auth::user()['id'])
                    {
                        abort(404);
                    }

                    if ($service['user']['status'] == 0 && $service['user_id'] != Auth::user()['id']) {
                        abort(404);
                    }

                   //get CustomerReview average for $service by service id
                   $auth_customer_reviews = $this->customerReviewsService->getCustomerReviewByUserId($service['id'], 'service', Auth::user()['id']);
                   //Calculate rating of user  average, quality, time, accuracy, communication for service
                   $this->customerReviewsRatingService->CalculateAuthRating($auth_customer_reviews);
                }else{
                    if($service['is_active'] == 0)
                    {
                        abort(404);
                    }

                    if ($service['user']['status'] == 0) {
                        abort(404);
                    }
                }
            //end get rate of current user for this service

            //start get average and percentage rate of service
            //get CustomerReview average for task by service id
            $customer_reviews = $this->customerReviewsService->getCustomerReview($service['id'], 'service_average');
            //start get all CustomerReview for this service

            //Calculate rating average, quality, time, accuracy, communication for service
            if(!$customer_reviews->isEmpty()){
                $this->customerReviewsRatingService->CalculateRating($customer_reviews[0]);
            }
            //end get average and percentage rate of service

            //get CustomerReview average for service by service id
            $customer_reviews = $this->customerReviewsService->getCustomerReview($service['id'], 'service');

            //start get default currency
            $configurations = Configuration::query()->where("key", "currency_symbol")->first();
            //end get default currency

            $data = [
                'service' => $service,
                'customer_reviews' => $customer_reviews,
                'customer_reviews_rating' =>$this->customerReviewsRatingService->getCustomerReviewsRating(),
                'auth_rating' => $this->customerReviewsRatingService->getAuthRating(),
                'configurations' => $configurations,
            ];

            return view('service.view', $data);
        }
        else{
            abort(404);
        }
    }


    /**
     * create service view
     *
     * @return View|RedirectResponse
     */
    //create service page
    public function create(): View|RedirectResponse
    {
        if(empty(Auth::user()->user_data['package']))
        {
            return redirect('/account/subscription');
        }

        $categories = $this->categoryService->getCategories();
        $configurations = Configuration::query()->where("key", "currency_code")->first();

        $data = [
            'categories' => $categories,
            'configurations' => $configurations,
        ];
        return view('service.create', $data);
    }


    /**
     * create service
     *
     * @param ServiceRequest $request
     * @return RedirectResponse
     */
    //create service to database
    public function store(ServiceRequest $request): RedirectResponse
    {

        $data = $request->only('images','category_id','service_name','service_description','starting_price','ending_price','location_lng','location_lat');
        $data['user_id'] = Auth::user()->getAuthIdentifier();

        $response = $this->services_Service->addNewService($data);

        if ($response['status']){
            Session::flash('title','Congratulations');
            Session::flash('massage', $response['massage']);
            Session::flash('successButtonText', 'View Service');
            Session::flash('successButtonUrl', 'service-view/' . $response['services']['service_slug']);
        }else{
            Session::flash('title','Sorry Can\'t add service');
            Session::flash('massage', $response['massage']);
        }
        return redirect()->route('status');
    }

    /**
     * get service By Category
     *
     * @param ServiceRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function getServiceByCategory(Request  $request, int $id): JsonResponse
    {
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

        return response()->json($sorted->values()->all());
    }

    /**
     * get service By SubCategory
     *
     * @param ServiceRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function getServiceBySubCategory(Request  $request, int $id): JsonResponse
    {
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

        return response()->json($sorted->values()->all());
    }


    /**
     * create or Update service rate (CustomerReview)
     *
     *
     * @param ServiceRequest $request
     * @return JsonResponse
     */
    public function serviceUpdateRate(Request $request): JsonResponse
    {
        $flag = $this->customerReviewsService->UpdateCustomerReview($request, 'service');

        if($flag) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully updated your review'
            ]);
        }
        else{
            return response()->json('Failed to update review');
        }
    }

    /**
     * delete service
     *
     *
     * @param int $id
     * @return View
     */
    public function deleteService(int $id): View
    {
        $data = $this->services_Service->show($id);

        if($data['service-found']){
            $service = $data['service'];
            if($service['user_id'] == Auth::user()['id'])
            {
                $this->services_Service->delete($id);
            }
        }

        $configurations = Configuration::query()->where("key", "currency_symbol")->first();

        $data = [
            'service' => Auth::user()['services'],
            'tasks' => Auth::user()['tasks'],
            'configurations' => $configurations,
        ];
        $this->notificationService->removeNotifications($id, 'service');
        $this->customerReviewsService->removeReviews($id, 'service');
        return view('account.service-task', $data);
    }

    /**
     * update service view
     *
     *
     * @param int $id
     * @return View
     */
    //update service page
    public function updateService(int $id): View
    {
        $data = $this->services_Service->show($id);

        if($data['service-found']){
            $service = $data['service'];
            $categories = $this->categoryService->getCategories();
            if($service['user_id'] == Auth::user()['id'])
            {
                $configurations = Configuration::query()->where("key", "currency_code")->first();

                $data = [
                    'categories' => $categories,
                    'service' =>  $service,
                    'configurations' => $configurations,
                ];
                return view('service.update',  $data);
            }
        }

        $configurations = Configuration::query()->where("key", "currency_symbol")->first();

        $data = [
            'service' => Auth::user()['services'],
            'tasks' => Auth::user()['tasks'],
            'configurations' => $configurations,
        ];
        return view('account.service-task', $data);
    }

    /**
     * update service
     *
     *
     * @param UpdateServiceRequest $request
     * @return View
     */
    //update service to database
    public function updateServiceData(UpdateServiceRequest $request): View
    {
        $request['user_id'] = Auth::user()['id'];

        $request['service_id'] = $request['id'];
        //update new images

        if (!empty($request['images'])){
            $this->services_Service->createServiceImages($request);
        }

        $this->services_Service->update($request, $request['id']);

        $configurations = Configuration::query()->where("key", "currency_symbol")->first();

        $data = [
            'service' => Auth::user()['services'],
            'tasks' => Auth::user()['tasks'],
            'configurations' => $configurations,
           ];
        return view('account.service-task', $data);
    }

    /**
     * delete service Images
     *
     *
     * @param UpdateServiceRequest $request
     * @return JsonResponse
     */
    public function deleteServiceImages(Request $request): JsonResponse
    {
        $data = $this->services_Service->show($request['service_id']);

        if($data['service-found'])
        {
            $service = $data['service'];
            $user_id = auth('sanctum')->id();
            if($service['user_id'] == $user_id){
                $image_found = $this->services_Service->deleteServiceImages($request);
                if($image_found)
                {
                    return $this->sendResponse([], 'image element deleted successfully');
                }
                else{
                    return $this->sendError('image does not exist in this Service to delete Service Image', 'something went wrong');
                }
            }
        }

        return $this->sendError('service element does not exist to  Delete service Images');
    }
}
