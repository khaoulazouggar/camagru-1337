<?php
    class Posts extends Controller{
        public function __construct(){
             $this->postModel = $this->model('Post');
             $this->userModel = $this->model('User');
        }

        public function SaveImage(){
          if(isset($_SESSION['user_id']))
            {
            if(isset($_POST['imgData']) && isset($_POST['filtrsticker'])){
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $upload_dir = "../public/img/";
                $img = $_POST['imgData'];
                $filter = $_POST['filtrsticker'];
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $file = $upload_dir . mktime().'.png';
                file_put_contents($file, $data);
                $sourceImage = $filter;
                $destImage = $file;
                list($srcWidth, $srcHeight) = getimagesize($sourceImage);
                $src = imagecreatefrompng($sourceImage);
                $dest = imagecreatefrompng($destImage);
                imagecopyresized($dest, $src, 0, 0, 0, 0, 150, 150, $srcWidth, $srcHeight);
                imagepng($dest, $file, 9);
                move_uploaded_file($dest, $file);
                
                $data = [
                    'user_id'  => $_SESSION['user_id'],
                    'path' => $file,
                ];
                  if($this->postModel->save($data)){
                  }else
                    return false;
            }
            }else
            {
              redirect('pages/index');
            }
        }

        public function webcam(){
            if(isset($_SESSION['user_id']))
            {
             $posts = $this->postModel->getImagesbyUsr($_SESSION['user_id']);
             $data = ['posts' => $posts];
                 $this->view('posts/webcam', $data);
            }else
            {
              redirect('pages/index');
            }
             
         }

        public function deletePost(){
            if(isset($_SESSION['user_id']))
            {
              //On vérifie que tous les jetons sont là
              if (isset($_SESSION['token']) AND isset($_POST['token']) AND !empty($_SESSION['token']) AND !empty($_POST['token'])){
                if ($_SESSION['token'] == $_POST['token']){

                  $data = $this->postModel->getImagesbyUsr($_SESSION['user_id']);
                  if(isset($_POST['submit1'])){
                      $postId = $_POST['submit1'];
                      if($this->postModel->deletePost($postId, $_SESSION['user_id']))
                      {
                          redirect('posts/webcam');
                      }
                      else
                          die('ERROR');
                  }
                  $this->view('posts/webcam',$data);
                }else
                  redirect('pages/error');
              }else{                 
                  // Les token ne correspondent pas
                  redirect('pages/error');
              }
          }else
          {
            redirect('pages/index');
          }
        }

        public function profilePic()
        {
          if(isset($_SESSION['user_id']))
          {
            //On vérifie que tous les jetons sont là
            if (isset($_SESSION['token']) AND isset($_POST['token']) AND !empty($_SESSION['token']) AND !empty($_POST['token'])){
              if ($_SESSION['token'] == $_POST['token']){
                if(isset($_POST['submit2']))
                {
                    $path = $_POST['submit2'];
                    if($this->userModel->profilePic($path, $_SESSION['user_id']))
                    {
                        redirect('posts/home');
                    }
                    else
                      die('ERROR');
                }
              }else
              redirect('pages/error');
            }else{ 
              // Les token ne correspondent pas
              redirect('pages/error');
            }
            }else
            {
              redirect('pages/index');
            }
        }


             //////////home////////////////
        public function home(){
        
            $postsPerPage = 5;
            $totalPosts = $this->postModel->count_posts();
            $totalPages = ceil($totalPosts/$postsPerPage);
        
            if(isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 AND $_GET['page'] <= $totalPages){
        
              $_GET['page'] = intval($_GET['page']);
              $currentPage = $_GET['page'];    
            }else
              $currentPage = 1;
        
            $depart = ($currentPage - 1) * $postsPerPage;
            
              $posts = $this->postModel->get_posts($depart, $postsPerPage);
              $likes = $this->postModel->getlikes();
              $comments = $this->postModel->getcomments();
              $data =[
                'comments'=> $comments,
                'likes' => $likes,
                'posts' => $posts,
                'totalPages' => $totalPages,
                'currentPage' => $currentPage,
                'depart' => $depart,
              ];
              $this->view('posts/home',$data);
            }

          public function comment(){
            if(isset($_SESSION['user_id']))
            {
              if(isset($_POST['c_post_id']) && isset($_POST['c_user_id']) && isset($_POST['comment']) && !empty($_POST['comment']))
              {
                  $data = [
                      'posts_id'=> $_POST['c_post_id'],
                      'user_id' => $_POST['c_user_id'],
                      'comment' => $_POST['comment'],
                  ];
                  $com = $this->userModel->get_commenter($data['user_id']);
                  $uid = $this->postModel->getUserByPostId($data['posts_id']);
                  $d = $this->userModel->get_dest($uid->user_id);
                  if($this->postModel->addComment($data) && $d->notif == 1)
                  {
                    $destinataire = $d->email;
                    $sujet = "Your post has been commented" ;
                    $message = '
                    <p>Hi,
                        <br /><br />
                        @'.$com->username.', commented your post .
                    </p>
                    <p>
                        <br />--------------------------------------------------------
                        <br />This is an automatic mail , please do not reply.
                    </p> ';
              
                    $headers = "Content-type:text/html;charset=UTF-8" . "\r\n";
            
                    mail($destinataire, $sujet, $message, $headers); 
                      
                  }
              }
            }else
            {
              redirect('pages/index');
            }
          }
        
        public function Like(){
          if(isset($_SESSION['user_id']))
          {
            if(isset($_POST['post_id']) && isset($_POST['user_id']) && isset($_POST['c']) && isset($_POST['like_nbr']))
            {
                $data = [
                    'posts_id'=> $_POST['post_id'],
                    'user_id' => $_POST['user_id'],
                    'c' => $_POST['c'],
                    'like_nbr' => $_POST['like_nbr']
                ];
                 $this->postModel->like_nbr($data);
                if($data['c'] == 'fa fa-heart')
                {
                  
                  if($this->postModel->deleteLike($data))
                  {
    
                  }
                  else
                  {
                    die('something went wrong');
                  }
                }
                else if($data['c'] == 'fa fa-heart-o')
                {
                  
                  if($this->postModel->addLike($data))
                  {
                  }
                  else
                  {
                    die('something went wrong');
                  }
                }
                   
            }
          }else
          {
            redirect('pages/index');
          }
        }         
    }
?>