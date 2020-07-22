<?php
add_shortcode('wpam', 'login_shortcode');

function login_shortcode($params = array()) {
	$account = new Account;
	if(isset($_POST['logout'])) {
		$account->logout();
		echo '<div>You are logged out</div>';
	} else {
		if ( $account->sessionLogin() ) {
			echo '<div>Logged in as ' . $account->getName() . '</div>';
			echo '<div>Not ' . $account->getName() . '? <a href="' . $_SERVER['REQUEST_URI'] . '?logout=1">Logout</a> </div>';
		} else {
			$loggedin = false;
			if(isset($_POST['username'])&&isset($_POST['password'])) {
			    //error_log($_POST['password']);
				$loggedin = $account->login($_POST['username'],$_POST['password']);
				if(!$loggedin) {
					echo '<div style="color: red">Invalid Username or Password</div>';
				}
			}
			if($loggedin) {
				echo '<div>Logged in as ' . $account->getName() . '</div>';
				echo '<div>Not ' . $account->getName() . '? <a href="' . $_SERVER['REQUEST_URI'] . '?logout=1">Logout</a> </div>';
			} else {
				echo '<div><form action="'.$_SERVER['REQUEST_URI'] .'" method="post">';
				echo '<div><label for="username">Username:<br><input type="text" id="username" name="username" value="'.$_POST['username'].'"/></label><br>';
				echo '<label for="password">Password:<br><input type="text" id="password" name="password" value="'.$_POST['password'],'"/></label></div>';
				echo '<div><input type="submit"></div>';
				echo '</form></div>';
			}
		}
	}
}