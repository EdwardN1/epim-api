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
    echo '<div><input type="submit"></div>';
    echo '</form></div>';
    echo '<div><a href="' . strtok($_SERVER['REQUEST_URI'], '?') . '?lostpassword=1">Lost your password?</a></div>';
}

function reset_form($recapture, $stage)
{
    if ('1' == $stage) {
        echo '<div class="wpam-login-form"><h4>Reset your password</h4><form action="' . strtok($_SERVER['REQUEST_URI'], '?') . '?lostpassword=2" method="post">';
        echo '<div><label for="username">Enter your username or email address:<br><input type="text" id="username" name="username" value=""/></label><br>';
        if ('' != $recapture) echo $recapture;
        echo '<div><input type="submit"></div>';
        echo '</form></div>';
        echo '<div><a href="' . strtok($_SERVER['REQUEST_URI'], '?') . '">Return to Login</a></div>';
    } else {
        if ('2' == $stage) {
            $postID = false;
            if(isset($_POST['username'])) {
                $token = bin2hex(random_bytes(56));
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
                }
            } else {
                wp_redirect(strtok($_SERVER['REQUEST_URI'], '?') . '?lostpassword=1');
            }
        } else {
            if ('3' == $stage) {
                /**
                 *
                 * ======================== Token Conformation section
                 *
                 */
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
            echo '<div>You are logged out</div>';
            login_form($recapture);
            echo '</form></div>';
        } else {
            echo '<div>Logged in as ' . $account->getName() . '</div>';
            echo '<div>Not ' . $account->getName() . '? <a href="' . strtok($_SERVER['REQUEST_URI'], '?') . '?logout=1">Logout</a> </div>';
        }
    } else {
        $loggedin = false;
        $recaptured = false;
        if ('' != $recapture) {
            if (array_key_exists("g-recaptcha-response", $_POST)) {
                $response = $_POST["g-recaptcha-response"];
                $url = 'https://www.google.com/recaptcha/api/siteverify';
                $data = array(
                    'secret' => get_option('wpam_recapture_secret_key'),
                    'response' => $_POST["g-recaptcha-response"]
                );
                $options = array(
                    'http' => array(
                        'method' => 'POST',
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
                reset_form($recapture, 1);
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
            echo '<div>Logged in as ' . $account->getName() . '</div>';
            echo '<div>Not ' . $account->getName() . '? <a href="' . strtok($_SERVER['REQUEST_URI'], '?') . '?logout=1">Logout</a> </div>';
        } else {
            login_form($recapture);
        }
    }

}