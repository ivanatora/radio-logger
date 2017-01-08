/*
var items = new vis.DataSet([
    {id: 'A', content: 'Period A', start: '2014-01-16', end: '2014-01-22', type: 'background'},
    {id: 'B', content: 'Period B', start: '2014-01-25', end: '2014-01-30', type: 'background', className: 'negative'},
    {id: 1, content: 'item 1<br>start', start: '2014-01-23'},
    {id: 2, content: 'item 2', start: '2014-01-10 10:00:00', end: '2014-01-10 10:03:00'},
    {id: 3, content: 'item 3', start: '2014-01-10 11:00:00'},
    {id: 4, content: 'item 4', start: '2014-01-19', end: '2014-01-24'},
    {id: 5, content: 'item 5', start: '2014-01-28', type: 'point'},
    {id: 6, content: 'item 6', start: '2014-01-26'}
]);
*/
var items = new vis.DataSet(aVisDataset);

var container = document.getElementById('visualization');
var options = {
    start: sVisStart,
    end: sVisEnd,
    editable: false,
    min: dtStart,
    max: dtEnd,
    zoomMin: 1000 * 60 * 60,
    zoomMax: 1000 * 60 * 60 * 24
};

var timeline = new vis.Timeline(container, items, options);


timeline.on('select', function(e){
    var id = e.items[0];
    $('ul.playlist li').removeClass('active');
    var recording = $('li[data-id="'+id+'"]');
    recording.addClass('active');
    initAudio(recording);
    song.play();
    bAudioRunning = true;
    $('.playpause').text('||');
})

$('.share').click(function(e){
    e.preventDefault();
    var elem = $('.playlist li.active');
    prompt('Press Ctrl + C, then Enter to copy to clipboard', window.location.href+'#'+elem.data('id'));
})

setTimeout(function(){
    var hash = window.location.hash;
    if (hash != ''){
        var matches = hash.match(/#(\d+)$/)
        var id = matches[1];
        timeline.setSelection(id, {focus: true})
        
        $('ul.playlist li').removeClass('active');
        var recording = $('li[data-id="'+id+'"]');
        recording.addClass('active');
        initAudio(recording);
        bAudioRunning = true;
        song.play();
        $('.playpause').text('||');
    }
}, 1000)
