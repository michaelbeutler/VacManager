var users;
var employers;

$(document).ready(function () {
    function fillTable(data) {
        switch (data.code) {
            case 200:
                // success
                users = data.users;
                employers = data.employers;

                $(data.users).each(function (index, user) {
                    var content = '<td>' + user.id + '</td>';
                    content += '<td>' + user.username + '</td>';
                    content += '<td><a href="mailto:' + user.email + '">' + user.email + '</a></td>';
                    content += '<td>' + user.firstname + '</td>';
                    content += '<td>' + user.lastname + '</td>';
                    content += '<td>' + user.create_date + '</td>';
                    content += '<td>' + user.update_date + '</td>';
                    var element = $('<tr class="user-entry" onclick="showUser(this);">' + content + '</tr>');
                    $(element).data("user", user);
                    $('#tableUser tbody').append(element);
                });

                $('#tableUser').dataTable();
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
        url: "./includes/admin.php",
        async: true,
        contentType: "application/json; charset=utf-8",
        success: function (data) {
            fillTable(data);
        }
    });
});

function showUser(element) {
    $('.user-entry').each(function (index, element) {
        $(element).removeClass('active');
    });
    $(element).addClass('active');
    var user = $(element).data("user");

    $('.selected-username').text(user.username);

    var employer = employers.find(function (element) { return element.id == user.employer_id });
    $('.selected-employer-name').html('<i class="fa fa-map-marker"> ' + employer.name);

    $('#logInAsOtherUser').prop('disabled', false);
    $('#logInAsOtherUser').click(function () {
        console.log(user.username, user.password);
        logInAsOtherUser(user);
    });
}

function logInAsOtherUser(user) {
    swal({
        title: "Are you sure?",
        text: "You will be logged in as " + user.username + "!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes",
        closeOnConfirm: false
    }, function () {
        $.ajax({
            type: "GET",
            dataType: 'json',
            url: "./includes/login_as_other_user.php",
            async: true,
            data: {
                "id": user.id
            },
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                switch (data.code) {
                    case 200:
                        // success
                        swal("Account changed", "You will be transferd in 5 sec!", "success");
                        setTimeout(function () { window.location.replace("index.php"); }, 5000);
                        break;
                    case 405:
                        $('#logInAsOtherUser').prop('disabled', true);
                        break;
                    default:
                        console.error(data.description);
                        break;
                }
            }
        });
    });
}