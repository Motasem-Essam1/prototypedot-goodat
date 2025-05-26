<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\Categories\AddCategoryRequest;
use App\Http\Requests\Dashboard\Categories\UpdateCategoryRequest;
use App\Http\Requests\Dashboard\Categories\UploadImage;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Utils\FileUtil;
use Illuminate\Support\Str;

class CategoryController extends BaseController
{
    private $categoryModel;
    private $fileUtil;
    public function __construct(Category $categoryModel, FileUtil $fileUtil)
    {
        $this->categoryModel = $categoryModel;
        $this->fileUtil = $fileUtil;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $categories = CategoryResource::collection(Category::with('subCategories')->with('subCategories.parentCategory')->get());
        return $this->sendResponse($categories,'Category data fetched successfully');
    }

    public function show($id){
        $category = Category::where('id', $id)->with('subCategories')->with('subCategories.parentCategory')->first();
        if($category){
            $category = CategoryResource::make($category);
            return $this->sendResponse($category,'Category data fetched successfully to show');
        }
        return $this->sendError('faild',['category dosn\'t exist to show']);
    }

    public function store(AddCategoryRequest $request){
        $data = $request->only('category_name','is_active');
        $data["category_slug"] = Str::of($data['category_name'])->slug('-');
        $category = $this->categoryModel->create($data);
        if($category){
            $category = CategoryResource::make($category);
            return $this->sendResponse($category, 'Category created success');
        }else{
            return $this->sendError('Category not created something went wrong', []);
        }
    }

    public function uploadImage(UploadImage $request)
    {
        $name = $request->category_id . "-" . time();
        $path = "category";
        $category = Category::where('id', $request->category_id)->with('subCategories')->with('subCategories.parentCategory')->first();
        if ($category->category_image) {
            $this->fileUtil->deleteFileByPath($category->category_image);
        }
        if (isset($request->category_image)) {
            $image_path = $this->fileUtil->uploadFile($request->category_image, $name, $path);
            $category->category_image = $image_path;
        }
        $category->save();

        if ($image_path) {
            $category = CategoryResource::make($category);
            return $this->sendResponse($category, 'Category uploaded successfully');
        } else {
            return $this->sendError('failed.', ['Category not upload something went wrong']);
        }
    }

    public function update(UpdateCategoryRequest $request, $id){
        $category =  Category::where('id' , $id)->first();
        if($category){
            $newCategory = $request->safe()->only('category_name', 'is_active');
            $newCategory["category_slug"] = Str::of($request->category_name)->slug('-');
            $category->update($newCategory);
            $category = CategoryResource::make($category);
            return $this->sendResponse($category,'category updated successfully');
        }else{
            return $this->sendError('category element dosn\'t exist', []);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $category = Category::find($id);
        if(!$category){
            return $this->sendError('this element dosn\'t exist', []);
        }
        if(!$category->subCategories()->exists()){
            $category->delete();
            return $this->sendResponse([],'category deleted successfully');
        }else{
            return $this->sendError('category element can\'t be deleted', []);
        }
    }
}
