<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- The buttons to control the stream -->
<div class="button-group">
  <button id="btn-start" type="button" class="btn btn-danger">Start Streaming</button>
  <button id="btn-capture" type="button" class="btn btn-secondary">Capture Image</button>
  <button id="btn-save" type="button" class="btn btn-success">Save Image</button>
  <button class="btn btn-info" id="clear" disabled>Clear</button>
</div>

<!-- Video Element & Canvas -->

  <div class="container row">
  
    <div class="col">
      <div class="play-area-sub">
        <h3 style="text-align: center;">The Stream</h3>
        <div>
          <img id="imgfilter" class="img-fluid" width="150" height="150" style="display: none; position: absolute;">
          <video  id="stream"  width="400" height="300"></video>
          <br>
					<div class="form-check form-check-inline">
		  				<input class="form-check-input" type="radio" name="filter" id="Donut" value="../public/img/Donut.png">
		  				<img src="../public/img/Donut.png" width="64" height="64">
					</div>
					<div class="form-check form-check-inline">
		  				<input class="form-check-input" type="radio" name="filter" id="Star" value="../public/img/Star.png">
		  				<img src="../public/img/Star.png" width="64" height="64">
					</div>
					<div class="form-check form-check-inline">
		  				<input class="form-check-input" type="radio" name="filter" id="Happy" value="../public/img/Happy.png">
		  				<img src="../public/img/Happy.png" width="64" height="64">
          </div>
          <br><br>
          <input id="file" type="file"  name="file" class="btn btn-info" accept="image/jpg, image/jpeg, image/png"></input><br>
        </div>
      </div>
    </div>
   
      <div class="col">
        <h3 style="text-align: center;">The Capture</h3>
        <div  width="400" height="300">
          <canvas class="img-fluid" id="canvas" width="400" height="300"></canvas>
          <canvas  id="canvas2" width="400" height="300"></canvas>
        </div>
      </div>

    </div>

    <div class="col">
			<div class="card border-0 text-white">
				<div>
          <div class="card bg-info">
            <div class="card-title"><h3 style="text-align: center;">Photos</h3></div>
          </div>
		            <hr class="bg-white mt-2 mb-5">
		    	<div style="width:100%;height: 800px; overflow-y:auto; overflow-x:hidden;">
			        <div class="row text-center">
                  <?php if(is_array($data['posts'])){
                          foreach($data['posts'] as $posts):
                          ?>
			                    <a class="m-3 pr-5 pt-1 pl-5 pb-5">
                            <img class="img-fluid img-thumbnail" src="<?php echo $posts->path;?>" height="100%" width="100%">
                            <form action="<?php echo  URLROOT;?>/posts/deletePost" method="POST">
                            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                              <button type="submit" name="submit1" class="btn btn-danger w-100 fa fa-trash-o fa-fw"  value="<?php echo $posts->id;?>"> Delete</button>
                            </form>
                            <form action="<?php echo  URLROOT;?>/posts/profilePic" method="POST">
                            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                              <button type="submit" name="submit2" class="btn btn-info  w-100 fa fa-user-circle-o fa-fw" value="<?php echo $posts->path;?>"> set as profile picture</button>
                            </form>
			                    </a><br>			            
			            <?php endforeach;}?>
			        </div>
					</div>
				</div>
			</div>
		</div>

  </div>

<script src="<?php echo URLROOT;?>/public/js/webcam.js"></script>
<?php require APPROOT . '/views/inc/footer.php'; ?>