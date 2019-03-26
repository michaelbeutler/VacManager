$(document).ready(function () {
    $('#formChangePassword').submit(function (e) {
        e.preventDefault();

        var password = $('#passwordChangePassword').val();
        var repeat = $('#repeatChangePassword').val()

        function callback(data) {
            switch (data.code) {
                case 200:
                    // success
                    swal("Password changed!", "You will be logged out in 5 sec!", "success");
                    setTimeout(function () { window.location.replace("login.html"); }, 5000);
                    break;
                default:
                    alert(data.description);
                    console.error(data.description);
                    break;
            }
        }

        if (password == repeat) {
            if (isEmptyOrSpaces(password)) {
                $.Notification.autoHideNotify('error', 'bottom right', 'Password', "Password is empty!")
            } else {
                swal({
                    title: "Are you sure?",
                    text: "You will be logged out and your password will be changed!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, change it!",
                    closeOnConfirm: false
                }, function () {
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
                });
            }

        } else {
            $.Notification.autoHideNotify('error', 'bottom right', 'Password', "Password's don't match")
        }

        return false;
    });

    $('#loadClassEvents').click(function () {
        var checked;
        if ($('#loadClassEvents').is(':checked')) {
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
                        $.Notification.autoHideNotify('error', 'bottom right', 'Loading classevents', 'Loading class events is now deactivated!')
                    } else {
                        $('#loadClassEvents').prop("checked", true);
                        $.Notification.autoHideNotify('success', 'bottom right', 'Loading classevents', 'Loading class events is now activated!')
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

function isEmptyOrSpaces(str) {
    return str === null || str.match(/^ *$/) !== null;
}