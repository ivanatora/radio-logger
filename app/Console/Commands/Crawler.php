<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Channel;
use App\Recording;
use LaravelMP3;

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
        $aExtensions = array('mp3');
        $sRootDir = '/home/ivanatora/Downloads/sdrecord-ivanatora/mp3/';

        // last saved recording
        $aLastRecording = Recording::orderBy('created_at', 'DESC')->first();
        if (empty($aLastRecording)){
            $iDiffDays = 999;
        }
        else {
            $sFilename = $aLastRecording->filename;
            print_r($sFilename);
            $tsModified = filemtime($sFilename);
            $tsNow = time();
            $iDiffDays = ceil(($tsNow - $tsModified) / (24 * 3600));
        }

        $sCmd = "find $sRootDir -mtime -$iDiffDays";
        $res = `$sCmd`;
        $aLines = explode("\n", $res);
        print_r($aLines);

        foreach ($aLines as $sFullpath){
            $sExtension = pathinfo($sFullpath, PATHINFO_EXTENSION);
            if (! in_array($sExtension, $aExtensions)){
                continue;
            }
            $bFileExist = Recording::where('filename', $sFullpath)->count();

            if ($bFileExist){
                continue;
            }
            if (!preg_match('!/(\d{4})_(\d\d)_(\d\d)__(\d\d)_(\d\d)_(\d\d).raw.mp3!', $sFullpath, $aMatches)){
                continue;
            }

//            $sDuration = LaravelMP3::getBitrate($sFullpath);
            $getID3 = new \getID3;
            $aInfo = $getID3->analyze($sFullpath);
            $sDuration = (int) $aInfo['playtime_seconds'];

            print "Adding $sFullpath $sDuration\n";
            $oRecording = new Recording;
            $oRecording->channel_id = 1;
            $oRecording->filename = $sFullpath;
            $oRecording->date_start = $aMatches[1].'-'.$aMatches[2].'-'.$aMatches[3].' '.
                $aMatches[4].':'.$aMatches[5].':'.$aMatches[6];
            $oRecording->duration = $sDuration;
            $oRecording->date_end = date('Y-m-d H:i:s', strtotime('+'.$oRecording->duration.' seconds', strtotime($oRecording->date_start)));
            $oRecording->save();
        }
    }
}
