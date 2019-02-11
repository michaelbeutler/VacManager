$(document).ready(function () {
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
});