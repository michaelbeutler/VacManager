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

    function isNullOrEmpty(value) {
        return !(typeof value === "string" && value.length > 0);
    }

    function showMessage(msg) {
        $('.message-box').html('<p class="text-danger">' + msg + '</p>');
    }

    $('#formLogin').submit(function (e) {
        e.preventDefault();

        if (!isNullOrEmpty($('#username').val()) && !isNullOrEmpty($('#password').val())) {
            $.ajax({
                type: "GET",
                dataType: 'json',
                url: "./includes/login.php",
                async: true,
                data: {
                    "username": $('#username').val(),
                    "password": SHA512($('#password').val()),
                    "next": getQueryVariable('next')
                },
                contentType: "application/json; charset=utf-8",
                success: function (data) {
                    switch (data.code) {
                        case 200:
                            // success
                            if (data.url !== null && data.url !== undefined && data.url !== "false") {
                                window.location.replace(data.url);
                            } else {
                                window.location.replace("index.php");
                            }   
                            break;
                        case 203:
                            $('.form-group-username').addClass('has-error');
                            $('.form-group-password').addClass('has-error');
                            showMessage(data.description);
                            break;
                        case 205:
                            $.Notification.notify('error', 'bottom right', 'Banned', data.description);
                            break;
                        default:
                            console.error(data.description);
                            $.Notification.notify('error', 'bottom right', 'ERROR', data.description);
                            break;
                    }
                }
            });
        } else {
            $('.form-group-username').addClass('has-warning');
            $('.form-group-password').addClass('has-warning');
            showMessage('Please enter username and password.');
        }

        return false;
    });
});

function getQueryVariable(variable)
{
       var query = window.location.search.substring(1);
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
}