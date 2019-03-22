$(document).ready(function(){
    $('#formChangePassword').submit(function(e){
        e.preventDefault();
        
        var password = $('#passwordChangePassword').val();
        var repeat = $('#repeatChangePassword').val()

        function callback(data) {
            switch (data.code) {
                case 200:
                    // success
                    window.location.replace("./bin/logout.php");
                    break;
                default:
                    alert(data.description);
                    console.error(data.description);
                    break;
            }
        }

        if (password == repeat) {
            $.ajax({
                type: "GET",
                dataType: 'json',
                url: "./bin/changePassword.php",
                async: true,
                data: {
                    "password": SHA512(password),
                    "repeat": SHA512(repeat)
                },
                contentType: "application/json; charset=utf-8",
                success: function (data) {
                    callback(data);
                }
            });
        }

        return false;
    });
});