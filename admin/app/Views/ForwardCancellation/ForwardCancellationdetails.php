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
<form action="<?php echo  base_url("saveforwardcancellationdetails")?>" method="POST" enctype="multipart/form-data">
<h2 class="mb-5"><?php echo $title ?></h2>

<div class="row">
<div class="col-md-3 col-sm-12">
<div class="form-group"><label class="form-label"><?php echo $pade_title1 ?></label> 
<select class="form-control select2 w-100"  name="deal_no[]" id="deal_no<?php echo $i?>" data-dealcount="<?php echo $i?>" required>
<option value="">Select Deal No</option>
<?php foreach ($forwardcoverdetails as $row) : ?> <option value='<?php echo $row['forward_coverdetails_id'] ?>' <?php echo (!empty($query[0]->role_id) && $query[0]->role_id == $row['forward_coverdetails_id']) ? 'selected' : '' ?>><?php echo $row['deal_no'] ?></option> <?php endforeach; ?>
</select>
</div>
	<input type="hidden" name="count_items" id="count_items" value="<?php echo  $i?>" />
		   <div class="form-group"><label class="form-label"><?php echo $pade_title5 ?></label> 
		   <input type="hidden" name="cancelledforwardamounthid[]" id="cancelledforwardamounthid<?php echo  $i?>"  />
	<input id="cancelledforwardamount<?php echo  $i?>" type="number" name="cancelledforwardamount[]" step=".01" placeholder="Cancelled Forward Amount (FC)"  class="form-control"  required/>

		</div>
	


</div>
<div class="col-md-3 col-sm-12">
	   <div class="form-group"><label class="form-label"><?php echo $pade_title6 ?></label> 
<input id="utilisedforwardamount<?php echo  $i?>" type="number" data-cancid="<?php echo $i?>" name="utilisedforwardamount[]" step=".01" placeholder="Utilised Forward Amount (FC)"  class="form-control utilisedforwardamount"  value="" required/>

	</div>
<div class="form-group"><label class="form-label"><?php echo $pade_title2 ?></label>

<input id="cancellationrate<?php echo  $i?>" type="number" name="cancellationrate[]" oninput="validatedigit(this)" step=".0001" placeholder="Cancellation Rate" class="form-control" required="">
<input id="utilisedamount<?=$i?>" name="utilisedamount[]" type="hidden" class="form-control"  required/>
   
   </div>
 
</div>
  <div class="col-md-3 col-sm-12">
	
	 <div class="form-group"><label class="form-label"><?php echo $pade_title9 ?></label>
	<input id="utilizationrate<?php echo  $i?>" type="number" oninput="validatedigit(this)" name="utilizationrate[]" placeholder="Utilization Rate" step='.0001' class="form-control" required="">
	</div>
	 <div class="form-group"><label class="form-label"><?php echo $pade_title4 ?></label> 
		 <input id="cancellationdate<?=$i?>" placeholder="MM/DD/YYYY" name="cancellationdate[]" class="form-control datepicker" type="text" required/>
		</div>
</div>
<div class="col-md-3 col-sm-12">
		 <div class="form-group"><label class="form-label"><?php echo $pade_title7 ?></label>
		 <input id="utilizationdate<?=$i?>" placeholder="MM/DD/YYYY" name="utilizationdate[]" class="form-control datepicker" type="text"  required/>
		 </div>
</div>


<div class="col-md-12">
<div class="table-responsive">
<table class="table card-table table-vcenter text-nowrap  align-items-center" id="plansec">
	<thead class="thead-light">
		<tr class='mobcheck'>
			<th><input class='check_all' type='checkbox' onclick="select_all()" /> Select All
			</th>

		</tr>
	</thead>
	<tbody>


	</tbody>
</table>
</div>
</div>
</div>
<button type="submit" class="btn rounded-0 btn-primary bg-gradient">Submit</button>
<button type="button" class="btn btn-info mt-1 mb-1" id="addforwarddetails"><i
	class="fas fa-plus-circle"></i> Add More Details</button>
<button type="button" class="btn hideing btn-danger mt-1 mb-1" id="deletemealplans"><i
	class="fas fa-minus"></i> Delete Details</button>
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

<!-- Sweet alert Plugin -->
<script src="assets/plugins/sweet-alert/sweetalert.min.js"></script>
<script src="assets/js/sweet-alert.js"></script>

<script>

async function checkSelection(cancid) {
var deal_no = $('#deal_no'+cancid).val();
if (deal_no == '') {
message = "Please Select Deal No";
swal(message);
$('#utilisedforwardamount'+cancid).val('');
$('#cancelledforwardamount'+cancid).val('');
$('#cancelledforwardamounthid'+cancid).val('');
}
}




