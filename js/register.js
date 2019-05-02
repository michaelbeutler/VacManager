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

    $("#firstname").rules("add", {
        required: true,
        minlength: 2,
        maxlength: 45,
        messages: {
            required: "Please provide a firstname",
            minlength: "Your firstname must be at least 2 characters long",
            maxlength: "Your firstname can be max 45 characters long"
        }
    });

    $("#lastname").rules("add", {
        required: true,
        minlength: 2,
        maxlength: 45,
        messages: {
            required: "Please provide a lastname",
            minlength: "Your lastname must be at least 2 characters long",
            maxlength: "Your lastname can be max 45 characters long"
        }
    });

    $("#email").rules("add", {
        required: true,
        minlength: 6,
        maxlength: 45,
        messages: {
            required: "Please provide a email",
            minlength: "Your email must be at least 2 characters long",
            maxlength: "Your email can be max 45 characters long"
        }
    });

    $("#password").rules("add", {
        required: true,
        minlength: 5,
        maxlength: 30,
        messages: {
            required: "Please provide a password",
            minlength: "Your password must be at least 2 characters long",
            maxlength: "Your password can be max 45 characters long"
        }
    });

    $("#confirm_password").rules("add", {
        required: true,
        messages: {
            required: "Please confirm your password"
        }
    });

    $("#employer").rules("add", {
        required: true,
        messages: {
            required: "Please provide a employer"
        }
    });

    $("#agree").rules("add", {
        required: true,
        messages: {
            required: "Please accept our term's"
        }
    });

    // validate signup form on keyup and submit
    $('#formRegister').validate({
    });

    $('#formRegister').submit(function (e) {
        e.preventDefault();

        var firstname = $('#firstname').val();
        var lastname = $('#lastname').val();
        var email = $('#email').val();
        var password1 = $('#password').val();
        var password2 = $('#confirm_password').val();
        var employer = $('#employer').val();
        //var currentVacDays = $('#currentVacDays').val();

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
                    "vacDays": 25
                },
                contentType: "application/json; charset=utf-8",
                success: function (data) {
                    callback(data);
                }
            });
        }

        return false;
    });
})
