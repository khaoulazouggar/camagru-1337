  
    <nav class="navbar navbar-expand-lg navbar-dark bg-info mb-3">
  <div class="container">
    <a class="navbar-brand" href="<?php echo URLROOT; ?>"><?php echo SITENAME; ?></a>
    <button class="navbar-toggler" type="button" id="btn" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URLROOT; ?>/posts/home">Home</a>
        </li>
      </ul>

      <ul class="navbar-nav ml-auto">
        <?php if(isset($_SESSION['user_id'])) : ?> 
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URLROOT; ?>/users/edit"><?php echo $_SESSION['user_username'];?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URLROOT; ?>/posts/webcam">Camera</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URLROOT; ?>/users/logout">Logout</a>
        </li>
        <?php else : ?>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URLROOT; ?>/users/register">Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URLROOT; ?>/users/login">Login</a>
        </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<script>
document.getElementById('btn').addEventListener('click', function(){
 if( document.getElementById('navbarsExampleDefault').style.display == "block")
   document.getElementById('navbarsExampleDefault').style.display = "none";
 else
   document.getElementById('navbarsExampleDefault').style.display = "block";
 });
</script>