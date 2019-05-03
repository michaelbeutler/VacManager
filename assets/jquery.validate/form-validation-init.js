
/**
* Theme: Velonic Admin Template
* Author: Coderthemes
* Form Validator
*/

!function ($) {
    "use strict";

    var FormValidator = function () {
        this.$signupForm = $("#formRegister"),
            this.$loginForm = $('#formLogin')
    };

    //init
    FormValidator.prototype.init = function () {
        // validate the comment form when it is submitted
        this.$signupForm.validate({
            rules: {
                email: {
                    email: true,
                    required: true,
                    minlength: 6,
                    maxlength: 45,
                },
                firstname: {
                    required: true,
                    minlength: 2,
                    maxlength: 45
                },
                lastname: {
                    required: true,
                    minlength: 2,
                    maxlength: 45
                },
                password: {
                    required: true,
                    minlength: 5,
                    maxlength: 30
                },
                confirm_password: {
                    required: true,
                    equalTo: "#password"
                },
                employer: {
                    required: true
                },
                currentVacDays: {
                    required: true,
                    digits: true
                },
                agree: {
                    required: true
                }
            },
            messages: {
                email: {
                    required: "Please provide a email",
                    minlength: "Your email must be at least 6 characters long",
                    maxlength: "Your email can be max 45 characters long"
                },
                firstname: {
                    required: "Please provide a firstname",
                    minlength: "Your firstname must be at least 2 characters long",
                    maxlength: "Your firstname can be max 45 characters long"
                },
                lastname: {
                    required: "Please provide a lastname",
                    minlength: "Your lastname must be at least 2 characters long",
                    maxlength: "Your lastname can be max 45 characters long"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long",
                    maxlength: "Your password can be max 45 characters long"
                },
                confirm_password: {
                    required: "Please confirm your password",
                    equalTo: "Password's do not match"
                },
                employer: {
                    required: "Please provide a employer"
                },
                agree: {
                    required: "Please accept our term's"
                }
            }
        });

        // validate login form when it is submitted
        this.$loginForm.validate({
            rules: {
                username: {
                    required: true
                },
                password: {
                    required: true
                }
            }
        });

        // propose username by combining first- and lastname
        $("#username").focus(function () {
            var firstname = $("#firstname").val();
            var lastname = $("#lastname").val();
            if (firstname && lastname && !this.value) {
                this.value = firstname + "." + lastname;
            }
        });

        //code to hide topic selection, disable for demo
        var newsletter = $("#newsletter");
        // newsletter topics are optional, hide at first
        var inital = newsletter.is(":checked");
        var topics = $("#newsletter_topics")[inital ? "removeClass" : "addClass"]("gray");
        var topicInputs = topics.find("input").attr("disabled", !inital);
        // show when newsletter is checked
        newsletter.click(function () {
            topics[this.checked ? "removeClass" : "addClass"]("gray");
            topicInputs.attr("disabled", !this.checked);
        });

    },
        //init
        $.FormValidator = new FormValidator, $.FormValidator.Constructor = FormValidator
}(window.jQuery),


    //initializing 
    function ($) {
        "use strict";
        $.FormValidator.init()
    }(window.jQuery);