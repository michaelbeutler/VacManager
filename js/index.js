$(document).ready(function () {
    refreshDashboard();
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

    $('#vacationTable').dataTable({
        ajax: {
            url: "./includes/new/vacation.inc.php?action=GET_ALL_VACATIONS&view=MIN",
            type: "POST",
            dataSrc: 'data'
        },
        columns: [
            { data: 'title' },
            { data: 'start' },
            { data: 'end' },
            { data: 'days' },
            { data: 'accepted' }
        ],
        paging: false,
        searching: false,
        info: false,
        columnDefs: [
            {
                targets: 4,
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
            }
        ]
    });
});

function refreshDashboard() {
    getContingent(new Date().getFullYear(), function (data) {
        if (data.code == 200) {
            console.log(data);
            $('#vacDaysUsedPanel').text(data.data.used_days);
            $('#vacDaysLeftPanel').text(data.data.left_days);
            if (data.data.left_days <= 0) {
                $('#vacDaysLeftIcon').removeClass('ion-checkmark');
                $('#vacDaysLeftIcon').removeClass('text-success');
                $('#vacDaysLeftIcon').addClass('ion-close');
                $('#vacDaysLeftIcon').addClass('text-danger');
            }
        }
    });
}
