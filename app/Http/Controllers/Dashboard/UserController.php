<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\Users\UpdateUserRequest;
use App\Http\Requests\Mobile\Auth\RegisterRequestApi;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserData;
use App\Models\CustomerReview;
use App\Models\UserSubCategories;
use App\Services\CustomerReviewsService;
use App\Services\UserService;
use App\Services\NotificationService;
use App\Utils\FileUtil;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;


class UserController extends BaseController
{
    private $userModel;
    private $fileUtil;
    private $userService;
    private $notification_service;
    public function __construct(User $userModel,
                                FileUtil $fileUtil,
                                UserService $userService,
                                NotificationService $notification_service,
                                private readonly CustomerReviewsService $customerReviewsService)
    {
        $this->userModel = $userModel;
        $this->fileUtil = $fileUtil;
        $this->userService = $userService;
        $this->notification_service = $notification_service;
    }
    /**
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $users = UserResource::collection(User::with('user_data')->get());
        return $this->sendResponse($users, "User data fetched successfully");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterRequestApi $request)
    {
        $data = $request->only(['name', 'email', 'phone_number', 'phone_code', 'password', 'invitation_code', 'is_provider', 'avatar', 'sub_categories']);
        $data['provider'] = 'admin';
        $data['phoned_Signed'] = true;
        $user = $this->userService->createUser($data);
        if (isset($request->avatar)){
            $name = $user['data']->id . "-" . time();
            $path = "user-profile";
            $user_data = UserData::where('user_id', $user['data']->id)->first();
            $image_path = $this->fileUtil->uploadFile($request->avatar, $name, $path);
            $user_data->avatar = $image_path;
            $user_data->save();
        }
        event(new Registered($user['data']));
        $result = UserResource::make(User::where('id' , $user['data']->id)->with('user_data')->first());

        //upload user sub_categories
        if(!empty($data['sub_categories']))
        {
            foreach($data['sub_categories'] as $item){
                UserSubCategories::updateOrInsert(
                    ['user_category_id' => $item, 'user_id' => $user['data']->id],
                );
            }
        }

        if ($user['sms_status']){
            return $this->sendResponse($result, 'User confirmation code sent to your number');
        }else{
            return $this->sendResponse($result, 'user created successfully');
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
        $user = User::where('id', $id)->with('user_data')->first();

        if(empty($user->user_data['avatar']))
        {
            UserData::where('user_id', $id)->update(array('avatar' => 'assets/img/subscriptions/ninja.svg'));
        }

        if($user){
            $user = UserResource::make($user);

            $user_sub_categories = UserSubCategories::with('SubCategory')->where('user_id', $id)->get()->toArray();
            $data = [
                'user' => $user,
                'user_sub_categories' => $user_sub_categories
              ];

            return $this->sendResponse($data,'User data fetched successfully to show');
        }
        return $this->sendError('faild',['User element dosn\'t exist to show']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user){
        $data = [
            'id' => $user->id,
            'full_name' => $request->fullname ,
            'email' => $request->email ,
            'country_code' => $request->phone_code ,
            'phone_number' => $request->phone,
            'user_sub_categories' => $request->sub_categories
        ];
        $uploadedData = $this->userService->uploadUserData($data);
        if ($uploadedData){
            $user =  UserResource::make(User::where('id' , $request->id)->with('user_data')->first());
            return $this->sendResponse($user, 'User success to update');
        }else{
            return $this->sendError('failed.', 'User not update something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        $user = User::where('id', $id)->with('services.images')->first();
//        if(!$user){
//            return $this->sendError('User element dosn\'t exist to delete', []);
//        }
//
//        $user->delete();
//        $this->notification_service->removeNotifications($id, 'provider');
//
//        return $this->sendResponse([],'User element deleted successfully');

        return $this->sendResponse([],'Delete user freezed temporary');
    }

    public function updatePassword(Request $request){
        $request->validate([
            'id'            => 'required|exists:users,id,deleted_at,NULL',
            'password'      => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $data = $request->only('id', 'password');
        $user = User::where('id' , $data['id'])->with('user_data')->first();
        $user->password = Hash::make($data['password']);
        if ($user->save()){
            $user =  UserResource::make($user);
            return $this->sendResponse($user, 'User update success');
        }else{
            return $this->sendError('failed.', 'User not update something went wrong');
        }
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:users,id,deleted_at,NULL',
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);
        $name = $request->id . "-" . time();
        $path = "user-profile";
        $user_data = UserData::where('user_id', $request->id)->first();
        if($user_data->avatar){
            $this->fileUtil->deleteFileByPath($user_data->avatar);
        }
        if (isset($request->avatar)){
            $image_path = $this->fileUtil->uploadFile($request->avatar, $name, $path);
            $user_data->avatar = $image_path;
        }
        $user_data->save();

        if ($image_path){
            $user =  UserResource::make(User::where('id' , $request->id)->with('user_data')->first());
            return $this->sendResponse($user, 'User uploaded successfully');
        }else{
            return $this->sendError('failed.', ['User not uploaded something went wrong']);
        }
    }

    public function status(Request $request) {
        $request->validate([
            'id' => 'required|exists:users,id,deleted_at,NULL',
            'status' => 'required'
        ]);

        $user = User::where('id', $request->id)->with('user_data')->first();
        $user->status = $request->status == 1 ? 1 : 0;
        $user->save();

        $this->customerReviewsService->UpdateCustomerReviewUserStatus($request->id, $request->status);

        if($user) {
            $userData = UserResource::make($user);
            return $this->sendResponse( $userData, $user->status ? 'User is active now (ON)' : 'User is inactive now (OFF)');
        } else {
            return $this->sendError('User active fail something went wrong', []);
        }
    }

    public function verify(Request $request){
        $request->validate([
            'id' => 'required|exists:users,id,deleted_at,NULL',
            'type' => ['required', Rule::in(['email','phone'])]
        ]);
        $user = User::where('id' , $request->id)->with('user_data')->first();
        if($request->type == 'email'){
            $user->email_verified_at = date('Y-m-d H:i:s');
        }else{
            $user->phone_verify_at = date('Y-m-d H:i:s');
        }
        if($user->save()){
            $user = UserResource::make($user);
            return $this->sendResponse( $user, 'User verify success');
        }else{
            return $this->sendError('User verify fail something went wrong', []);
        }
    }

    public function getAllCustomerReview(): \Illuminate\Http\JsonResponse
    {
        $customer_reviews = CustomerReview::whereIn('review_type', ['provider', 'service', 'task'])->with(['customer' => function ($query) {
            $query->select('id', 'name');
        }])->get();
        return $this->sendResponse($customer_reviews, "All Customer Review fetched successfully");

        //return response($customer_reviews, "User data fetched successfully");
    }

    public function getCustomerReview($id): \Illuminate\Http\JsonResponse
    {
        $customer_reviews = CustomerReview::where('id', $id)->whereIn('review_type', ['provider', 'service', 'task'])->with(['customer' => function ($query) {
            $query->select('id', 'name');
        }])->first();
        return $this->sendResponse($customer_reviews, "Customer Review by id fetched successfully");

        //return response($customer_reviews, "User data fetched successfully");
    }

    public function CustomerReviewUpdateApprovel(Request $request)
    {
         $customer_reviews = CustomerReview::where("id",$request->id)->update(["approvel" => $request->approvel]);
         $data = CustomerReview::where("id",$request->id)->with('user')->get()->first();

        $this->customerReviewsService->UpdateCustomerReviewAverage($data->review_id, $data->review_type);

         // customer_id = user id
         // review_id = item id

        if ($customer_reviews) {
            $noty = [];
            $item_id = null;
            $item_type = null;
            $user_to_notify = null;
            $user_name = User::where('id', $data->customer_id)->get()->first();
            if ($data->review_type == 'provider') {
                $user_notify = CustomerReview::where("id",$request->id)->with('user')->get()->first();
                $user_to_notify = $user_notify->user->id;
                $item_id = $data->review_id;
                $item_type = 'provider';
            } elseif($data->review_type == 'service') {
                $user_notify = CustomerReview::where("id",$request->id)->with('Service')->get()->first();
                $user_to_notify = $user_notify->service->user_id;
                $item_id = $data->review_id;
                $item_type = 'service';
            } elseif ($data->review_type == 'task') {
                $user_notify = CustomerReview::where("id",$request->id)->with('Task')->get()->first();
                $user_to_notify = $user_notify->task->user_id;
                $item_id = $data->review_id;
                $item_type = 'task';
            }

            $noty["user_id"]            = $data->customer_id;
            $noty["user_to_notify"]     = $user_to_notify;
            $noty["user_name"]          = $user_name->name;
            $noty["item_type"]          = $item_type;
            $noty["item_id"]            = $item_id;
            $noty["action_type"]        = 'review';
            $this->notification_service->fireNotification($noty);
        }

         return $this->sendResponse($data, 'Customer Review approvel updated successfully');
    }
}

