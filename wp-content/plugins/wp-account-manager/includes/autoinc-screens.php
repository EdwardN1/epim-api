<?php
add_shortcode('wpam', 'login_shortcode');

function login_form($recapture)
{
    $username = '';
    $password = '';
    if(isset($_POST['username'])) $username = $_POST['username'];
    if(isset($_POST['password'])) $password = $_POST['password'];
    echo '<div class="wpam-login-form"><form action="' . strtok($_SERVER['REQUEST_URI'], '?') . '" method="post">';
    echo '<div><label for="username">Username:<br><input type="text" id="username" name="username" value="' . $username . '"/></label><br>';
    echo '<label for="password">Password:<br><input type="password" id="password" name="password" value="' . $password, '"/></label></div>';
    if ('' != $recapture) echo $recapture;
    echo '<div><input type="submit" class="button" value="Log In"></div>';
    echo '</form></div>';
    echo '<div><a href="' . strtok($_SERVER['REQUEST_URI'], '?') . '?lostpassword=1">Lost your password?</a></div>';
}

function reset_form($recapture, $stage)
{
    if ('1' == $stage) {
        echo '<div class="wpam-login-form"><h4>Reset your password</h4><form action="' . strtok($_SERVER['REQUEST_URI'], '?') . '?lostpassword=2" method="post">';
        echo '<div><label for="username">Enter your username or email address:<br><input type="text" id="username" name="username" value=""/></label><br>';
        if ('' != $recapture) echo $recapture;
        echo '<div><input type="submit" class="button" value="Reset"></div>';
        echo '</form></div>';
        echo '<div><a href="' . strtok($_SERVER['REQUEST_URI'], '?') . '">Return to Login</a></div>';
    } else {
        if ('2' == $stage) {
            $postID = false;
            if(isset($_POST['username'])) {
                $token = sanitize_text_field(bin2hex(random_bytes(56)));
                $account = new Account;
                $postID = $account->getIdFromName($_POST['username']);
                if(!$postID) {
                    $postID = $account->getIdFromEmail($_POST['username']);
                }
                if(!$postID) {
                    echo '<div style="color: red">Account not found</div>';
                    echo '<div><a href="' . strtok($_SERVER['REQUEST_URI'], '?') . '">Return to Login</a></div>';
                } else {
                    /**
                     *
                     * ========== Set Token to Account and send email with token
                     *
                     *
                     */
                    update_post_meta($postID,'_wpam_accounts_reset_token',$token);
                    $to = get_post_meta($postID,'_wpam_accounts_email',true);
                    $subject = 'Reset Password Requested For: '.get_bloginfo( 'name' );
	                if (isset($_SERVER['HTTPS']) &&
	                    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
	                    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
	                    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
		                $protocol = 'https://';
	                }
	                else {
		                $protocol = 'http://';
	                }
	                $message = 'Reset your login password here: '. $protocol . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?').'?lostpassword=3&z='.$postID.'&token='.$token;
	                if(wp_mail( $to, $subject, $message )) {
		                echo '<div>Your password reset request has been sent. Please check your inbox.</div>';
		                echo '<div><a href="' . strtok($_SERVER['REQUEST_URI'], '?') . '">Return to Login</a></div>';
	                } else {
		                echo '<div style="color: red">Email has not been processed. Please contact the site admin.</div>';
		                echo '<div><a href="' . strtok($_SERVER['REQUEST_URI'], '?') . '">Return to Login</a></div>';
	                }
                }
            } else {
                wp_redirect(strtok($_SERVER['REQUEST_URI'], '?') . '?lostpassword=1');
            }
        } else {
            if ('3' == $stage) {
                /**
                 *
                 * ======================== Token Confirmation section
                 *
                 */
                if(isset($_GET['z'])) {
                	if(isset($_GET['token'])) {
                		$postID = sanitize_text_field($_GET['z']);
                		$token = sanitize_text_field($_GET['token']);
                		$savedToken = get_post_meta($postID,'_wpam_accounts_reset_token',true);
                		if($token==$savedToken) {
			                echo '<div><form action="' . strtok($_SERVER['REQUEST_URI'], '?') . '?lostpassword=4" method="post"></div>';
			                echo '<input type="hidden" name="token" value="'.$token.'"/>';
			                echo '<input type="hidden" name="z" value="'.$postID.'"/>';
			                echo '<div><label for="passwd1">Enter your new password:<br><input type="password" id="passwd1" name="passwrd1"></label></div>';
			                echo '<div><label for="passwd2">R-enter your new password:<br><input type="password" id="passwd2" name="passwrd2"></label></div>';
			                if ('' != $recapture) echo $recapture;
			                echo '<div><input type="submit" class="button" value="Update"></div>';
			                echo '</form></div>';
		                } else {
			                echo '<div style="color: red">Sorry this link has expired.</div>';
			                echo '<div><a href="' . strtok($_SERVER['REQUEST_URI'], '?') . '">Return to Login</a></div>';
		                }
	                } else {
		                echo '<div style="color: red">Account not found</div>';
		                echo '<div><a href="' . strtok($_SERVER['REQUEST_URI'], '?') . '">Return to Login</a></div>';
	                }
                } else {
	                echo '<div style="color: red">Account not found</div>';
	                echo '<div><a href="' . strtok($_SERVER['REQUEST_URI'], '?') . '">Return to Login</a></div>';
                }
            } else {
            	if('4'==$stage) {
		            $postID = sanitize_text_field($_POST['z']);
		            $token = sanitize_text_field($_POST['token']);
		            $pass1 = sanitize_text_field($_POST['passwrd1']);
		            $pass2 = sanitize_text_field($_POST['passwrd2']);
		            $savedToken = get_post_meta($postID,'_wpam_accounts_reset_token',true);
		            if($pass1!=$pass2) {
		            	if($savedToken==$token) {
				            update_post_meta($postID,'_wpam_accounts_reset_token','');
			            }
			            echo '<div style="color: red">Your passwords do not match. Password has not been updated.</div>';
			            echo '<div><a href="' . strtok($_SERVER['REQUEST_URI'], '?') . '">Return to Login</a></div>';
		            } else {
			            if($token==$savedToken) {
			            	$account = new Account;
				            $hash = password_hash($pass1, PASSWORD_DEFAULT);
				            $account = new Account;
				            if($account->isPasswdValid($pass1)) {
					            update_post_meta($postID, '_wpam_accounts_password', $hash);
					            update_post_meta($postID,'_wpam_accounts_reset_token','');
					            echo '<div>Your password has been reset</div>';
					            echo '<div><a href="' . strtok($_SERVER['REQUEST_URI'], '?') . '">Please Login</a></div>';
				            } else {
					            if($savedToken==$token) {
						            update_post_meta($postID,'_wpam_accounts_reset_token','');
					            }
					            echo '<div style="color: red">Sorry this password is invalid. Please make sure it is between 8 and 16 characters long.</div>';
					            echo '<div><a href="' . strtok($_SERVER['REQUEST_URI'], '?') . '">Return to Login</a></div>';
				            }
			            } else {
				            echo '<div style="color: red">Sorry this link has expired.</div>';
				            echo '<div><a href="' . strtok($_SERVER['REQUEST_URI'], '?') . '">Return to Login</a></div>';
			            }
		            }

	            }
            }
        }
    }
}

