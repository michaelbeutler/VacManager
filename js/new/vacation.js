var editVacationForm;
function setAddVacationForm(form) {
    var title = $(form).find('.add-vacation-title');
    var description = $(form).find('.add-vacation-description');
    var start = $(form).find('.add-vacation-start');
    var end = $(form).find('.add-vacation-end');
    var days = $(form).find('.add-vacation-days');
    var type = $(form).find('.add-vacation-type');

    $(form).on('submit', function (e) {
        e.preventDefault();

        console.log($(this).valid());
        if (!$(this).valid()) {
            return;
        }

        function callback(data) {
            switch (data.code) {
                case 200:
                    // success
                    $('.add-vacation-modal').modal('hide');
                    break;
                default:
                    console.error(data.description);
                    break;
            }
        }

        $.ajax({
            type: "GET",
            dataType: 'json',
            url: "./includes/new/vacation.inc.php",
            async: true,
            data: {
                "action": "CREATE_VACATION",
                "title": $(title).val(),
                "description": $(description).val(),
                "start": $(start).val(),
                "end": $(end).val(),
                "days": $(days).val(),
                "vacation_type_id": $(type).val()
            },
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                callback(data);
            }
        });

        return false;
    });
}

function setEditVacationForm(form) {
    editVacationForm = $(form);
    var title = $(form).find('.edit-vacation-title');
    var description = $(form).find('.edit-vacation-description');

    $(form).on('submit', function (e) {
        e.preventDefault();

        function callback(data) {
            switch (data.code) {
                case 200:
                    // success
                    $('.edit-vacation-modal').modal('hide');
                    swal("Vacation/Request changed", "Your vacation is now updated!", "success");
                    break;
                default:
                    console.error(data.description);
                    break;
            }
        }

        var id = $(form).data('id');

        swal({
            title: "Are you sure?",
            text: "Do you want to save the changes this vacation/request?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            closeOnConfirm: false
        }, function () {
            $.ajax({
                type: "GET",
                dataType: 'json',
                url: "./includes/new/vacation.inc.php",
                async: true,
                data: {
                    "action": "EDIT_VACATION",
                    "id": id,
                    "title": $(title).val(),
                    "description": $(description).val()
                },
                contentType: "application/json; charset=utf-8",
                success: function (data) {
                    callback(data);
                }
            });
        });

        return false;
    });
}

function fillEditVacationForm(form, id) {
    $.ajax({
        type: "GET",
        dataType: 'json',
        url: "./includes/new/vacation.inc.php",
        async: true,
        data: {
            "action": "GET_VACATION",
            "id": id
        },
        contentType: "application/json; charset=utf-8",
        success: function (data) {
            var vacation = data.data;
            $(form).data('id', id);

            var title = $(form).find('.edit-vacation-title');
            var description = $(form).find('.edit-vacation-description');

            title.val(vacation.title);
            description.val(vacation.description);

            $(form).parent().parent().parent().modal('show')
        }
    });
}

function setVacationTable(table) {
    var table = $(table).on('init.dt', function () {
        $('.btn-vacation-delete').click(function () {
            var btn = $(this);
            var id = $(this).data('id');
            swal({
                title: "Are you sure?",
                text: "Do you want to delete/cancel this vacations/request?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    type: "GET",
                    dataType: 'json',
                    url: "./includes/new/vacation.inc.php",
                    async: true,
                    data: {
                        action: "CANCEL_VACATION",
                        id: id
                    },
                    contentType: "application/json; charset=utf-8",
                    success: function (data) {
                        switch (data.code) {
                            case 200:
                                // success
                                $(btn).parent().parent().fadeOut();
                                swal("Vacation/Request canceled", "Your vacations are canceled now!", "success");
                                break;
                            default:
                                console.error(data.description);
                                break;
                        }
                    }
                });
            });
        });
        $('.btn-vacation-edit').click(function (e) {
            var btn = $(this);
            var id = $(this).data('id');
            fillEditVacationForm($(editVacationForm), id);
        });
    }).dataTable({
        ajax: {
            url: "./includes/new/vacation.inc.php?action=GET_ALL_VACATIONS",
            type: "GET",
            dataSrc: 'data'
        },
        columns: [
            { data: 'title' },
            { data: 'description' },
            { data: 'start' },
            { data: 'end' },
            { data: 'days' },
            { data: 'status' },
            { data: 'user_status.username' },
            { data: 'vacation_type.name' },
            { data: 'id' }
        ],
        paging: true,
        responsiv: true,
        searching: true,
        info: true,
        columnDefs: [
            {
                targets: 5,
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
                targets: 7,
                orderData: false,
                searchable: false,
                render: function (data, type, row) {
                    switch (data) {
                        case "Ferien":
                            data = '<span class="label label-default">' + data + '</span>';
                            break;;
                        default:
                            data = '<span class="label label-danger">ERROR</span>';
                            break;;
                    }
                    return data;
                }
            },
            {
                targets: 8,
                orderData: false,
                searchable: false,
                render: function (data, type, row) {
                    return '<button data-id="' + data + '" type="button" class="btn btn-secondary btn-bordered waves-effect btn-sm btn-vacation-edit"><i class="fa fa-edit"></i></button> <button data-id="' + data + '" type="button" class="btn btn-danger btn-bordered waves-effect btn-sm btn-vacation-delete"><i class="fa fa-trash"></i></button>';
                }
            }
        ]
    });
}