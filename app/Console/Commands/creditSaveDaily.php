<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\admin;
use App\Models\superstockez;
use App\Models\stockez;
use App\Models\admin\agent;
class creditSaveDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:credit-save-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admin = admin::latest()->first();
        $super = superstockez::all();
        $stockez = stockez::all();
        $agent = agent::all();
    }

}