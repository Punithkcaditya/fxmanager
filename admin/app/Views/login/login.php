<?= $this->extend('layouts/loginheader') ?>
<?= $this->section('content') ?>
<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 p-5">
				<form class="login100-form validate-form" action="<?= base_url('dashboard') ?>" method="post" id="myid"  accept-charset="utf-8">
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
					<?php if($session->getFlashdata('warning')): ?>
                        <div class="alert alert-warning rounded-0">
                            <?= $session->getFlashdata('warning') ?>
                        </div>
                    <?php endif; ?>
					<span class="login100-form-title">
						Admin Login
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<!-- <input class="input100" type="text" name="email" placeholder="Email"> -->

						<input type="text" class="input100 form-control rounded-0" id="email" name="user_name" autofocus placeholder="Enter User Name" >
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
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


	

		
                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn btn-primary">Login</button>
                    </div>

					<div class="text-center pt-2">
					
					
                      
					</div>

					<div class="text-center pt-1">
				
                 
                    </a>
					</div>
				</form>
			</div>
		</div>
	</div>
    <?= $this->endSection() ?>