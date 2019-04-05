function loadVacType(callback) {
    $.ajax({
        type: "POST",
        dataType: 'jsonp',
        url: "./includes/get_vacation_type.php",
        async: true,
        contentType: "application/json; charset=utf-8",
        success: function (data) {
            callback(data);
        }
    });
}

function setVacTypes(data, element) {
    confirm();
    $(element).html('');
    $(data.types).each(function (data) {
        $(element).append('<option value="' + data.types.id + '">' + data.types.name + '</option>');
    });
}