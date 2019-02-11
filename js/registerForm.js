$(document).ready(function () {
    // Fill employer input
    getEmployerList(function (data) {
        switch (data.code) {
            case 200:
                $('#employer').prop('disabled', false);
                $(data.employer).each(function (index, element) {
                    $('#employer').append('<option value="' + element.id + '">' + element.name + '</option>')
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

    $('#class').prop('disabled', true);
    loadJobForm(function (data) {
        $(data).each(function (index, element) {
            $('#profession').append('<option value="' + element.beruf_id + '">' + element.beruf_name + '</option>')
        });
    });

    $('#profession').change(function () {
        $('#class').html('<option>...</option>');
        if ($('#profession').val() != null && $('#profession').val() != '...') {
            $('#class').prop('disabled', false);
            loadClassForm($('#profession').val(), function (data) {
                $(data).each(function (index, element) {
                    $('#class').append('<option data-name="' + element.klasse_name + '" data-longname="' + element.klasse_longname + '" value="' + element.klasse_id + '">' + element.klasse_name + '</option>')
                });
            });
        } else {
            $('#class').prop('disabled', true);
        }
    })

    $('#lastname, #firstname').change(function () {
        if ($(this).val() == '') {
            $('#username').val('vorname.nachname');
        } else {
            $('#username').val($('#firstname').val().toLowerCase() + "." + $('#lastname').val().toLowerCase());
        }
    });

    $('#formRegister').submit(function (e) {
        e.preventDefault();

        var firstname = $('#firstname').val();
        var lastname = $('#lastname').val();
        var birthdate = $('#birthdate').val();
        var username = $('#username').val();
        var password1 = $('#password1').val();
        var password2 = $('#password2').val();
        var workstart = $('#workstart').val();
        var workend = $('#workend').val();
        var iclass = $('#class').val();
        var iclassLongname = $('#class').find(':selected').data('longname');
        var iclassName = $('#class').find(':selected').data('name');
        var employer = $('#employer').val();

        function callback(data) {
            switch (data.code) {
                case 200:
                    // success
                    window.location.replace("login.html");
                    break;
                default:
                    $('#modalBodyError').html('<h5>Code ' + data.code + '</h5><p>' + data.description + '</p>');
                    $('#errorModalCenter').modal('show');
                    console.error(data.description);
                    break;
            }
        }

        var valid = true;
        if (valid) {
            $.ajax({
                type: "GET",
                dataType: 'json',
                url: "./bin/register.php",
                async: true,
                data: {
                    "inputFirstname": firstname,
                    "inputLastname": lastname,
                    "inputBirthdate": birthdate,
                    "inputUsername": username,
                    "inputPassword": SHA512(password1),
                    "inputRepeatPassword": password2,
                    "inputStartWork": workstart,
                    "inputEndWork": workend,
                    "inputClassId": iclass,
                    "inputClassLongname": iclassLongname,
                    "inputClassName": iclassName,
                    "inputEmployerId": employer
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
