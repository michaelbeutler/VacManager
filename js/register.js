$(document).ready(function () {
    // Fill employer input
    getEmployerList(function (data) {
        switch (data.code) {
            case 200:
                $('#selectEmployer').prop('disabled', false);
                $(data.employer).each(function (index, element) {
                    $('#selectEmployer').append('<option value="' + element.id + '">' + element.name + '</option>')
                });
                break;
            case 201:
                // 0 results
                $('#selectEmployer').prop('disabled', true);
                break;
            default:
                $('#selectEmployer').prop('disabled', true);
                console.error(data.description);
                break;
        }
    });

    $('#formRegister').submit(function (e) {
        e.preventDefault();

        var firstname = $('#inputFirstname').val();
        var lastname = $('#inputLastname').val();
        var email = $('#inputEmail').val();
        var password1 = $('#inputPassword').val();
        var password2 = $('#inputRepeatPassword').val();
        var employer = $('#selectEmployer').val();
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
