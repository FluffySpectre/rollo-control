var rolloPositions = [0.33, 0.5, 0.66];
var currentDay = new Date().getDay();

$(document).on("pagecreate", function() {
    $('#tabOne').click();
});

function onUpClick() {
    $.notifyBar({ cssClass: 'warning', html: 'Vorgang läuft...', delay: 999999 });

    $.get('api/rollo.php?up=1', function(data) {
        $.notifyBar({ cssClass: 'success', html: 'Rollo wird geöffnet!' });
    });
}

function onStopClick() {
    $.notifyBar({ cssClass: 'warning', html: 'Vorgang läuft...', delay: 999999 });

    $.get('api/rollo.php?stop=1', function(data) {
        $.notifyBar({ cssClass: 'success', html: 'Rollo wurde gestoppt!' });
    });
}

function onDownClick() {
    $.notifyBar({ cssClass: 'warning', html: 'Vorgang läuft...', delay: 999999 });

    $.get('api/rollo.php?down=1', function(data) {
        $.notifyBar({ cssClass: 'success', html: 'Rollo wird geschlossen!' });
    });
}

function onShutClick(index) {
    $.notifyBar({ cssClass: 'warning', html: 'Vorgang läuft...', delay: 999999 });

    var rolloPos = rolloPositions[index];
    $.get('api/rollo.php?position=' + rolloPos, function(data) {
        $.notifyBar({ cssClass: 'success', html: 'Rollo ist in Position!' });
    });
}

function onSaveTimingsClick() {
    // iterate over all dateboxes in order to get the timings
    var timings = [];
    for (var i = 0; i < 7; i++) {
        var on = $('#dbOn' + i).val();
        var off = $('#dbOff' + i).val();
        if (on != '' && off != '') {
            var t = [on, off];
            timings.push(t);
        } else {
            $.notifyBar({ cssClass: 'error', html: 'Bitte erst alle Zeiten eingeben!' });
            return;
        }
    }

    // send the timings json encoded to the server
    $.post('api/rollo.php', { 'timings': JSON.stringify(timings) }).done(function(data) {
        $.notifyBar({ cssClass: 'success', html: 'Timer aktualisiert!' });
    });
}

function onTimerTabClick() {
    getTimings();
    getSunriseSunsetTimes();
}

function getTimings() {
    // highlight the current day
    $('.day-row').removeClass('day-label-highlight');
    $('#dayLabel' + currentDay).addClass('day-label-highlight');

    $.get('api/rollo.php?timings=1', function(data) {
        for (var i = 0; i < data.length; i++) {
            $('#dbOn' + i).val(data[i][0]);
            $('#dbOff' + i).val(data[i][1]);
        }
    }, 'json');
}

function getSunriseSunsetTimes() {
    // gps location of our house: 52.988206, 8.852984
    $.get('https://api.sunrise-sunset.org/json?lat=52.988206&lng=8.852984&formatted=0', function(data) {
        if (data && data.status === 'OK') {
            var sr = new Date(data.results.sunrise);
            var ss = new Date(data.results.sunset);
            $("#sunriseLabel").html('<b>' + getFormattedTimeString(sr) + '</b> Uhr');
            $("#sunsetLabel").html('<b>' + getFormattedTimeString(ss) + '</b> Uhr');
        } else {
            $("#sunriseLabel").html('<b>---</b>');
            $("#sunsetLabel").html('<b>---</b>');
        }
    });
}

function getFormattedTimeString(d) {
    var h = d.getHours();
    if (h < 10)
        h = '0' + h;
    var m = d.getMinutes();
    if (m < 10)
        m = '0' + m;
    return h + ':' + m;
}