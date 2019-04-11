$(document).ready(function () {
    $('#addVacForm').submit(function (e) {
        e.preventDefault();

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
            url: "./includes/add_vacation.php",
            async: true,
            data: {
                "title": description,
                "start": start,
                "end": end,
                "numDays": numDays,
                "vacType": vacType
            },
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                callback(data);
            }
        });

        return false;
    });
});