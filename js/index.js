$(document).ready(function () {
    getContingent(new Date().getFullYear(), function (data) {
        if (data.code == 200) {
            $('#vacDaysUsedPanel').text(data.used);
            $('#vacDaysLeftPanel').text(data.left);
        }
    });
});