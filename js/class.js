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