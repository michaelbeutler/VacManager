$(document).ready(function () {
    // Fill employer input
    getEmployerList(function (data) {
        switch (data.code) {
            case 200:
                $('#employer').prop('disabled', false);
                $(data.data).each(function (index, element) {
                    $('#employer').append('<option selected value="">...</option>');
                    $('#employer').append('<option value="' + element.id + '">' + element.name + '</option>');
                });
                break;
            case 201:
                // 0 results
                $('#employer').prop('disabled', true);
                break;
            default:
                $('#employer').prop('disabled', true);
                console.error(data.description);
                break;
        }
    });

    $('#formRegister').submit(function (e) {
        e.preventDefault();

        var firstname = $('#firstname').val();
        var lastname = $('#lastname').val();
        var email = $('#email').val();
        var password1 = $('#password').val();
        var password2 = $('#confirm_password').val();
        var employer = $('#employer').val();
        var currentVacDays = $('#currentVacDays').val();

        function callback(data) {
            switch (data.code) {
                case 200:
                    // success
                    swal({
                        title: "Good job!",
                        text: "Your account is now online!",
                        type: "success"
                    }, function () {
                        window.location.replace("login.html");
                    })
                    break;
                case 210:
                    // username already taken
                    swal("Username already taken!", "Your username is already taken, pleas contact the administrator.", "warning");
                    break;
                case 901:
                    // passwords do not match
                    swal("Password", "Please check your password and confirm it again. They look not the same.", "warning");
                    break;
                default:
                    swal("Error " + data.code, data.description, "warning")
                    console.error(data.description);
                    break;
            }
        }

        if ($('#formRegister').valid() && employer !== "none" && $('input[type=checkbox]').prop('checked')) {
            grecaptcha.execute('6LfM2LYUAAAAAIOaYwCRbxSyu2QG-KAtyTznY0Gu', { action: 'register' }).then(function (token) {
                $.ajax({
                    type: "GET",
                    dataType: 'json',
                    url: "./includes/register.php",
                    async: true,
                    data: {
                        "firstname": firstname,
                        "lastname": lastname,
                        "username": firstname.toLowerCase() + '.' + lastname.toLowerCase(),
                        "email": email,
                        "password": SHA512(password1),
                        "repeat": SHA512(password2),
                        "employerId": employer,
                        "vacDays": currentVacDays,
                        'token': token
                    },
                    contentType: "application/json; charset=utf-8",
                    success: function (data) {
                        callback(data);
                    }
                });
            });
        }

        return false;
    });
})
