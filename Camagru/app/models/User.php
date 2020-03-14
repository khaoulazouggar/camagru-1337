<?php
    class User {
        private $db;

        public function __construct(){
            $this->db = new Database;
        }

        // Register user
        public function register($data){
            $this->db->query('INSERT INTO users (firstname, lastname, username, email, password, token) 
            VALUES(:firstname, :lastname, :username, :email, :password, :token)');
            // Bind values
            $this->db->bind(':firstname', $data['firstname']);
		    $this->db->bind(':lastname', $data['lastname']);
		    $this->db->bind(':username', $data['username']);
            $this->db->bind(':email', $data['email']); 
            $this->db->bind(':password', $data['password']);
            $this->db->bind(':token', $data['token']);

            // Execute
            if($this->db->execute()){
                return true;
            } else {
                return false;
            }
        }

        // Login User
        public function login($username, $password){
            $this->db->query('SELECT * FROM users WHERE username = :username');
            $this->db->bind(':username', $username);

            $row = $this->db->single();
            $hashed_password = $row->password;
            if(password_verify($password, $hashed_password)){
                return $row;
            } else {
                return false;
            }
        }
        // Find user by email
        public function findUserbyEmail($email){
            $this->db->query('SELECT * FROM users where email = :email');
            // Bind values
            $this->db->bind(':email', $email);  
            $this->db->single();
            // Check row
            if($this->db->rowCount() > 0){
                return true;
            } else {
                return false;
            }
        }

        // Get user by email
        public function getUserByEmail($email)
        {
            $this->db->query('SELECT * FROM users WHERE email = :email');
            $this->db->bind(':email', $email);
            $row = $this->db->single();
            return $row;
        }
        // Find user by username
        public function findUserByUsername($username){
            $this->db->query('SELECT * FROM users WHERE username = :username');
            $this->db->bind(':username', $username);
    
            $row = $this->db->single();
            
        //check row
            if($this->db->rowCount() > 0){
                return $row;
            } else {
                return false;
            }
        }


        // Edit user
        public function edit($data){
            $row = $this->findUserById();
            if($row)
            {
                $hashed_password = $row->password;
                if(password_verify($data['edit_password'] ,$hashed_password)){
                    $_SESSION['notif'] = $data['checkbox_send_notif'];
                    if($this->update($data))
                        return true;
                    else 
                        return false;
                }
                else
                    return false;
            }
        }
        // Find user by id
        public function findUserById(){
            $this->db->query('SELECT * FROM users WHERE id = :id');
            $this->db->bind(':id', $_SESSION['user_id']);
    
            $row = $this->db->single();
        
            return $row; 
        }

        // Update
        public function update($data)
        {
            $this->db->query('SELECT * FROM users WHERE id = :id1');
            $this->db->bind(':id1', $data['id']);
            $row = $this->db->single();
            $mail = $data['edit_email'] != "" ? $data['edit_email'] : $row->email;
            $pass = $data['edit_new_password'] != "" ? password_hash($data['edit_new_password'], PASSWORD_DEFAULT) : $row->password;
            $username = $data['edit_username'] != "" ? $data['edit_username'] : $row->username;
            $firstname = $data['edit_firstname'] != "" ? $data['edit_firstname'] : $row->firstname;
            $lastname = $data['edit_lastname'] != "" ? $data['edit_lastname'] : $row->lastname;
            
            $data['edit_password'] = $pass;
            $_SESSION['user_username'] = $username;
            $_SESSION['user_firstname'] = $firstname;
            $_SESSION['user_lastname'] = $lastname;
            $_SESSION['user_email'] = $mail;
            $_SESSION['user_password'] = $pass;
            $this->db->query('UPDATE `users` SET `firstname`=:edit_firstname,`lastname`=:edit_lastname,
            `username`=:edit_username,`email`=:edit_email,`password`=:edit_password, `notif`= :n WHERE id = :id');
            $this->db->bind(':edit_firstname', $firstname);
            $this->db->bind(':edit_lastname', $lastname);
            $this->db->bind(':edit_username', $username);
            $this->db->bind(':edit_email', $mail);
            $this->db->bind(':edit_password', $pass);
            $this->db->bind(':n', $data['checkbox_send_notif']);
            $this->db->bind(':id', $data['id']);

            if($this->db->execute()){
                return true;
            }else {
                return false;
            }
        }

        // confirm
        public function confirm($data)
        {
            $token = $data['token'];
            $this->db->query('UPDATE users SET confirmed = 1 WHERE token= :token');
            $this->db->bind(':token', $token);
            // $this->db->query('UPDATE users SET token = NULL WHERE token= :token');
            // $this->db->bind(':token', $token);
        
            if($this->db->execute())
            {
                $this->db->query('UPDATE users SET token = null WHERE token= :token');
                $this->db->bind(':token', $token);
                if($this->db->execute()) return true;
                return true;
            }
            else{
                return false;
            }
        }

         // Get user by email
         public function getUserByToken($token)
         {
             $this->db->query('SELECT * FROM users WHERE token = :token');
             $this->db->bind(':token', $token);
             $row = $this->db->single();
             return $row;
         }
        
        // checkuserconfirmed
        public function checkuserconfirmed($username)
        {
            $this->db->query('SELECT * FROM users WHERE username = :username');
            $this->db->bind(':username', $username);
            $row = $this->db->single();
            $chek = $row->confirmed;
            if($chek == 1){
                return true;
            }else{
              return false;
            }
        } 

        //profilePicture
        public function updateTokenbyemail($data){
            $this->db->query('UPDATE users SET token = :token WHERE email = :email');
            $this->db->bind(':token', $data['token']);
            $this->db->bind(':email', $data['email']);
            if($this->db->execute())
                 return true;
            else
                 return false;
         }

        // changepassword
        public function changepass($data)
        {
            $pass = $data['password'];
            $token = $data['token'];
            // $this->db->query("SELECT * FROM users where token= :token");
            // $this->db->bind(':token', $token);
            // $result = $this->db->single();
            // if($result){
            $this->db->query("UPDATE users SET password=:password WHERE token= :token");
            $this->db->bind(':password', $pass);
            $this->db->bind(':token', $token);
            if($this->db->execute())
            {
                $this->db->query('UPDATE users SET token = null WHERE token= :token');
                $this->db->bind(':token', $token);
                if($this->db->execute()) return true;
                return true;
            }
            else{
                return false;
            }
            
        // }
        // else
        //     return false;
          
        }

        //profilePicture
        public function profilePic($path, $user_id){
            $this->db->query('UPDATE users SET profile_photo = :p WHERE id = :id');
            $this->db->bind(':id', $user_id);
            $this->db->bind(':p', $path);
            if($this->db->execute())
                 return true;
            else
                 return false;
         }

         public function get_commenter($user_id){
            $this->db->query('SELECT * FROM users WHERE id = :id');
            $this->db->bind(':id',$user_id);
            $result = $this->db->single();
            if($result)
            return ($result);
            else
            return false;
        } 

        public function get_dest($user_id){
            $this->db->query('SELECT * FROM users WHERE id = :id');
            $this->db->bind(':id',$user_id);
            $result = $this->db->single();
            if($result)
            return ($result);
            else
            return false;
        } 
    }
?>