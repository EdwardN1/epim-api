<?php

/*
 * 	This class file can be downloaded from Alex Web Develop "PHP Login and Authentication Tutorial":
 * 	
 * 	https://alexwebdevelop.com/user-authentication/
 * 	
 * 	You are free to use and share this script as you like.
 * 	If you want to share it, please leave this disclaimer inside.
 * 	
 * 	Subscribe to my free newsletter and get my exclusive PHP tips and learning advice:
 * 	
 * 	https://alexwebdevelop.com/
 * 	
*/


class Account
{
    /* Class properties (variables) */

    /* The ID of the logged in account (or NULL if there is no logged in account) */
    private $id;

    /* The name of the logged in account (or NULL if there is no logged in account) */
    private $name;

    /* TRUE if the user is authenticated, FALSE otherwise */
    private $authenticated;


    /* Public class methods (functions) */

    /* Constructor */
    public function __construct()
    {
        /* Initialize the $id and $name variables to NULL */
        $this->id = NULL;
        $this->name = NULL;
        $this->authenticated = FALSE;
    }

    /* Destructor */
    public function __destruct()
    {

    }

    /* "Getter" function for the $id variable */
    public function getId()
    {
        return $this->id;
    }

    /* "Getter" function for the $name variable */
    public function getName()
    {
        return $this->name;
    }

    /* "Getter" function for the $authenticated variable */
    public function isAuthenticated()
    {
        return $this->authenticated;
    }

    /* Add a new account to the system and return its ID (the account_id column of the accounts table) */
    public function addAccount($name, $passwd, $email)
    {
        /* Global $pdo object */
        //global $pdo;

        /* Trim the strings to remove extra spaces */
        $name = trim($name);
        $passwd = trim($passwd);

        /* Check if the user name is valid. If not, throw an exception */
        if (!$this->isNameValid($name)) {
            throw new Exception('Invalid user name');
        }

        /* Check if the password is valid. If not, throw an exception */
        if (!$this->isPasswdValid($passwd)) {
            throw new Exception('Invalid password');
        }

        /* Check if the email address is valid. If not, throw an exception */
        if (!$this->isEmailValid($email)) {
            throw new Exception('Invalid email address');
        }

        /* Check if an account having the same name already exists. If it does, throw an exception */
        if (!is_null($this->getIdFromName($name))) {
            throw new Exception('User name not available');
        }

        /* Check if an account having the same email already exists. If it does, throw an exception */
        if (($this->getIdFromEmail($email))) {
            throw new Exception('Email address not available');
        }


        /* Password hash */
        $hash = password_hash($passwd, PASSWORD_DEFAULT);

        /* Finally, add the new account */

        $post_id = wp_insert_post(array(
            'post_type' => 'wpam_accounts',
            'post_title' => $name,
        ));

        /* Insert query template */
        //$query = 'INSERT INTO myschema.accounts (account_name, account_passwd) VALUES (:name, :passwd)';


        if ($post_id) {
            // insert post meta
            add_post_meta($post_id, '_wpam_accounts_password', $hash);
            add_post_meta($post_id, '_wpam_accounts_email', $email);
            add_post_meta($post_id, '_wpam_accounts_account_enabled', 'yes');
            add_post_meta($post_id, '_wpam_accounts_registered_time', date(DATE_RFC2822));
        } else {
            throw new Exception('Cannot add account error');
        }


        /* Values array for PDO */
        //$values = array(':name' => $name, ':passwd' => $hash);

        /* Execute the query */
        /*try
        {
            $res = $pdo->prepare($query);
            $res->execute($values);
        }
        catch (PDOException $e)
        {*/
        /* If there is a PDO exception, throw a standard exception */
        /*throw new Exception('Database query error');
     }*/

        /* Return the new ID */
        //return $pdo->lastInsertId();
        return $post_id;
    }

