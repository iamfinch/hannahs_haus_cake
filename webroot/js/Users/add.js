$(document).ready(function() {
    const delay = (callback, wait) => {
        let timeoutId = null;
        return (...args) => {
            window.clearTimeout(timeoutId);
            timeoutId = window.setTimeout(() => {
                callback(...args);
            }, wait);
        };
    };

    const validateForm = delay(
        (mouseEvent) => {
            const password = $("#password").val();
            const lowerCaseRegex = /[A-Z]/;
            const upperCaseRegex = /[a-z]/;
            const numbsCaseRegex = /[0-9]/;
            const specsCaseRegex = /[^A-Za-z0-9]/;
            
            let valid = true;

            if (lowerCaseRegex.test(password)) {
                $("#lowerCheck").removeClass("glyphicon-remove")
                    .addClass("glyphicon-ok")
                    .css("color", "green");
            } else {
                $("#lowerCheck").removeClass("glyphicon-ok")
                    .addClass("glyphicon-remove")
                    .css("color", "red");
                valid = false;
            }

            if (upperCaseRegex.test(password)) {
                $("#upperCheck").removeClass("glyphicon-remove")
                    .addClass("glyphicon-ok")
                    .css("color", "green");
            } else {
                $("#upperCheck").removeClass("glyphicon-ok")
                    .addClass("glyphicon-remove")
                    .css("color", "red")
                valid = false;
            }

            if (numbsCaseRegex.test(password)) {
                $("#numberCheck").removeClass("glyphicon-remove")
                    .addClass("glyphicon-ok")
                    .css("color", "green");
            } else {
                $("#numberCheck").removeClass("glyphicon-ok")
                    .addClass("glyphicon-remove")
                    .css("color", "red")
                valid = false;
            }

            if (specsCaseRegex.test(password)) {
                $("#specialCheck").removeClass("glyphicon-remove")
                    .addClass("glyphicon-ok")
                    .css("color", "green");
            } else {
                $("#specialCheck").removeClass("glyphicon-ok")
                    .addClass("glyphicon-remove")
                    .css("color", "red");
                valid = false;
            }

            if (password.length >= 8) {
                $("#lengthCheck").removeClass("glyphicon-remove")
                    .addClass("glyphicon-ok")
                    .css("color", "green");
            } else {
                $("#lengthCheck").removeClass("glyphicon-ok")
                    .addClass("glyphicon-remove")
                    .css("color", "red")
                valid = false;
            }

            if (!$("#fname").val()) {
                valid = false;
            }

            if (!$("#lname").val()) {
                valid = false;
            }

            if (!$("#email").val()) {
                valid = false;
            }

            if (!$("#countryid").val()) {
                valid = false;
            }

            if (!$("#stateid").val()) {
                valid = false;
            }

            if (!$("#housingtypeid").val()) {
                valid = false;
            }

            if (!$("#address1").val()) {
                valid = false;
            }

            if (
                $("#housingtypeid").val() != 1 
                && $("#housingtypeid").val()
                && !$("#address2").val()
            ) {
                valid = false;
            }

            if (!$("#zipcode").val()) {
                valid = false;
            }

            if (valid && password == $("#confirmpassword").val()) {
                $("button[type=submit]").removeAttr("disabled")
            } else {
                $("button[type=submit]").attr("disabled", "disabled")
            }
        },
        350
    );

    //validates our password through a debouncer to ensure we reduce amounts of calls
    $("input").on("change keyup", validateForm)
    $("select").on("change keyup", validateForm)


    $("#confirmpassword").on("keyup", function() {
        validateForm();
    });

    //hiding them on load instead of through php as we don't have the options for states initially
    //and we don't need the second address unless the user sets the housing type to a specific setting
    $("#stateid").parent().hide();
    $("#address2").parent().hide();
    $("button[type=submit]").attr("disabled", "disabled")

    $("#countryid").on("change", function (event) {
        const countryId = Number.parseInt($(this).val());
        if (!countryId) {
            $("#stateid").parent().hide()
        } else {
            const callback = (result) => {
                if (!result) {
                    console.error("something went wrong");
                }
                
                $("#stateid").html(result);
                $("#stateid").parent().show()
            };

            const errorCallback = (data) => {
                console.error("something went wrong");
                $("#stateid").parent().hide()
            };

            getStates(countryId, callback, errorCallback)
        }
    });

    $("#housingtypeid").on("change", function() {
        if ($(this).val() != 1 && $(this).val()) {
            $("#address2").parent()
                .show()
                .addClass("required");
        } else {
            $("#address2").parent()
                .hide()
                .removeClass("required");
        }
    });
});