<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/calendar/page.css'); ?>">

<div class="container-fluid pt-8">
<?= $this->include('bottomtopbar/topbar') ?>
<div class="card-body">
<div class="nav-wrapper p-0">
<?= $this->include('message/message') ?>  
</div>
</div>
<div class="card shadow ">
<div class="card-body">

<div class="tab-content" id="myTabContent">

<!-- strat -->
<!-- end -->
<form action="<?php echo  base_url("saveforwardcover")?>" method="POST" enctype="multipart/form-data">
<h2 class="mb-5"><?php echo $title ?></h2>

<div class="row">
<div class="col-md-3 col-sm-12">
<div class="form-group"><label class="form-label"><?php echo $pade_title1 ?></label>
<select class="form-control select2 w-100" name="refno[]"  id="refno<?php echo $i?>">
<option>Select  Exposure Ref No.</option>
<?php foreach ($exposuretype as $row) : ?> <option value='<?php echo $row['transaction_id'] ?>' <?php echo (!empty($query[0]->role_id) && $query[0]->role_id == $row['transaction_id']) ? 'selected' : '' ?>><?php echo $row['exposurereInfo'] ?></option> <?php endforeach; ?>
</select>
</div>

<div class="form-group"><label class="form-label"><?php echo $pade_title4 ?></label>
<select name="currency"
id="currency"
class="form-control" data-currencyid="<?=$i?>" required>
<option value="">-- Choose Currency --
</option>
<?php foreach ($currency as $row): ?>
<option value="<?php echo $row['currency_id'] ?>"><?php echo $row['Currency'] ?></option>
<?php endforeach;?>
</select>
</div>


<div class="form-group"><label class="form-label"><?php echo $pade_title9 ?></label>
<input id="contractedrate" type="text" name="contractedrate"  value=''  placeholder="Contracted Rate" class="form-control"  required/>
</div>

<div class="form-group"><label class="form-label"><?php echo $pade_title14 ?></label>
<input id="unallocatedamountinfc" type="text" name="unallocatedamountinfc"  value=''  placeholder="Unallocated Amountin FC" class="form-control"  required/>
</div>

</div>
<div class="col-md-3 col-sm-12">

<!-- Forward/ Option Start -->
<input type="hidden" name="count_items" id="count_items" value="<?php echo  $i?>" />

<div class="form-group"><label class="form-label"><?php echo $pade_title2 ?></label> 
<input id="bankname" type="text" name="bankname"  value=''  placeholder="Bank Name" class="form-control"  required readonly/>
</div>


<div class="form-group"><label class="form-label"><?php echo $pade_title5 ?></label>
<select name="buysell" id="buysell" class="form-control" data-currencyid="<?=$i?>" required>
<option value="">-- Buy / Sell --
<option value="2">Buy</option>
<option value="1">Sell</option>
</select>
</div>


<div class="form-group"><label class="form-label"><?php echo $pade_title11 ?></label>
<input id="amountinfc" type="number" name="amountinfc" step=".01" placeholder="Amount In FC" class="form-control"  required/>
</div>

</div>


<div class="col-md-3 col-sm-12">
<div class="form-group"><label class="form-label"><?php echo $pade_title3 ?></label>
<input id="dealno" name="dealno" class="form-control" type="text" placeholder="Deal No" required/>
</div>

<div class="form-group"><label class="form-label"><?php echo $pade_title6 ?></label>
<input id="maturedate" name="maturedate" class="form-control datepicker" type="text" placeholder="MM/DD/YYYY" required/>
</div>

<div class="form-group"><label class="form-label"><?php echo $pade_title12 ?></label>
<input id="amountallocated" type="number" name="amountallocated" step=".0001" placeholder="Amount Allocated" class="form-control"  required/>
</div>

</div>


<div class="col-md-3 col-sm-12">
<div class="form-group"><label class="form-label"><?php echo $pade_title33 ?></label>
<input id="dealdate" name="dealdate" class="form-control datepicker" placeholder="MM/DD/YYYY"  type="text" placeholder="Deal Date" required/>
</div>

<div class="form-group"><label class="form-label"><?php echo $pade_title8 ?></label>
<input id="forwardamont" type="number" name="forwardamont" step=".0001" placeholder="Forward Amount" class="form-control"  required/>
</div>

<div class="form-group"><label class="form-label"><?php echo $pade_title13 ?></label>
<input id="freeforwardamont" type="number" name="freeforwardamont" step=".0001" placeholder="Freeforward Amount" class="form-control"  required/>
</div>
</div>

</div>
<div class="col-md-12 text-center">
    <button type="submit" class="btn rounded-0 btn-primary bg-gradient">Submit</button>
</div>
</form>



</div>

</div>
</div>
<!-- Dynamic fields for plan desc -->
<!-- Dynamic fields for plan desc -->
</div>







<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>
<!-- Don't forget to include Jquery also -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<!--Select2 js-->
<script src="assets/plugins/select2/select2.full.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/calendar/calendar.js'); ?>"></script>

<script>

$(function(e) {
	'use strict';
	$('.select2').select2()
	$("#e2").select2({
		placeholder: "Select a State",
		allowClear: true
	});
	$(document).on("select2:select", ".select2", function(e) {
		var selectedValue = e.params.data.id;
			        if(selectedValue) {
			            $.ajax({
							url: 'forwardallocationdependantdata',
							type: "POST",
							dataType: 'Json',
			                data: {'selectedValue':selectedValue},
			                success: function(data) {
								console.log(data);
                                $('#bankname').val(data.bank_name);
                                $.ajax({
                                url: '<?php echo base_url("dependantdealno"); ?>',
                                type: "POST",
                                data: {'bank_id':data.bank_id},
                                success: function(data) {
                                    console.log(data);
                                  }
                                });
			                },
						error: function(xhr, status, error) {
							console.log('Error:', error);
						}
					});
			        }else{
                        $('#bankname').val('');
			        }
	  });
});

</script>