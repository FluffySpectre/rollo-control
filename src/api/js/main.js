var rolloPositions = [0.33, 0.5, 0.66];

$(document).on("pagecreate", function() {
    $('#tabOne').click();

    $.notifyBar({ cssClass: 'success', html: 'Rollo wird geöffnet!' });
});

function onUpClick() {
    $.get('rollo.php?up=1', function(data) {
        $.notifyBar({ cssClass: 'success', html: 'Rollo wird geöffnet!' });
    });
}

function onStopClick() {
    $.get('rollo.php?stop=1', function(data) {
        $.notifyBar({ cssClass: 'success', html: 'Rollo wurde gestoppt!' });
    });
}

function onDownClick() {
    $.get('rollo.php?down=1', function(data) {
        $.notifyBar({ cssClass: 'success', html: 'Rollo wird geschlossen!' });
    });
}

function onShutClick(index) {
    $.notifyBar({ cssClass: 'warning', html: 'Vorgang läuft...' });

    var rolloPos = rolloPositions[index];
    $.get('rollo.php?position=' + rolloPos, function(data) {
        $.notifyBar({ cssClass: 'success', html: 'Rollo ist in Position!' });
    });
}