<?php

class Post{
    private $db;

	public function __construct(){
		$this->db = new Database;
	}
	
	public function save($data){
       
		$this->db->query('INSERT INTO posts (user_id, path, created_at) VALUES(:user_id, :path, NOW())');
		$this->db->bind(':user_id', $data['user_id']);
		$this->db->bind(':path', $data['path']);

		if($this->db->execute()){
			return true;
		}else {
			return false;
		}
	}
	
	public function getImagesbyUsr($user_id){
		$this->db->query('SELECT * FROM posts WHERE user_id = :user_id ORDER BY created_at DESC');
		$this->db->bind(':user_id', $user_id);
		$result = $this->db->resultSet();
		if($result)
			return ($result);
		else
			return false;
	}

	public function deletePost($postId, $user_id){
		$this->db->query('DELETE FROM posts WHERE id = :post_id AND user_id = :user_id');
		$this->db->bind(':post_id', $postId);
		$this->db->bind(':user_id', $user_id);
		if($this->db->execute())
			return true;
		else
			return false;
   }


   			///////////////home//////////////////
   public function get_posts($depart, $postsPerPage){
	$this->db->query('SELECT *, posts.path as path1, posts.id as postId, users.id as userId, posts.created_at as createin, like_nbr as like_nb
					FROM `posts`
					INNER JOIN users 
					ON posts.user_id = users.id
					ORDER BY posts.created_at DESC LIMIT '.$depart.','.$postsPerPage.'');

	$r = $this->db->resultSet();
	if($r)
		return $r;
	else
		return false;
	}

	public function getcomments()
  {
    $this->db->query('SELECT * FROM `comments` INNER JOIN users ON comments.user_id = users.id');
    $result = $this->db->resultSet();
    if($result)
      return ($result);
    else
      return false;
  }

  public function addComment($data)
  {
      $this->db->query('INSERT INTO comments (user_id, posts_id, comment) VALUES (:user_id, :posts_id, :comment)');
        $this->db->bind(':user_id',$data['user_id']);
        $this->db->bind(':posts_id',$data['posts_id']);
        $this->db->bind(':comment',$data['comment']);

        if($this->db->execute())
        {
            return true;
        }else
            return false;
  }

  public function addLike($data)
    {
        $this->db->query('INSERT INTO likes (user_id, posts_id) VALUES (:user_id, :posts_id)');
        $this->db->bind(':user_id',$data['user_id']);
        $this->db->bind(':posts_id',$data['posts_id']);

        if($this->db->execute())
        {
            return true;
        }else
            return false;
    }
    public function deleteLike($data)
    {
        $this->db->query('DELETE FROM likes WHERE user_id = :user_id AND posts_id = :posts_id');
        $this->db->bind(':user_id',$data['user_id']);
        $this->db->bind(':posts_id',$data['posts_id']);

        if($this->db->execute())
        {
            return true;
        }else
            return false;
    }

  public function like_nbr($data)
  {
    $this->db->query('UPDATE posts SET like_nbr = :like_nbr WHERE id = :posts_id');

    $this->db->bind(':like_nbr', $data['like_nbr']);
    $this->db->bind(':posts_id', $data['posts_id']);

    if($this->db->execute()){
      return true;
    }else {
      return false;
    }
  }

  public function getlikes(){
	$this->db->query('SELECT * FROM likes');
	$result = $this->db->resultSet();
	return ($result);
}
  public function count_posts(){
    $this->db->query('SELECT count(*) FROM posts');

    $c = $this->db->ftchColumn();
    if($c)
      return $c;
    else
      return false;
  }

  public function getUserByPostId($postId){
    $this->db->query('SELECT * FROM posts WHERE id = :posts_id');
    $this->db->bind(':posts_id',$postId);

    $result = $this->db->single();
    if($result)
      return ($result);
    else
      return false;
  } 

}
?>