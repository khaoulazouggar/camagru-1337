<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2>Create An Account</h2>
                <p class="text-info">Please fill out this form to register with us</p>
                <form action="<?php echo URLROOT; ?>/users/register" method="post">
                    <div class="form-group">
                        <label for="firstname">First name: <sup>*</sup></label>
                        <input type="text" name="firstname" class="form-control form-control-lg 
                        <?php echo (!empty($data['firstname_err'])) ? 'is-invalid' : ''; ?>" 
                        value="<?php echo $data['firstname'] ?>">
                        <span class="invalid-feedback"><?php echo $data['firstname_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last name: <sup>*</sup></label>
                        <input type="text" name="lastname" class="form-control form-control-lg 
                        <?php echo (!empty($data['lastname_err'])) ? 'is-invalid' : ''; ?>" 
                        value="<?php echo $data['lastname'] ?>">
                        <span class="invalid-feedback"><?php echo $data['lastname_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="username">Username: <sup>*</sup></label>
                        <input type="text" name="username" class="form-control form-control-lg 
                        <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" 
                        value="<?php echo $data['username'] ?>">
                        <span class="invalid-feedback"><?php echo $data['username_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email: <sup>*</sup></label>
                        <input type="email" name="email" class="form-control form-control-lg 
                        <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" 
                        value="<?php echo $data['email'] ?>">
                        <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="password">Password: <sup>*</sup></label>
                        <input type="password" name="password" class="form-control form-control-lg 
                        <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" 
                        value="<?php echo $data['password'] ?>">
                        <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password: <sup>*</sup></label>
                        <input type="password" name="confirm_password" class="form-control form-control-lg 
                        <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" 
                        value="<?php echo $data['confirm_password'] ?>">
                        <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
                    </div>

                    <div class="row">
                      <div class="col">
                        <input type="submit" value="Register" class="btn-success btn-block">
                      </div>
                      <div class="col">
                        <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-light btn-block">Have an account? Login</a>
                      </div>  
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>