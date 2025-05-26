<?php

namespace App\Console\Commands;

use App\Models\DeviceToken;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemoveDeviceTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:deviceTokens';

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
        $multiple_tokens_ids = DeviceToken::whereDate('created_at', '<=', Carbon::now()->subDays(30))->pluck('id')->toArray();
        if ($multiple_tokens_ids) {
            DeviceToken::whereIn('id', $multiple_tokens_ids)->delete();
        }

        echo Carbon::now() . ' <br />';
    }
}
