function setVacationTypeInput(element) {
    var input = $(element);

    function callback(data) {
        $(input).html('');
        $(data.data).each(function (index, type) {
            $(input).append('<option value="' + type.id + '">' + type.name + '</option>');
        });
    };

    $.ajax({
        type: "GET",
        dataType: 'json',
        url: "./includes/new/vacation_type.inc.php",
        data: {
            action: 'GET_ALL_VACATION_TYPES'
        },
        async: true,
        contentType: "application/json; charset=utf-8",
        success: function (data) {
            callback(data);
        }
    });
}