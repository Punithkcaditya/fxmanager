<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/calendar/page.css'); ?>">

<div class="container-fluid pt-8">

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
			<form action="<?php echo  base_url("savepaymentreceiptdetails")?>" method="POST" enctype="multipart/form-data">
				<h2 class="mb-5"><?php echo $title ?></h2>
				
				<div class="row">
					<div class="col-md-3 col-sm-12">
						<!-- Underlying Exposure Start -->
						<div class="form-group"><label class="form-label"><?php echo $pade_title6 ?></label> 
						<select class="form-control select2 refnom w-100" name="exposurerefno" id="exposurerefno<?php echo $i?>" required>
						<option value="">Select Underlying Exposure Ref. No.</option>
						<?php foreach ($exposuretype as $row) : ?> <option value='<?php echo $row['transaction_id'] ?>' ><?php echo $row['exposurereInfo'] ?></option> <?php endforeach; ?>
						</select>
						</div>
						<!-- Underlying Exposure End -->
						<!-- dateof_Settlement -->
						<div class="form-group"><label class="form-label"><?php echo $pade_title4 ?></label> 
						<input id="dateof_Settlement<?=$i?>" name="dateof_Settlement" class="form-control datepicker" placeholder="MM/DD/YYYY" type="text" required>
						</div>
						<!-- dateof_Settlement -->
						
						<input type="hidden" name="banknamehidd" id="banknamehidd"  />
						<input type="hidden" name="value_INRhidd" id="value_INRhidd"  />
						<input type="hidden" name="target_Valuehidd" id="target_Valuehidd"  />
						<input type="hidden" name="exposurecurrencyhidd" id="exposurecurrencyhidd"  />
						<input type="hidden" name="amountfchidd" id="amountfchidd"  />
						<input type="hidden" name="count_items" id="count_items" value="<?php echo  $i?>" />
						
					</div>

					<div class="col-md-3 col-sm-12">
						<!-- Bank Name -->
						<div class="form-group"><label class="form-label"><?php echo $pade_title1 ?></label>
						<input id="bankname<?php echo  $i?>" type="text" name="bankname" placeholder="Bank Name"  class="form-control"  required/>
						</div>
						<!-- Bank Name -->
						
					</div>

					<div class="col-md-3 col-sm-12">
						<!-- Exposure Currency  -->
						<div class="form-group"><label class="form-label"><?php echo $pade_title5 ?></label> 
						<select class="form-control w-100" name="exposurecurrency" id="exposurecurrency<?php echo $i?>" required>
						<option value="">Exposure Currency</option>
						<?php foreach ($currency as $row) : ?> <option value='<?php echo $row['currency_id'] ?>' ><?php echo $row['Currency'] ?></option> <?php endforeach; ?>
						</select>
						</div>
						<!-- Exposure Currency End -->
					</div>

					<div class="col-md-3 col-sm-12">
						<!-- Amount in FC -->
					<div class="form-group"><label class="form-label"><?php echo $pade_title8 ?></label>
						<input id="amountfc<?php echo  $i?>" type="number" name="amountfc" step=".0001" placeholder="Amount (FC)" class="form-control" required="">
						</div>
						<!-- Amount in FC -->
					</div>
					
					
					

					<div class="col-md-12">
					<div class="table-responsive">
					<table class="table card-table table-vcenter text-nowrap  align-items-center" id="plansec">
						<thead class="thead-light">
							<tr>
								<th><input class='check_all' type='checkbox' onclick="select_all()" /> Select All
								</th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>


						</tbody>
					</table>
				</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<div class="card shadow">
							<div class="card-header">
								<h4 class="mb-0">IF FORWARD UTILIZED </h4>
							</div>
							<div class="card-body">
								<div class="form-group"><label class="form-label"><?php echo $pade_title14 ?></label>
								<select class="form-control dealnosel select2 w-100" multiple="multiple" name="dealnoref[]" id="dealnoref<?php echo $i?>">
								<option value="">Deal No</option>
								</select>
								</div>
								<div id="addnewrow">
								
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="card shadow">
							<div class="card-header">
								<h4 class="mb-0">IF AT SPOT</h4>
							</div>
						<div class="card-body">
						<div class="form-group">
						<label class="form-label"><?php echo $pade_title17 ?></label>
						<input id="spotAmount<?php echo  $i?>" type="number" name="spotAmount" step=".0001" placeholder="Spot Amount" class="form-control" required="">
						</div>
						
						<div class="form-group">
						<label class="form-label"><?php echo $pade_title18 ?></label>
						<input id="spotAmountrate<?php echo  $i?>" type="number" name="spotAmountrate" oninput="validatedigit(this)" step=".0001" placeholder="Rate" class="form-control" required="">
						</div>
							</div>
						</div>
					</div>
				</div>
				<button type="submit" class="btn rounded-0 btn-primary bg-gradient">Submit</button>
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



