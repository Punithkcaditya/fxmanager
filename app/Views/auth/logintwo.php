<?= $this->extend('layouts/login_basetwo') ?>

<?= $this->section('content') ?>
<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 p-5">

				<form class="login100-form validate-form" action="<?= base_url('login') ?>" method="POST">
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
						Member Login
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<!-- <input class="input100" type="text" name="email" placeholder="Email"> -->
                        <input type="email" class="input100 form-control rounded-0" id="email" name="email" autofocus placeholder="jsmith@mail.com" value="<?= !empty($data->getPost('email')) ? $data->getPost('email') : '' ?>" required="required">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<!-- <input class="input100" type="password" name="pass" placeholder="Password"> -->
                        <input type="password" class="input100 form-control rounded-0" id="password" name="password" placeholder="**********" required="required">
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
                        <button class="login100-form-btn btn-primary">Login</button>
                    </div>

					<div class="text-center pt-2">
					
						<!-- <a class="txt2" href="forgot.html">
							Username / Password?
						</a> -->
                        <a href="<?= base_url('/Auth/register') ?>" class="txt2">Create an Account</a><br>
					</div>

					<div class="text-center pt-1">
						<!-- <a class="txt2" href="register.html">
							Create your Account
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a> -->
                        <a href="<?= base_url('/Blog') ?>" class="txt2">Back to Site 
                        <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                    </a>
					</div>
				</form>
			</div>
		</div>
	</div>
    <?= $this->endSection() ?>