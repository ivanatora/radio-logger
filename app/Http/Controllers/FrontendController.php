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
                'url' => '/date/'.$sDate
            ];
            $aOut['monthly'][] = $aMonthItem;
        }

        echo json_encode($aOut);
        exit;
    }

    public function getForDate(Request $request){
        $sDate = $request->route('date');
        if (! preg_match('/\d\d\d\d-\d\d-\d\d/', $sDate)){
            header('Location: /');
            exit();
        }
        $sDateStart = date('Y-m-d 00:00:00', strtotime($sDate));
        $sDateEnd = date('Y-m-d 23:59:59', strtotime($sDate));

        $tmp = Recording::where([
            ['date_start', '>=', $sDateStart],
            ['date_start', '<=', $sDateEnd]
        ])->orderBy('date_start', 'ASC')->get();

        $aVisDataset = [];
        foreach ($tmp as $item){
            $aVisDataset[] = [
                'id' => $item->id,
                'content' => $item->date_start,
                'start' => $item->date_start,
                'end' => $item->date_end,
                'filename' => basename($item->filename)
            ];
        }

        $iTotalDuration = 0;
        foreach ($tmp as $item){
            $iTotalDuration += $item->duration;
        }
        $sDurationString = '';
        $iSeconds = $iMinutes = $iHours = 0;
        $iSeconds = $iTotalDuration;
        if ($iSeconds > 60){
            $iSeconds = $iSeconds % 60;
            $iMinutes = floor($iTotalDuration / 60);
        }
        if ($iMinutes > 60){
            $iMinutes = $iMinutes % 60;
            $iHours = floor($iMinutes / 60);
        }
        $sDurationString = sprintf('%02d:%02d:%02d', $iHours, $iMinutes, $iSeconds);

        $aOutData = [
            'date' => $sDate,
            'total' => $tmp->count(),
            'seconds' => $iTotalDuration,
            'duration_string' => $sDurationString,
//            'recordings' => $tmp->toArray(),
            'vis_dataset' => $aVisDataset,
            'vis_start' => $sDateStart,
            'vis_end' => date('Y-m-d H:i:s', strtotime('+1 hour', strtotime($sDateStart))),
            'date_end' => $sDateEnd
        ];
        return view('day', $aOutData);
    }
}