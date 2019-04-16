function getContingent(year, callback) {
    $.ajax({
        type: "GET",
        dataType: 'json',
        url: "./includes/new/contingent.inc.php",
        async: true,
        contentType: "application/json; charset=utf-8",
        success: function (data) {
            switch (data.code) {
                case 200:
                    // success
                    callback(data);
                    break;
                case 250:
                    alert(data.description);
                    break;
                default:
                    console.error(data.description);
                    break;
            }
        }
    });
}