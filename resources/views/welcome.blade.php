<!doctype html>
<html lang="en">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta charset='utf-8'>
    <head>
        <!--[if lt IE 9]>
                <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- META ===================================================== -->
        <title>Radio archive</title>
        <meta name="description" content="Archive of radio recordings via RTL-SDR">

        <!-- Favicon  ========================================== -->


        <!-- CSS ======================================================
                <link rel="stylesheet" href="css/responsivetables.css">-->
        <!-- Demo CSS (don't use) -->
        <link href="https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="css/monthly.css">
    </head>
    <body>
        <div class="page">
            <h1>Radio archive</h1>
            <h2>Previous days recordings</h2>
            <div style="width:100%; max-width:600px; display:inline-block;">
                <div class="monthly" id="mycalendar"></div>
            </div>
            
        </div>
        <!-- JS ======================================================= -->
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/monthly.js"></script>
        <script type="text/javascript">
            $(window).load(function () {

                $('#mycalendar').monthly({
                    weekStart: 'Mon',
                    mode: 'event',
                    jsonUrl: 'events.json',
                    dataType: 'json'
//                    xmlUrl: 'events.xml'
                });

                switch (window.location.protocol) {
                    case 'http:':
                    case 'https:':
                        // running on a server, should be good.
                        break;
                    case 'file:':
                        alert('Just a heads-up, events will not work when run locally.');
                }

            });
        </script>
    </body>
</html>
