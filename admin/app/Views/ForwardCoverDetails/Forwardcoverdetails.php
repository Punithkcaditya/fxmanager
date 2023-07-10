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
<form action="<?php echo  base_url("saveforwardcoverdetails")?>" method="POST" enctype="multipart/form-data">
<h2 class="mb-5"><?php echo $title ?></h2>

<div class="row">
<div class="col-md-3 col-sm-12">
<!-- Underlying Exposure strat -->
<div class="form-group"><label class="form-label"><?php echo $pade_title7 ?></label>
<select class="form-control select2 w-100" name="refno[]" data-currencytype="<?php echo $i?>" id="refno<?php echo $i?>">
<option>Select Underlying Exposure Ref No.</option>
<?php foreach ($exposuretype as $row) : ?> <option value='<?php echo $row['transaction_id'] ?>' <?php echo (!empty($query[0]->role_id) && $query[0]->role_id == $row['transaction_id']) ? 'selected' : '' ?>><?php echo $row['exposurereInfo'] ?></option> <?php endforeach; ?>
</select>
</div>
<!-- Underlying Exposure end -->

<!-- Forward Option -->
<div class="form-group"><label class="form-label"><?php echo $pade_title5 ?></label> 
<select name="fordwardoption[]" id="fordwardoption<?=$i?>" class="form-control" required>
<option value="">-- Choose type --</option>
<option value="Forward">Forward</option>
<option value="Option">Option</option>
</select>
</div>


<!-- Contracted rate Start -->
<div class="form-group"><label class="form-label"><?php echo $pade_title11 ?></label>
<input id="contractedrate" type="number" name="contractedrate[]" min='0' value='0' step='.0001' placeholder="Contracted Rate" class="form-control"  required/>
</div>
<!-- Contracted rate End -->


</div>
<div class="col-md-3 col-sm-12">

<!-- Forward/ Option Start -->
<input type="hidden" name="count_items" id="count_items" value="<?php echo  $i?>" />
<div class="form-group"><label class="form-label"><?php echo $pade_title13 ?></label> 
<select name="bank[]" id="bank<?=$i?>" class="form-control" required>
<option value="">-- Choose Bank --</option>
<?php foreach ($bank as $row): ?>
<option value="<?php echo $row['bank_id'] ?>"><?php echo $row['bank_name'] ?></option>
<?php endforeach;?>
</select>
</div>

<!-- Forward/ Option end -->

<!-- Currency Bought Start -->
<div class="form-group"><label class="form-label"><?php echo $pade_title2 ?></label>
<input class="form-control" type="text" placeholder="Currency Bought" name="currencybought[]" id="currencybought<?php echo $i?>" readonly required/>
</div>

<!-- Currency Bought End -->
<!-- expirydate -->
<div class="form-group"><label class="form-label"><?php echo $pade_title12 ?></label>
<input id="expirydate<?=$i?>" name="expirydate[]" class="form-control datepicker" type="text" placeholder="MM/DD/YYYY"  required/>
</div>

</div>
<div class="col-md-3 col-sm-12">

<!-- Deal No Start -->
<div class="form-group"><label class="form-label"><?php echo $pade_title6 ?></label> 
<input id="dealno<?php echo  $i?>" type="text" name="dealno[]" placeholder="Deal No"  class="form-control"  required/>
</div>
<!-- Deal No End -->

<!-- Currency Sold Start -->

<div class="form-group"><label class="form-label"><?php echo $pade_title4 ?></label> 
<input class="form-control" type="text" placeholder="Currency Sold" name="currencysold[]" id="currencysold<?php echo $i?>" readonly required/>
</div>

<!-- Currency Sold End -->

</div>


<div class="col-md-3 col-sm-12">
<!-- Deal Date Start -->
<div class="form-group"><label class="form-label"><?php echo $pade_title9 ?></label>
<input id="dealdate<?=$i?>" name="dealdate[]" class="form-control datepicker" type="text" placeholder="MM/DD/YYYY" required/>
</div>
<!-- Deal Date End  -->

<!-- Amount (FC) Start -->
<div class="form-group"><label class="form-label"><?php echo $pade_title8 ?></label>
<input id="targetrat" type="number" name="amountFC[]" step=".0001" placeholder="Amount FC" class="form-control"  required/>
</div>
<!-- Amount (FC) End -->

</div>


<div class="col-md-12">
<div class="table-responsive">
<table class="table card-table table-vcenter text-nowrap  align-items-center" id="plansec">
<thead class="thead-light">
<tr class="mobcheck">
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
class="fas fa-plus-circle"></i> Add More Forward Cover Details</button>
<button type="button" class="btn hideing btn-danger mt-1 mb-1" id="deletemealplans"><i
class="fas fa-minus"></i> Delete Forward Cover Details</button>
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
<script src="assets/js/select2.js"></script>

