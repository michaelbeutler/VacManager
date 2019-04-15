$(document).ready(function () {
    $('#addVacForm').submit(function (e) {
        e.preventDefault();

        var title = $('#addVacTitle').val();
        var description = $('#addVacDescription').val();
        var start = $('#addVacStart').val();
        var end = $('#addVacEnd').val();
        var numDays = $('#addVacNumDay').val();
        var vacType = $('#vacationTypeSelection').val();

        function callback(data) {
            switch (data.code) {
                case 200:
                    // success
                    $('#vac-add-modal').modal('hide');
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
                "title": title,
                "description": description,
                "start": start,
                "end": end,
                "days": numDays,
                "vacation_type_id": vacType
            },
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                callback(data);
            }
        });

        return false;
    });
});