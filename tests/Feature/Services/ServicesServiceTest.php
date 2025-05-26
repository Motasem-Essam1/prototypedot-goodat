<?php

namespace Services;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

use App\Services\ServicesService;
use App\Services\UserService;

use App\Services\SubCategoryService;
use App\Services\CustomerReviewsService;

use App\Utils\FileUtil;
use App\Utils\SmsUtil;

use App\Models\Services;

class ServicesServiceTest extends TestCase
{

    use DatabaseTransactions;

    //variable declarations
    private ServicesService $services_service;

    private array $user_created;
    private array $service_created;

    public function setUp(): void
    {
        //variable definitions
        parent::setUp();
        $fileUtil = new FileUtil;
        $subCategoryService = new SubCategoryService;
        $customerReviewsService = new CustomerReviewsService;
        $smsUtil =  new SmsUtil;

        $user_Service =  new UserService($smsUtil, $fileUtil);
        $this->services_service = new ServicesService($fileUtil, $subCategoryService, $customerReviewsService);

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

        //create service
        $data['user_id'] = $this->user_created['data']['id'];
        $data['category_id'] = 1;
        $data['service_name'] = "name service name";
        $data['service_description'] = "service description";
        $data['starting_price'] = 300;
        $data['ending_price'] = 500;
        $data['location_lng'] = "1236548745632115498";
        $data['location_lat'] =  "1236548745632115498";
        $this->service_created = $this->services_service->addNewService($data);

    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_addNewService()
    {
        $this->assertDatabaseHas('services', [
            'id' => $this->service_created['service_id'],
        ]);
    }

    public function test_index()
    {
        //create another service
        $data['user_id'] = $this->user_created['data']['id'];
        $data['category_id'] = 1;
        $data['service_name'] = "second service name";
        $data['service_description'] = "service description";
        $data['starting_price'] = 300;
        $data['ending_price'] = 500;
        $data['location_lng'] = "1236548745632115498";
        $data['location_lat'] =  "1236548745632115498";
        $this->service_created = $this->services_service->addNewService($data);

        //get services
        $response = $this->services_service->index();

        if(count($response) > 1)
        {
            $this->assertTrue(true);
        }
        else{
            $this->fail();
        }
    }

    public function test_getServiceBySlug()
    {
        $response = $this->services_service->getServiceBySlug(Str::slug('name service name'));

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
        $service = $this->services_service->show($this->service_created['service_id']);

        if($service['service-found'])
        {
            $this->assertEquals('name service name', $service['service']['service_name']);
        }
        else{
            $this->fail();
        }
    }

    public function test_getServicesByCategory()
    {
        $service = $this->services_service->getServicesByCategory(1);

        if(count($service) > 0)
        {
            $this->assertTrue(true);
        }
        else{
            $this->fail();
        }
    }

    public function test_getServiceBySubCategory()
    {
        $service = $this->services_service->getServiceBySubCategory(1);

        if(count($service) > 0)
        {
            $this->assertTrue(true);
        }
        else{
            $this->fail();
        }
    }

    public function test_getUserServices()
    {
        //get User services
        $response = $this->services_service->getUserServices($this->user_created['data']['id']);

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
        //update service
        $service['category_id'] = 2;
        $service['parent_category_id'] = 3;
        $service['service_name'] ="new name";
        $service['service_slug'] = Str::slug($service['service_name']);
        $service['service_description'] = "new description";
        $service['starting_price'] = 300;
        $service['ending_price'] =900;
        $service['location_lng'] = "3511";
        $service['location_lat'] = "3513";

        $this->services_service->update($service, $this->service_created['service_id']);

        $service_get = Services::query()->where('id', $this->service_created['service_id'])->first();

        $this->assertEquals($service_get['service_name'], $service['service_name']);

    }

    public function test_delete()
    {
       //delete service
        $this->services_service->delete($this->service_created['service_id']);

        $task = $this->services_service->show($this->service_created['service_id']);

        if($task['service-found'])
        {
            $this->fail();
        }
        else{
            $this->assertTrue(true);
        }

    }
}
