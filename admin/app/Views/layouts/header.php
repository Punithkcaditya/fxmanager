<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
	<meta content="Start your development with a Dashboard for Bootstrap 4." name="description">
	<meta content="Spruko" name="author">
  

	<!-- Title -->
    <title><?= isset($page_title) ? $page_title.' | ' : "" ?><?= env('system_name') ?></title>

	<!-- Favicon -->
	<link href="<?php echo base_url('assets/img/brand/favicon.png') ?>" rel="icon" type="image/png">

	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800" rel="stylesheet">

	<!-- Icons -->
	<link href="<?php echo base_url('assets/css/icons.css') ?>" rel="stylesheet">

    <!-- bootstrap multiselect -->
    
	
    <!--Bootstrap.min css-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>">

	<!-- Ansta CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/dashboard.css'); ?>">

	<!-- Tabs CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/tabs/style.css'); ?>">

	<!-- jvectormap CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/jvectormap/jquery-jvectormap-2.0.2.css'); ?>">

	<!-- Custom scroll bar css-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/customscroll/jquery.mCustomScrollbar.css'); ?>">

    	
    <!-- Data table css -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/datatable/dataTables.bootstrap4.min.css'); ?>"  >
	<!-- Sidemenu Css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/toggle-sidebar/css/sidemenu.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/fonts/css/fontawesome-all.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/fileuploads/css/dropify.css'); ?>">

    <!-- Ansta Scripts -->
	<!-- Core -->   
		<!--Select2 css-->
	<link rel="stylesheet" href="assets/plugins/select2/select2.css">
	
	
		<!-- sweetalert css-->
	<link href="assets/plugins/sweet-alert/sweetalert.css" rel="stylesheet" />
     
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" /> -->
<!-- Calendar -->


<!-- calendar end -->

    <script src="https://code.jquery.com/jquery-3.4.1.js"></script> 
   <!-- fe icon -->
   <script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>
   <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>


</head>
<body class="app sidebar-mini rtl" >
<?= $this->renderSection('content') ?>

<script type="text/javascript" src="<?php echo base_url('assets/plugins/jquery/dist/jquery.min.js'); ?>"></script>

<!-- Data tables -->
<script type="text/javascript" src="<?php echo base_url('assets/plugins/datatable/jquery.dataTables.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/plugins/datatable/dataTables.bootstrap4.min.js'); ?>"></script>


<script type="text/javascript" src="<?php echo base_url('assets/js/popper.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js'); ?>"></script>

<!--  bootstrap multiselect js -->

	<!-- Echarts JS -->


	<!-- Fullside-menu Js-->
    <script type="text/javascript" src="<?php echo base_url('assets/plugins/toggle-sidebar/js/sidemenu.js'); ?>"></script>

	<!-- Custom scroll bar Js-->
    <script type="text/javascript" src="<?php echo base_url('assets/plugins/customscroll/jquery.mCustomScrollbar.concat.min.js'); ?>"></script>

	<!-- peitychart -->
    <script type="text/javascript" src="<?php echo base_url('assets/plugins/peitychart/jquery.peity.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/plugins/peitychart/peitychart.init.js'); ?>"></script>

	<!-- Vector Plugin -->
    <script type="text/javascript" src="<?php echo base_url('assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/plugins/jvectormap/gdp-data.js'); ?>"></script>

    <script type="text/javascript" src="<?php echo base_url('assets/plugins/jvectormap/jquery-jvectormap-us-aea-en.js'); ?>"></script>

    <script type="text/javascript" src="<?php echo base_url('assets/plugins/jvectormap/jquery-jvectormap-uk-mill-en.js'); ?>"></script>

    <script type="text/javascript" src="<?php echo base_url('assets/plugins/jvectormap/jquery-jvectormap-au-mill.js'); ?>"></script>

    <script type="text/javascript" src="<?php echo base_url('assets/plugins/jvectormap/jquery-jvectormap-ca-lcc.js'); ?>"></script>

    <script type="text/javascript" src="<?php echo base_url('assets/js/dashboard2map.js'); ?>"></script>
 
    <script type="text/javascript" src="<?php echo base_url('assets/plugins/fileuploads/js/dropify.min.js'); ?>"></script>
 
	<!-- calendar -->
	<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
	<!-- Ansta JS -->
    <script type="text/javascript" src="<?php echo base_url('assets/js/custom.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/customtwo.js'); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
<!-- multi sect -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


    <script>
		$('.datepicker').datepicker({
		showOtherMonths: true,
		selectOtherMonths: true,
		format: "dd/mm/yyyy",
        autoclose: true
		});

	$('.dropify').dropify({
	messages: {
		'default': 'Drag and drop a file here or click',
		'replace': 'Drag and drop or click to replace',
		'remove': 'Remove',
		'error': 'Ooops, something wrong appended.'
	},
	error: {
		'fileSize': 'The file size is too big (2M max).'
	}
	});

	</script>
</body>
</html>