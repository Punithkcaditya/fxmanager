<nav class="navbar navbar-top  navbar-expand-md navbar-dark" id="navbar-main">
						<div class="container-fluid">
							<a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-toggle="sidebar" href="#"></a>

							<!-- Horizontal Navbar -->


							<!-- Brand -->
				
								<a class="navbar-brand d-none" style="color: rgb(0 195 237);" href="<?= base_url() ?>">FX</a>
							</a>
							<!-- Form -->

							<!-- User -->
							<ul class="navbar-nav align-items-center ">

								<li class="nav-item dropdown d-none d-sm-block">
									<a aria-expanded="false" aria-haspopup="true" class="nav-link pr-md-0" data-toggle="dropdown" href="#" role="button">
										<div class="media align-items-center">
											<span class="avatar avatar-sm rounded-circle">
											<img alt="Image placeholder" src="<?= base_url('assets/img/faces/female/32.jpg') ?>"></span>
											<div class="media-body ml-2 d-none d-lg-block">

											</div>
										</div>
									</a>
								
								</li>
								<li class="nav-item dropdown  d-md-flex" style="padding-right: 1em;">
									<a aria-expanded="false" aria-haspopup="true" class="nav-link pr-0" data-toggle="dropdown" href="#" role="button">
										<div class="media align-items-center">
											<i class="fe fe-user "></i>
										</div>
									</a>
									<div class="dropdown-menu dropdown-menu-lg dropdown-menu-arrow dropdown-menu-right">
										<a class="dropdown-item" href="<?= base_url('adminlogout') ?>"><span class="iconify" data-icon="fe:logout"></span> Logout</a>
									</div>
								</li>
							</ul>
						</div>
					</nav>  