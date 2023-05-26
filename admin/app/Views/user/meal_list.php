<div class="container-fluid pt-8">
<?php echo $this->include('bottomtopbar/topbar') ?>

<div class="row">
<div class="col-md-12">
<div class="card shadow">
<div class="card-header">
<h2 class="mb-0"><?php echo $page_heading ?></h2>
</div>
<div class="card-body accordion2">

<?php foreach ($query as $key): ?>

<div class="panel-group1" id="accordion<?php echo $key['day_id'] ?>">
<div class="panel panel-default mb-4">
<div class="panel-heading1 ">
<h4 class="panel-title1">
<a class="accordion-toggle collapsed" data-toggle="collapse"
data-parent="#accordion<?php echo $key['day_id'] ?>"
href="#collapse<?php echo $key['day_id'] ?>"
aria-expanded="false"><?php 
$date = date_create($key['date']);
echo date_format($date, 'M d Y'); ?></a>
</h4>
</div>
<div id="collapse<?php echo $key['day_id'] ?>" class="panel-collapse collapse"
role="tabpanel" aria-expanded="false">
<div class="panel-body">
<div class="card shadow">
<div class="card-body" style="padding: 1rem;display: flex;justify-content: space-around;">
<a href="<?php echo base_url($mealedit.'/'.$key['day_id']) ?>" class="mx-2 text-decoration-none text-primary"><i class="fa fa-edit"></i></a>
<a href="<?php echo base_url($mealdelete.'/'.$key['day_id']) ?>" class="mx-2 text-decoration-none text-danger" onclick="if(confirm('Are you sure to delete  - <?php echo 'tesr' ?> from list?') !== true) event.preventDefault()"><i class="fa fa-trash"></i></a>
</div>
</div>

<div class="row">
<?php
$string = $key['mealtitle_concatenated'];
$stringtwo = $key['meal_description_concatenated'];
$stringthree = $key['mealtime_concatenated'];
$arrayone = explode("/bk", $string);
$arraytwo = explode("/bu", $stringtwo);
$arraythree = explode("/ti", $stringthree);
$result = array_map(null, $arrayone, $arraytwo, $arraythree);
foreach ($result as $key => $value) {?>
<div class="col-md-6">
<div class="card shadow">
<div class="card-body">
<h4 class="card-title"><?php echo $value[2] ?> <?php echo $value[0] ?></h4>
<p class="card-text"> <?php echo $value[1] ?></p>
</div>
</div>
</div>
<?php }?>
</div>
</div>
</div>
</div>
</div>
<?php endforeach;?>
<?php if (count($query) <= 0) : ?>
<h3 class="p-1 text-center" colspan="4">No result found</h3>
<?php endif ?>
</div>
</div>
</div>
</div>

</div>