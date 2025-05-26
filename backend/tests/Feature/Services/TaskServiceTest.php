<?php

namespace Services;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

use App\Services\TaskService;
use App\Services\UserService;

use App\Services\SubCategoryService;
use App\Services\CustomerReviewsService;

use App\Utils\FileUtil;
use App\Utils\SmsUtil;

use App\Models\Task;


class TaskServiceTest extends TestCase
{
    use DatabaseTransactions;

    //variable declarations
    private TaskService $task_service;

    private array $user_created;
    private array $task_created;


    /**
     * This method is called before each test.
     */
    public function setUp(): void
    {
        //variable definitions
        parent::setUp();
        $fileUtil = new FileUtil;
        $subCategoryService = new SubCategoryService;
        $customerReviewsService = new CustomerReviewsService;
        $smsUtil =  new SmsUtil;

        $user_Service =  new UserService($smsUtil, $fileUtil);
        $this->task_service = new TaskService($fileUtil, $subCategoryService, $customerReviewsService);

        //create user
        $user['name'] = "Tom";
        $user['email'] = "Tom@gmail.com";
        $user['phone_number'] = "12131415";
        $user['phone_code'] = "20";
        $user['password'] = "Tom123";
        $user['phoned_Signed'] = 1;
        $user['is_provider'] = 1;
        $user['invitation_code'] = "";
        $user['provider'] = "web";
        $this->user_created = $user_Service->createUser($user);

        //user is logged in
        $this->actingAs($this->user_created['data']);

        //create task
        $data['user_id'] = $this->user_created['data']['id'];
        $data['category_id'] = 1;
        $data['task_name'] = "name task name";
        $data['task_description'] = "task description";
        $data['starting_price'] = 100;
        $data['ending_price'] = 200;
        $data['location_lng'] = "1236548745632115498";
        $data['location_lat'] =  "1236548745632115498";
        $this->task_created = $this->task_service->addNewTask($data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_addNewTask()
    {
        $this->assertDatabaseHas('tasks', [
            'id' => $this->task_created['task_id'],
        ]);
    }

    public function test_index()
    {
        //create another task
        $data['user_id'] = $this->user_created['data']['id'];
        $data['category_id'] = 1;
        $data['task_name'] = "name task name2";
        $data['task_description'] = "service description";
        $data['starting_price'] = 100;
        $data['ending_price'] = 200;
        $data['location_lng'] = "1236548745632115498";
        $data['location_lat'] =  "1236548745632115498";
        $this->task_created = $this->task_service->addNewTask($data);

        //get tasks
        $response = $this->task_service->index();

        if(count($response) > 1)
        {
            $this->assertTrue(true);
        }
        else{
            $this->fail();
        }
    }

    public function test_getTaskBySlug()
    {
        $response = $this->task_service->getTaskBySlug(Str::slug('name task name'));

        if($response == null)
        {
            $this->fail();
        }
        else{
            $this->assertTrue(true);
        }
    }

    public function test_show()
    {
        $task = $this->task_service->show($this->task_created['task_id']);

        if($task['task-found'])
        {
            $this->assertEquals('name task name', $task['task']['task_name']);
        }
        else{
            $this->fail();
        }
    }

    public function test_getTasksByCategory()
    {
        $task = $this->task_service->getTasksByCategory(1);

        if(count($task) > 0)
        {
            $this->assertTrue(true);
        }
        else{
            $this->fail();
        }
    }

    public function test_getTaskBySubCategory()
    {
        $task = $this->task_service->getTaskBySubCategory(1);

        if(count($task) > 0)
        {
            $this->assertTrue(true);
        }
        else{
            $this->fail();
        }
    }

    public function test_getUserTasks()
    {
        //get User services
        $response = $this->task_service->getUserTasks($this->user_created['data']['id']);

        //dd($response)
        if(count($response) > 0)
        {
            $this->assertTrue(true);
        }
        else{
            $this->fail();
        }
    }

    public function test_update()
    {
        //update task
        $task['category_id'] = 2;
        $task['parent_category_id'] = 3;
        $task['task_name'] ="new name";
        $task['task_slug'] = Str::slug($task['task_name']);
        $task['task_description'] = "new description";
        $task['starting_price'] = 300;
        $task['ending_price'] =900;
        $task['location_lng'] = "3511";
        $task['location_lat'] = "3513";

        $this->task_service->update($task, $this->task_created['task_id']);

        $task_get = Task::query()->where('id', $this->task_created['task_id'])->first();

        $this->assertEquals($task_get['task_name'], $task['task_name']);
    }

    public function test_delete()
    {
        //delete task
        $this->task_service->delete($this->task_created['task_id']);

        $task = $this->task_service->show($this->task_created['task_id']);

        if($task['task-found'])
        {
            $this->fail();
        }
        else{
            $this->assertTrue(true);
        }
    }
}
