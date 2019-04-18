function setVacationTable(table) {
    $(table).dataTable({
        ajax: {
            url: "./includes/new/vacation.inc.php?action=GET_ALL_VACATIONS&view=NOT_ACCEPTED",
            type: "GET",
            dataSrc: 'data'
        },
        columns: [
            { data: 'user.username' },
            { data: 'start' },
            { data: 'end' },
            { data: 'days' },
            { data: 'id' }

        ],
        paging: true,
        responsiv: true,
        searching: true,
        info: true,
        columnDefs: [
            {
                targets: 4,
                orderData: false,
                searchable: false,
                render: function (data, type, row) {
                    return '<button onclick="acceptVacation(' + data + ')" type="button" class="btn btn-success btn-sm"> <i class="fa fa-check"></i> </button> <button onclick="refuseVacation(' + data + ')" type="button" class="btn btn-danger btn-sm"> <i class="fa fa-gavel"></i> </button>';
                }
            }
        ]
    });
}

function setVacationTableDetail(table) {
    $(table).dataTable({
        ajax: {
            url: "./includes/new/vacation.inc.php?action=GET_ALL_VACATIONS&view=NOT_ACCEPTED",
            type: "GET",
            dataSrc: 'data'
        },
        columns: [
            { data: 'user.username' },
            { data: 'start' },
            { data: 'end' },
            { data: 'days' },
            { data: 'status' },
            { data: 'create_date' },
            { data: 'id' }

        ],
        paging: true,
        responsiv: true,
        searching: true,
        info: true,
        columnDefs: [
            {
                targets: 4,
                render: function (data, type, row) {
                    switch (data) {
                        case "Pending":
                            data = '<span class="label label-warning">Pending</span>';
                            break;;
                        case "Accepted":
                            data = '<span class="label label-success">Accepted</span>';
                            break;;
                        case "Refused":
                            data = '<span class="label label-danger">Refused</span>';
                            break;;
                        case "Canceled":
                            data = '<span class="label label-danger">Canceled</span>';
                            break;;
                        default:
                            data = '<span class="label label-danger">ERROR</span>';
                            break;;
                    }
                    return data;
                }
            },
            {
                targets: 6,
                orderData: false,
                searchable: false,
                render: function (data, type, row) {
                    return '<button onclick="acceptVacation(' + data + ')" type="button" class="btn btn-success btn-sm"> <i class="fa fa-check"></i> </button> <button onclick="refuseVacation(' + data + ')" type="button" class="btn btn-danger btn-sm"> <i class="fa fa-gavel"></i> </button>';
                }
            }
        ]
    });
}

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