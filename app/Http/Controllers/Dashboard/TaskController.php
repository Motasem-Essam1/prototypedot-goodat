<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController;

use App\Http\Requests\Mobile\Tasks\AddTaskImagesRequest;
use App\Http\Requests\Dashboard\Services\AddTaskRequest;
use App\Http\Requests\Dashboard\Services\UpdateTaskRequest;

use App\Http\Resources\UploadImagesTaskResource;
use App\Services\CustomerReviewsRatingService;
use App\Services\CustomerReviewsService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

use App\Http\Resources\TaskResource;
use App\Models\Task;

use App\Services\TaskService;
use Illuminate\Http\JsonResponse;



class TaskController extends BaseController
{
    public function __construct(private readonly TaskService $task_service,
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
        $tasks = $this->task_service->index();
        $tasks = TaskResource::collection($tasks);
        return $this->sendResponse($tasks,'Task data fetched successfully');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddTaskRequest $request
     * @return JsonResponse
     */
    public function store(AddTaskRequest $request): JsonResponse
    {
        $data = $request->only('images', 'user_id','category_id','task_name','task_description','starting_price','ending_price','location_lng','location_lat');
        $response = $this->task_service->addNewTask($data);
        if($response['status']){
            $task = TaskResource::make(Task::query()->where('id', $response['task_id'])->first());
            return $this->sendResponse($task, 'Task create success');
        }else{
            return $this->sendError('Task not create something went wrong', [$response['massage']]);
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
        $data = $this->task_service->show($id);

        if($data['task-found'])
        {
            $task = $data['task'];
            $task = new TaskResource($task);
            //set average and percentage value equal zero
            $this->customerReviewsRatingService->setRatingZero();

            //start get average and percentage rate of task
                //get CustomerReview average for task by task id
                $customer_reviews = $this->customerReviewsService->getCustomerReview($task['id'], 'task_average');

                //Calculate rating average, quality, time, accuracy, communication for task
                if(!$customer_reviews->isEmpty()){
                    $this->customerReviewsRatingService->CalculateRating($customer_reviews[0]);
                }
            //end get average and percentage rate of task

            $customer_reviews = $this->customerReviewsService->getCustomerReview($task['id'], 'task');


            $data = [
                'task' => $task,
                'customer_reviews' => $customer_reviews,
                'customer_reviews_rating' =>$this->customerReviewsRatingService->getCustomerReviewsRating(),
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

        $task = $this->task_service->update($request, $id);
        $task = TaskResource::make($task);

        return $this->sendResponse($task,'Task updated successfully');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $data = $this->task_service->show($id);

        if(!$data['task-found']){
            return $this->sendError('Task element does not exist to delete');
        }

        $this->task_service->delete($id);

        $this->notificationService->removeNotifications($id, 'task');
        $this->customerReviewsService->removeReviews($id, 'task');

        return $this->sendResponse([],'Task deleted successfully');
    }

    /**
     * add task image.
     *
     * @param AddTaskImagesRequest $request
     * @return JsonResponse
     */
    public function addTaskImages(AddTaskImagesRequest $request): JsonResponse
    {
        $this->task_service->createTaskImages($request);
        $task = UploadImagesTaskResource::make(Task::query()->where('id', $request['task_id'])->with('images')->first());
        return $this->sendResponse($task, 'Task success to addTaskImages');
    }

    /**
     * delete task image.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deleteImage(int $id): JsonResponse
    {
       $check = $this->task_service->deleteTaskImagesByImagesId($id);

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
            'id' => 'required|exists:tasks,id,deleted_at,NULL',
            'is_active' => 'required'
        ]);

        $data = Task::where('id', $request->id)->first();
        $data->is_active = $request->is_active == 1 ? 1 : 0;
        $data->save();

        if($data) {
            $taskData = TaskResource::make($data);
            return $this->sendResponse($taskData, $data->is_active ? 'Task is active now (ON)' : 'Task is inactive now (OFF)');
        } else {
            return $this->sendError('Task active fail something went wrong', []);
        }
    }
}
