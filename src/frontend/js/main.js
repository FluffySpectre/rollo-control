var rolloPositions = [0.33, 0.5, 0.66];
var rolloAnimationPositions = ['-66%', '-50%', '-33%'];
var rolloPosition = 0;

$(document).on("pagecreate", function() {
    //$('#tabOne').click();
    $('#tabFour').click();

    $("#rollo").animate({ top: '-100%' }, 42000);
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

function getRolloPosition() {
    $.ajax('api/rollo.php?get_position=1', function(data) {
        if (data.position) {
            rolloPosition = data.position;
        }
    });
}

function updateRolloAnimationPosition() {
    // TODO
}