    /* Delete an account (selected by its ID) */
    public function deleteAccount($id)
    {
        /* Global $pdo object */
        //global $pdo;

        /* Check if the ID is valid */
        if (!$this->isIdValid($id)) {
            throw new Exception('Invalid account ID');
        }

        /* Query template */
        //$query = 'DELETE FROM myschema.accounts WHERE (account_id = :id)';

        /* Values array for PDO */
        //$values = array(':id' => $id);

        /* Execute the query */
        /*try
        {
            $res = $pdo->prepare($query);
            $res->execute($values);
        }
        catch (PDOException $e)
        {*/
        /* If there is a PDO exception, throw a standard exception */
        //throw new Exception('Database query error');
        /*}*/

        /* Delete the Sessions related to the account */
        $args = array(
            'title' => $id,
            'post_type' => 'wpam_sessions',
            'post_status' => 'any',
            'posts_per_page' => -1
        );
        $this_query = new WP_Query($args);
        $query_IDS = array();
        if ($this_query->have_posts()) {
            while ($this_query->have_posts()): $this_query->the_post();
                $query_IDS[] = get_the_ID();
            endwhile;
        }
        wp_reset_postdata();
        foreach ($query_IDS as $query_ID) {
            wp_delete_post($query_ID);
        }
        //$query = 'DELETE FROM myschema.account_sessions WHERE (account_id = :id)';

        /* Values array for PDO */
        //$values = array(':id' => $id);

        /* Execute the query */
        /*try {
            $res = $pdo->prepare($query);
            $res->execute($values);
        } catch (PDOException $e) {*/
        /* If there is a PDO exception, throw a standard exception */
        /*throw new Exception('Database query error');
    }*/
    }

    /* Edit an account (selected by its ID). The name, the password and the status (enabled/disabled) can be changed */
    public function editAccount($id, $name, $passwd, $email, $enabled)
    {
        /* Global $pdo object */
        //global $pdo;

        /* Trim the strings to remove extra spaces */
        $name = trim($name);
        $passwd = trim($passwd);

        /* Check if the ID is valid */
        if (!$this->isIdValid($id)) {
            throw new Exception('Invalid account ID');
        }

        /* Check if the user name is valid. */
        if (!$this->isNameValid($name)) {
            throw new Exception('Invalid user name');
        }

        /* Check if the password is valid. */
        if (!$this->isPasswdValid($passwd)) {
            throw new Exception('Invalid password');
        }

        /* Check if the Email Address is valid. */
        if (!$this->isEmailValid($email)) {
            throw new Exception('Invalid email address');
        }

        /* Check if a post with this id exists */

        $thisPost = get_post($id);

        if (is_null($thisPost)) {
            throw new Exception('Post does not exist');
        }

        /* Check if an account having the same name already exists (except for this one). */
        $idFromName = $this->getIdFromName($name);

        if (!is_null($idFromName) && ($idFromName != $id)) {
            throw new Exception('User name already used');
        }

        /* Check if an account having the same email already exists (except for this one). */
        $idFromEmail = $this->getIdFromEmail($email);

        if (($idFromEmail) && ($idFromEmail != $id)) {
            throw new Exception('Email address already used');
        }

        /* Finally, edit the account */

        /* Enabled */
        $isEnabled = 'yes';
        if ($enabled != 'yes') $isEnabled = 'no';

        /* Password hash */
        $hash = password_hash(sanitize_text_field($passwd), PASSWORD_DEFAULT);

        $aPost = array(
            'ID' => $id,
            'post_title' => sanitize_text_field($name)
        );

        wp_update_post($aPost);

        update_post_meta($id, '_wpam_accounts_email', sanitize_text_field($email));
        update_post_meta($id, '_wpam_accounts_password', $hash);
        update_post_meta($id, '_wpam_accounts_account_enabled', $isEnabled);

        /* Edit query template */
        //$query = 'UPDATE myschema.accounts SET account_name = :name, account_passwd = :passwd, account_enabled = :enabled WHERE account_id = :id';


        /* Int value for the $enabled variable (0 = false, 1 = true) */
        //$intEnabled = $enabled ? 1 : 0;

        /* Values array for PDO */
        //$values = array(':name' => $name, ':passwd' => $hash, ':enabled' => $intEnabled, ':id' => $id);

        /* Execute the query */
        /*try {
            $res = $pdo->prepare($query);
            $res->execute($values);
        } catch (PDOException $e) {*/
        /* If there is a PDO exception, throw a standard exception */
        /*throw new Exception('Database query error');
    }*/
    }

