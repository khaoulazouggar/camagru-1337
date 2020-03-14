<?php require APPROOT . '/views/inc/header.php'; ?>

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
            <?php echo flash("edit_success"); ?>
                <h2>Edit your Account</h2>
                <p class="text-info">Please fill out this form to edit your Account</p>
                <form action="<?php echo URLROOT; ?>/users/edit" method="post">
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                    <div class="form-group">
                        <label for="edit_firstname">New First Name: </label>
                        <input type="text" name="edit_firstname" class="form-control form-control-lg 
                        <?php echo (!empty($data['edit_firstname_err'])) ? 'is-invalid' : ''; ?>" 
                        value="<?php echo $data['edit_firstname'] ?>">
                        <span class="invalid-feedback"><?php echo $data['edit_firstname_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_lastname">New Last Name: </label>
                        <input type="text" name="edit_lastname" class="form-control form-control-lg 
                        <?php echo (!empty($data['edit_lastname_err'])) ? 'is-invalid' : ''; ?>" 
                        value="<?php echo $data['edit_lastname'] ?>">
                        <span class="invalid-feedback"><?php echo $data['edit_lastname_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_username">New User Name: </label>
                        <input type="text" name="edit_username" class="form-control form-control-lg 
                        <?php echo (!empty($data['edit_username_err'])) ? 'is-invalid' : ''; ?>" 
                        value="<?php echo $data['edit_username'] ?>">
                        <span class="invalid-feedback"><?php echo $data['edit_username_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">New Email: </label>
                        <input type="email" name="edit_email" class="form-control form-control-lg 
                        <?php echo (!empty($data['edit_email_err'])) ? 'is-invalid' : ''; ?>" 
                        value="<?php echo $data['edit_email'] ?>">
                        <span class="invalid-feedback"><?php echo $data['edit_email_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_new_password">New Password: </label>
                        <input type="password" name="edit_new_password" class="form-control form-control-lg 
                        <?php echo (!empty($data['edit_new_password_err'])) ? 'is-invalid' : ''; ?>" 
                        value="<?php echo $data['edit_new_password'] ?>">
                        <span class="invalid-feedback"><?php echo $data['edit_new_password_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="new_confirm_password">Confirm Password: </label>
                        <input type="password" name="new_confirm_password" class="form-control form-control-lg 
                        <?php echo (!empty($data['new_confirm_password_err'])) ? 'is-invalid' : ''; ?>" 
                        value="<?php echo $data['new_confirm_password'] ?>">
                        <span class="invalid-feedback"><?php echo $data['new_confirm_password_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_password">Old Password: <sup>*</sup></label>
                        <input type="password" name="edit_password" class="form-control form-control-lg 
                        <?php echo (!empty($data['edit_password_err'])) ? 'is-invalid' : ''; ?>" 
                        value="<?php echo $data['edit_password'] ?>">
                        <span class="invalid-feedback"><?php echo $data['edit_password_err']; ?></span>
                    </div>
                    <div>
                        <?php if($_SESSION['notif'] == 1) {?>
                            <input type="checkbox" name="checkbox_send_notif" checked><i class="fa fa-envelope-o fa-fw"></i>
                            <label class="form-check-label" for="materialIndeterminate2">Receive notifications by email</label>
                        <?php }else {?>
                            <input type="checkbox" name="checkbox_send_notif" unchecked><i class="fa fa-envelope-o fa-fw"></i>
                            <label class="form-check-label" for="materialIndeterminate2">Receive notifications by email</label>
                        <?php }?>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col">
                        <input type="submit" value="Edit" class="btn btn-success btn-block">
                      </div>
                      <div class="col">
                        <a href="<?php echo URLROOT; ?>/pages/index" class="btn btn-outline-info btn-block"><i class="fa fa-backward"></i> Back to index</a>
                      </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>