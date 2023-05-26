<?= $this->extend('layouts/login_basetwo') ?>

<?= $this->section('content') ?>
<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 p-5">
         
				<form class="login100-form validate-form" action="<?= base_url('register') ?>" method="POST">
                <?php if($session->getFlashdata('error')): ?>
                        <div class="alert alert-danger rounded-0">
                            <?= $session->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    <?php if($session->getFlashdata('success')): ?>
                        <div class="alert alert-success rounded-0">
                            <?= $session->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
					<span class="login100-form-title">
                    Create New Account
					</span>

                    <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<!-- <input class="input100" type="text" name="email" placeholder="Email"> -->
                        <input type="text" class="input100 form-control rounded-0" id="name" name="name" autofocus placeholder="John Smith" value="<?= !empty($data->getPost('name')) ? $data->getPost('name') : '' ?>" required="required">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<!-- <input class="input100" type="text" name="email" placeholder="Email"> -->
                        <input type="email" class="input100 form-control rounded-0" id="email" name="email" autofocus placeholder="jsmith@mail.com" value="<?= !empty($data->getPost('email')) ? $data->getPost('email') : '' ?>" required="required">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>


                    <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<!-- <input class="input100" type="text" name="email" placeholder="Email"> -->
                        <input type="password" class="input100 form-control rounded-0" id="password" name="password" placeholder="Password" required="required">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>

                    <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<!-- <input class="input100" type="text" name="email" placeholder="Email"> -->
                        <input type="password" class="input100 form-control rounded-0" id="cpassword" name="cpassword" placeholder="Confirm Password" required="required">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>

				

					<!-- <div class="container-login100-form-btn">
						<a href="index-2.html" class="login100-form-btn btn-primary">
							Login
						</a>
					</div> -->
                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn btn-primary">Register</button>
                    </div>

					<div class="text-center pt-2">
					
						<!-- <a class="txt2" href="forgot.html">
							Username / Password?
						</a> -->
                        <a href="<?= base_url('/') ?>" class="txt2">Already have an Account?</a><br>
					</div>

			
				</form>
			</div>
		</div>
	</div>
    <?= $this->endSection() ?>