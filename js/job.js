function loadJobForm(callback) {
    $.ajax({
        type: "GET",
        dataType: 'jsonp',
        url: "http://sandbox.gibm.ch/berufe.php",
        async: false,
        contentType: "application/json; charset=utf-8",
        success: function (data) {
            callback(data.berufe);
        }
    });
}