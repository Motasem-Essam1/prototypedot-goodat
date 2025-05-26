<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\BaseController;
use App\Http\Resources\TaskResource;

use App\Models\Configuration;
use App\Services\TaskService;
use App\Services\CustomerReviewsRatingService;
use App\Services\CustomerReviewsService;
use App\Services\CategoryService;
use App\Services\NotificationService;

use App\Http\Requests\Mobile\Services\UpdateTaskRequest;
use App\Http\Requests\Web\Services\TaskRequest;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class  TaskController extends BaseController
{


    public function __construct(private readonly TaskService $task_service,
                                private readonly CustomerReviewsRatingService $customerReviewsRatingService,
                                private readonly CustomerReviewsService $customerReviewsService,
                                private readonly CategoryService $categoryService,
                                private readonly NotificationService $notificationService) {

    }

    /**
     * view Task
     *
     * @param string $slug
     * @return View
     */
    public function view(string $slug): View
    {

        //set average and percentage value equal zero
        $this->customerReviewsRatingService->setRatingZero();

        //get task by slug (task name)
        $data = $this->task_service->getTaskBySlug($slug);

        if($data['task-found'])
        {
            $task = $data['task'];

            //start get rate of current user for this task
                if (Auth::check())
                {
                    if($task['is_active'] == 0 && $task['user_id'] != Auth::user()['id'])
                    {
                        abort(404);
                    }

                    if ($task['user']['status'] == 0 && $task['user_id'] != Auth::user()['id']) {
                        abort(404);
                    }

                    //get CustomerReview average for task by task id
                    $auth_customer_reviews = $this->customerReviewsService->getCustomerReviewByUserId($task['id'], 'task', Auth::user()['id']);
                    //Calculate rating of user  average, quality, time, accuracy, communication for task
                    $this->customerReviewsRatingService->CalculateAuthRating($auth_customer_reviews);
                }
                else{
                    if($task['is_active'] == 0)
                    {
                        abort(404);
                    }

                    if ($task['user']['status'] == 0) {
                        abort(404);
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

            $data = [
                'task' => $task,
                'customer_reviews' => $customer_reviews,
                'customer_reviews_rating' =>$this->customerReviewsRatingService->getCustomerReviewsRating(),
                'auth_rating' => $this->customerReviewsRatingService->getAuthRating(),
                'configurations' => $configurations,
            ];


            return view('task.view', $data);
        }
        else{
            abort(404);
        }
    }


    /**
     * create Task view
     *
     * @return View
     */
    //create task page
    public function create(): View
    {
        $categories = $this->categoryService->getCategories();
        $configurations = Configuration::query()->where("key", "currency_code")->first();

        $data = [
            'categories' => $categories,
            'configurations' => $configurations,
        ];

        return view('task.create', $data);
    }



    /**
     * create Task
     *
     * @param TaskRequest $request
     * @return RedirectResponse
     */
    //create task to database
    public function store(TaskRequest $request): RedirectResponse
    {
        $data = $request->only('images','category_id','task_name','task_description','starting_price','ending_price','location_lng','location_lat');
        $data['user_id'] = Auth::user()->getAuthIdentifier();

        $response = $this->task_service->addNewTask($data);

        if ($response['status']){
            Session::flash('title','Congratulations');
            Session::flash('massage', $response['massage']);
            Session::flash('successButtonText', 'View Task');
            Session::flash('successButtonUrl', 'task-view/' . $response['tasks']['task_slug']);
        }else{
            Session::flash('title','Sorry Can\'t add task');
            Session::flash('massage', $response['massage']);
        }
        return redirect()->route('status');
    }

    /**
     * get Task By Category
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function getTasksByCategory(Request $request, int $id): JsonResponse
    {
        TaskResource::$latitude = $request['latitude'];
        TaskResource::$longitude = $request['longitude'];

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

        return response()->json($sorted->values()->all());
    }


    /**
     * get Task By SubCategory
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function getTaskBySubCategory(Request  $request, int $id): JsonResponse
    {
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
     * create or Update Task rate (CustomerReview)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function taskUpdateRate(Request $request): JsonResponse
    {
        //update customer review of task
        $flag = $this->customerReviewsService->UpdateCustomerReview($request, 'task');

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
     * delete Task
     *
     * @param int $id
     * @return View
     */
    public function deleteTask(int $id): View
    {
        $data = $this->task_service->show($id);


        if($data['task-found']){
            $task = $data['task'];
            if($task['user_id'] == Auth::user()['id'])
            {
                $this->task_service->delete($id);
            }
        }
        $configurations = Configuration::query()->where("key", "currency_symbol")->first();


        $data = [
            'service' => Auth::user()['services'],
            'tasks' => Auth::user()['tasks'],
            'configurations' => $configurations,
        ];
        $this->notificationService->removeNotifications($id, 'task');
        $this->customerReviewsService->removeReviews($id, 'task');
        return view('account.service-task', $data);
    }

    /**
     * update Task view
     *
     * @param int $id
     * @return View
     */
    //update task page
    public function updateTask(int $id): View
    {
        $data = $this->task_service->show($id);

        if($data['task-found']){
            $task = $data['task'];
            $categories = $this->categoryService->getCategories();
            if($task['user_id'] == Auth::user()['id'])
            {
                $configurations = Configuration::query()->where("key", "currency_code")->first();

                $data = [
                    'categories' => $categories,
                    'task' =>  $task,
                    'configurations' => $configurations,
                ];
                return view('task.update',  $data);
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
     * update Task
     *
     * @param UpdateTaskRequest $request
     * @return View
     */
    //update task to database
    public function updateTaskData(UpdateTaskRequest $request): View
    {
        $request['user_id'] = Auth::user()['id'];

        $request['task_id'] = $request['id'];

        if (!empty($request['images'])){
            $this->task_service->createTaskImages($request);
        }

        $this->task_service->update($request, $request['id']);

        $configurations = Configuration::query()->where("key", "currency_symbol")->first();


        $data = [
            'service' => Auth::user()['services'],
            'tasks' => Auth::user()['tasks'],
            'configurations' => $configurations,

        ];
        return view('account.service-task', $data);
    }

    /**
     * delete Task Images
     *
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
}