    /* Login with username and password */
    public function login($name, $passwd)
    {
        /* Global $pdo object */
        //global $pdo;

        /* Trim the strings to remove extra spaces */
        $name = trim($name);
        $passwd = trim($passwd);

        /* Check if the user name is valid. If not, return FALSE meaning the authentication failed */
        if (!$this->isNameValid($name)) {
            error_log('Login: Invalid username: '.$name);
            return FALSE;
        }

        /* Check if the password is valid. If not, return FALSE meaning the authentication failed */
        if (!$this->isPasswdValid($passwd)) {
            error_log('Login: Invalid Password: '.$passwd);
            return FALSE;
        }

        /* Look for the account in the db. Note: the account must be enabled (account_enabled = 1) */

        $postID = $this->getIdFromName($name);

        if (!$postID) {
            error_log('Login: Cannot find record for: '.$name);
            return FALSE;
        }

        $isEnabled = (get_post_meta($postID, '_wpam_accounts_account_enabled', true) == 'yes');

        if (!$isEnabled) {
            error_log('Login: Account not enabled for: '.$name);
            return FALSE;
        }

        $postPassword = get_post_meta($postID, '_wpam_accounts_password', true);

        if (password_verify($passwd, $postPassword)) {
            /* Authentication succeeded. Set the class properties (id and name) */
            $this->id = $postID;
            $this->name = $name;
            $this->authenticated = TRUE;

            /* Register the current Sessions on the database */
            $this->registerLoginSession();

            /* Finally, Return TRUE */
            return TRUE;
        }

        /* If we are here, it means the authentication failed: return FALSE */
        error_log('Login: Password not verified: '.$passwd.' for User: '.$name);
        return FALSE;
    }

    /* A sanitization check for the account username */
    public function isNameValid($name)
    {
        /* Initialize the return variable */
        $valid = TRUE;

        /* Example check: the length must be between 8 and 16 chars */
        $len = mb_strlen($name);

        if (($len < 8) || ($len > 16)) {
            $valid = FALSE;
        }

        /* You can add more checks here */

        return $valid;
    }

    /* A sanitization check for the account password */
    public function isPasswdValid($passwd)
    {
        /* Initialize the return variable */
        $valid = TRUE;

        /* Example check: the length must be between 8 and 16 chars */
        $len = mb_strlen($passwd);

        if (($len < 8) || ($len > 16)) {
            $valid = FALSE;
        }

        /* You can add more checks here */

        return $valid;
    }

    public function isEmailValid($email)
    {
        /* Initialize the return variable */
        $valid = filter_var($email, FILTER_VALIDATE_EMAIL);

        /* You can add more checks here */

        return $valid;
    }


    /* A sanitization check for the account ID */
    public function isIdValid($id)
    {
        /* Initialize the return variable */
        $valid = TRUE;

        /* Example check: the ID must be between 1 and 1000000 */

        if (($id < 1) || ($id > 1000000)) {
            $valid = FALSE;
        }

        /* You can add more checks here */

        return $valid;
    }

    /* Login using Sessions */
    public function sessionLogin()
    {
        /* Global $pdo object */
        //global $pdo;

        /* Check that the Session has been started */
        if (session_status() == PHP_SESSION_ACTIVE) {
            /*
                Query template to look for the current session ID on the account_sessions table.
                The query also make sure the Session is not older than 7 days
            */

            $sID = session_id();

            $args = array(
                'title' => $sID,
                'post_type' => 'wpam_sessions',
                'post_status' => 'any',
                'posts_per_page' => -1
            );

            $this_query = new WP_Query($args);
            if ($this_query->have_posts()) {
                $spID = $this_query->posts[0]->ID;
                $this->id = get_post_meta($spID, '_wpam_sessions_account_id', true);
                $accountPost = get_post($this->id);
                $this->name = $accountPost->post_title;
                $this->authenticated = TRUE;

                return TRUE;
            }

        }

        /* If we are here, the authentication failed */
        return FALSE;
    }

