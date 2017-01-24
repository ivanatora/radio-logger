<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class codes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates 4 000 000 codes';

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
        $aCodes = [];

        for ($i = 0; $i < 400000; $i++){
            if ($i % 100000 == 0){
                print "$i\n";
            }
            $aCodes[] = $this->generateRandomString();
        }

        print "Codes DONE\n";

        $sOutString = '';
        foreach ($aCodes as $sCode){
            $sOutString .= 'GM'.$sCode."\r\n";
        }
        print "String DONE\n";

        file_put_contents('/home/ivanatora/codes.txt', $sOutString);
    }

    public function generateRandomString()
    {
        $characters       = '2345679ABCDEFHJKMNPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        $iPrevIdx = -2;
        $idx = 0;
        for ($i = 0; $i < 6; $i++) {
            do {
                $idx = rand(0, $charactersLength - 1);
            } while ($idx == $iPrevIdx + 1);
            $randomString .= $characters[$idx];
            $iPrevIdx = $idx;
        }
        return $randomString;
    }
}
