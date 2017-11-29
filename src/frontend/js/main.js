var rolloPositions = [0.33, 0.5, 0.66];

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

function getTimings() {
    $.get('api/rollo.php?timings=1', function(data) {
        for (var i = 0; i < data.length; i++) {
            $('#dbOn' + i).val(data[i][0]);
            $('#dbOff' + i).val(data[i][1]);
        }
    }, 'json');
}