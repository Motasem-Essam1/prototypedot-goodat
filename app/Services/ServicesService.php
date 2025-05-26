<?php

namespace App\Services;

use App\Models\Images;
use App\Models\Services;
use App\Models\SubCategory;
use App\Models\User;
use App\Utils\FileUtil;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class ServicesService
{
    public function __construct(private readonly FileUtil $fileUtil,
                                private readonly SubCategoryService $subCategoryService)
    {

    }

    /**
     * add New Service
     * @param array $data
     * @return array
     */
    public function addNewService(array $data): array
    {
        $response = [];
        $user = User::query()->find($data['user_id']);
        $package = $user['user_data']['package'];
        $services = $user['services'];

        if (!isset($package)){
            $response['status'] = false;
            $response['massage'] = "Please subscribe to any package";
            return $response;
        }

        if ($package->number_of_services <= $services->count()){
            $response['status'] = false;
            $response['massage'] = "Maximum number of services please upgrade your package";
            return $response;
        }

        if (!empty($data['images']) && $package->number_of_images_per_service < count($data['images'])-1){
            $response['status'] = false;
            $response['massage'] = "Maximum number of images";
            return $response;
        }

        $subCategory = SubCategory::query()->where('is_active', 1)->find($data['category_id']);

        if(!$subCategory)
        {
            $response['status'] = false;
            $response['massage'] = "category_id not found";
            return $response;
        }

        $parent_category_id = $subCategory['category_id'];


        $service = new Services();
        $service['user_id'] = $data['user_id'];
        $service['category_id'] = $data['category_id'];
        $service['parent_category_id'] = $parent_category_id;

        $text  = $data['service_name'];
        $service['service_name'] = $text;
        $service['service_slug'] = Str::slug($text);

        $text  = $data['service_description'];
        $service['service_description'] = $text;
        $service['starting_price'] = $data['starting_price'];
        $service['ending_price'] = $data['ending_price'];
        $service['location_lng'] = $data['location_lng'];
        $service['location_lat'] = $data['location_lat'];
        $service->save();

        if (!empty($data['images'])){
            $images = $data['images'];
            $paths = $this->fileUtil->uploadMultiFiles($images, "service-".strtotime(now()), 'service');
            $imageList = array();
            foreach ($paths as $path){
                $image = new Images();
                $image['image_path'] = $path;
                $image->save();
                $imageList[] = $image['id'];
            }
            $service->images()->attach($imageList);
        }
        $response['services'] = $service;
        $response['status'] = true;
        $response['massage'] = "Service Added";
        $response['service_id'] = $service['id'];

        return $response;
    }

    /**
    * get all Services
    *
    * @return Collection
    */
    public function index(): Collection
    {
        return Services::with('images')->get();
    }

    /**
     * get service by service name
     *
     * @param string $slug
     * @return array
     */
    public function getServiceBySlug(string $slug): array
    {
        try {
            $services = Services::query()->where('service_slug', $slug)->firstOrFail();
            $data['service-found'] = true;
            $data['service'] = $services;
            return $data;
        }
        catch(Exception)
        {
            $data['service-found'] = false;
            return $data;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id,
     * @return array
     */
    public function show(int $id): array
    {
        try{
            $service = Services::query()->findOrFail($id);
            $data['service-found'] = true;
            $data['service'] = $service;
            return $data;
        }
        catch(Exception)
        {
            $data['service-found'] = false;
            return $data;
        }
    }

    /**
     * get Service by Category
     * @param int $category_id,
     * @return Collection
     */
    public function getServicesByCategory(int $category_id): Collection
    {
        //get sub_category by Category_id
        $sub_category = $this->subCategoryService->getSubCategoryByCategoryId($category_id);

        //get service by sub_category ids
        return $this->getServicesBySubCategoryId($sub_category->toArray());
    }

     /**
     * get Service by SubCategory
     *
     * @param int $sub_category_id,
     * @return Collection
     */
    public function getServiceBySubCategory(int $sub_category_id): Collection
    {
        return $this->getServicesBySubCategoryId([$sub_category_id]);
    }

    /**
     * get service by SubCategory_Id
     *
     * @param array $SubCategory_ids
     * @return Collection
     */
    public function getServicesBySubCategoryId(array $SubCategory_ids): Collection
    {
        return Services::query()->whereIn("category_id", $SubCategory_ids)->where('is_active', true)
            ->with('CustomerReview')->get();
    }

    /**
     * get users Services
     *
     * @param int $user_id,
     * @return Collection
     */
    public function getUserServices(int $user_id): Collection
    {
        $user = User::query()->find($user_id);
        return $user['services'];
    }

    /**
     * delete Task by id
     * @param $request
     * @param int $id
     * @return array|Builder|Model|Collection|Builder[]
     */
    public function update($request,int $id): array|Builder|Collection|Model
    {

        $service = Services::query()->find($id);
        $service['category_id'] = $request['category_id'];
        $service['parent_category_id'] = $request['parent_category_id'];
        $service['service_name'] = $request['service_name'];
        $service['service_slug'] = Str::slug($request['service_name']);
        $service['service_description'] = $request['service_description'];
        $service['starting_price'] = $request['starting_price'];
        $service['ending_price'] = $request['ending_price'];
        $service['location_lng'] = $request['location_lng'];
        $service['location_lat'] = $request['location_lat'];
        $service['user_id'] = $request['user_id'];
        $service->update();
        return $service;
    }

    /**
     * delete service by id
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $service = Services::query()->find($id);

        foreach($service['images'] as $image)
        {
            $this->fileUtil->deleteFileByPath($image['image_path']);
            /** @var Services $service*/
            $service->images()->detach($image['id']);
            $image->delete();
        }

        $service->delete();

    }


    /**
     * create Service Images
     *
     * @param $request
     * @return void
     */
    public function createServiceImages($request): void
    {
        $service = Services::query()->where('id', $request['service_id'])->with('images')->first();
        $paths = $this->fileUtil->uploadMultiFiles($request['images'], "service-".strtotime(now()), 'service');
        $imageList = array();
        foreach ($paths as $path){
            $image = new Images();
            $image['image_path'] = $path;
            $image->save();
            $imageList[] = $image['id'];
        }
        /** @var Services $service*/
        $service->images()->attach($imageList);

    }

    /**
     * delete Task Images
     *
     * @param Request $request
     * @return bool
     */
    public function deleteServiceImages(Request $request): bool
    {
        try{
            $service = Services::query()->where('id', $request['service_id'])->first();
            $image = Images::query()->findOrFail($request['images_id']);
            /** @var Services $service*/
            $check = $service->images()->detach($request['images_id'], $request['service_id']);
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
    public function deleteServiceImagesByImagesId(int $id): bool
    {
        try{
            $image = Images::query()->findOrFail($id);
            $Images_service_id = Services::query()->join('images_services', 'services.id', '=', 'images_services.services_id')
            ->where('images_services.images_id', '=', $id)->select('services.id')
            ->first();
            $Service = Services::query()->where('id', $Images_service_id['id'])->first();

            /** @var Services $Service */
            $Service->images()->detach($id);
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
