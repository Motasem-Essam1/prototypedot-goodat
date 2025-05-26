<?php

namespace App\Services;

use App\Models\SubCategory;
use App\Models\Images;
use App\Models\Task;
use App\Models\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use App\Utils\FileUtil;
use Illuminate\Http\Request;
use Exception;


class TaskService
{


    public function __construct(private readonly FileUtil $fileUtil,
                                private readonly SubCategoryService $subCategoryService)
    {

    }

    /**
     * add New Task
     * @param array $data
     * @return array
     */
    public function addNewTask(array $data): array
    {
        $response = [];

        $subCategory = SubCategory::query()->where('is_active', 1)->find($data['category_id']);

        if(!$subCategory)
        {
            $response['status'] = false;
            $response['massage'] = "category_id not found";
            return $response;
        }

        $parent_category_id = $subCategory['category_id'];

        $service = new Task();
        $service['user_id'] = $data['user_id'];
        $service['category_id'] = $data['category_id'];
        $service['parent_category_id'] = $parent_category_id;

        $text  = $data['task_name'];
        $service['task_name'] = $text;
        $service['task_slug'] = Str::slug($text);

        $text = $data['task_description'];
        $service['task_description'] = $text;
        $service['starting_price'] = $data['starting_price'];
        $service['ending_price'] = $data['ending_price'];
        $service['location_lng'] = $data['location_lng'];
        $service['location_lat'] = $data['location_lat'];
        $service->save();

        if (!empty($data['images'])){
            $images = $data['images'];
            $paths = $this->fileUtil->uploadMultiFiles($images, "task-" . strtotime(now()), 'task');
            $imageList = array();
            foreach ($paths as $path) {
                $image = new Images();
                $image['image_path'] = $path;
                $image->save();
                $imageList[] = $image['id'];
            }
            $service->images()->attach($imageList);
        }
        $response['tasks'] = $service;
        $response['status'] = true;
        $response['massage'] = "Task Added";
        $response['task_id'] = $service['id'];

        return $response;
    }


    /**
     * get all task
     *
     * @return Collection
     */
    public function index(): Collection
    {
        return Task::with('images')->get();
    }


    /**
     * get task by task name
     *
     * @param string $slug
     * @return array
     */
    // TODO: set function return type
    public function getTaskBySlug(string $slug): array
    {
        try{
        // TODO: validate if task not found
        $task = Task::query()->where('task_slug', $slug)->firstOrFail();
        $data['task-found'] = true;
        $data['task'] = $task;
        return $data;
        }
        catch(Exception)
        {
            $data['task-found'] = false;
            return $data;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id,
     * @return array
     */
    // TODO: set function return type
    public function show(int $id): array
    {
        try{
            $task = Task::query()->findOrFail($id);
            $data['task-found'] = true;
            $data['task'] = $task;
            return $data;
        }
        catch(Exception)
        {
            $data['task-found'] = false;
            return $data;
        }
    }

    // TODO: set function return type
    // TODO: change parameter name to be category_id

    /**
     * get task by Category
     * @param int $category_id,
     * @return Collection
     */
    public function getTasksByCategory(int $category_id): Collection
    {
        //get sub_category by Category_id
        $sub_category = $this->subCategoryService->getSubCategoryByCategoryId($category_id);

        //get task by sub_category ids
        return $this->getTasksBySubCategoryId($sub_category->toArray());
    }




    // TODO: set function return type
    // TODO: change parameter name to be sub_category_id
     /**
     * get task by SubCategory
     *
     * @param int $sub_category_id,
     * @return Collection
     */
    public function getTaskBySubCategory(int $sub_category_id) : Collection
    {
        return $this->getTasksBySubCategoryId([$sub_category_id]);
    }

    /**
     * get task by SubCategory Id
     *
     * @param array $SubCategory_ids
     * @return Collection
     */
    // TODO: make this function private
    // TODO: set function return type
    private function getTasksBySubCategoryId(array $SubCategory_ids): Collection
    {
        return Task::query()->whereIn("category_id", $SubCategory_ids)->where('is_active', true)
            ->with('CustomerReview')->get();
    }

     /**
     * get user tasks
     *
     * @param int $user_id,
     * @return Collection
     */
    public function getUserTasks(int $user_id): Collection
    {
        $user = User::query()->find($user_id);
        return $user['tasks'];
    }



    /**
     * delete Task by id
     * @param $request
     * @param int $id
     * @return array|Builder|Model|Collection|Builder[]
     */
    // TODO: set function return type
    public function update($request,int $id): array|Builder|Collection|Model
    {
        $task = Task::query()->find($id);
        $task['category_id'] = $request['category_id'];
        $task['parent_category_id'] = $request['parent_category_id'];
        $task['task_name'] = $request['task_name'];
        $task['task_slug'] = Str::slug($request['task_name']);
        $task['task_description'] = $request['task_description'];
        $task['starting_price'] = $request['starting_price'];
        $task['ending_price'] = $request['ending_price'];
        $task['location_lng'] = $request['location_lng'];
        $task['location_lat'] = $request['location_lat'];
        $task['user_id'] = $request['user_id'];
        $task->update();
        return  $task;
    }



    /**
     * delete Task by id
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $task = Task::query()->find($id);

        // return $task->images;
        foreach($task['images'] as $image)
        {
            $this->fileUtil->deleteFileByPath($image['image_path']);
            /** @var Task $task */
            $task->images()->detach($image['id']);
            $image->delete();
        }

        $task->delete();

    }


    /**
     * create Task Images
     *
     * @param $request
     * @return void
     */
    public function createTaskImages($request): void
    {
        $task = Task::query()->where('id', $request['task_id'])->with('images')->first();
        $paths = $this->fileUtil->uploadMultiFiles($request['images'], "task-".strtotime(now()), 'task');
        $imageList = array();
        foreach ($paths as $path){
            $image = new Images();
            $image['image_path'] = $path;
            $image->save();
            $imageList[] = $image['id'];
        }
        /** @var Task $task */
        $task->images()->attach($imageList);

    }

    /**
     * delete Task Images
     *
     * @param Request $request
     * @return bool
     */
    public function deleteTaskImages(Request $request): bool
    {
        try{
            $task = Task::query()->where('id', $request['task_id'])->first();
            $image = Images::query()->findOrFail($request['images_id']);
            /** @var Task $task */
            $check  = $task->images()->detach($request['images_id'], $request['task_id']);
            if($check)
            {
                $this->fileUtil->deleteFileByPath($image['image_path']);
                $image->delete();
                return true;
            }
            else{
                return false;
            }
        }
        catch(Exception)
        {
            return false;
        }
    }


     /**
     * delete Task Images By ImagesId
     *
     * @param int $id
     * @return bool
     */
    public function deleteTaskImagesByImagesId(int $id): bool
    {
        try{
            $image = Images::query()->findOrFail($id);
            $Images_task_id = Task::query()->join('images_task', 'tasks.id', '=', 'images_task.task_id')
            ->where('images_task.images_id', '=', $id)->select('tasks.id')
            ->first();
            $task = Task::query()->where('id', $Images_task_id['id'])->first();

            /** @var Task $task */
            $task->images()->detach($id);
            $this->fileUtil->deleteFileByPath($image['image_path']);
            $image->delete();
            return true;
        }
        catch(Exception)
        {
            return false;
        }
    }


}