    /* Logout the current user */
    public function logout()
    {
        /* Global $pdo object */
        //global $pdo;


        //error_log('logging out');

        /* If there is no logged in user, do nothing */
        if (is_null($this->id)) {
            return;
        }

        //error_log('continuing logout');

        /* Reset the account-related properties */
        $this->id = NULL;
        $this->name = NULL;
        $this->authenticated = FALSE;

        /* If there is an open Session, remove it from the account_sessions table */
        if (session_status() == PHP_SESSION_ACTIVE) {

            $sID = session_id();

            //error_log('$sID = '.$sID);

            $args = array(
                'title' => $sID,
                'post_type' => 'wpam_sessions',
                'post_status' => 'any',
                'posts_per_page' => -1
            );

            $this_query = new WP_Query($args);
            $query_IDS = array();
            if ($this_query->have_posts()) {
                while ($this_query->have_posts()): $this_query->the_post();
                    $query_IDS[] = get_the_ID();
                endwhile;
            }
            wp_reset_postdata();
            foreach ($query_IDS as $query_ID) {
                error_log('deleteing post');
                wp_delete_post($query_ID);
            }
        }
    }

    /* Close all account Sessions except for the current one (aka: "logout from other devices") */
    public function closeOtherSessions()
    {
        /* Global $pdo object */
        //global $pdo;

        /* If there is no logged in user, do nothing */
        if (is_null($this->id)) {
            return;
        }

        /* Check that a Session has been started */
        if (session_status() == PHP_SESSION_ACTIVE) {
            /* Delete all account Sessions with session_id different from the current one */

            $sID = session_id();

            $args = array(
                'meta_key' => '_wpam_sessions_account_id',
                'meta_value' => $this->id,
                'post_type' => 'wpam_sessions',
                'post_status' => 'any',
                'posts_per_page' => -1
            );
            $this_query = new WP_Query($args);

            $query_IDS = array();
            if ($this_query->have_posts()) {
                while ($this_query->have_posts()): $this_query->the_post();
                    if (get_the_title() != $sID) {
                        $query_IDS[] = get_the_ID();
                    }
                endwhile;
            }
            wp_reset_postdata();
            foreach ($query_IDS as $query_ID) {
                wp_delete_post($query_ID);
            }

        }
    }

    /* Returns the account id having $name as name, or false if it's not found */
    public function getIdFromName($name)
    {
        $id = false;

        $args = array(
            'title' => $name,
            'post_type' => 'wpam_accounts',
            'post_status' => 'any',
            'posts_per_page' => -1
        );
        $this_query = new WP_Query($args);
        if ($this_query->have_posts()) {
            $id = $this_query->posts[0]->ID;
        }

        return $id;
    }

    /* Returns the account id having $email as email, or false if it's not found */
    public function getIdFromEmail($email)
    {
        $id = false;

        $args = array(
            'meta_key' => '_wpam_accounts_email',
            'meta_value' => $email,
            'post_type' => 'wpam_accounts',
            'post_status' => 'any',
            'posts_per_page' => -1
        );
        $this_query = new WP_Query($args);
        if ($this_query->have_posts()) {
            $id = $this_query->posts[0]->ID;
        }

        return $id;
    }

    /* Private class methods */

    /* Saves the current Session ID with the account ID */
    private function registerLoginSession()
    {
        /* Global $pdo object */
        //global $pdo;

        /* Check that a Session has been started */
        if (session_status() == PHP_SESSION_ACTIVE) {
            /* 	Use a REPLACE statement to:
                - insert a new row with the session id, if it doesn't exist, or...
                - update the row having the session id, if it does exist.
            */
            //error_log('PHP_SESSION_ACTIVE');
            $sID = session_id();

            $args = array(
                'title' => $sID,
                'post_type' => 'wpam_sessions',
                'post_status' => 'any',
                'posts_per_page' => -1
            );

            $postID = null;

            $this_query = new WP_Query($args);
            if ($this_query->have_posts()) {
                $postID = $this_query->posts[0]->ID;
            }

            if(is_null($postID)) {
                //error_log('Adding new session');
                $newSession = array(
                    'post_type' => 'wpam_sessions',
                    'post_title' => $sID,
                    'post_status' => 'publish'
                );
                $newSessionID = wp_insert_post($newSession);
                //error_log('$newSessionID = '.$newSessionID);
                update_post_meta($newSessionID, '_wpam_sessions_account_id',$this->id);
                update_post_meta($newSessionID,'_wpam_sessions_login_time',date(DATE_RFC2822));
            } else {
                update_post_meta($postID,'_wpam_sessions_account_id',$this->id);
                update_post_meta($postID,'_wpam_sessions_login_time',date(DATE_RFC2822));
            }


        }
    }
}