<!-- Date Picker-->
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

<!-- Sweet alert Plugin -->
<script src="assets/plugins/sweet-alert/sweetalert.min.js"></script>
<script src="assets/js/sweet-alert.js"></script>


<script>
// adding more fields
var i=2;
numval = 2;



async function checkSelection() {
	var exposurerefno = $('[name=exposurerefno] option:selected').val();
	if (exposurerefno == '') {
	message = "Please Select Underlying Exposure Ref";
	swal(message);
	$('#value_INR1').val('');
	$('#target_Value1').val('');
	$('#exposurecurrency1').val('');
	}
}


async function checkdealSelection() {
	var dealnoref = $('[name=dealnoref] option:selected').val();
	if (dealnoref == '') {
	message = "Please Select Deal no ref";
	swal(message);
	$('#forwardAmount1').val('');
	$('#forwardamountRate1').val('');
	$('#amountfc1').val('');
	$('#bankname1').val('');
	}
}

$('#value_INR1').on('click', function() {
checkSelection();
});

$('#target_Value1').on('click', function() {
checkSelection();
});

$('#exposurecurrency1').on('click', function() {
checkSelection();
});

$('#amountfc1').on('click', function() {
checkSelection();
});

$('#spotAmount1').on('click', function() {
checkdealSelection();
});


$('#spotAmountrate1').on('click', function() {
checkdealSelection();
});

$('#bankname1').on('click', function() {
checkSelection();
});


$(function(e) {
	'use strict';
	$('.select2').select2()
	$("#e2").select2({
		placeholder: "Select a State",
		allowClear: true
	});
	$('.refnom').on('change', function (e)
	{
		var exposurerefno = $('[name=exposurerefno] option:selected').val();
		if (exposurerefno != '') {
		$.ajax({
		 url: '<?php echo base_url("/paymentreceiptdetailsdependant"); ?>',
		 type: "POST",
		 data: {'exposurerefno':exposurerefno},
		 success: function(data) {
			var arr = $.parseJSON(data);
			console.log(arr);
				$.ajax({
				url: '<?php echo base_url("/dependantdropdowns"); ?>',
				type: "POST",
				data: {'forwardcoverdetailsid':arr['transaction_id']},
				success: function(data) {
				 $('#dealnoref1').html(data);
				}
				});
			
			
			$('#target_Value1').val(arr['targetRate']*arr['amountinFC']);
			$('#target_Valuehidd').val(arr['targetRate']*arr['amountinFC']);
			$('#value_INR1').val(arr['targetRate']*arr['amountinFC']);
			$('#value_INRhidd').val(arr['targetRate']*arr['amountinFC']);
			$('#amountfc1').val(arr['amountinFC']);
			$('#amountfchidd').val(arr['amountinFC']);
			$('#spotAmount1').val(arr['amountinFC']);
			$('#bankname1').val(arr['bank_name']);
			$('#banknamehidd').val(arr['bank_name']);
			$('#exposurecurrency1').val(arr['currency']);
			$('#exposurecurrencyhidd').val(arr['currency']);
			$("#bankname1").prop("disabled", true);
			$("#exposurecurrency1").prop("disabled", true);
			$("#amountfc1").prop("disabled", true);
			$("#value_INR1").prop("disabled", true);
			$("#target_Value1").prop("disabled", true);
			$("#spotAmount1").prop("disabled", true);
		 },
			error: function(xhr, status, error) {
				// Handle the error
				console.log('Error:', error);
			}
		});
		}
	});
	$('.dealnosel').on('change', function (e)
	{
		
		var dealnoref = $(e.currentTarget).val();
		console.log(dealnoref);
		$("#addnewrow").empty();
		if (dealnoref.length > 0) {
		var sum = 0;
		dealnoref.forEach(function(element) {
		$.ajax({
		 url: '<?php echo base_url("/paymentreceiptdetailsdependant"); ?>',
		 type: "POST",
		 data: {'dealnoref':element},
		 success: function(data) {
			var arr = $.parseJSON(data);
			var data="<div class='row'><div class='col-lg-6'><div class='form-group'><label class='form-label'>Forward Amount</label>";
			data += " <input  type='number' value='"+arr['amount_FC']+"' name='forwardAmount[]' placeholder='Forward Amount' class='form-control' readonly>";
			data += "</div> </div> <div class='col-lg-6'> <div class='form-group'> <label class='form-label'>Rate</label> <input  type='number' name='forwardamountRate[]' placeholder='Rate' class='form-control' value='"+arr['contracted_Rate']+"' readonly>";
			data += "</div></div></div>";
			$('#addnewrow').append(data);
			sum += parseInt(arr['amount_FC']);	
			var valamountfc = $('#amountfc1').val();
			$('#spotAmount1').val(valamountfc-sum);			
		 }
		});
		});
		}
	});

});





