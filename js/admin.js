$(document).ready(function () {
    function fillTable(data) {
        switch (data.code) {
            case 200:
                // success
                $(data.users).each(function (index, user) {
                    var content = '<td>' + user.id + '</td>';
                    content += '<td>' + user.firstname + '</td>';
                    content += '<td>' + user.lastname + '</td>';
                    content += '<td>' + user.create_time + '</td>';
                    $('#tableUser tbody').append('<tr>' + content + '</tr>');
                });
                break;
            default:
                alert(data.description);
                console.error(data.description);
                break;
        }
    }

    $.ajax({
        type: "GET",
        dataType: 'json',
        url: "./bin/admin.php",
        async: true,
        contentType: "application/json; charset=utf-8",
        success: function (data) {
            fillTable(data);
        }
    });
});

function isEmptyOrSpaces(str) {
    return str === null || str.match(/^ *$/) !== null;
}