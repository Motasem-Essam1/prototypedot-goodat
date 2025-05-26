<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\SubCategories\{AddSubCategoryRequest,
    DeleteSubCategoryRequest,
    UpdateSubCategoryRequest,
    UploadImage};
use App\Http\Resources\SubCategoryResource;
use App\Models\SubCategory;
use App\Utils\FileUtil;
use Illuminate\Support\Str;

class SubCategoryController extends BaseController
{
    private $subCategoryModel;
    private $fileUtil;
    public function __construct(SubCategory $subCategoryModel, FileUtil $fileUtil)
    {
        $this->subCategoryModel = $subCategoryModel;
        $this->fileUtil = $fileUtil;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $subCategories = SubCategoryResource::collection(SubCategory::with('parentCategory')->get());
        return $this->sendResponse($subCategories,'SubCategory data fetched successfully');
    }
    public function show($id){
        $sub_category = SubCategory::where('id', $id)->with('parentCategory')->first();

        if($sub_category){
            $sub_category = SubCategoryResource::make($sub_category);
            return $this->sendResponse($sub_category,'data fetched successfully');
        }
        return $this->sendError('faild',['sub category dosn\'t exist']);
    }

    public function store(AddSubCategoryRequest $request){
        $data = $request->only('category_id','sub_category_name','is_active');
        $data["sub_category_slug"] = Str::of($data['sub_category_name'])->slug('-');
        $subCategory = $this->subCategoryModel->create($data);
        if($subCategory){
            $subCategory = SubCategory::where('id', $subCategory->id)->with('parentCategory')->first();
            $subCategory = SubCategoryResource::make($subCategory);
            return $this->sendResponse( $subCategory, 'SubCategory create success');
        }else{
            return $this->sendError('SubCategory not created something went wrong', []);
        }
    }

    public function update(UpdateSubCategoryRequest $request, $id){
        $sub_category =  SubCategory::where('id' , $id)->first();
        if($sub_category){
            $newSubCategory = $request->safe()->only('category_id','sub_category_name', 'is_active');
            $newSubCategory["sub_category_slug"] = Str::of($request->sub_category_name)->slug('-');
            $sub_category->update($newSubCategory);
            $sub_category = SubCategory::where('id', $sub_category->id)->with('parentCategory')->first();
            $sub_category = SubCategoryResource::make($sub_category);
            return $this->sendResponse($sub_category,'sub category updated successfully');
        }else{
            return $this->sendError('SubCategory element dosn\'t exist to update', []);
        }

    }

    public function uploadImage(UploadImage $request)
    {
        $name = $request->sub_category_id . "-" . time();
        $path = "sub_categories";
        $subCategory =  SubCategory::where('id' , $request->sub_category_id)->with('parentCategory')->first();
        if($subCategory->sub_category_image){
            $this->fileUtil->deleteFileByPath($subCategory->sub_category_image);
        }
        if (isset($request->sub_category_image)){
            $image_path = $this->fileUtil->uploadFile($request->sub_category_image, $name, $path);
            $subCategory->sub_category_image = $image_path;
        }
        $subCategory->save();

        if ($image_path){
            $subCategory = SubCategoryResource::make($subCategory);
            return $this->sendResponse($subCategory, 'SubCategory uploaded successfully');
        }else{
            return $this->sendError('failed.', ['SubCategory not uploaded something went wrong']);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $subCategory = SubCategory::find($id);
        if(!$subCategory){
            return $this->sendError('SubCategory element dosn\'t exist to delete', []);
        }
        $subCategory->delete();
        return $this->sendResponse([],'sub category deleted successfully');
    }
}
