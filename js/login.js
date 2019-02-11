$(document).ready(function () {
    $('#username').focus();

    $('#username').keypress(function () {
        if (event.which == 13) {
            event.preventDefault();
            $('#password').focus();
        }
    });

    $('#password').keypress(function () {
        if (event.which == 13) {
            event.preventDefault();
            $('#formLogin').submit();
        }
    });

    $('#formLogin').submit(function (e) {
        e.preventDefault();

        var valid = true;
        if (valid) {
            $.ajax({
                type: "GET",
                dataType: 'json',
                url: "./bin/login.php",
                async: true,
                data: {
                    "username": $('#username').val(),
                    "password": SHA512($('#password').val())
                },
                contentType: "application/json; charset=utf-8",
                success: function (data) {
                    switch (data.code) {
                        case 200:
                            // success
                            window.location.replace("index.php");
                            break;
                        case 203:
                            alert(data.description);
                            break;
                        case 205:
                            $.Notification.notify('error', 'bottom right', 'Banned', data.description);
                            break;
                        default:
                            console.error(data.description);
                            break;
                    }
                }
            });
        }

        return false;
    });
});