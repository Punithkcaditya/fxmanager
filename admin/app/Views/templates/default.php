<?= $this->extend("layouts/header") ?>


<?= $this->section("content") ?>
<div id="global-loader"></div>
<div class="page">
    <div class="page-main">
        <!-- Sidebar menu-->
        <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
        <aside class="app-sidebar ">

            <div class="sidebar-img">
                <?= $this->include("sidemenu/sidemenu") ?>
            </div>

        </aside>
        <!-- Sidebar menu-->

        	<!-- app-content-->
		<div class="app-content ">
			<div class="side-app">
				<div class="main-content">
                    	<!-- Top navbar -->
					<?= $this->include("navbar/navbar") ?>
					<!-- Top navbar-->
					
			
						
                         <?= $this->include($view) ?>

     			<!-- Footer -->
                 <?= $this->include("footer/footer") ?>
						<!-- Footer -->
					</div>
				</div>
			</div>
		</div>
</div>
<!-- Back to top -->
<a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>
<?= $this->endSection() ?>