<script type="text/javascript" src="<?php echo base_url('assets/calendar/calendar.js'); ?>"></script>

<script>




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
data += "<td><label class='form-label'>Underlying Exposure Ref.</label><div class='form-group'><select class='form-control select2 refno"+i+" w-100' name='refno[]' id='refno"+i+"' data-currencytype='"+i+"'> <option>Select Underlying Exposure Ref No.</option> <?php foreach ($exposuretype as $row) : ?> <option value='<?php echo $row['transaction_id'] ?>' <?php echo (!empty($query[0]->role_id) && $query[0]->role_id == $row['transaction_id']) ? 'selected' : '' ?>><?php echo $row['exposurereInfo'] ?></option> <?php endforeach; ?> </select></div><label class='form-label'>Forward/ Option</label><div class='form-group'><select name='fordwardoption[]' id='fordwardoption"+i+"' class='form-control' required><option value=''>Choose type</option><option value='Forward'>Forward</option><option value='Option'>Option</option></select></div><label class='form-label'>Contracted rate</label><input id='contractedrate"+i+"' type='number' name='contractedrate[]' min='0' value='0' step='.0001' placeholder='Contracted Rate' class='form-control' required=''></div></td>";
data += "<td><label class='form-label mt-2'>Choose Bank</label><div class='form-group'><select name='bank[]' id='bank"+i+"' class='form-control' required> <option value=''>-- Choose Bank -- </option> <?php foreach ($bank as $row): ?> <option value='<?php echo $row['bank_id'] ?>'><?php echo $row['bank_name'] ?></option> <?php endforeach;?> </select> </div><label class='form-label'>Currency Bought</label><div class='form-group'><input class='form-control' type='text' placeholder='Currency Bought' name='currencybought[]' id='currencybought"+i+"' readonly required/></div><label class='form-label'>Expiry Date</label><div class='form-group'><div class='form-group'><input id='expirydate"+i+"' name='expirydate[]' class='form-control datepicker'  type='text' placeholder='MM/DD/YYYY' required/></div></td>";
data += "<td><div class='form-group'><label class='form-label'>Deal No</label><input id='dealno"+i+"' type='text' name='dealno[]' placeholder='Deal No' class='form-control' required=''></div><div class='form-group'><label class='form-label'>Currency Sold</label><input class='form-control' type='text' placeholder='Currency Sold' name='currencysold[]' id='currencysold"+i+"' readonly required/> </div><div class='heighsvnrem'></div></td>";
data += "<td><div class='form-group'><label class='form-label'>Deal Date</label><input id='dealdate"+i+"' name='dealdate[]' placeholder='MM/DD/YYYY' class='form-control datepicker' type='text'  required/></div><div class='form-group'><label class='form-label'>Amount (FC)</label><input id='targetrat' type='number' name='amountFC[]' step='.0001' placeholder='Amount FC' class='form-control' required=''></div><div class='heighsvnrem'></div></td></tr>";
$('#plansec').append(data);
$('.refno'+i).select2({  width: '100%'});
$(document).on("select2:select", ".select2", function(e) {
var selectedValue = e.params.data.id;
var datacurrencyid = $(this).data("currencytype");
console.log(datacurrencyid);
            if(selectedValue) {
                $.ajax({
                    url: '<?php echo base_url("/dependantcurrency"); ?>',
                    type: "POST",
                    dataType: 'Json',
                    data: {'selectedValue':selectedValue},
                    success: function(data) {
                            var response = JSON.stringify(data);
                        var parsedResponse = JSON.parse(response);
                        var currencyValue = parsedResponse.Currency;
                        var currencyBought, currencySold;
                        if (parsedResponse.exposureType === "1") {
                            currencyBought = currencyValue.substring(currencyValue.length / 2);
                            currencySold = currencyValue.substring(0, currencyValue.length / 2);
                            } else {
                            currencyBought = currencyValue.substring(0, currencyValue.length / 2);
                            currencySold = currencyValue.substring(currencyValue.length / 2);
                            }
                            $('#currencybought'+datacurrencyid).val(currencyBought);
                            $('#currencysold'+datacurrencyid).val(currencySold);
                    },
                error: function(xhr, status, error) {
                    // Handle the error
                    console.log('Error:', error);
                }
            });
            }else{
                $('#currencybought'+datacurrencyid).val('');
                $('#currencysold'+datacurrencyid).val('');
            }
});
i++;
$('.datepicker').datepicker({
showOtherMonths: true,
selectOtherMonths: true,
format: "dd/mm/yyyy",
autoclose: true
});
});

</script>