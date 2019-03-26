$(document).ready(function () {
    $('#formChangePassword').submit(function (e) {
        e.preventDefault();

        var password = $('#passwordChangePassword').val();
        var repeat = $('#repeatChangePassword').val()

        function callback(data) {
            switch (data.code) {
                case 200:
                    // success
                    window.location.replace("./bin/logout.php");
                    break;
                default:
                    alert(data.description);
                    console.error(data.description);
                    break;
            }
        }

        if (password == repeat) {
            $.ajax({
                type: "GET",
                dataType: 'json',
                url: "./bin/change_password.php",
                async: true,
                data: {
                    "password": SHA512(password),
                    "repeat": SHA512(repeat)
                },
                contentType: "application/json; charset=utf-8",
                success: function (data) {
                    callback(data);
                }
            });
        }

        return false;
    });

    $('#loadClassEvents').click(function () {
        var checked;
        if($('#loadClassEvents').is(':checked')) {
            checked = 1;
        } else {
            checked = 0;
        }

        function callback(data) {
            switch (data.code) {
                case 200:
                    // success
                    if (data.value == 0) {
                        $('#loadClassEvents').prop("checked", false);
                    } else {
                        $('#loadClassEvents').prop("checked", true);
                    }
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
            url: "./bin/user_preference.php",
            async: true,
            data: {
                "option": "loadClassEvents",
                "value": checked
            },
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                callback(data);
            }
        });

    });
});