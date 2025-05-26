<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\BaseController;
use App\Http\Resources\ConfigurationResource;
use App\Http\Resources\CustomerReviewUserResource;
use App\Http\Resources\Mobile\CustomerReviewResource;
use App\Models\Configuration;
use App\Models\Services;
use App\Services\SubCategoryService;
use Illuminate\Http\Request;

use App\Http\Resources\Mobile\ServiceResource;
use App\Http\Requests\Dashboard\Services\ServiceRequest;
use App\Http\Requests\Dashboard\Services\UpdateServiceRequest;

use App\Http\Requests\Mobile\Services\AddServiceImagesRequest;

use App\Services\ServicesService;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;

use App\Services\CustomerReviewsRatingService;
use App\Services\CustomerReviewsService;

class ServiceController extends BaseController
{


    public function __construct(private readonly ServicesService $services_Service,
                                private readonly CustomerReviewsRatingService $customerReviewsRatingService,
                                private readonly CustomerReviewsService $customerReviewsService,
                                private readonly NotificationService $notificationService,
                                private readonly SubCategoryService $subCategoryService)
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user_id = auth('sanctum')->user()['id'];
        $services = $this->services_Service->getUserServices($user_id);
        $services = ServiceResource::collection($services);
        $services = collect($services);
        $services = $services ->filter(function ($value, $key){
            return $value['is_active'] == "1";
        });

        $services = $services ->filter(function ($value, $key){
            return $value['user_status'] == 1;
        });

