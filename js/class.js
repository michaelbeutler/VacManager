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
    for (var year = new Date().getFullYear() - 1; year < new Date().getFullYear() + 1; year++) {
        for (var i = 1; i < 52; i++) {
            $.ajax({
                type: "GET",
                dataType: 'jsonp',
                url: "http://sandbox.gibm.ch/tafel.php?woche="+i+"-"+year+"&klasse_id=" + id,
                async: false,
                contentType: "application/json; charset=utf-8",
                success: function (data) {
                    data.tafel = _.uniqBy(data.tafel, 'tafel_datum');
                    addClassEvents(parseEvent(data.tafel));
                }
            });
        }
    }
}

function parseEvent(tafel) {
    var returnValue = [];
    $(tafel).each(function (index, event) {
        returnValue.push({
            id: event.tafel_id,
            start: event.tafel_datum,
            end: event.tafel_datum,
            allDay: true,
            weekday: event.tafel_wochentag,
            title: "School Day",
            rendering: 'background',
            backgroundColor: 'green'
        })
    })
    return returnValue;
}

function addClassEvents(data) {
    $(data).each(function (index, event) {
        $('#calendar').fullCalendar('renderEvent', event, true);
    })
}