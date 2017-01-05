<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Channel;
use App\Recording;
use Illuminate\Support\Facades\Log;

class FrontendController extends Controller
{
    public function events(Request $request){
        $aOut = [
            'monthly' => []
        ];
        $iMonth = $request->input('m');
        $iYear = $request->input('y');
        $sDateStart = sprintf("%4d-%02d-01", $iYear, $iMonth);
        $sDateEnd = date('Y-m-t', strtotime($sDateStart));

        Log::debug("DATE:", ['date' => $sDateStart]);

        $tmp = Recording::where([
            ['date_start', '>=', $sDateStart],
            ['date_start', '<=', $sDateEnd]
        ])->get();

        $aDateMapCount = [];
        $aDateMapDuration = [];
        foreach ($tmp as $item){
            $sDate = date('Y-m-d', strtotime($item->date_start));
            if (! isset($aDateMapCount[$sDate])){
                $aDateMapCount[$sDate] = 0;
                $aDateMapDuration[$sDate] = 0;
            }
            $aDateMapCount[$sDate]++;
            $aDateMapDuration[$sDate] += $item->duration;
        }

        foreach ($aDateMapCount as $sDate => $sCount){
            $sDuration = $aDateMapDuration[$sDate];

            $aMonthItem = [
                'id' => $sDate,
//                'name' => $sCount . ' / ' . $sDuration,
                'name' => $sCount . ' items',
                'startdate' => date('Y-m-d', strtotime($sDate)),
                'enddate' => date('Y-m-d', strtotime($sDate)),
                'starttime' => '0:00',
                'endtime' => '23:59',
                'color' => '#FF0000',
                'url' => 'get'.$sDate
            ];
            $aOut['monthly'][] = $aMonthItem;
        }

        echo json_encode($aOut);
        exit;
    }
    
}