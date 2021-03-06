<!doctype html>
<html lang="en">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta charset='utf-8'>
    <head>
        <!--[if lt IE 9]>
                <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <title>Recordings for <?= $date ?></title>
        <meta name="description" content="A method for responsive tables">

        <link href="https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="/css/styles.css?nc=<?=time()?>">
        <!--<link rel="stylesheet" href="/css/daily.css">-->
        <link rel="stylesheet" href="/css/audio.css?nc=<?=time()?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.17.0/vis.min.css">
        <script>
            var aVisDataset = <?= json_encode($vis_dataset); ?>;
            var aNotesDataset = <?= json_encode($notes_dataset); ?>;
            var sVisStart = <?= json_encode($vis_start) ?>;
            var dtStart = new Date(sVisStart);
            var sVisEnd = <?= json_encode($vis_end) ?>;
            var sDateEnd = <?= json_encode($date_end) ?>;
            var dtEnd = new Date(sDateEnd);
        </script>
    </head>
    <body>
        <div class="page">
            <h1><a href="/">Back to Month</a></h1>
            <h1>
                <? if (!empty($prev_date)):?> <a href="/date/<?=$prev_date?>">&lt;</a><? endif; ?>
                Archive for <?= $date ?>
                <? if (!empty($next_date)):?> <a href="/date/<?=$next_date?>">&gt;</a><? endif; ?>
            </h1>
            <h2>Total <?= $total ?> items / <?= $duration_string ?> total duration</h2>
            <div id="visualization"></div>

            <div class="myplayer">
                <button class="previous small"><<</button>
                <span class="title"></span>
                <button class="forward small">>></button>
                <button class="playpause small">></button>
                <button class="share">Share current</button>
                <button class="comment">Add comment to current</button>
            </div>
            <ul class="playlist hidden">
                @foreach ($vis_dataset as $item)
                <li audiourl="/play/{{$item['id']}}.mp3" data-id="{{$item['id']}}">{{$item['filename']}}</li>
                @endforeach;
            </ul>
        </div>

        <script type="text/javascript" src="/js/jquery.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.17.0/vis.min.js"></script>
        <script type="text/javascript" src="http://code.jquery.com/ui/1.8.21/jquery-ui.min.js"></script>
        <script type="text/javascript" src="/js/daily.js?nc=<?=time()?>"></script>
        <script type="text/javascript" src="/js/audio.js?nc=<?=time()?>"></script>
    </body>
</html>
