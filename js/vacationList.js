$(document).ready(function () {
    function fillTable(data) {
        $(data).each(function (index, event) {
            $('#vacList').append('<tr id="' + event.id + '"><td>' + (index + 1) + '</td><td>' + event.title + '</td><td>' + event.start + '</td><td>' + event.end + '</td><td>' + event.days + '</td></tr>')
        });
    }
    $.ajax({
        type: "GET",
        dataType: 'json',
        url: "./bin/get_vacations.php",
        async: true,
        contentType: "application/json; charset=utf-8",
        success: function (data) {
            fillTable(data);
        }
    });
});