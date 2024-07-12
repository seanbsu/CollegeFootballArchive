$(document).ready(function() {

    ///////////////////////////////////////////////////////////////////
    //Display controls for the change password forms
    ///////////////////////////////////////////////////////////////////
    // Check if the query string parameter "show_reset_form" is present
    const showResetForm = getParameterByName("show_reset_form");
    const emailSent = getParameterByName("email_sent");
    const passwordChanged = getParameterByName("password_changed");

    // Hide all forms initially
    $(".change-password, .password-changed, .popup-email-notification-msg").hide();


    // Show the form based on the "show_reset_form" value
    if (showResetForm === "1") {
        $(".change-password").show();
        $(".change-password-form").hide();
    }

    // Show the email sent form if "email_sent" is set to 1
    if (emailSent === "1") {
        $(".popup-email-notification-msg").show();
        $(".change-password-form").hide();
    }

    if(passwordChanged === "1"){
        $(".password-changed").show();
        $(".change-password-form").hide();
        $("#password-changed").show();
    }
    function checkPasswordMatch() {
        const newPassword = $.trim($("#new-password").val());
        const confirmPassword = $.trim($("#confirm-password").val());
        const passwordError = $(".password-error");
        const passwordMatch = $(".passwords-match");

        if (newPassword !== confirmPassword) {
            passwordError.show();
        } else if(newPassword === confirmPassword) {
            passwordError.hide();
            passwordMatch.show();
        } else{
            passwordError.show();
            passwordMatch.hide();
        }
    }
    $("#confirm-password").on("input", checkPasswordMatch);

    // Function to extract query string parameters
    function getParameterByName(name) {
        const url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        const regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)");
        const results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return "";
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }


    ///////////////////////////////////////////////////////////////////
    //Display controls for the Teams Page
    ///////////////////////////////////////////////////////////////////
    // Function to handle live search
    $(document).ready(function() {
        $("#team-search").on("input", function() {
            const searchTerm = $(this).val().toLowerCase();

            $.ajax({
                url: 'teams-functions.php',
                type: 'GET',
                data: {
                    search: searchTerm
                },
                success: function(data) {
                    // Replace the table with data from the PHP script
                    $(".teams-table").html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus, errorThrown);
                }
            });
        });
    });

    ///////////////////////////////////////////////////////////////////
    //Display controls for the Signup Page
    ///////////////////////////////////////////////////////////////////

    // Function to hide all error messages
    function hideAllErrorMessages() {
        $(".email-error").hide();
        $("#password-error").hide();
        $("#old-password-error").hide();
        $("#terms-error").hide();
        $("#email_exists").hide();

    }

// Function to show the error message
    function showErrors(errorCodes) {
        hideAllErrorMessages();

        // Show the error messages corresponding to the error codes
        for (var i = 0; i < errorCodes.length; i++) {
            $("#" + errorCodes[i]).show();
        }
    }

// Parse the query string
    var queryString = window.location.search.substring(1);
    var params = queryString.split('&');
    var messages = {};

    for (var i = 0; i < params.length; i++) {
        var param = params[i].split('=');
        messages[param[i]] = decodeURIComponent(param[1]);
    }

    var errorCodes = Object.values(messages);

