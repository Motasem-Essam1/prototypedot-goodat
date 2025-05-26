<?php

namespace App\Console\Commands;

use App\Models\Package;
use App\Models\UserData;
use App\Models\PackageDuration;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class PackageDurationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:duration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::today()->format('Y-m-d');
        $users_end_subscriptions = PackageDuration::whereDate('end_date', '<=', $today)->where('canceled_at', null)->get()->pluck('user_id')->toArray();
        $check_durations = PackageDuration::whereIn("user_id", $users_end_subscriptions)->where('canceled_at', null)->get()->pluck('user_id')->toArray();
        if ($users_end_subscriptions) {
            UserData::whereIn("user_id", $users_end_subscriptions)->update(['package_id' => 1]);
            if ($check_durations) {
                PackageDuration::whereIn("user_id", $users_end_subscriptions)->where('canceled_at', null)->orderBy('id', 'DESC')->update(['canceled_at' => $today]);

                // Insert default package to all users ended their packages
                $package = Package::where('id', 1)->first();
                $package_months = 12;
                $total_price = $package['price'] * $package_months;

                foreach ($users_end_subscriptions as $user_id) {
                    $data = new PackageDuration;
                    $data['user_id']            = $user_id;
                    $data['package_id']         = $package['id'];
                    $data['number_of_months']   = $package_months;
                    $data['start_date']         = Carbon::now()->format('Y-m-d');
                    $data['end_date']           = Carbon::now()->addMonth($package_months);
                    $data['total_price']        = $total_price;
                    $data->save();
                }
            }
        }

        echo Carbon::now() . ' <br />';
    }
}
