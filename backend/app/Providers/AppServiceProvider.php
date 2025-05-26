<?php

namespace App\Providers;

use App\Models\DeviceToken;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Notification;
use App\Models\Attribute;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        view()->composer('*', function ($view) {
            $notify = null;
            if (Auth::check()) {
                $user_id = Auth::user()->id;
                $data = Notification::where('user_to_notify', $user_id)->with('user')->get()->all();
                $is_read = Notification::where('user_to_notify', $user_id)->where('is_read', 0)->with('user')->get()->all();

                $notify = [
                    'data' => $data,
                    'is_read' => count($is_read),
                    'success' => true
                ];

            }

            View::share('notify', $notify);
        });

        view()->composer('*', function ($view) {
            $firebaseToken = DeviceToken::whereNotNull('device_token')->pluck('device_token')->all();
            View::share('request_token', $firebaseToken);
        });


        view()->composer('*', function ($view) {
            $data = Attribute::where('status', 1)->get();
            if (!is_null($data)){
                $sheared_data = array();
                foreach ($data as $item){
                    $sheared_data += [
                        $item->key => $item->value
                    ];
                }
                View::share('attributes', $sheared_data);
            }

        });
    }
}
