<?php

namespace App\Http\Controllers\Web;

use App\Console\Commands\PackageDuration;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\TaskResource;
use App\Models\Category;
use App\Models\Configuration;
use App\Models\Services;
use App\Models\SubCategory;
use App\Models\UserSubCategories;
use App\Models\Task;
use App\Models\UserData;
use App\Models\User;
use App\Models\CustomerReview;
use App\Models\Visitors;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home(){
        Visitors::saveVisitor();
        $providers = UserData::where('user_type', 'Service Provider')->get();
        $categories = Category::where('is_active', 1)->get();

        //for CustomerReview section
        $CustomerReviews = CustomerReview::orderBy('rate','DESC')->limit(10)->with('user.user_data')->with('customer.user_data')
        ->where('review_type', '=', "provider")->where('approvel', '1')->where('status', 1)->get();

        //start get UserSubCategories
        foreach($CustomerReviews as $CustomerReview)
        {
                $user_sub_categories = UserSubCategories::with('SubCategory')->where('user_id', $CustomerReview->review_id)->get()->toArray();
                $user_sub_categories_text = implode(", ",  array_map(function ($item) {
                    return $item['sub_category']['sub_category_name'];
                }, $user_sub_categories)
                );
                $CustomerReview['user_sub_categories_text'] = $user_sub_categories_text;
        }
        //end get UserSubCategories

        //start get default currency
        $configurations = Configuration::query()->where("key", "currency_symbol")->first();
        //end get default currency


        $data = [
            'providers' => $providers,
            'categories' => $categories,
            'customer_reviews' => $CustomerReviews,
            'configurations' => $configurations,
        ];

//        $test = [
//            'end_of_day' => Carbon::today(),
//            'after_12_month' => Carbon::now()->addMonth(12),
//            'after_12_month_start_day' => Carbon::now()->addMonth(12)->startOfDay(),
//            'dd' => Carbon::create(Carbon::now()->addMonth(12)),
//            'services' => Services::whereDate('created_at', '>=', Carbon::today())->get()->pluck('user_id')->toArray(),
//            'fourth' => Category::whereDate('created_at', '<', Carbon::today())->get()
//        ];
//
//        return $test;

        return view('welcome', $data);
    }


    public function search(Request $request){
        $categories = Category::where('is_active', 1)->get();
        //search query
        $q = $request->q;
        $c = $request->c;
        $rating = $request->rating;
        $categories_text = str_replace('-', ' ', $request->category);
        $search_category = explode('|', $categories_text);
        $order = "";

        if($request->order_search == "")
        {
            $order = "ASC";
        }
        else{
            $order = $request->order_search;
        }

        if(empty($q))
        {
            $response = "please fill search bar";
           return redirect()->back()->withErrors($response);
        }

        if(!is_null($request->rating)){

            $service = Services::orderBy('service_name', $order)->where('is_active', 1)->Where(function ($query) use ($q){
                $query->where('service_name', 'LIKE', "%{$q}%")
                    ->orWhere('service_description', 'LIKE', "%{$q}%")
                    ->orWhere('starting_price', 'LIKE', "%{$q}%")
                    ->orWhereHas('category', function($query) use ($q) {
                        $query->where('sub_category_name', 'LIKE', "%{$q}%");
                    })->orWhereHas('parentCategory', function($query) use ($q) {
                        $query->where('category_name', 'LIKE', "%{$q}%");
                    })->orWhereHas('user', function($query) use ($q) {
                        $query->where('name', 'LIKE', "%{$q}%");
                    });
                })->WhereHas('CustomerReview', function($q) use ($rating)
                {
                    $q->whereBetween('rate', [$rating, $rating+1 -0.01]);

                });

                $tasks = Task::orderBy('task_name', $order)->where('is_active', 1)->Where(function ($query) use ($q){
                    $query->where('task_name', 'LIKE', "%{$q}%")
                        ->orWhere('task_description', 'LIKE', "%{$q}%")
                        ->orWhere('starting_price', 'LIKE', "%{$q}%")
                        ->orWhere('ending_price', 'LIKE', "%{$q}%")
                        ->orWhereHas('category', function($query) use ($q) {
                            $query->where('sub_category_name', 'LIKE', "%{$q}%");
                        })->orWhereHas('parentCategory', function($query) use ($q) {
                            $query->where('category_name', 'LIKE', "%{$q}%");
                        })->orWhereHas('user', function($query) use ($q) {
                            $query->where('name', 'LIKE', "%{$q}%");
                        });
                    })->WhereHas('CustomerReview', function($q) use ($rating)
                    {
                        $q->whereBetween('rate', [$rating, $rating+1 -0.01]);
                    });
        }
        else{
            //return $request->rating;

            $service = Services::orderBy('service_name', $order)->with('CustomerReview')->where('is_active', 1)->Where(function ($query) use ($q){
                $query->where('service_name', 'LIKE', "%{$q}%")
                    ->orWhere('service_description', 'LIKE', "%{$q}%")
                    ->orWhere('starting_price', 'LIKE', "%{$q}%")
                    ->orWhereHas('category', function($query) use ($q) {
                        $query->where('sub_category_name', 'LIKE', "%{$q}%");
                    })->orWhereHas('parentCategory', function($query) use ($q) {
                        $query->where('category_name', 'LIKE', "%{$q}%");
                    })->orWhereHas('user', function($query) use ($q) {
                        $query->where('name', 'LIKE', "%{$q}%");
                    });
                });

                    $tasks = Task::orderBy('task_name', $order)->with('CustomerReview')->where('is_active', 1)->Where(function ($query) use ($q){
                    $query->where('task_name', 'LIKE', "%{$q}%")
                        ->orWhere('task_description', 'LIKE', "%{$q}%")
                        ->orWhere('starting_price', 'LIKE', "%{$q}%")
                        ->orWhere('ending_price', 'LIKE', "%{$q}%")
                        ->orWhereHas('category', function($query) use ($q) {
                            $query->where('sub_category_name', 'LIKE', "%{$q}%");
                        })->orWhereHas('parentCategory', function($query) use ($q) {
                            $query->where('category_name', 'LIKE', "%{$q}%");
                        })->orWhereHas('user', function($query) use ($q) {
                            $query->where('name', 'LIKE', "%{$q}%");
                        });
                    });
        }

        //get max price
        $max_price =300;
        $last_price =0;
        $last_price = Task::max('ending_price');
        if(!empty($last_price))
        {
            if($last_price > $max_price)
            $max_price = $last_price;
        }

        $last_price = Services::max('ending_price');
        if(!empty($last_price))
        {
            if($last_price > $max_price)
            $max_price = $last_price;
        }


     //dd($tasks);
        if (!is_null($request->category) && !is_null($request->price_start)){
            $service = $service->whereBetween('starting_price', [$request->price_start, $request->price_end])
                ->whereBetween('ending_price', [$request->price_start, $request->price_end])
                ->WhereHas('category', function($query) use ($q, $search_category) {
                    $query->whereIn('sub_category_name', $search_category);
                })->get();
            $tasks = $tasks->whereBetween('starting_price', [$request->price_start, $request->price_end])
                ->whereBetween('ending_price', [$request->price_start, $request->price_end])
                ->WhereHas('category', function($query) use ($q, $search_category) {
                    $query->whereIn('sub_category_name', $search_category);
                })->get();
            //dd($search_category);

        }else if(!is_null($request->category)){
            $service = $service->WhereHas('category', function($query) use ($q, $search_category) {
                $query->whereIn('sub_category_name', $search_category);
            })->get();
            $tasks = $tasks->WhereHas('category', function($query) use ($q, $search_category) {
                $query->whereIn('sub_category_name', $search_category);
            })->get();
        }else if(!is_null($request->price_start)){
            $service = $service->whereBetween('starting_price', [$request->price_start, $request->price_end])
                ->whereBetween('ending_price', [$request->price_start, $request->price_end])
                ->get();
            $tasks = $tasks->whereBetween('starting_price', [$request->price_start, $request->price_end])
                ->whereBetween('ending_price', [$request->price_start, $request->price_end])
                ->get();
        }
        else{
            $service = $service->get();
            $tasks = $tasks->get();
        }

        $tasks = TaskResource::collection($tasks);
        $statisticCollectionTasks = collect($tasks);
        $statisticCollectionTasks = $statisticCollectionTasks ->filter(function ($value, $key){
            return $value['user_status'] == 1;
        });


        $service = ServiceResource::collection($service);
        $statisticCollectionServices = collect($service);
        $statisticCollectionServices = $statisticCollectionServices ->filter(function ($value, $key){
            return $value['user_status'] == 1;
        });


        if($order == "ASC")
        {
            $tasks = $statisticCollectionTasks->sortBy('average')->values()->all();
            $service = $statisticCollectionServices->sortBy('average')->values()->all();
        }
        else{
            $tasks = $statisticCollectionTasks->sortByDesc('average')->values()->all();
            $service = $statisticCollectionServices->sortByDesc('average')->values()->all();
        }

        $configurations = Configuration::query()->where("key", "currency_symbol")->first();


        $data = [
            'categories' => $categories,
            'q' => $q,
            'c' => $c,
            'rating' => $rating,
            'service' => $service,
            'tasks' =>$tasks,
            'max_price' => $max_price,
            'order' => $order,
            'configurations' => $configurations,
        ];
        //return $service;
        return view('search', $data);
    }

    public function about(){
        return view('about');
    }

    public function privacy(){
        return view('privacy');
    }

    public function searchByKey(String $key){
        $myArray = [];


        //search user by key and take 3 of them
        $users = User::where('name', 'LIKE', "%{$key}%")->where('status', 1)->select('name', 'id')->take(3)->get()->toArray();
        foreach($users as $user)
        {
            $itemkey =
            [
                "type" => "Provider",
                "name" => $user['name'],
                "id" => $user['id']

            ];
            array_push($myArray, $itemkey);
        }


        //search serivce by key and take 3 of them
        $services = Services::where('is_active', '1')->where('service_name', 'LIKE', "%{$key}%")->take(9)->get();
        $services = ServiceResource::collection($services);
        $services = collect($services);
        $services = $services ->filter(function ($value, $key){
            return $value['user_status'] == 1;
        });

        foreach($services as $service)
        {
            $itemkey =
                [
                    "type" => "Service",
                    "name" => $service['service_name'],
                    "id" => $service['service_slug']
                ];
            array_push($myArray, $itemkey);
        }


        //search task by key  and take 3 of them
        $tasks = Task::where('is_active', '1')->where('task_name', 'LIKE', "%{$key}%")->take(3)->get();
        $tasks = TaskResource::collection($tasks);
        $tasks = collect($tasks);
        $tasks = $tasks ->filter(function ($value, $key){
            return $value['user_status'] == 1;
        });

        foreach($tasks as $task)
        {
            $itemkey =
            [
                "type" => "Task",
                "name" => $task['task_name'],
                "id" => $task['task_slug']
            ];
            array_push($myArray, $itemkey);
        }

        return response()->json($myArray);
    }
}
