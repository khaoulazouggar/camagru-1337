<?php
    session_start();

    // Flash message helper
    // EXAMPLE - flash('register_success', 'You are now registered');
    // DISPLAY IN VIEW - echo flash('register_success');
    function flash($name = '', $message = '', $class = 'alert alert-success'){
        if(!empty($name)){
            if(!empty($message) && empty($_SESSION[$name])){
                if(!empty($_SESSION[$name])){
                    unset($_SESSION[$name]);
                }
                
                if(!empty($_SESSION[$name. '_class'])){
                    unset($_SESSION[$name. '_class']);
                }

                $_SESSION[$name] = $message;
                $_SESSION[$name. '_class'] = $class;
            } elseif(empty($message) && !empty($_SESSION[$name])){
                $class = !empty($_SESSION[$name. '_class']) ? $_SESSION[$name. '_class'] : '';
                echo '<div class="'.$class.'" id="msg-flash">'.$_SESSION[$name].'</div>';
                unset($_SESSION[$name]);
                unset($_SESSION[$name. '_class']);
            }
        }
    }

    function createUserSession($user){
        $token = substr(md5(openssl_random_pseudo_bytes(20)), 10);
        $_SESSION['token'] = $token;

        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_username'] = $user->username;
        $_SESSION['user_firstname'] = $user->firstname;
        $_SESSION['user_lastname'] = $user->lastname;
        $_SESSION['user_password'] = $user->password;
        $_SESSION['notif'] = $user->notif;
        redirect('pages/index');
    }