function login_shortcode($params = array())
{
    $recapture = '';
    $do_recapture = false;
    if (is_array($params)) {
        if (array_key_exists('recapture', $params)) {
            if (isset($params['recapture'])) {
                $do_recapture = $params['recapture'] == 'true';
            }
        }
    }
    if ($do_recapture) {
        wp_enqueue_script('wpam-recapture-js');
        $recapture = '<div class="g-recaptcha" data-sitekey="' . get_option('wpam_recapture_site_key') . '" style="padding-bottom: 1em;"></div>';
    }
    $account = new Account;
    if ($account->sessionLogin()) {
        if (isset($_GET['logout'])) {
            $account->logout();
            do_action('wpam_before_logout_success');
            echo '<div>You are logged out</div>';
            login_form($recapture);
            echo '</form></div>';
	        do_action('wpam_after_logout_success');
        } else {
	        do_action('wpam_before_logged_in_success');
            echo '<div>Logged in as ' . $account->getName() . '</div>';
            echo '<div>Not ' . $account->getName() . '? <a href="' . strtok($_SERVER['REQUEST_URI'], '?') . '?logout=1">Logout</a> </div>';
	        do_action('wpam_after_logged_in_success');
        }
    } else {
        $loggedin = false;
        $recaptured = false;
        if ('' != $recapture) {
            if (array_key_exists("g-recaptcha-response", $_POST)) {
                $response = $_POST["g-recaptcha-response"];
                $url = 'https://www.google.com/recaptcha/api/siteverify';
                $proxy = 'tcp://proxyout.cly.dtc3.cf.saint-gobain.net:3128';
                $data = array(
                    'secret' => get_option('wpam_recapture_secret_key'),
                    'response' => $_POST["g-recaptcha-response"]
                );
                $options = array(
                    'http' => array(
                        'method' => 'POST',
                        /*'proxy' => $proxy,
                        'request_fulluri' => True,*/
                        'content' => http_build_query($data),
                        'header' =>
                            "Content-Type: application/x-www-form-urlencoded\r\n",
                    )
                );
                $context = stream_context_create($options);
                $verify = file_get_contents($url, false, $context);
                $captcha_success = json_decode($verify);
                if (($captcha_success->success == false) && (array_key_exists("g-recaptcha-response", $_POST))) {
                    echo '<div style="color: red">Please confirm you are not a robot</div>';
                } else if ($captcha_success->success == true) {
                    $recaptured = true;
                }
            }
        } else {
            $recaptured = true;
        }
        if (isset($_GET['lostpassword'])) {
            if ($recaptured) {
                reset_form($recapture, $_GET['lostpassword']);
            } else {
            	if($_GET['lostpassword']==3){
		            reset_form($recapture, 3);
	            } else {
            		if($_GET['lostpassword']==4) {
			            reset_form( $recapture, 4 );
		            } else {
			            reset_form($recapture, 1);
		            }

	            }
            }

            return;
        }
        if (isset($_POST['username']) && isset($_POST['password']) && $recaptured) {
            $loggedin = $account->login($_POST['username'], $_POST['password']);
            if (!$loggedin) {
                echo '<div style="color: red">Invalid Username or Password</div>';
            }
        }
        if ($loggedin) {
	        do_action('wpam_before_logged_in_success');
            echo '<div>Logged in as ' . $account->getName() . '</div>';
            echo '<div>Not ' . $account->getName() . '? <a href="' . strtok($_SERVER['REQUEST_URI'], '?') . '?logout=1">Logout</a> </div>';
	        do_action('wpam_after_logged_in_success');
        } else {
	        do_action('wpam_before_log_in_form');
            login_form($recapture);
	        do_action('wpam_after_log_in_form');
        }
    }

}