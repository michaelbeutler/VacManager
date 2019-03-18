function loadClassForm(id, callback) {
    $.ajax({
        type: "GET",
        dataType: 'jsonp',
        url: "http://sandbox.gibm.ch/klassen.php?beruf_id=" + id,
        async: false,
        contentType: "application/json; charset=utf-8",
        success: function (data) {
            callback(data.klassen);
        }
    });
}

function getClassEvents(id) {
    $.ajax({
        type: "GET",
        dataType: 'jsonp',
        url: "http://sandbox.gibm.ch/tafel.php?klasse_id=" + id,
        async: false,
        contentType: "application/json; charset=utf-8",
        success: function (data) {
            addClassEvents(parseEvent(data.tafel));
        }
    });
}

function parseEvent(tafel) {
    return {
        id: tafel.tafel_id,
        start: tafel.tafel_datum,
        end: tafel.tafel_datum,
        allDay: true,
        weekday: tafel.tafel_wochentag,
        title: "School Day"
    };
}

function addClassEvents(data) {
    $(data).each(function(index, event){
        console.log(event);
        $('#calendar').fullCalendar( 'renderEvent', event, true);
    })
}