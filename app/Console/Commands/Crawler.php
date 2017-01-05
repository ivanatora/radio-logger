<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Channel;
use App\Recording;

class Crawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl for audio files';

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
     * @return mixed
     */
    public function handle()
    {
        $sRootDir = '/home/ivanatora/Downloads/sdrecord-ivanatora/mp3/';
        
        // last saved recording
        $aLastRecording = Recording::orderBy('created_at', 'DESC')->first();
        print_r($aLastRecording);
    }
}
