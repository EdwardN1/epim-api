jQuery(document).ready(function ($) {
    /*$( "form#post #publish" ).hide();
    $( "form#post #publish" ).after("<input type=\'button\' value=\'Publish/Update\' class=\'sb_publish button-primary\' /><span class=\'sb_js_errors\'></span>");*/
    $("form#post").validate(
        {
            rules: {
                post_title: {
                    required: true
                },
                wpam_accounts_email: {
                    required: true,
                    email: true
                }
            }
        }
    );

    /*$( ".sb_publish" ).click(function() {
        var error = false
        //js validation here

        if (!error) {
            $( "form#post #publish" ).click();
        } else {
            $(".sb_js_errors").text("There was an error on the page and therefore this page can not yet be published.");
        }
    });*/
});
