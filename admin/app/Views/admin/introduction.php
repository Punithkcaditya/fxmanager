<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
	<meta content="Start your development with a Dashboard for Bootstrap 4." name="description">
	<meta content="Spruko" name="author">

	<!-- Title -->
	<title>Ansta - Responsive Multipurpose Admin Dashboard Template</title>

	<!-- Favicon -->
	<link href="assets/img/brand/favicon.png" rel="icon" type="image/png">

	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800" rel="stylesheet">

	<!-- Icons -->
	<link href="assets/css/icons.css" rel="stylesheet">

	<!--Bootstrap.min css-->
	<link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">

	<!-- Ansta CSS -->
	<link href="assets/css/dashboard.css" rel="stylesheet" type="text/css">

	<!-- Custom scroll bar css-->
	<link href="assets/plugins/customscroll/jquery.mCustomScrollbar.css" rel="stylesheet" />

	<!-- Sidemenu Css -->
	<link href="assets/plugins/toggle-sidebar/css/sidemenu.css" rel="stylesheet">

</head>

<body class="app sidebar-mini rtl">
	<div class="row">
		<div class="col-md-12">
			<div class="card shadow">
				<div class="card-header">
					<h2 class="mb-5 text-center"><b>KYC Form</b></h2>
					<h3 class="mb-0 text-center">SHYAM SUNDER JEWELS LLP</h3>
					<h4 class="text-center">No. 207, Opp MORE Megastore, Outer Ring Road, Marathahalli, Bangalore - 560102</h4>
				</div>
				<form action="<?php echo base_url('addemployeedetails') ?>" method="POST" enctype="multipart/form-data">

					<div class="card-body">
						<div class="row">
							<div class="col-md-6">

								<div class="form-row mb-4">
									<label for="date" class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">Date of Joining:</label>
									<div class="col-9 col-sm-10 col-md-9 col-lg-10">
										<input type="hidden" name="employee_id" value="<?php echo $employee_id?>" required>
										<input class="form-control datepicker" name="ssjl_date" placeholder="MM/DD/YYYY" type="date" required>
									</div>
								</div>
								<div class="form-row mb-4">
									<label for="fullname" class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">Full Name:</label>
									<div class="col-9 col-sm-10 col-md-9 col-lg-10">
										<input type="text" class="form-control" name="ssjl_full_name" placeholder="Full Name" value="" required>
									</div>
								</div>
								<div class="form-row mb-4">
									<label for="mobile" class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">Mobile #:</label>
									<div class="col-9 col-sm-10 col-md-9 col-lg-10">
										<input type="number" class="form-control" name="ssjl_mobile" placeholder="Mobile" value="" required>
									</div>
								</div>
								<div class="form-row mb-4">
									<label for="email" class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">Email Id :</label>
									<div class="col-9 col-sm-10 col-md-9 col-lg-10">
										<input type="email" class="form-control" name="ssjl_email" placeholder="Email" value="" required>
									</div>
								</div>
								<div class="form-row mb-4">
									<label for="ssjl_blood_group" class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">Blood Group :</label>
									<div class="col-9 col-sm-10 col-md-9 col-lg-10">
										<input type="text" class="form-control" name="ssjl_blood_group" placeholder="Blood Group" value="" required>
									</div>
								</div>
								<div class="form-row mb-4">
									<label for="ssjl_uan_no" class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">UAN No :</label>
									<div class="col-9 col-sm-10 col-md-9 col-lg-10">
										<input type="number" class="form-control" name="ssjl_uan_no" placeholder="UAN No" value="" >
									</div>
								</div>
								<div class="form-row mb-4">
									<label for="ssjl_adhaar_no" class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">AADHAR No :</label>
									<div class="col-9 col-sm-10 col-md-9 col-lg-10">
										<input type="number" class="form-control" id="ssjl_adhaar_no" name="ssjl_adhaar_no" placeholder="AADHAR No" value="" required>
									</div>
								</div>
								<div class="form-row mb-4">
									<label for="ssjl_bank_name" class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">Bank Name:</label>
									<div class="col-9 col-sm-10 col-md-9 col-lg-10">
										<input type="text" class="form-control" name="ssjl_bank_name" placeholder="Bank Name" value="" required>
									</div>
								</div>
								<div class="form-row mb-4">
									<label for="ssjl_marital_status" class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">Marital Status:</label>
									<div class="col-9 col-sm-10 col-md-9 col-lg-10">
										<select name="ssjl_marital_status" id="ssjl_marital_status" class="form-control custom-select" required>
											<option value="Married" data-data=''>Married</option>
											<option value="UnMarried" data-data=''>UnMarried</option>
										</select>
									</div>
								</div>
								<div class="form-row mb-4">
									<label for="ssjl_emergency_contact_person" class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">Emergency Contact Person :</label>
									<div class="col-9 col-sm-10 col-md-9 col-lg-10">
										<input type="text" class="form-control" name="ssjl_emergency_contact_person" placeholder="Emergency Contact Person" value="" >
									</div>
								</div>
								<div class="form-row mb-4">
									<label for="ssjl_reference_person" class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">Reference Person:</label>
									<div class="col-9 col-sm-10 col-md-9 col-lg-10">
										<input type="text" class="form-control" name="ssjl_reference_person" placeholder="Reference Person" value="" >
									</div>
								</div>
								<div class="form-row mb-4">
									<label for="ssjl_health_issue_any" class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">Health Issue If any:</label>
									<div class="col-9 col-sm-10 col-md-9 col-lg-10">
										<input type="text" class="form-control" name="ssjl_health_issue_any" placeholder="Health Issue If any" value="" >
									</div>
								</div>

							</div>
							<div class="col-md-6">
								<div class="form-row mb-4">
									<div class="col-9 col-sm-10 col-md-9 col-lg-10">
										<input type="file" id="image" name="ssl_profile_pic" accept="image/png, image/jpeg, image/jpg" required>
										<img id="preview" src="<?= base_url("uploads/cover-image/save_latest.png") ?> " alt="Preview">
									</div>
								</div>

							

								<div class="form-row mb-4">
									<label for="ssjl_date_of_birth" class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">Date of Birth:</label>
									<div class="col-9 col-sm-10 col-md-9 col-lg-10">
										<input class="form-control datepicker" name="ssjl_date_of_birth" placeholder="Date of Birth" type="date" required>
									</div>
								</div>

								<div class="form-row mb-4">
									<label for="ssjl_pan_no" class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">PAN No:</label>
									<div class="col-9 col-sm-10 col-md-9 col-lg-10">
										<input class="form-control" name="ssjl_pan_no" placeholder="PAN No" type="text" required>
									</div>
								</div>
								<div class="form-row mb-4">
									<label for="ssjl_bank_ac_no" class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">Bank AC/ No:</label>
									<div class="col-9 col-sm-10 col-md-9 col-lg-10">
										<input class="form-control" name="ssjl_bank_ac_no" placeholder="Bank AC/ No" type="number" required>
									</div>
								</div>
								<div class="form-row mb-4">
									<label for="ssjl_fathers_name" class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">Fathers Name:</label>
									<div class="col-9 col-sm-10 col-md-9 col-lg-10">
										<input class="form-control" name="ssjl_fathers_name" placeholder="Fathers Name" type="text" required>
									</div>
								</div>
								<div class="form-row mb-4">
									<label for="ssjl_emergency_contact_no" class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">Emergency Contact No:</label>
									<div class="col-9 col-sm-10 col-md-9 col-lg-10">
										<input class="form-control" name="ssjl_emergency_contact_no" placeholder="Emergency Contact No" type="number" required>
									</div>
								</div>
								<div class="form-row mb-4">
									<label for="ssjl_contact_no" class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">Contact No:</label>
									<div class="col-9 col-sm-10 col-md-9 col-lg-10">
										<input class="form-control" name="ssjl_contact_no" placeholder="Contact No" type="number" required>
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<div class="card shadow">
									<div class="card-header">
										<h2 class="mb-0">DOCUMENTS ATTACHED</h2>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<div class="form-row mb-4">
													<label for="ssjl_resume" class="col-5 col-sm-5 col-md-5 col-lg-5 col-form-label">Resume:</label>
													<div class="col-7 col-sm-7 col-md-7 col-lg-7">
														<input type="file" name="ssjl_resume" class="dropify" data-height="300" accept="image/png, image/jpeg, image/jpg, application/pdf" required />
													</div>
												</div>

												<div class="form-row mb-4">
													<label for="ssjl_contact_no" class="col-5 col-sm-5 col-md-5 col-lg-5 col-form-label">AADHAR COPY:</label>
													<div class="col-7 col-sm-7 col-md-7 col-lg-7">
														<input type="file" name="ssjl_adhaar_copy" class="dropify" data-height="300" accept="image/png, image/jpeg, image/jpg, application/pdf" required />
													</div>
												</div>

												<div class="form-row mb-4">
													<label for="ssjl_releiving_letter" class="col-5 col-sm-5 col-md-5 col-lg-5 col-form-label">Reliving Letter from previous employer:</label>
													<div class="col-7 col-sm-7 col-md-7 col-lg-7">
														<input type="file" name="ssjl_releiving_letter" class="dropify" data-height="300" accept="image/png, image/jpeg, image/jpg, application/pdf" required />
													</div>
												</div>

												<div class="form-row mb-4">
													<label for="ssjl_education_documents" class="col-5 col-sm-5 col-md-5 col-lg-5 col-form-label">Education
														qualification supporting documents:</label>
													<div class="col-7 col-sm-7 col-md-7 col-lg-7">
														<input type="file" name="ssjl_education_documents" class="dropify" data-height="300" accept="image/png, image/jpeg, image/jpg, application/pdf" required />
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-row mb-4">
													<label for="ssjl_pan_copy" class="col-5 col-sm-5 col-md-5 col-lg-5 col-form-label">PAN COPY:</label>
													<div class="col-7 col-sm-7 col-md-7 col-lg-7">
														<input type="file" name="ssjl_pan_copy" class="dropify" data-height="300" accept="image/png, image/jpeg, image/jpg, application/pdf" required />
													</div>
												</div>

												<div class="form-row mb-4">
													<label for="ssjl_previous_pay_slips" class="col-5 col-sm-5 col-md-5 col-lg-5 col-form-label">Previous Last 3 months pay slips:</label>
													<div class="col-7 col-sm-7 col-md-7 col-lg-7">
														<input type="file" name="ssjl_previous_pay_slips" class="dropify" data-height="300" accept="image/png, image/jpeg, image/jpg, application/pdf" required />
													</div>
												</div>

												<div class="form-row mb-4">
													<label for="ssjl_rent_agreement" class="col-5 col-sm-5 col-md-5 col-lg-5 col-form-label">Rent Agreement /permanent address proff:</label>
													<div class="col-7 col-sm-7 col-md-7 col-lg-7">
														<input type="file" name="ssjl_rent_agreement" class="dropify" data-height="300" accept="image/png, image/jpeg, image/jpg, application/pdf" required />
													</div>
												</div>
											</div>
											<div class="col-md-12" style="text-align: center;">
												<div class="d-grid gap-1">
													<button class="btn rounded-0 btn-primary bg-gradient" type="submit">Save</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
			</div>
		</div>
		</form>


		<script src="assets/plugins/jquery/dist/jquery.min.js"></script>
		<script src="assets/js/popper.js"></script>
		<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

		<!-- Optional JS -->
		<script src="assets/plugins/chart.js/dist/Chart.min.js"></script>
		<script src="assets/plugins/chart.js/dist/Chart.extension.js"></script>

		<!-- Data tables -->
		<script src="assets/plugins/datatable/jquery.dataTables.min.js"></script>
		<script src="assets/plugins/datatable/dataTables.bootstrap4.min.js"></script>

		<!-- Fullside-menu Js-->
		<script src="assets/plugins/toggle-sidebar/js/sidemenu.js"></script>

		<!-- Custom scroll bar Js-->
		<script src="assets/plugins/customscroll/jquery.mCustomScrollbar.concat.min.js"></script>

		<!-- Ansta JS -->
		<script src="assets/js/custom.js"></script>



		<script>
			$(document).ready(function() {
				$("#image").change(function() {
					console.log('pp');
					readURL(this);
				});
			});

			function readURL(input) {
				console.log(input);
				if (input.files && input.files[0]) {
					var reader = new FileReader();

					reader.onload = function(e) {
						$('#preview').attr('src', e.target.result);
					};

					reader.readAsDataURL(input.files[0]);
				}
			}
		</script>


</body>

</html>