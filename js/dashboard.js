$(document).ready(function () {
    refreshDashboard();

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
            { data: 'status' }
        ],
        paging: false,
        searching: false,
        info: false,
        responsive: true,
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
            }
        ]
    });
});

function refreshDashboard() {
    getContingent(new Date().getFullYear(), function (data) {
        if (data.code == 200) {
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
