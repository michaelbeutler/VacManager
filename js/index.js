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
});

function refreshDashboard() {
    getContingent(new Date().getFullYear(), function (data) {
        if (data.code == 200) {
            $('#vacDaysUsedPanel').text(data.used);
            $('#vacDaysLeftPanel').text(data.left);
            if (data.left <= 0) {
                $('#vacDaysLeftIcon').removeClass('ion-checkmark');
                $('#vacDaysLeftIcon').removeClass('text-success');
                $('#vacDaysLeftIcon').addClass('ion-close');
                $('#vacDaysLeftIcon').addClass('text-danger');
            }
        }
    });
}
