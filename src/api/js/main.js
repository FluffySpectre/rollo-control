var rolloPositions = [0.33, 0.5, 0.66];

$(document).on("pagecreate", function() {
    $.notify.defaults({ position: 'top left' });
    //$("#tabs").tabs("option", "active", 0);

    $('#tabOne').click();
});

function onUpClick() {
    $.get('rollo.php?up=1', function(data) {
        $.notify('Rollo wird geöffnet!', 'success');
    });
}

function onStopClick() {
    $.get('rollo.php?stop=1', function(data) {
        $.notify('Rollo wurde gestoppt!', 'success');
    });
}

function onDownClick() {
    $.get('rollo.php?down=1', function(data) {
        $.notify('Rollo wird geschlossen!', 'success');
    });
}

function onShutClick(index) {
    $.notify('Vorgang läuft...', { autoHide: false, className: 'info' });

    var rolloPos = rolloPositions[index];
    $.get('rollo.php?position=' + rolloPos, function(data) {
        $.notify('Rollo ist in Position!', 'success');
    });
}