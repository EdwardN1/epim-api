jQuery(document).ready(function ($) {

    let currentURL = window.location.href;
    let currentUsername = $('form #title').val();
    let currentEmail = $('input#wpam_accounts_email').val();

    //var titleGood = false;

    if(currentURL.includes('/post-new.php')) {
        $("form#post").validate(
            {
                rules: {
                    post_title: {
                        required: true,
                        remote: {
                            url: wpam_ajax_object.ajax_url,
                            tyoe: "post",
                            data: {
                                action: "wpam_username_unique",
                                security: wpam_ajax_object.security,
                                username: ''
                            },
                            complete: function (data) {
                                /*let resp = JSON.parse(JSON.stringify(data));
                                if(resp.responseText=='true') titleGood=true;
                                window.console.log('titleGood = '+titleGood);*/
                            }
                        }

                    },
                    wpam_accounts_email: {
                        required: true,
                        email: true,
                        remote: {
                            url: wpam_ajax_object.ajax_url,
                            tyoe: "post",
                            data: {
                                action: "wpam_email_unique",
                                security: wpam_ajax_object.security,
                                email_address: ''
                            },
                            complete: function (data) {
                                /*let resp = JSON.parse(JSON.stringify(data));
                                if(resp.responseText=='true') titleGood=true;
                                window.console.log('titleGood = '+titleGood);*/
                            }
                        }
                    }
                }
            }
        );

        $( "form#post #publish" ).hide();
        $( "form#post #publish" ).after("<input type=\'button\' value=\'Publish\' id=\'New-Publish\' class=\'sb_publish button-primary\' /><br><br><span class=\'sb_js_errors\' style=\'color: red;\'></span>");

    }

    if(currentURL.includes('/post.php')) {
        $("form#post").validate(
            {
                rules: {
                    post_title: {
                        required: true,
                        remote: {
                            url: wpam_ajax_object.ajax_url,
                            tyoe: "post",
                            data: {
                                action: "wpam_username_unique",
                                security: wpam_ajax_object.security,
                                username: currentUsername
                            },
                            complete: function (data) {
                                /*let resp = JSON.parse(data);
                                window.console.log(JSON.stringify(data, null, 4));
                                window.console.log(resp);
                                window.console.log(resp.reponseJSON);*/
                            }
                        }

                    },
                    wpam_accounts_email: {
                        required: true,
                        email: true,
                        remote: {
                            url: wpam_ajax_object.ajax_url,
                            tyoe: "post",
                            data: {
                                action: "wpam_email_unique",
                                security: wpam_ajax_object.security,
                                email_address: currentEmail
                            },
                            complete: function (data) {
                                /*let resp = JSON.parse(data);
                                window.console.log(JSON.stringify(data, null, 4));
                                window.console.log(resp);
                                window.console.log(resp.reponseJSON);*/
                            }
                        }
                    }
                }
            }
        );

        $( "form#post #publish" ).hide();
        $( "form#post #publish" ).after("<input type=\'button\' value=\'Update\' id=\'New-Publish\' class=\'sb_publish button-primary\' /><br><br><span class=\'sb_js_errors\' style=\'color: red;\'></span>");
    }



    $( ".sb_publish" ).click(function() {

       window.console.log($('#title').val())
        var error = false
        //js validation here


        error = !$("form#post").valid();

        if (!error) {

            $( "form#post #publish" ).click();
        } else {
            $(".sb_js_errors").text("There was an error on the page and therefore this page can not yet be published.");
        }
    });

    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

});
