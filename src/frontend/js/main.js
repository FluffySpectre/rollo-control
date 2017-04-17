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