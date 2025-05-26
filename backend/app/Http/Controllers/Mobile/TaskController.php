<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Mobile\Tasks\AddTaskImagesRequest;
use App\Http\Requests\Dashboard\Services\TaskRequest;
use App\Http\Requests\Dashboard\Services\UpdateTaskRequest;

use App\Http\Resources\ConfigurationResource;
use App\Http\Resources\CustomerReviewUserResource;
use App\Http\Resources\Mobile\CustomerReviewResource;
use App\Models\Configuration;
use App\Models\Task;
use App\Services\CustomerReviewsRatingService;
use App\Services\CustomerReviewsService;
use App\Services\SubCategoryService;
use App\Services\TaskService;
use App\Services\NotificationService;

use App\Http\Resources\Mobile\TaskResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class TaskController extends BaseController
{




    public function __construct(private readonly TaskService $task_service,
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
        $tasks = $this->task_service->getUserTasks($user_id);
        $tasks = TaskResource::collection($tasks);
        $tasks = collect($tasks);
        $tasks = $tasks ->filter(function ($value, $key){
            return $value['is_active'] == "1";
        });

        $tasks = $tasks ->filter(function ($value, $key){
            return $value['user_status'] == 1;
        });
        return $this->sendResponse($tasks,'Task data fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TaskRequest $request
     * @return JsonResponse
     */
    public function store(TaskRequest $request): JsonResponse
    {
        $data = $request->only('images','category_id','task_name','task_description','starting_price','ending_price','location_lng','location_lat');
        $data['user_id'] = auth('sanctum')->id();

        if(!$this->subCategoryService->checkCategoryIsActiveBySubCategoryId($data['category_id']))
        {
            return $this->sendError('category not found have subcategory '. $data['category_id'], "something went wrong");
        }
        else if(!$this->subCategoryService->checkSubCategoryIsActiveBySubCategoryId($data['category_id']))
        {
            return $this->sendError('SubCategory ' . $data['category_id'] . ' not found', "something went wrong");
        }

        $response = $this->task_service->addNewTask($data);


        if ($response['status']){
            $task = TaskResource::make(Task::query()->where('is_active', '1')->where('id', $response['task_id'])->first());
            return $this->sendResponse($task, 'success');
        }else{
            return $this->sendError('something went wrong', [$response['massage']]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id,
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        //set average and percentage value equal zero
        $this->customerReviewsRatingService->setRatingZero();

        $data = $this->task_service->show($id);

        if($data['task-found'])
        {
            $user_id = auth('sanctum')->id();

            $task = $data['task'];

            //start get rate of current user for this task
            if (auth('sanctum')->check())
            {
                if($task['is_active'] == 0 && $task['user_id'] != $user_id)
                {
                    return $this->sendError('failed cannot show task not found.', 'something went wrong');
                }

                if ($task['user']['status'] == 0 && $task['user_id'] != $user_id) {
                    return $this->sendError('failed cannot show task not found.', 'something went wrong');
                }

                //get CustomerReview average for task by task id
                $auth_customer_reviews = $this->customerReviewsService->getCustomerReviewByUserId($task['id'], 'task', $user_id);
                //Calculate rating of user  average, quality, time, accuracy, communication for task
                $this->customerReviewsRatingService->CalculateAuthRating($auth_customer_reviews);
            }
            else{
                if($task['is_active'] == 0)
                {
                    return $this->sendError('failed cannot show task not found.', 'something went wrong');
                }

                if ($task['user']['status'] == 0) {
                    return $this->sendError('failed cannot show task not found.', 'something went wrong');
                }
            }
            //end get rate of current user for this task

            //start get average and percentage rate of task
            //get CustomerReview average for task by task id
            $customer_reviews = $this->customerReviewsService->getCustomerReview($task['id'], 'task_average');

            //Calculate rating average, quality, time, accuracy, communication for task
            if(!$customer_reviews->isEmpty()){
                $this->customerReviewsRatingService->CalculateRating($customer_reviews[0]);
            }
            //end get average and percentage rate of task


            $customer_reviews = $this->customerReviewsService->getCustomerReview($task['id'], 'task');

            //start get default currency
            $configurations = Configuration::query()->where("key", "currency_symbol")->first();
            //end get default currency

            $task = TaskResource::make($task);

            $data = [
                'task' => $task,
                'customer_reviews' => CustomerReviewResource::collection($customer_reviews),
                'customer_reviews_rating' =>$this->customerReviewsRatingService->getCustomerReviewsRating(),
                'auth_rating' => $this->customerReviewsRatingService->getAuthRating(),
                'configurations' => ConfigurationResource::make($configurations),
            ];

            return $this->sendResponse($data, 'success');
        }
        else{
            return $this->sendError('failed',['Task element does not exist to show']);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTaskRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateTaskRequest $request,int $id): JsonResponse
    {
        $data = $this->task_service->show($id);

        if(!$data['task-found']){
            return $this->sendError('Task element does not exist to update');
        }

        $task =  $data['task'];

        if($task['user_id'] == auth('sanctum')->id())
        {
            $request['user_id'] = auth('sanctum')->id();
            $task = $this->task_service->update($request, $id);
            $task = TaskResource::make($task);
            return $this->sendResponse($task,'Task updated successfully');

        }
        else {
            return $this->sendError('failed cannot Update task of another user.', 'something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $data = $this->task_service->show($id);

        if(!$data['task-found']){
            return $this->sendError('Task element does not exist to delete');
        }

        $task =  $data['task'];

        if($task['user_id'] == auth('sanctum')->id())
        {
            $this->task_service->delete($id);

            $this->notificationService->removeNotifications($id, 'task');
            $this->customerReviewsService->removeReviews($id, 'task');

            return $this->sendResponse("delete task" , 'success');

        }
        else {
            return $this->sendError("Can't delete task for another user.", 'something went wrong');
        }
    }


    /**
     * add task image.
     * @param AddTaskImagesRequest $request
     * @return JsonResponse
     */
    public function addTaskImages(AddTaskImagesRequest $request): JsonResponse
    {
        $data = $this->task_service->show($request['task_id']);

        if($data['task-found'])
        {
            $task = $data['task'];

            $user_id = auth('sanctum')->id();
            if($task['user_id'] == $user_id){
                $this->task_service->createTaskImages($request);
                return $this->sendResponse([], 'image element add successfully');
            }
            else{
                return $this->sendError('failed cannot add task Images to another user.', 'something went wrong');
            }

        }
        else{
            return $this->sendError('Task element does not exist to add task Images');
        }
    }

    /**
     * delete task image.
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteTaskImages(Request $request): JsonResponse
    {
        $data = $this->task_service->show($request['task_id']);

        if($data['task-found'])
        {
            $task = $data['task'];

            $user_id = auth('sanctum')->id();
            if($task['user_id'] == $user_id){
                $image_found = $this->task_service->deleteTaskImages($request);
                if($image_found)
                {
                    return $this->sendResponse([], 'image element deleted successfully');
                }
                else{
                    return $this->sendError('image does not exist in this task to delete task Image', 'something went wrong');
                }
            }
            else{
                return $this->sendError('failed cannot delete task Images to another user.', 'something went wrong');
            }

        }

        return $this->sendError('Task element does not exist to delete task Images');
    }

    public function view(string $slug): JsonResponse
    {

        //set average and percentage value equal zero
        $this->customerReviewsRatingService->setRatingZero();

        //get task by slug (task name)
        $data = $this->task_service->getTaskBySlug($slug);

        if($data['task-found'])
        {
            $user_id = auth('sanctum')->id();

            $task = $data['task'];

            //start get rate of current user for this task
            if (auth('sanctum')->check())
            {
                if($task['is_active'] == 0 && $task['user_id'] != $user_id)
                {
                    return $this->sendError('failed cannot show task not found.', 'something went wrong');
                }

                if ($task['user']['status'] == 0 && $task['user_id'] != $user_id) {
                    return $this->sendError('failed cannot show task not found.', 'something went wrong');
                }

                //get CustomerReview average for task by task id
                $auth_customer_reviews = $this->customerReviewsService->getCustomerReviewByUserId($task['id'], 'task', $user_id);
                //Calculate rating of user  average, quality, time, accuracy, communication for task
                $this->customerReviewsRatingService->CalculateAuthRating($auth_customer_reviews);
            }
            else{
                if($task['is_active'] == 0)
                {
                    return $this->sendError('failed cannot show task not found.', 'something went wrong');
                }

                if ($task['user']['status'] == 0) {
                    return $this->sendError('failed cannot show task not found.', 'something went wrong');
                }
            }
            //end get rate of current user for this task

            //start get average and percentage rate of task
            //get CustomerReview average for task by task id
            $customer_reviews = $this->customerReviewsService->getCustomerReview($task['id'], 'task_average');

            //Calculate rating average, quality, time, accuracy, communication for task
            if(!$customer_reviews->isEmpty()){
                $this->customerReviewsRatingService->CalculateRating($customer_reviews[0]);
            }
            //end get average and percentage rate of task


            //get CustomerReview average for task by task id
            $customer_reviews = $this->customerReviewsService->getCustomerReview($task['id'], 'task');

            //start get default currency
            $configurations = Configuration::query()->where("key", "currency_symbol")->first();
            //end get default currency

            $task = TaskResource::make($task);

            $data = [
                'task' => $task,
                'customer_reviews' => CustomerReviewResource::collection($customer_reviews),
                'customer_reviews_rating' =>$this->customerReviewsRatingService->getCustomerReviewsRating(),
                'auth_rating' => $this->customerReviewsRatingService->getAuthRating(),
                'configurations' => ConfigurationResource::make($configurations),
            ];


            return $this->sendResponse($data, 'success');
        }
        else{
            return $this->sendError('task element does not exist to show');
        }
    }

    public function taskUpdateRate(Request $request): JsonResponse
    {
        $data = $this->task_service->show($request['review_id']);
        if($data['task-found']){
            //update customer review of task
            $flag = $this->customerReviewsService->UpdateCustomerReview($request, 'task');

            if($flag)
            {
                return $this->sendResponse([], 'task Customer Review element updated successfully');

            }
            else{
                return $this->sendError('task Customer Review element not updated');

            }
        }
        else{
            return $this->sendError('task element does not exist to rate');

        }

    }
}
