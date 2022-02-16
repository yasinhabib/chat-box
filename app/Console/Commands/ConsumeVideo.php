<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\VideoConferenceController;

class ConsumeVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume Video from broker';

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
        $controller = new VideoConferenceController();
        $controller->consume();
    }
}
