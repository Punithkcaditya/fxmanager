
					<div class="container-fluid pt-8">
					<?= $this->include('message/message') ?>
				</div>

				<!-- graph dashboard -->
				<div class="row">
					<div class="col-xl-12"><h2 class="text-center mb-3">Currency-wise Total Exposure = Inwards + 
Outwards</h2></div>
								<div class="col-xl-6">
									<div class="card  shadow overflow-hidden">
										<div class="card-header bg-transparent ">
											<div class="row align-items-center">
												<div class="col">
													<h6 class="text-uppercase text-light ls-1 mb-1">Overview</h6>
													<h2 class="mb-0">Quarterly Bar Chart</h2>
												</div>

											</div>
										</div>
										<div class="card-body">
											<!-- Chart -->
											<div id="echart" class="chart-dropshadow h-400"></div>
										</div>
									</div>
								</div>
								<div class="col-xl-6">
									<div class="card  shadow overflow-hidden">
										<div class="card-header bg-transparent">
											<div class="row align-items-center">
												<div class="col">
													<h6 class="text-uppercase text-light ls-1 mb-1">Overview</h6>
													<h2 class="mb-0">Monthly PIE Chart</h2>
												</div>

											</div>
										</div>
										<div class="card-body">
											<!-- Chart -->
											<div id="echart2" class="chart-dropshadow h-400"></div>
										</div>
									</div>
								</div>
							</div>

							<!-- chart 2 -->

							
							<div class="row">
								<div class="col-xl-6">
									<div class="card  shadow">
										<div class="card-header bg-transparent">
											<div class="row align-items-center">
												<div class="col">
													<h6 class="text-uppercase text-light ls-1 mb-1"></h6>
													<h2 class="mb-0">Currency-wise Total FX Amount V/S Hedged 
Amount Monthly</h2>
												</div>

											</div>
										</div>
										<div class="card-body">
											<!-- Chart -->
											<canvas id="lineChart" class="chart-dropshadow h-285"></canvas>
										</div>
									</div>
								</div>
								<div class="col-xl-6">
								<div class="card shadow">
										<div class="card-header bg-transparent">
											<div class="row align-items-center">
												<div class="col">
													<h6 class="text-uppercase text-muted ls-1 mb-1"></h6>
													<h2 class="mb-0">Currency-wise Total FX Amount V/S Hedged 
Amount Quarterly
</h2>
												</div>
											</div>
										</div>
										<div class="card-body">
											<!-- Chart -->
											<div class="chart">
												<div id="echart5" class="chart-dropshadow h-400"></div>
											</div>
										</div>
									</div>
								</div>
							</div>



							<!-- chart 3 -->

							<div class="row">
								<div class="col-xl-6">
									<div class="card  shadow">
										<div class="card-header bg-transparent">
											<div class="row align-items-center">
												<div class="col">
													<h6 class="text-uppercase text-light ls-1 mb-1"></h6>
													<h2 class="mb-0">Currency-wise % Hedged Monthly & 
Quarterly</h2>
												</div>

											</div>
										</div>
										<div class="card-body">
											<!-- Chart -->
											<canvas id="sales-chart" class="chart-dropshadow h-335"></canvas>
										</div>
									</div>
								</div>
								<div class="col-xl-6">
									<div class="card shadow">
										<div class="card-header bg-transparent">
											<div class="row align-items-center">
												<div class="col">
													<h6 class="text-uppercase text-muted ls-1 mb-1">Chart</h6>
													<h2 class="mb-0">Target Value V/S Current Portfolio Value 
Quarterly</h2>
												</div>
											</div>
										</div>
										<div class="card-body">
										<div id="echart1" class="h-400"></div>
										</div>
									</div>
								</div>
							</div>
<!-- scripts -->

	<!-- Echarts JS -->


	<!-- Adminx Scripts -->
	<!-- Core -->













		<!-- Optional JS -->
		<script src="<?php echo base_url('assets/plugins/chart.js/dist/Chart.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/plugins/chart.js/dist/Chart.extension.js') ?>"></script>

	<script src="<?php echo base_url('assets/plugins/chart-echarts/echarts.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/dashboard-sales.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/dashboard-it.js') ?>"></script>

	<script src="<?php echo base_url('assets/js/dashboard-marketing.js') ?>"></script>

	<script src="assets/js/echarts.js"></script>