        return $this->sendResponse($services,'Service data fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ServiceRequest  $request
     * @return JsonResponse
     */
    public function store(ServiceRequest $request): JsonResponse
    {
        $data = $request->only('images','category_id','service_name','service_description','starting_price','ending_price','location_lng','location_lat');
        $data['user_id'] = auth('sanctum')->id();

        if(!$this->subCategoryService->checkCategoryIsActiveBySubCategoryId($data['category_id']))
        {
            return $this->sendError('category not found have subcategory '. $data['category_id'], "something went wrong");
        }
        else if(!$this->subCategoryService->checkSubCategoryIsActiveBySubCategoryId($data['category_id']))
        {
            return $this->sendError('SubCategory ' . $data['category_id'] . ' not found', "something went wrong");
        }

        $response = $this->services_Service->addNewService($data);

        if($response['status']){
            $service = ServiceResource::make(Services::query()->where('is_active', '1')->where('id', $response['service_id'])->first());
            return $this->sendResponse($service, 'success');
        }else{
            return $this->sendError('something went wrong', [$response['massage']]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        //set average and percentage value equal zero
        $this->customerReviewsRatingService->setRatingZero();

        $data = $this->services_Service->show((int) $id);

        if($data['service-found'])
        {
            $user_id = auth('sanctum')->id();

            $service = $data['service'];

            //start get rate of current user for this service
            if (auth('sanctum')->check())
            {
                if($service['is_active'] == 0 && $service['user_id'] != $user_id)
                {
                    return $this->sendError('failed cannot show service not found.', 'something went wrong');
                }

                if ($service['user']['status'] == 0 && $service['user_id'] != $user_id) {
                    return $this->sendError('failed cannot show service not found.', 'something went wrong');
                }

                //get CustomerReview average for $service by service id
                $auth_customer_reviews = $this->customerReviewsService->getCustomerReviewByUserId($service['id'], 'service', $user_id);
                //Calculate rating of user  average, quality, time, accuracy, communication for service
                $this->customerReviewsRatingService->CalculateAuthRating($auth_customer_reviews);
            }else{
                if($service['is_active'] == 0)
                {
                    return $this->sendError('failed cannot show service not found.', 'something went wrong');
                }

                if ($service['user']['status'] == 0) {
                    return $this->sendError('failed cannot show service not found.', 'something went wrong');
                }
            }
            //end get rate of current user for this service

            //start get average and percentage rate of service
            //get CustomerReview average for service by service id
            $customer_reviews = $this->customerReviewsService->getCustomerReview($service['id'], 'service_average');

            //Calculate rating average, quality, time, accuracy, communication for service
            if(!$customer_reviews->isEmpty()){
                $this->customerReviewsRatingService->CalculateRating($customer_reviews[0]);
            }
            //end get average and percentage rate of service

            $customer_reviews = $this->customerReviewsService->getCustomerReview($service['id'], 'service');

            //start get default currency
            $configurations = Configuration::query()->where("key", "currency_symbol")->first();
            //end get default currency

            $service = ServiceResource::make($service);

            $data = [
                'service' => $service,
                'customer_reviews' => CustomerReviewResource::collection($customer_reviews),
                'customer_reviews_rating' =>$this->customerReviewsRatingService->getCustomerReviewsRating(),
                'auth_rating' => $this->customerReviewsRatingService->getAuthRating(),
                'configurations' => ConfigurationResource::make($configurations),
            ];

            return $this->sendResponse($data, 'success');
        }
        else{
            return $this->sendError('failed',['service element does not exist to show']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateServiceRequest $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(UpdateServiceRequest $request,int $id): JsonResponse
    {

        $data = $this->services_Service->show($id);

        if(!$data['service-found']){
            return $this->sendError('service element does not exist to update');
        }

        $service =  $data['service'];

        if($service['user_id'] == auth('sanctum')->id())
        {
            $request['user_id'] = auth('sanctum')->id();
            $service = $this->services_Service->update($request, $id);
            $service = ServiceResource::make($service);
            return $this->sendResponse($service,'service updated successfully');

        }
        else {
            return $this->sendError('failed cannot Update service of another user.', 'something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $data = $this->services_Service->show($id);

        if(!$data['service-found']){
            return $this->sendError('service element does not exist to delete');
        }

        $service =  $data['service'];

        if($service['user_id'] == auth('sanctum')->id())
        {
            $this->services_Service->delete($id);
            $this->notificationService->removeNotifications($id, 'service');
            $this->customerReviewsService->removeReviews($id, 'service');

            return $this->sendResponse("delete service" , 'success');

        }
        else {
            return $this->sendError("Can't delete service for another user.", 'something went wrong');
        }
    }

    /**
     * add service image
     * @param AddServiceImagesRequest $request
     * @return JsonResponse
     */
    public function addServiceImages(AddServiceImagesRequest $request): JsonResponse
    {
        $data = $this->services_Service->show($request['service_id']);

        if($data['service-found'])
        {
            $service = $data['service'];

            $user_id = auth('sanctum')->id();
            if($service['user_id'] == $user_id){
                $this->services_Service->createServiceImages($request);
                return $this->sendResponse([], 'image element add successfully');
            }
            else{
                return $this->sendError('failed cannot add service Images to another user.', 'something went wrong');
            }
        }
        else{
            return $this->sendError('service element does not exist to add service Images');
        }
    }

    /**
     * delete Service image.
     *
     * @param Request $request
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


    public function view(string $slug): JsonResponse
    {
        //set average and percentage value equal zero
        $this->customerReviewsRatingService->setRatingZero();

        //get service by slug (service name)
        $data = $this->services_Service->getServiceBySlug($slug);

        if($data['service-found'])
        {
            $user_id = auth('sanctum')->id();

            $service = $data['service'];

            //start get rate of current user for this service
            if (auth('sanctum')->check())
            {
                if($service['is_active'] == 0 && $service['user_id'] != $user_id)
                {
                    return $this->sendError('failed cannot show service not found.', 'something went wrong');
                }

                if ($service['user']['status'] == 0 && $service['user_id'] != $user_id) {
                    return $this->sendError('failed cannot show service not found.', 'something went wrong');
                }

                //get CustomerReview average for $service by service id
                $auth_customer_reviews = $this->customerReviewsService->getCustomerReviewByUserId($service['id'], 'service', $user_id);
                //Calculate rating of user  average, quality, time, accuracy, communication for service
                $this->customerReviewsRatingService->CalculateAuthRating($auth_customer_reviews);
            }else{
                if($service['is_active'] == 0)
                {
                    return $this->sendError('failed cannot show service not found.', 'something went wrong');
                }

                if ($service['user']['status'] == 0) {
                    return $this->sendError('failed cannot show service not found.', 'something went wrong');
                }
            }
            //end get rate of current user for this service


            //start get average and percentage rate of service
            //get CustomerReview average for service by service id
            $customer_reviews = $this->customerReviewsService->getCustomerReview($service['id'], 'service_average');
            //start get all CustomerReview for this provider

            //Calculate rating average, quality, time, accuracy, communication for service
            if(!$customer_reviews->isEmpty()){
                $this->customerReviewsRatingService->CalculateRating($customer_reviews[0]);
            }
            //end get average and percentage rate of service

            //get CustomerReview average for task by service id
            $customer_reviews = $this->customerReviewsService->getCustomerReview($service['id'], 'service');

            //start get default currency
            $configurations = Configuration::query()->where("key", "currency_symbol")->first();
            //end get default currency

            $data = [
                'service' => ServiceResource::make($service),
                'customer_reviews' => CustomerReviewResource::collection($customer_reviews),
                'customer_reviews_rating' =>$this->customerReviewsRatingService->getCustomerReviewsRating(),
                'auth_rating' => $this->customerReviewsRatingService->getAuthRating(),
                'configurations' => ConfigurationResource::make($configurations),
            ];
            return $this->sendResponse($data, 'success');
        }
        else{
            return $this->sendError('service element does not exist to show');
        }
    }

    public function serviceUpdateRate(Request $request): JsonResponse
    {
        $data = $this->services_Service->show($request['review_id']);

        if($data['service-found'])
        {
            $flag = $this->customerReviewsService->UpdateCustomerReview($request, 'service');

            if($flag)
            {
                return $this->sendResponse([], 'service Customer Review element updated successfully');
            }
            else{
                return $this->sendResponse([], 'service Customer Review element not updated');
            }
        }
        else{
            return $this->sendError('service element does not exist to rate');

        }
    }
}
