<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Mobile\Services\AddServiceImagesRequest;
use App\Http\Requests\Dashboard\Services\AddServiceRequest;
use App\Http\Requests\Dashboard\Services\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\UploadImagesServiceResource;
use App\Models\Services;
use App\Services\ServicesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Services\CustomerReviewsRatingService;
use App\Services\CustomerReviewsService;
use App\Services\NotificationService;

class ServiceController extends BaseController
{


    public function __construct(private readonly ServicesService $serviceService,
                                private readonly CustomerReviewsRatingService $customerReviewsRatingService,
                                private readonly CustomerReviewsService $customerReviewsService,
                                private readonly NotificationService $notificationService)
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $services = $this->serviceService->index();
        $services = ServiceResource::collection($services);
        return $this->sendResponse($services,'Service data fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddServiceRequest $request
     * @return JsonResponse
     */
    public function store(AddServiceRequest $request): JsonResponse
    {
        $data = $request->only('images', 'user_id','category_id','service_name','service_description','starting_price','ending_price','location_lng','location_lat');
        $response = $this->serviceService->addNewService($data);
        if($response['status']){
            $service = ServiceResource::make(Services::query()->where('id', $response['service_id'])->first());
            return $this->sendResponse($service, 'Service create success');
        }else{
            return $this->sendError('Service not create something went wrong', [$response['massage']]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $data = $this->serviceService->show($id);

        if($data['service-found'])
        {   $service = $data['service'];
            $service = new ServiceResource($service);

            //set average and percentage value equal zero
            $this->customerReviewsRatingService->setRatingZero();

            //start get average and percentage rate of task
                //get CustomerReview average for task by task id
                $customer_reviews = $this->customerReviewsService->getCustomerReview($service['id'], 'service_average');

                //Calculate rating average, quality, time, accuracy, communication for task
                if(!$customer_reviews->isEmpty()){
                    $this->customerReviewsRatingService->CalculateRating($customer_reviews[0]);
                }
            //end get average and percentage rate of task

            $customer_reviews = $this->customerReviewsService->getCustomerReview($service['id'], 'service');


            $data = [
                'service' => $service,
                'customer_reviews' => $customer_reviews,
                'customer_reviews_rating' =>$this->customerReviewsRatingService->getCustomerReviewsRating(),
            ];

            return $this->sendResponse($data, 'success');

        }
        else{
            return $this->sendError('failed',['Service element does not exist to show']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateServiceRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateServiceRequest $request,int $id): JsonResponse
    {
        $data = $this->serviceService->show($id);

        if(!$data['service-found']){
            return $this->sendError('Service element does not exist');

        }

        $service = $this->serviceService->update($request, $id);
        $service = ServiceResource::make($service);

        return $this->sendResponse($service,'Service updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $data = $this->serviceService->show($id);

        if(!$data['service-found']){
            return $this->sendError('Service element does not exist');

        }

        $this->serviceService->delete($id);
        $this->notificationService->removeNotifications($id, 'service');
        $this->customerReviewsService->removeReviews($id, 'service');
        return $this->sendResponse([],'Service element deleted successfully');
    }

    /**
     * add service image.
     *
     * @param AddServiceImagesRequest $request
     * @return JsonResponse
     */
    public function addServiceImages(AddServiceImagesRequest $request): JsonResponse
    {
        $this->serviceService->createServiceImages($request);
        $service = UploadImagesServiceResource::make(Services::query()->where('id', $request['service_id'])->with('images')->first());
        return $this->sendResponse($service, 'service success to addServiceImages');
    }

    /**
     * delete Service image.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deleteImage(int $id): JsonResponse
    {
        $check = $this->serviceService->deleteServiceImagesByImagesId($id);

        if($check)
        {
         return $this->sendResponse([],'image element deleted successfully');
        }
        else{
         return $this->sendResponse([],'image element does not exist to deleted');
        }
    }

    public function status(Request $request) {
        $request->validate([
            'id' => 'required|exists:services,id,deleted_at,NULL',
            'is_active' => 'required'
        ]);

        $data = Services::where('id', $request->id)->first();
        $data->is_active = $request->is_active == 1 ? 1 : 0;
        $data->save();

        if($data) {
            $serviceData = ServiceResource::make($data);
            return $this->sendResponse($serviceData, $data->is_active ? 'Service is active now (ON)' : 'Service is inactive now (OFF)');
        } else {
            return $this->sendError('Service active fail something went wrong', []);
        }
    }
}
