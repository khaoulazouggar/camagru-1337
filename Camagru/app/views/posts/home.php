<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container">
    <!-- paginnation -->
    <nav aria-label="Page navigation example justify-content-center">
        <ul class="pagination justify-content-center">
            <?php 

            if(($data['currentPage']-1) > 0)
                echo '<li class="page-item"><a class="page-link" href="http://localhost/Camagru/posts/home?page='.($data['currentPage']-1).'">Previous</a></li>';
            else
                echo '<li class="page-item"><a class="page-link">Previous</a></li>';

            for($i =1; $i <= $data['totalPages']; $i++){
                if($i == $data['currentPage'])
                    echo '<li class="page-item"><a class="page-link">'.$i.'</a></li>';
                else
                    echo '<li class="page-item"><a class="page-link" href="http://localhost/Camagru/posts/home?page='.$i.'">'.$i.'</a></li>';
            }
            if(($data['currentPage']+1) <= $data['totalPages'])
                echo '<li class="page-item"><a class="page-link" href="http://localhost/Camagru/posts/home?page='.($data['currentPage']+1).'">Next</a></li>';
            else
                echo '<li class="page-item"><a class="page-link">Next</a></li>';

            ?>
        </ul>
    </nav>

    <div class="row justify-content-center">
        <div class="col-m-6">
            <?php if(is_array($data['posts'])){
                foreach ($data['posts'] as $post) :?>
                <div class="shadow mb-5">
                    <div class="card">
                            <!-- header -->
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="mr-2">
                                        
                                            <img class="rounded-circle" width="50" height="50" src="<?php echo $post->profile_photo;?>" alt="">
                                        </div>
                                        <div >
                                            <div class="h5">@<?php echo $post->username;?></div>
                                            <div class="text-muted"><?php echo $post->firstname; echo " "; echo$post->lastname;?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- body -->
                            <div class="card-body">
                                <div class="text-muted mb-2"> <i class="fa fa-clock-o"></i><?php echo $post->createin;?></div>
                            
                                <img src="<?php echo $post->path1; ?>" class="img img-fluid" width="495" height="400">
                            </div>
                            <!-- footer -->
                            <div class="card-footer">
                                <!-- like -->
                                <?php if(is_array($data['likes'])){
                                    $liked = false;    
                                    foreach ($data['likes'] as $like) {
                                    
                                        if ($like->user_id == $_SESSION['user_id'] && $like->posts_id == $post->postId) {
                                            $liked = true; ?>
                                            <i class = "fa fa-heart"
                                            data-post_id="<?php echo $post->postId; ?>" 
                                            data-user_id="<?php echo $_SESSION['user_id']; ?>" 
                                            data-like_nbr="<?php echo $post->like_nbr;?>" 
                                            onclick="like(event)"
                                            id="l_<?php echo $post->postId;?>"
                                            name="li_<?php echo $post->postId;?>">    
                                            </i>
                                            <?php
                                        }
                                    }
                                    if ($liked === false) {?>
                                        <i class = "fa fa-heart-o"  
                                        data-post_id="<?php echo $post->postId;?>" 
                                        data-like_nbr="<?php echo $post->like_nbr;?>" 
                                        data-user_id="<?php echo $_SESSION['user_id'];?>" 
                                        onclick="like(event)" id="l_<?php echo $post->postId;?>"
                                        name="li_<?php echo $post->postId;?>"> 
                                        </i>
                                    <?php }
                                }?>
                                <a id="li_nb_<?php echo $post->postId;?>" class="card-link text-muted">
                                <?php if ($post->like_nbr == 0){echo "";} else echo $post->like_nbr;?></a>
                                <!-- comments -->
                                <a class="card-link"><i class="fa fa-comment"></i> Comments</a>   
                                <div class=" mt-2" >
                                    <textarea  class="form-control mb-2" placeholder="write a comment..." style="resize:none"></textarea>
                                    <button data-c-user_id="<?php echo $_SESSION['user_id'];?>"
                                    data-c-post_id="<?php echo $post->postId;?>" class="btn btn-info w-25 pull-right" name="cmnt">Add</button>
                                    <br>    
                                </div>
                                <?php
                                    if(is_array($data['comments']))
                                    {
                                    foreach($data['comments'] as $comment)
                                    {
                                        if($comment->posts_id == $post->postId)
                                        {
                                        ?>
                                            <hr class="mb-1 mt-4">
                                            <ul class="media-list">
                                                <li class="media">                    
                                                    <div class="media-body">
                                                        <p><?php echo htmlspecialchars($comment->comment);?></p>
                                                    </div>
                                                </li>
                                            </ul>
                                        <?php
                                        }
                                    }
                                }?>         
                        </div>
                    </div>       
                </div>
              <br>
            <?php endforeach; }?>
        </div>
    </div> 
</div>

<script src="<?php echo URLROOT;?>/public/js/home.js"></script>
<?php require APPROOT . '/views/inc/footer.php'; ?>