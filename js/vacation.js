$(document).ready(function () {
    function callback(data) {
        $('#vacationTypeSelection').html('');
        $(data.types).each(function (index, type) {
            $('#vacationTypeSelection').append('<option value="' + type.id + '">' + type.name + '</option>');
        });
    };

    $.ajax({
        type: "GET",
        dataType: 'json',
        url: "./includes/get_vacation_type.php",
        async: true,
        contentType: "application/json; charset=utf-8",
        success: function (data) {
            callback(data);
        }
    });
    var table = $('#vacationTable').on('init.dt', function () {
        $('.btn-vacation-delete').click(function () {
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
                    url: "./includes/vacation.php",
                    async: true,
                    data: {
                        "action": "deleteVacation",
                        "id": id
                    },
                    contentType: "application/json; charset=utf-8",
                    success: function (data) {
                        switch (data.code) {
                            case 200:
                                // success
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
    }).dataTable({
        ajax: {
            url: "./includes/vacation.php?action=getVacations",
            type: "POST"
        },
        paging: true,
        responsiv: true,
        searching: true,
        info: true,
        columnDefs: [
            {
                targets: 5,
                render: function (data, type, row) {
                    switch (data) {
                        case "0":
                            data = '<span class="label label-warning">Pending</span>';
                            break;;
                        case "1":
                            data = '<span class="label label-success">Accepted</span>';
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
                    return '<button data-id="' + data + '" type="button" class="btn btn-secondary btn-bordered waves-effect btn-sm btn-vacation-delete"> <i class="fa fa-trash"></i> </button>';
                }
            }
        ]
    });
})