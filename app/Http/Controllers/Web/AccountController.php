<?php

namespace App\Http\Controllers\Web;


use App\Http\Requests\Dashboard\Users\UserRequest;
use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Package;
use App\Models\User;
use App\Models\Category;
use App\Models\UserSubCategories;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use App\Models\Services;
use App\Models\Task;
use App\Models\Favorite;
use App\Models\CustomerReview;
use App\Http\Resources\ServiceResource;
use Illuminate\Http\JsonResponse;
use App\Models\Images;
use App\Utils\FileUtil;
use App\Http\Requests\Dashboard\Services\UpdateTaskRequest;
use App\Http\Requests\Dashboard\Services\UpdateServiceRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;


class AccountController extends Controller
{
    private $userService;
    private $fileUtil;

    public function __construct(UserService $userService, FileUtil $fileUtil)
    {
        $this->userService = $userService;
        $this->fileUtil = $fileUtil;
    }

    //View Functions
    public function account(){
        $categories = Category::where('is_active', 1)->with('subCategories')->where('is_active', true)->get();
        $user_sub_categories = UserSubCategories::with('SubCategory')->where('user_id', Auth::user()->id)->get()->toArray();

        $data = [
            'avatar' => Auth::User()->user_data->avatar,
            'full_name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'phoned_Signed' => Auth::user()->phoned_Signed,
            'phone_number' => '+'. Auth::User()->country_code . Auth::User()->phone_number,
            'phone' => Auth::User()->phone_number,
            'country_code' => Auth::User()->country_code,
            'categories' => $categories,
            'user_sub_categories' => $user_sub_categories
        ];

        return view('account.account', $data);
    }

    public function changePassword(){
        if(Auth::user()->phoned_Signed == 1)
        {
            return view('account.password');
        }
        else{
            return redirect()->back();
        }
    }

    public function myFavorites() {
        // Services
        $services_ids = Favorite::where('item_type', 'service')->where('user_id', Auth::user()->id)->get()->pluck('favorite_id')->toArray();
        $services = Services::where('is_active', '1')->whereIn("id", $services_ids)->with('images')->with('user.user_data')->paginate(15, ['*'], 'services');
        //start get Services rate
        foreach( $services as $service)
        {
            $avg  = CustomerReview::with('Service')->where('review_type', 'service')->where('review_id', $service->id)->where('approvel', '1')->avg('rate');

            if(empty($avg))
            {
                $service['rate'] = 0;

            }
            else {
                $service['rate'] = sprintf("%0.2f", $avg);;
            }
        }
        //end get Services rate



        // Tasks
        $tasks_ids = Favorite::where('item_type', 'task')->where('user_id', Auth::user()->id)->get()->pluck('favorite_id')->toArray();
        $tasks = Task::where('is_active', '1')->whereIn("id", $tasks_ids)->with('images')->with('user.user_data')->paginate(15, ['*'], 'tasks');
        foreach( $tasks as $task)
        {

            $avg  = CustomerReview::with('Service')->where('review_type', 'Task')->where('review_id', $task->id)->avg('rate');

            if(empty($avg))
            {
                $task['rate'] = 0;

            }
            else {
                $task['rate'] = sprintf("%0.2f", $avg);
            }
        }



        // Providers
        $providers_ids = Favorite::where('item_type', 'provider')->where('user_id', Auth::user()->id)->get()->pluck('favorite_id')->toArray();
        $providers = User::whereIn("id", $providers_ids)->with('user_data')->with('UserSubCategories')->paginate(15, ['*'], 'providers');
        //start get UserSubCategories
        foreach($providers as $user)
        {
                $user_sub_categories = UserSubCategories::with('SubCategory')->where('user_id', $user->id)->get()->toArray();
                $user_sub_categories_text = implode(", ",  array_map(function ($item) {
                    return $item['sub_category']['sub_category_name'];
                }, $user_sub_categories)
                );
                $user['user_sub_categories_text'] = $user_sub_categories_text;

                //get rate
                if($user->CustomerReview()->where('review_type', 'provider_average')->get()->first())
                {
                    $user['rate'] = $user->CustomerReview()->where('review_type', 'provider_average')->get()->first()->rate;
                }
                else{
                    $user['rate'] = 0;
                }

                //get  number of customer review
                if($user->CustomerReview()->where('review_type', 'provider')->get())
                {
                    $user['customer_review_number'] = $user->CustomerReview()->where('review_type', 'provider')->get()->count();
                }
                else{
                    $user['customer_review_number'] = 0;
                }
        }
        //end get UserSubCategories


        //start get default currency
        $configurations = Configuration::query()->where("key", "currency_symbol")->first();
        //end get default currency

       //return $services[1];

        $data = [
            'services'  => $services,
            'providers' => $providers,
            'tasks'     => $tasks,
            'configurations' => $configurations,

        ];

        return view('account.my-favorites', $data);
    }

    public function serviceTask(){
        $configurations = Configuration::query()->where("key", "currency_symbol")->first();

        $data = [
            'service' => Auth::user()->services,
            'tasks' => Auth::user()->tasks,
            'configurations' => $configurations,
        ];
        return view('account.service-task', $data);
    }

    public function reviews(){
        $service_CustomerReviews = CustomerReview::where('customer_id',  Auth::user()->id)->with('Service')->where('review_type', '=' ,"service")->get();
        $provider_CustomerReviews = CustomerReview::where('customer_id', Auth::user()->id)->with('user.user_data')->where('review_type', "provider")->get();
        $configurations = Configuration::query()->where("key", "currency_symbol")->first();

        $data = [
            'service_CustomerReviews' => $service_CustomerReviews,
            'provider_CustomerReviews' => $provider_CustomerReviews,
            'configurations' => $configurations,
        ];
        //return $service_CustomerReviews;
        return view('account.reviews', $data);
    }

    public function subscription(){
        $packages = Package::where('is_public', true)->get();
        $configurations = Configuration::query()->where("key", "currency_symbol")->first();

        $data = [
            'type' => Auth::user()->user_data->user_type,
            'packages' => $packages,
            'current_package' => Auth::user()->user_data->package,
            'configurations' => $configurations,
        ];
        return view('account.subscription', $data);
    }

    //Business Functions
    public function uploadUserProfileImage(UserRequest $request)
    {
        $data = [
            'id' => Auth::user()->getAuthIdentifier(),
            'avatar' => $request->file('avatar'),
            'full_name' => $request->fullname ,
            'country_code' => $request->phone_code ,
            'phone_number' => $request->phone,
            'user_sub_categories' => $request->category_id
        ];

        if(Auth::user()->phoned_Signed == 1)
            $data['email'] = $request->email;
        else
            $data['email'] = Auth::user()->email;

        $this->userService->uploadUserData($data);
        return redirect()->route('account.account');
    }

    public function updatePassword(Request $request){
        $request->validate([
           'old_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $data = $request->only('old_password', 'password');
        $data['id'] = Auth::user()->getAuthIdentifier();
        $response = $this->userService->updatePassword($data);
        return redirect()->back()->withErrors($response);
    }
}
