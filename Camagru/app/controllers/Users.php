<?php 
    class Users extends Controller{
        public function __construct(){
            $this->userModel = $this->model('User');
        }

        public function register(){
            if(!$this->isloggedIn())
            {
                // Check for POST
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    // Process form

                    $token = substr(md5(openssl_random_pseudo_bytes(20)), 10);
                    // Sanitize POST data
                    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                    // Init data
                    $data =[
                        'firstname' => trim($_POST['firstname']),
                        'lastname' => trim($_POST['lastname']),
                        'username' => trim($_POST['username']),
                        'email' => trim($_POST['email']),
                        'password' => trim($_POST['password']),
                        'confirm_password' => trim($_POST['confirm_password']),
                        'token' => $token,
                        'firstname_err' => '',
                        'lastname_err' => '',
                        'username_err' => '',
                        'email_err' => '',
                        'password_err' => '',
                        'confirm_password_err' => ''
                    ];

                    // Validate Email
                    if(empty($data['email'])){
                        $data['email_err'] = 'Please enter email';
                    } else {
                        // Check email
                        if($this->userModel->findUserbyEmail($data['email'])){
                            $data['email_err'] = 'Email is already taken';
                        }
                    }

                    // Validate Name
                    if(empty($data['firstname'])){
                        $data['firstname_err'] = 'Please enter firstname';
                    }else if(strlen($data['firstname']) > 30)
                        $data['firstname_err'] = 'Firstname Too long';
                    else if(!ctype_alpha($data['firstname'])){
                        $data['firstname_err'] = 'Firstname Must be Alphabetic';
                    }
                    if(empty($data['lastname'])){
                        $data['lastname_err'] = 'Please enter lastname';
                    }else if(strlen($data['lastname']) > 30)
                        $data['lastname_err'] = 'Lastname Too long';
                    else if(!ctype_alpha($data['lastname'])){
                        $data['lastname_err'] = 'Lastname Must be Alphabetic';
                    }
                    if(empty($data['username'])){
                        $data['username_err'] = 'Please enter username';
                    }else if(strlen($data['username']) > 30)
                        $data['username_err'] = 'Username Too long';
                    else if(!ctype_alnum($data['username'])){
                        $data['username_err'] = 'Username Should be AlphaNumeric';
                    }else {
                        
                        if($this->userModel->findUserByUsername($data['username'])){
                            $data['username_err'] = 'Username is already taken';
                        }
                    }

                    // Validate Password
                    if(empty($data['password'])){
                        $data['password_err'] = 'Please enter password';
                    }elseif(strlen($data['password']) < 6){
                        $data['password_err'] = 'Password must be at least 6 characters';
                    }else if (!preg_match('@[A-Z]@', $data['password'])){
                        $data['password_err'] = 'Password must contain an upper case';
                    }else if (!preg_match('@[a-z]@', $data['password'])){
                        $data['password_err'] = 'Password must contain a  lower case';
                    }else if (!preg_match('@[0-9]@', $data['password']))
                        $data['password_err'] = 'Password must contain a number';

                    // Validate Confirm_password
                    if(empty($data['confirm_password'])){
                        $data['confirm_password_err'] = 'Please confirm_password';
                    }else {
                        if($data['password'] != $data['confirm_password']){
                            $data['confirm_password_err'] = 'Password do not match'; 
                        }
                    }

                    // Make sure errors are empty
                    if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) && empty($data['firstname_err']) && empty($data['lastname_err'])){
                        
                        // Hash Password
                        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                        // Validated
                        $to  = $data['email'];
                        $subject = 'Confirm account';
                        $message = '
                            <html>
                            <head>
                            </head>
                            <body>
                            <p>Welcome to Camagru,
                            <br /><br />
                            <br/>
                            <p>To activate your account please click <a href="http://localhost/Camagru/users/confirm/?token='. $token .'">Here</a></p>
                            </p>
                            <p>
                                <br />--------------------------------------------------------
                                <br />This is an automatic mail , please do not reply.
                            </p>                
                            </body>
                            </html>
                        ';
                        $headers = 'Content-type: text/html; charset=iso-8859-1'."\r\n";
                        mail($to, $subject, $message , $headers);
                        // Register User
                        if($this->userModel->register($data)){
                            flash('register_success', 'Please Check Your Email To Confirm Your Account!','alert alert-warning');
                            redirect('users/login');
                        } else {
                            die('Something went wrong');
                        }
                    } else {
                        // Load view with errors
                    $this->view('users/register', $data);
                    }

                } else {
                    // Init data
                    $data =[
                        'firstname' => '',
                        'lastname' => '',
                        'username' => '',
                        'email' => '',
                        'password' => '',
                        'confirm_password' => '',
                        'firstname_err' => '',
                        'lastname_err' => '',
                        'username_err' => '',
                        'email_err' => '',
                        'password_err' => '',
                        'confirm_password_err' => ''
                    ];

                    // Load view
                    $this->view('users/register', $data);
                }
            }
            else
                redirect('pages/index');
        }

        public function login(){
            if(!$this->isloggedIn())
            {
                // Check for POST
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    // Process form

                    // Sanitize POST data
                    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                    // Init data
                    $data =[
                        'username' => trim($_POST['username']),
                        'password' => trim($_POST['password']), 
                        'username_err' => '',
                        'password_err' => '',
                    ];

                    // Validate username
                    if(empty($data['username'])){
                        $data['username_err'] = 'Please enter username';
                    }

                    // Validate Password
                    if(empty($data['password'])){
                        $data['password_err'] = 'Please enter password';
                    }

                    // Check for username
                    if($this->userModel->findUserByUsername($data['username']) == false){
                        $data['username_err'] = 'No user found';
                    }elseif($this->userModel->checkuserconfirmed($data['username'])){

                    }else{
                    $data['username_err'] = 'Please Check Your Email';
                        
                    }

                    // Make sure errors are empty
                    if(empty($data['username_err']) && empty($data['password_err'])){
                        // Validated
                        // Check and set logged in user
                        $loggedInUser = $this->userModel->login($data['username'], $data['password']);

                        if($loggedInUser){
                            // Create Session
                            createUserSession($loggedInUser);
                        }
                        else {
                            $data['password_err'] = 'Password incorrect';

                            $this->view('users/login', $data);
                        }
                    } else {
                        // Load view with errors
                    $this->view('users/login', $data);
                    }
                } else {
                    // Init data
                    $data =[
                        'username' => '',
                        'password' => '', 
                        'username_err' => '',
                        'password_err' => '',
                    ];

                    // Load view
                    $this->view('users/login', $data);
                }
            }
            else
            redirect('pages/index');
        }

        public function logout(){
            unset($_SESSION['user_id']);
            unset($_SESSION['user_email']);
            unset($_SESSION['user_username']);
            unset($_SESSION['user_firstname']);
            unset($_SESSION['user_lastname']);
            session_destroy();
            redirect('users/login');
        }

        public function isloggedIn(){
            if(isset($_SESSION['user_id'])){
                return true;
            } else {
                return false;
            }
        }
        
        public function edit(){
            // Check for POST
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Process form
                if (isset($_SESSION['token']) AND isset($_POST['token']) AND !empty($_SESSION['token']) AND !empty($_POST['token'])){
                    if ($_SESSION['token'] == $_POST['token']){
                        // Sanitize POST data
                        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                        // Init data
                        $data = [
                            'id' => $_SESSION['user_id'],
                            'edit_username' => trim($_POST['edit_username']),
                            'edit_lastname' => trim($_POST['edit_lastname']),
                            'edit_firstname' => trim($_POST['edit_firstname']),
                            'edit_email' => trim($_POST['edit_email']),
                            'edit_new_password' => $_POST['edit_new_password'],
                            'new_confirm_password' => trim($_POST['new_confirm_password']),
                            'edit_password' => $_POST['edit_password'],
                            'checkbox_send_notif' => $_POST['checkbox_send_notif'],
                            'edit_username_err' => '',
                            'edit_lastname_err' => '',
                            'edit_firstname_err' => '',
                            'edit_email_err' => '',
                            'edit_new_password_err' => '',
                            'new_confirm_password_err' => '',
                            'edit_password_err' => ''
            
                        ];
                        
                        // Validate username
                        if ($data['edit_username']){
                            if(!ctype_alnum($data['edit_username'])){
                                $data['edit_username_err'] = 'Username Should be AlphaNumeric';
                            }else if($this->userModel->findUserByUsername($data['edit_username']))
                                $data['edit_username_err'] = 'Username is already taken';
                        }
            

                        // Validate lastname
                        if ($data['edit_lastname']){
                            if(!ctype_alpha($data['edit_lastname'])){
                                $data['edit_lastname_err'] = 'Lastname Must be Alphabetic';
                            }
                        }
                    
                        // Validate firstname
                        if ($data['edit_firstname']){
                            if(!ctype_alpha($data['edit_firstname'])){
                                $data['edit_firstname_err'] = 'Firstname Must be Alphabetic';
                            }   
                        } 
                        
                        // Validate email
                        if ($data['edit_email']){
                            if($this->userModel->findUserByEmail($data['edit_email'])){
                                $data['edit_email_err'] = 'Email is already taken';
                            }
                            else if (!filter_var($data['edit_email'], FILTER_VALIDATE_EMAIL)) {
                                $data['edit_email_err'] = 'Email is not valid';
                            }
                        }

                        // Validate new_password
                        if ($data['edit_new_password']){
                            if(strlen($data['edit_new_password']) < 6)
                                $data['edit_new_password_err'] = 'Password must be at least 6 characters';
                            else if (!preg_match('@[A-Z]@', $data['edit_new_password']))
                                $data['edit_new_password_err'] = 'Password must contain an upper case';
                            else if (!preg_match('@[a-z]@', $data['edit_new_password']))
                                $data['edit_new_password_err'] = 'Password must contain a  lower case';
                            else if (!preg_match('@[0-9]@', $data['edit_new_password']))
                                $data['edit_new_password_err'] = 'Password must contain a number';
                        }
                        // Validate new_confirm_password
                        if($data['edit_new_password'])
                        {
                            if(empty($data['new_confirm_password'])){
                                $data['new_confirm_password_err'] = 'Please confirm_password';}
                        }else {
                            if($data['edit_new_password'] != $data['new_confirm_password']){
                                $data['new_confirm_password_err'] = 'Password do not match'; 
                            }
                        }
                    
                        // Validate password
                        if(empty($data['edit_password']))
                            $data['edit_password_err'] = 'Please enter current password';

                        // Make sure errors are empty
                        if(empty($data['edit_firstname_err']) && empty($data['edit_lastname_err']) && empty($data['edit_username_err']) && empty($data['edit_email_err']) && empty($data['edit_new_password_err']) && empty($data['edit_password_err']))
                        {
                            if(isset($data['checkbox_send_notif'])){   
                                $data['checkbox_send_notif'] = 1;
                            }
                            else
                                $data['checkbox_send_notif'] = 0;
                            if($this->userModel->edit($data)){
                                flash('edit_success', 'Your account has been successfully edited');
                                redirect('users/edit');
                            }else{
                                $data['edit_password_err'] = 'Incorrect password';
                                $this->view('users/edit', $data);
                            }
                        }
                        else
                            $this->view('users/edit', $data);
                    }else
                    redirect('pages/error');
                }else{ 
                  // Les token ne correspondent pas
                  redirect('pages/error');
                }
    
            }else{
                $data = [
                    'id' => '',
                    'edit_username' => '',
                    'edit_lastname' => '',
                    'edit_firstname' => '',
                    'edit_email' => '',
                    'edit_new_password' => '',
                    'new_confirm_password' => '',
                    'edit_password' => '',
                    'edit_username_err' => '',
                    'edit_lastname_err' => '',
                    'edit_firstname_err' => '',
                    'edit_email_err' => '',
                    'edit_new_password_err' => '',
                    'new_confirm_password_err' => '',
                    'edit_password_err' => '',
    
                ];
                if(isset($_SESSION['user_id']))
                    $this->view('users/edit', $data);
                else
                    $this->view('users/login');
            }
           }
           public function confirm(){
            $data =['token' => $_GET['token']];
                $row = $this->userModel->getUserByToken($data['token']);
                if(isset($_GET['token']) && $_GET['token'] != "")
                {
                    if($_GET['token'] == $row->token)
                    {
                        $page = ['title' => "Thank You"];
                        $this->view("users/confirm", $page);
                        $data = ['token' => $_GET['token']];
                        $this->userModel->confirm($data);
                    }
                    else
                    redirect('users/token');
                }
                else
                    $this->view("users/login");
        }
        
        public function fgpass()
        {
            

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data =[
                    'email' => trim($_POST['email']),
                    'email_err' => '' 
                ];
                if(empty($data['email'])){
                    $data['email_err'] = 'Please enter email';
                }
                elseif($this->userModel->findUserbyEmail($data['email']) == false){
                    $data['email_err'] = 'Email Not Found';
                }
                    
                if(empty($data['email_err']))
                {
                        $row = $this->userModel->getUserByEmail($data['email']);
                        if($row->token == "")
                        {
                            $token1 = substr(md5(openssl_random_pseudo_bytes(20)), 10);
                            $data['token'] = $token1;
                            $this->userModel->updateTokenbyemail($data);
                            $row = $this->userModel->getUserByEmail($data['email']);                           
                        }
                       
                            $token = $row->token;
                            $to  = $data['email'];
                            $subject = 'Reset password';
                            $message = '
                                <html>
                                <head>
                                </head>
                                <body>
                                    <p>To recover your account please click here <a href="http://localhost/Camagru/users/changepass/?token='.$token .'"><button 
                                    type="button" class="btn btn-outline-info">Change Password</button></a></p>
                                    <p>
                                        <br />--------------------------------------------------------
                                        <br />This is an automatic mail , please do not reply.
                                    </p> 
                                </body>
                                </html>
                            ';                       
                            $headers = 'Content-type: text/html; charset=iso-8859-1'."\r\n";
                            if(mail($to, $subject, $message , $headers))
                                redirect('users/emailsend');
                        }
                    
                
                else{
                     $this->view('users/fgpass', $data);
                }

            }
            else{
                $data =['email' => '',
                    'email_err' => ''];
                $this->view('users/fgpass', $data);
            
                }       
        }

        public function emailsend(){
           
            $page = ['title' => "Thank You"];
            
            $this->view("users/emailsend", $page);
           
        }

        public function changepass()
        {   if($_GET['token'] == "") $this->view('users/login');
            else{
                $data =['token' => $_GET['token']];
                $row = $this->userModel->getUserByToken($data['token']);
                if($_GET['token'] == $row->token)
                {
                    if(isset($_GET['token'])){
                            $token = $_GET['token'];
                        if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
                            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                            $data =[
                                'password' => trim($_POST['password']),
                                'confirm_password' => trim($_POST['confirm_password']),
                                'token' => $token,
                                'password_err' => '',
                                'confirm_password_err' => ''
                            ];
                            if(empty($data['password'])){
                                $data['password_err'] = 'Please enter Password';
                            }elseif(strlen($_POST['password']) < 6 ){
                                $data['password_err'] = 'Password must be at least 6 characters';
                            }else if (!preg_match('@[A-Z]@', $data['password'])){
                                $data['password_err'] = 'Password must contain an upper case';
                            }else if (!preg_match('@[a-z]@', $data['password'])){
                                $data['password_err'] = 'Password must contain a  lower case';
                            }else if (!preg_match('@[0-9]@', $data['password']))
                                $data['password_err'] = 'Password must contain a number';

                            if(empty($data['confirm_password'])){
                                $data['confirm_password_err'] = 'Please confirm Password';
                            }elseif($_POST['password'] != $_POST['confirm_password']){
                                $data['confirm_password_err'] = 'Passwords not match';
                            }
                            if(empty($data['password_err']) && empty($data['confirm_password_err'])){
                                $data['password'] =  password_hash($data['password'], PASSWORD_DEFAULT);
                            
                                if($this->userModel->changepass($data)){
                                
                                    flash('register_success', 'Your Password is Changed');
                                    redirect('users/login');
                                } else {
                                    redirect('users/login');
                                }
                            }else{
                                
                                $this->view('users/changepass', $data);
                            }
                        }
                        else{
                        $data =[
                            'password' => '',
                            'confirm_password' => '',
                            'password_err' => '',
                            'confirm_password_err' => ''
                        ];
                        $this->view('users/changepass', $data);
                        }
                    }
                }
                else
                redirect('users/token');   
            }       
        }

        public function token(){
           
            $page = ['title' => "Sorry"];
            
            $this->view("users/token", $page);
           
        }
    }
?>
