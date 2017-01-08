// inner variables
var song;
var bAudioRunning = false;
var volume = $('.volume');
initAudio($('.playlist li:first-child'));

function initAudio(elem) {
    var url = elem.attr('audiourl');
    var title = elem.text();
    $('.player .title').text(title);
    if (song){
        song.pause();
    }
    song = new Audio(url);
    song.volume = 1;
    if (bAudioRunning){
        song.play();
    }
    song.onended = function(){
        var next = $('.playlist li.active').next();
        if (next.length == 0) {
            next = $('.playlist li:first-child');
        }
        initAudio(next);
    }
//    bAudioRunning = true;
    
// timeupdate event listener
    $('.playlist li').removeClass('active');
    elem.addClass('active');
    
    $('.title').html(elem.html());
    
    timeline.setSelection(elem.data('id'), {focus: true})
}

// play click
$('.playpause').click(function (e) {
    e.preventDefault();
    if (bAudioRunning){
        song.pause();
        bAudioRunning = false;
        $(this).text('>');
    }
    else {
        song.play();
        bAudioRunning = true;
        $(this).text('||');
    }
});

// forward click
$('.forward').click(function (e) {
    e.preventDefault();
    song.pause();
    var next = $('.playlist li.active').next();
    if (next.length == 0) {
        next = $('.playlist li:first-child');
    }
    initAudio(next);
    bAudioRunning = true;
});
// rewind click
$('.previous').click(function (e) {
    e.preventDefault();
    song.pause();
    var prev = $('.playlist li.active').prev();
    if (prev.length == 0) {
        prev = $('.playlist li:last-child');
    }
    initAudio(prev);
    bAudioRunning = true;
});