$("#addpaymentreceiptdetails").on('click',function(){
$("#plansec").css("display", "block");
var counts = $("#count_items").val();
var counter = parseInt(counts);
$('#count_items').val(++counter);
document.getElementById("count_items").value = counter;
count=$('#plansec tr').length;
var data="<tr><td><input type='checkbox' class='case'/></td>";
data += "<td style='width: 50em;'><label class='form-label'>Deal No</label><div class='form-group'><select class='form-control select2 deal_no"+i+" w-100' name='deal_no[]' id='deal_no"+i+"'> <option>Select Deal No</option> <?php foreach ($forwardcoverdetails as $row) : ?> <option value='<?php echo $row['forward_coverdetails_id'] ?>' <?php echo (!empty($query[0]->role_id) && $query[0]->role_id == $row['forward_coverdetails_id']) ? 'selected' : '' ?>><?php echo $row['deal_no'] ?></option> <?php endforeach; ?> </select></div><div class='form-group'><label class='form-label'>Cancelled Forward Amount (FC)</label><input id='cancelledforwardamount"+i+"' type='number' name='cancelledforwardamount[]' placeholder='Cancelled Forward Amount (FC)' class='form-control' required=''></div></td>";
data += "<td style='width: 50em;'><div class='form-group'><label class='form-label'>Utilised Forward Amount (FC)</label><input id='utilisedforwardamount"+i+"' type='number' name='utilisedforwardamount[]' placeholder='Utilised Forward Amount (FC)' class='form-control' required></div><label class='form-label'>Cancellation Rate</label><div class='form-group'><input id='cancellationrate"+i+"' name='cancellationrate[]' class='form-control' placeholder='Cancellation Rate'  required/></div></td>";
data += "<td style='width: 50em;'><label class='form-label'>Utilization Rate</label><div class='form-group'><input id='utilizationrate"+i+"' type='text' name='utilizationrate[]' class='form-control' placeholder='Utilization Rate'  required/></div><label class='form-label'>Cancellation Date</label> <div class='form-group'><input id='cancellationdate"+i+"' name='cancellationdate[]' class='form-control'  required/></div></td>";
data += "<td style='width: 50em;'><label class='form-label'>Utilization Date</label><div class='form-group'><input id='utilizationdate"+i+"' name='utilizationdate[]' class='form-control'  required/></div><div class='form-control invisible'></div><div class='form-control invisible'></div></td></tr>";
$('#plansec').append(data);
$('.deal_no'+i).select2({  width: '100%'});
var dateObject = pikadayResponsive(document.getElementById("utilizationdate"+i));
var dateObject = pikadayResponsive(document.getElementById("cancellationdate"+i));
i++;
});





</script>