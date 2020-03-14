<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="jumbotron jumbotron-flud text-center">
        <div class="container">
            <h1 class="display-3 text-info" ><?php echo $data['title']; ?></h1>
            <p class="lead"><?php echo $data['description']; ?></p>
            <h1 class="display-1"><?php echo $data['enjoy']; ?></h1>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>