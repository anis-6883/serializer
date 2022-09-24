<?php

namespace App\Console\Commands;

use Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\Episode;
use Illuminate\Console\Command;

class PushNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sent notification to android or ios users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
    }
}
