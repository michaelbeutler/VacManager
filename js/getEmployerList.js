function getEmployerList(callback) {
    $.ajax({
        type: "GET",
        dataType: 'json',
        url: "./includes/new/employer.inc.php",
        data: {
            action: 'GET_ALL_EMPLOYERS'
        },
        async: true,
        contentType: "application/json; charset=utf-8",
        success: function (data) {
            callback(data);
        }
    });
}