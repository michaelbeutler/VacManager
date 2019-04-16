$(document).ready(function () {
    function fillTable(data) {
        if (data.request !== null) {
            $('#tableVacationRequests tbody').empty();
        }
        $(data.requests).each(function (index, request) {
            $('#tableVacationRequests').find('tbody').append('<tr id="request' + request.id + '"><td>' + request.username + '</td><td>' + request.title + '</td><td>' + request.start + ' - ' + request.end + '</td><td>' + request.days + '</td><td>' + request.create_date + '</td><td><button onclick="acceptVacation(' + request.id + ')" class="btn btn-success">Accept</button> <button onclick="refuseVacation(' + request.id + ')" class="btn btn-danger">Refuse</button></td></tr>')
        });
    }
    $.ajax({
        type: "GET",
        dataType: 'json',
        url: "./includes/get_not_accepted_vacations.php",
        async: true,
        contentType: "application/json; charset=utf-8",
        success: function (data) {
            fillTable(data);
        }
    });
});

function acceptVacation(id) {
    swal({
        title: "Are you sure?",
        text: "Do you really want to accept this vacation request?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#398439",
        confirmButtonText: "Accept",
        closeOnConfirm: false
    }, function () {
        $.ajax({
            type: "GET",
            dataType: 'json',
            url: "./includes/new/vacation.inc.php",
            async: true,
            data: {
                "action": "ACCEPT_VACATION",
                "id": id
            },
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                switch (data.code) {
                    case 200:
                        // success
                        swal("Success", "You accepted the request!", "success");
                        $('#request' + id).fadeOut();
                        break;
                    case 201:
                        // success but cant send mail
                        swal("Success", "You accepted the request!", "success");
                        $('#request' + id).fadeOut();
                        break;
                    default:
                        console.error(data.description);
                        break;
                }
            }
        });
    });
}

function refuseVacation(id) {
    swal({
        title: "Are you sure?",
        text: "Do you really want to refuse this vacation request?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Refuse",
        closeOnConfirm: false
    }, function () {
        $.ajax({
            type: "GET",
            dataType: 'json',
            url: "./includes/new/vacation.inc.php",
            async: true,
            data: {
                "action": "REFUSE_VACATION",
                "id": id
            },
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                switch (data.code) {
                    case 200:
                        // success
                        swal("Success", "You refused the request!", "success");
                        $('#request' + id).fadeOut();
                        break;
                    default:
                        console.error(data.description);
                        break;
                }
            }
        });
    });
}