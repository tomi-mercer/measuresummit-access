
jQuery(function ($) {
    if( $('#login_error').length ) {
        $('#login_error').wrapInner( "<div class='error-msg'></div>");
    }

    $('#loginform').append( '<div class="signup">Don’t have an account? <a href="https://measuresummit.com/">Sign up</a></div>' );

});