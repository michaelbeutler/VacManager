function getEmployerList(callback) {
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: "./bin/getEmployerList.php",
        async: true,
        contentType: "application/json; charset=utf-8",
        success: function (data) {
            callback(data);
        }
    });
}