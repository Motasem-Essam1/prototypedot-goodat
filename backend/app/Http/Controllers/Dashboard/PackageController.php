<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\Packages\AddPackageRequest;
use App\Http\Requests\Dashboard\Packages\UpdatePackageRequest;
use App\Http\Requests\Dashboard\Packages\UploadImage;
use App\Http\Resources\PackageResource;
use App\Http\Resources\UserResource;
use App\Services\SubscriptionService;
use App\Services\PackageDurationService;
use App\Models\Package;
use App\Models\User;
use App\Models\UserData;
use App\Utils\FileUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PackageController extends BaseController
{
    private $packageModel;
    private $fileUtil;
    private $subscriptionService;
    private $packageDurationService;
    public function __construct(
        Package $packageModel,
        FileUtil $fileUtil,
        SubscriptionService $subscriptionService,
        PackageDurationService $packageDurationService
    )
    {
        $this->packageModel = $packageModel;
        $this->fileUtil = $fileUtil;
        $this->subscriptionService = $subscriptionService;
        $this->packageDurationService = $packageDurationService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = PackageResource::collection(Package::get());
        return $this->sendResponse($packages,'data fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddPackageRequest $request)
    {
        $data = $request->only('package_name','slug' ,'number_of_services', 'number_of_images_per_service', 'search_package_priority', 'tasks_notification_criteria', 'has_price', 'has_condition', 'is_public', 'per_month', 'price', 'description', 'color', 'phone_status');
        $package = $this->packageModel->create($data);
        if (isset($request->image)){
            $name = $package->id . "-" . time();
            $path = "packages";
            $image_path = $this->fileUtil->uploadFile($request->image, $name, $path);
            $package->image = $image_path;
            $package->save();
        }
        if($package){
            $package = PackageResource::make($package);
            return $this->sendResponse($package, 'success');
        }else{
            return $this->sendError('something went wrong', []);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $package = Package::find($id);

        if(empty($package['image']))
        {
            Package::where('id', $id)->update(array('image' => 'assets/img/subscriptions/ninja.svg'));
        }

        if($package){
            $package = PackageResource::make($package);
            return $this->sendResponse($package,'package data fetched successfully');
        }
        return $this->sendError('faild',['package dosn\'t exist']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePackageRequest $request, $id)
    {
        $package =  Package::find($id);
        if($package){
            $data = $request->only('package_name', 'slug','number_of_services', 'number_of_images_per_service', 'search_package_priority', 'tasks_notification_criteria', 'has_price', 'has_condition', 'is_public', 'per_month', 'price', 'description', 'color', 'phone_status');
//            $data["slug"] = Str::of($request->package_name)->slug('-');
            $package->update($data);
            $package = PackageResource::make($package);
            return $this->sendResponse($package,'package element updated successfully');
        }else{
            return $this->sendError('package element dosn\'t exist', []);
        }
    }

    public function isPublic(Request $request){
        $request->validate([
            'id' => 'required|exists:packages,id',
        ]);
        $data = $request->only('id');
        $package = Package::find($request->id);
        if($package->is_public == 1){
            $data['is_public'] = 0;
        }else{
            $data['is_public'] = 1;
        }
        $package->update($data);
        $user = PackageResource::make($package);
        return $this->sendResponse( $user, $package->is_public == 1 ? 'Success change package status to [ON]' : 'Success change package status to [OFF]');
    }

    public function assignPackageToUser(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:packages,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->messages(), 'something went wrong');
        }

        $data = $request->only('user_id', 'package_id');
        $userData = UserData::where('user_id', $data['user_id'])->first();
        $userData->package_id = $data['package_id'];
        $userData->user_type = "Service Provider";
        if($userData->save()){
            $user = UserResource::make(User::with('user_data')->first());
            $this->packageDurationService->upgradePackageDuration($data['user_id'], $data['package_id'], $this->packageDurationService->package_months);
            return $this->sendResponse( $userData, 'package update success');
        }else{
            return $this->sendError('something went wrong', []);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function destroy($id)
//    {
//        $package = Package::find($id);
//        if(!$package){
//            return $this->sendError('this element dosn\'t exist', []);
//        }
//        if($package->delete()){
//            return $this->sendResponse([],'element deleted successfully');
//        }else{
//            return $this->sendError('this element can\'t be deleted', []);
//        }
//    }

    public function uploadImage(UploadImage $request)
    {
        $name = $request->package_id . "-" . time();
        $path = "packages";
        $package = Package::find($request->package_id);
        if ($package->image) {
            $this->fileUtil->deleteFileByPath($package->image);
        }
        if (isset($request->image)) {
            $image_path = $this->fileUtil->uploadFile($request->image, $name, $path);
            $package->image = $image_path;
        }
        $package->save();

        if ($image_path) {
            $package = PackageResource::make($package);
            return $this->sendResponse($package, 'uploaded successfully');
        } else {
            return $this->sendError('failed.', ['something went wrong']);
        }
    }
}