// Display errors based on query string parameters
    if (errorCodes.length > 0) {
        showErrors(errorCodes);
    }


        // display controls for the forms
    const signInVerification = getParameterByName("valid_token");
    const signUpEmailSent = getParameterByName("email_sent");
    const signUpEmailResent = getParameterByName("email_resent");

    $(" .popup-signup, .popup-signup-resend, .popup-email-resent, .sign-in-form-signup").hide();

    if (signInVerification === "1") {
        $(".sign-in-form-signup").show();
        $(".popup-signup-init").hide();
    }
    if(signUpEmailSent === "1") {
        $(".popup-signup").show();
        $(".popup-signup-init").hide();
        // Check if the email_not_found session variable is set
        if (typeof emailNotFound !== 'undefined' && emailNotFound) {
            // Display an error message in your "Resend Verification Email" form
            $(".popup-signup").hide();
            $(".popup-signup-resend").show();
            $("#resend-email-error").show();
        }
    }
    // resend email link click event
    $("#resend-email-link").click(function(e) {
        e.preventDefault(); // Prevent the default link behavior

        // Show the "popup-signup-resend" form and hide other forms
        $(".popup-signup-init, .popup-signup, .popup-email-resent, .sign-in-form-signup").hide();
        $(".popup-signup-resend").show();
    });

    if(signUpEmailResent === "1") {
        $(".popup-email-resent").show();
        $(".popup-signup-init").hide();
    }


    ///////////////////////////////////////////////////////////////////
    //Display controls for the Sign-In Page
    ///////////////////////////////////////////////////////////////////
    // Check if the "error" query string parameter is present
    const errorParam = getParameterByName("error");

    if (errorParam === "invalid-credentials") {
        // Display an error message on the sign-in form
        $("#login-error-msg").show();
    }

    ///////////////////////////////////////////////////////////////////
    //Display controls for adding User Favorite Teams
    ///////////////////////////////////////////////////////////////////

        // Function to handle the star icon click
    $(".favorite-star input[type='checkbox']").each(function() {
        const checkbox = $(this);
        const isChecked = checkbox.is(":checked");
        const starIcon = checkbox.next('span');

        // Set the star icon's color based on the initial checkbox state
        starIcon.css('color', isChecked ? 'gold' : 'transparent');

        checkbox.click(function() {
            const checkbox = $(this);
            const teamName = checkbox.closest(".main-indiv-team").find("h1").text().trim();

            const isChecked = checkbox.is(":checked");
            const starIcon = checkbox.next('span');

            // Make an AJAX request to add or remove the team from favorites
            $.ajax({
                url: 'add_remove_favorite_team.php',
                type: 'POST',
                data: {
                    action: isChecked ? 'add' : 'remove',
                    teamName: teamName
                },
                success: function(data) {
                    if (data === 'added') {
                        starIcon.css('color', 'gold');
                    } else if (data === 'removed') {
                        starIcon.css('color', 'transparent');
                    } else {
                        starIcon.css('color', 'blue');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus, errorThrown);
                }
            });
        });
    });

    ///////////////////////////////////////////////////////////////////
    //Display controls for Individual Team Page dropdown
    ///////////////////////////////////////////////////////////////////

    $('#season-select-team').on('change', function () {
        var selectedYear = $(this).val();
        var teamNameElement = document.querySelector('.name-nickname h1');

        var teamName = teamNameElement.textContent.trim();

        $.ajax({
            type: 'GET',
            url: 'fetch-opponents.php',
            data: { team: teamName, year: selectedYear },
            success: function (data) {

                $('.team-opponent-table tbody').html(data);
            },
            error: function () {
                alert('Error fetching opponent data from the server.');
            }
        });
    });

    ///////////////////////////////////////////////////////////////////
    //Display controls for Individual Team Page dropdowns
    ///////////////////////////////////////////////////////////////////

    var $selectedSeason, $selectedWeek;
    $('#season-select-seasons, #week-select').on('change', function () {
         selectedSeason = $('#season-select-seasons').val();
         selectedWeek = $('#week-select').val();

        $.ajax({
            type: 'GET',
            url: 'fetch-season-data.php',
            data: { year: selectedSeason, week: selectedWeek },
            success: function (data) {
                $('#breadcrumb-season').html(selectedSeason);
                $('#breadcrumb-week').html('Week '+ selectedWeek);
                $('.main-indiv-team h1').html(selectedSeason + ' Season');
                $('.team-opponent-table tbody').html(data);
                window.history.replaceState({}, document.title, window.location.pathname);
            },
            error: function () {
                alert('Error fetching data from the server.');
            }
        });
    });

});
