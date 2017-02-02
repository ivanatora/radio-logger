<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Channel;
use App\Recording;
use App\Note;
use Illuminate\Support\Facades\Log;

class FrontendController extends Controller
{

    public function events(Request $request)
    {
        $aOut       = [
            'monthly' => []
        ];
        $iMonth     = $request->input('m');
        $iYear      = $request->input('y');
        $sDateStart = sprintf("%4d-%02d-01 00:00:00", $iYear, $iMonth);
        $sDateEnd   = date('Y-m-t 23:59:59', strtotime($sDateStart));

//        Log::debug("DATE:", ['date' => $sDateStart, 'dateend' => $sDateEnd]);

        $tmp = Recording::where([
                ['date_start', '>=', $sDateStart],
                ['date_start', '<=', $sDateEnd]
            ])->get();

        $aDateMapCount    = [];
        $aDateMapDuration = [];
        foreach ($tmp as $item) {
            $sDate = date('Y-m-d', strtotime($item->date_start));
            if (!isset($aDateMapCount[$sDate])) {
                $aDateMapCount[$sDate]    = 0;
                $aDateMapDuration[$sDate] = 0;
            }
            $aDateMapCount[$sDate] ++;
            $aDateMapDuration[$sDate] += $item->duration;
        }

        foreach ($aDateMapCount as $sDate => $sCount) {
            $sDuration = $aDateMapDuration[$sDate];

            $aMonthItem        = [
                'id' => $sDate,
//                'name' => $sCount . ' / ' . $sDuration,
                'name' => $sCount.' items',
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

    public function getForDate(Request $request)
    {
        $sDate = $request->route('date');
        if (!preg_match('/\d\d\d\d-\d\d-\d\d/', $sDate)) {
            header('Location: /');
            exit();
        }
        $sDateStart = date('Y-m-d 00:00:00', strtotime($sDate));
        $sDateEnd   = date('Y-m-d 23:59:59', strtotime($sDate));

        $tmp = Recording::where([
                ['date_start', '>=', $sDateStart],
                ['date_start', '<=', $sDateEnd]
            ])->orderBy('date_start', 'ASC')->get();

        $aVisDataset   = [];
        $aNotesDataset = [];
        foreach ($tmp as $item) {
            $aVisDataset[] = [
                'id' => $item->id,
                'content' => $item->date_start,
                'start' => $item->date_start,
                'end' => $item->date_end,
                'filename' => basename($item->filename),
                'item_type' => 'recording'
            ];

            $aNotes = $item->notes;
            foreach ($aNotes as $item2) {
                $aNotesDataset[] = [
                    'id' => 'note_'.$item2->id,
                    'content' => $item2->body,
                    'start' => $item->date_start,
                    'className' => 'item_note',
                    'item_type' => 'note'
                ];
            }
        }

        $iTotalDuration = 0;
        foreach ($tmp as $item) {
            $iTotalDuration += $item->duration;
        }
        $sDurationString = '';
        $iSeconds        = $iMinutes        = $iHours          = 0;
        $iSeconds        = $iTotalDuration;
        if ($iSeconds > 60) {
            $iSeconds = $iSeconds % 60;
            $iMinutes = floor($iTotalDuration / 60);
        }
        if ($iMinutes > 60) {
            $iMinutes = $iMinutes % 60;
            $iHours   = floor($iMinutes / 60);
        }
        $sDurationString = sprintf('%02d:%02d:%02d', $iHours, $iMinutes,
            $iSeconds);

        $aOutData = [
            'date' => $sDate,
            'total' => $tmp->count(),
            'seconds' => $iTotalDuration,
            'duration_string' => $sDurationString,
//            'recordings' => $tmp->toArray(),
            'vis_dataset' => $aVisDataset,
            'notes_dataset' => $aNotesDataset,
            'vis_start' => $sDateStart,
            'vis_end' => date('Y-m-d H:i:s',
                strtotime('+1 hour', strtotime($sDateStart))),
            'date_end' => $sDateEnd,
            'prev_date' => '',
            'next_date' => ''
        ];

        $tmp = Recording::where('date_start', '<', $sDateStart)->orderBy('date_start', 'DESC')->first();
        if ($tmp){
            $aOutData['prev_date'] = date('Y-m-d', strtotime($tmp->date_start));
        }

        $tmp = Recording::where('date_end', '>', $sDateEnd)->orderBy('date_start', 'ASC')->first();
        if ($tmp){
            $aOutData['next_date'] = date('Y-m-d', strtotime($tmp->date_start));
        }

        return view('day', $aOutData);
    }

    public function play(Request $request)
    {
        $id         = $request->route('id');
        $oRecording = Recording::find($id);
        header('Content-type: audio/mpeg');
        header('X-Pad: avoid browser bug');
        header('Cache-Control: no-cache');
        readfile($oRecording->filename);
    }

    public function addComment(Request $request)
    {
        $iRecordingId = $request->input('recording_id');
        $sBody        = $request->input('body');

        $oRecording = Recording::find($iRecordingId);

        $oNote               = new Note;
        $oNote->body         = $sBody;
        $oNote->recording_id = $iRecordingId;
        $oNote->channel_id   = $oRecording->channel_id;
        $oNote->save();

        $aOut = [
            'success' => true,
            'data' => [
                'id' => 'note_'.$oNote->id,
                'content' => $sBody,
                'start' => $oRecording->date_start,
                'className' => 'item_note',
                'item_type' => 'note'
            ]
        ];
        echo json_encode($aOut);
        exit();
    }
}