$(function(e) {
'use strict';
$('.select2').select2()
$("#e2").select2({
placeholder: "Select a State",
allowClear: true
});
attachChangeListeners();

});

function attachInputListeners() {
$('.utilisedforwardamount').each(function() {
$(this).on('input', function() {
var cancid = $(this).data('cancid');
var amt = $(this).val();
var utilisedamount = $('#utilisedamount'+cancid).val();
$('#cancelledforwardamount' + cancid).attr('value', utilisedamount - amt);
$('#cancelledforwardamounthid' + cancid).attr('value', utilisedamount - amt);
checkSelection(cancid);
$('#cancelledforwardamount'+cancid).prop("disabled", true);
});
});
}


function attachChangeListeners() {
$('.select2').each(function() {
$(this).on('change', function (e) {
var deal_no = $(this).find('option:selected').val();
var dealcount = $(this).data('dealcount');
if (deal_no != '') {
$.ajax({
url: '<?php echo base_url("/cancelationforwardamount"); ?>',
type: "POST",
data: {'deal_no':deal_no},
success: function(data) {
var arr = $.parseJSON(data);
console.log(arr);
$('#utilisedamount'+dealcount).val(arr['amount_FC']);
}
});
}
});
});
}

// adding more fields
var i=2;
numval = 2;

$("#addforwarddetails").on('click',function(){
$("#plansec").css("display", "block");
var counts = $("#count_items").val();
var counter = parseInt(counts);
$('#count_items').val(++counter);
document.getElementById("count_items").value = counter;
count=$('#plansec tr').length;
var data="<tr class='mobcheck'><td><input type='checkbox' class='case'/></td>";
data += "<td><label class='form-label'>Deal No</label><div class='form-group'><select class='form-control select2 deal_no"+i+" w-100' name='deal_no[]' data-dealcount="+i+" id='deal_no"+i+"'> <option value=''>Select Deal No</option> <?php foreach ($forwardcoverdetails as $row) : ?> <option value='<?php echo $row['forward_coverdetails_id'] ?>' <?php echo (!empty($query[0]->role_id) && $query[0]->role_id == $row['forward_coverdetails_id']) ? 'selected' : '' ?>><?php echo $row['deal_no'] ?></option> <?php endforeach; ?> </select></div><div class='form-group'><label class='form-label'>Cancelled Forward Amount (FC)</label><input type='hidden' name='cancelledforwardamounthid[]' id='cancelledforwardamounthid"+i+"'  /><input id='cancelledforwardamount"+i+"' type='number' step='.01' name='cancelledforwardamount[]' placeholder='Cancelled Forward Amount (FC)' class='form-control' required=''><input id='utilisedamount"+i+"' name='utilisedamount[]' type='hidden' class='form-control'  required/></div></td>";
data += "<td><div class='form-group'><label class='form-label'>Utilised Forward Amount (FC)</label><input id='utilisedforwardamount"+i+"' type='number' step='.01' data-cancid="+i+" name='utilisedforwardamount[]' placeholder='Utilised Forward Amount (FC)' class='form-control utilisedforwardamount' required></div><label class='form-label'>Cancellation Rate</label><div class='form-group'><input id='cancellationrate"+i+"' name='cancellationrate[]' type='number' oninput='validatedigit(this)' step='.0001' class='form-control' placeholder='Cancellation Rate'  required/></div></td>";
data += "<td><label class='form-label'>Utilization Rate</label><div class='form-group'><input id='utilizationrate"+i+"' type='number' oninput='validatedigit(this)' step='.0001' name='utilizationrate[]' class='form-control' placeholder='Utilization Rate'  required/></div><label class='form-label'>Cancellation Date</label> <div class='form-group'><input id='cancellationdate"+i+"' name='cancellationdate[]' type='text' placeholder='MM/DD/YYYY' class='form-control datepicker'  required/></div></td>";
data += "<td><label class='form-label'>Utilization Date</label><div class='form-group'><input id='utilizationdate"+i+"' name='utilizationdate[]' class='form-control datepicker'  type='text' placeholder='MM/DD/YYYY' required/></div><div class='form-control invisible d-none d-sm-block'></div><div class='form-control invisible d-none d-sm-block'></div></td></tr>";
$('#plansec').append(data);
$('.deal_no'+i).select2({  width: '100%'});
attachInputListeners();
attachChangeListeners();
i++;
$('.datepicker').datepicker({
showOtherMonths: true,
selectOtherMonths: true,
format: "dd/mm/yyyy",
autoclose: true
});
});


attachInputListeners();
attachChangeListeners();
</script>