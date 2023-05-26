<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title.' | ' : "" ?><?= env('system_name') ?></title>

<!-- Favicon -->
<link href="assets/img/brand/favicon.png" rel="icon" type="image/png">

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800" rel="stylesheet">

<!-- Icons -->
<link href="<?php echo base_url('assets/css/icons.css'); ?>" rel="stylesheet">

<!--Bootstrap.min css-->
<link rel="stylesheet" href="<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>">

<!-- Ansta CSS -->
<link href="<?php echo base_url('assets/css/dashboard.css'); ?>" rel="stylesheet" type="text/css">

<!-- Single-page CSS -->
<link href="<?php echo base_url('assets/plugins/single-page/css/main.css'); ?>" rel="stylesheet" type="text/css">

</head>

<body class="bg-gradient-primary">
    <?= $this->renderSection('content') ?>
    <script src="<?php echo base_url('assets/plugins/jquery/dist/jquery.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/popper.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js'); ?>"></script>
</body>

</html>