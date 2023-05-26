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
    
        <input type="hidden" name="count_items" id="count_items" value="<?php echo  $i?>" />
                <div class="form-group"><label class="form-label"><?php echo $pade_title5 ?></label> 
        <input id="fordwardoption<?php echo  $i?>" type="text" name="fordwardoption[]" placeholder="Forward/ Option"  class="form-control"  required/>

            </div>
            <div class="form-group"><label class="form-label"><?php echo $pade_title11 ?></label>
        <input id="contractedrate" type="number" name="contractedrate[]" min='0' value='0' step='.0001' placeholder="Contracted Rate" class="form-control"  required/>
        </div>
    
        <div class="form-group"><label class="form-label"><?php echo $pade_title12 ?></label>
        <input id="expirydate<?=$i?>" name="expirydate[]" class="form-control"  required/>
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
            <div class="form-group"><label class="form-label"><?php echo $pade_title6 ?></label> 
    <input id="dealno<?php echo  $i?>" type="text" name="dealno[]" placeholder="Deal No"  class="form-control"  required/>

        </div>
<div class="form-group"><label class="form-label"><?php echo $pade_title2 ?></label>
<select class="form-control" name="currencybought[]"id="currencybought<?php echo $i?>">
<option>Currency Bought</option>
<?php foreach ($currency as $row) : ?> <option value='<?php echo $row['currency_id'] ?>' <?php echo (!empty($query[0]->role_id) && $query[0]->role_id == $row['currency_id']) ? 'selected' : '' ?>><?php echo $row['Currency'] ?></option> <?php endforeach; ?> </select>

        </div>
      
    </div>
        <div class="col-md-3 col-sm-12">
        
            <div class="form-group"><label class="form-label"><?php echo $pade_title9 ?></label>
        <input id="dealdate<?=$i?>" name="dealdate[]" class="form-control"  required/>
        </div>
            <div class="form-group"><label class="form-label"><?php echo $pade_title4 ?></label> 
            <select name="currencysold[]"
            id="currencysold<?=$i?>"
            class="form-control" required>
            <option value="">-- Choose Sold --
            </option>
            <?php foreach ($currency as $row) : ?>
            <option value="<?php echo $row['currency_id'] ?>" <?php echo (!empty($query[0]->role_id) && $query[0]->role_id == $row['currency_id']) ? 'selected' : '' ?>><?php echo $row['Currency'] ?></option>
            <?php endforeach; ?>
            </select>
            </div>
    </div>
    <div class="col-md-3 col-sm-12">

            


                <div class="form-group"><label class="form-label"><?php echo $pade_title7 ?></label>
<select class="form-control select2 w-100" name="refno[]" id="refno<?php echo $i?>">
<option>Select Underlying Exposure Ref No.</option>
<?php foreach ($exposuretype as $row) : ?> <option value='<?php echo $row['transaction_id'] ?>' <?php echo (!empty($query[0]->role_id) && $query[0]->role_id == $row['transaction_id']) ? 'selected' : '' ?>><?php echo $row['exposurereInfo'] ?></option> <?php endforeach; ?>
</select>
</div>

            <div class="form-group"><label class="form-label"><?php echo $pade_title8 ?></label>
        <input id="targetrat" type="number" name="amountFC[]" placeholder="Amount FC" class="form-control"  required/>
        </div>
    
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
data += "<td><div class='form-group'><label class='form-label'>Forward/ Option</label><input id='fordwardoption"+i+"' type='text' name='fordwardoption[]' placeholder='Forward/ Option' class='form-control' required=''> </div><div class='form-group'><label class='form-label'>Contracted rate</label><input id='contractedrate"+i+"' type='number' name='contractedrate[]' min='0' value='0' step='.0001' placeholder='Contracted Rate' class='form-control' required=''></div><label class='form-label'>Expiry Date</label><div class='form-group'><input id='expirydate"+i+"' name='expirydate[]' class='form-control'  required/></div></td>";
data += "<td><div class='form-group'><label class='form-label'>Deal No</label><input id='dealno"+i+"' type='text' name='dealno[]' placeholder='Deal No' class='form-control' required=''></div><label class='form-label'>Currency Bought</label><div class='form-group'><select name='currencybought[]' id='currencybought"+i+"'  class='form-control' required> <option value=''>-- Currency Bought -- </option> <?php foreach ($currency as $row) : ?> <option value='<?php echo $row['currency_id'] ?>' <?php echo (!empty($query[0]->role_id) && $query[0]->role_id == $row['currency_id']) ? 'selected' : '' ?>><?php echo $row['Currency'] ?></option> <?php endforeach; ?> </select></div><div class='heighsvnrem'></div></td>";
data += "<td><label class='form-label'>Deal Date</label><div class='form-group'><input id='dealdate"+i+"' name='dealdate[]' class='form-control'  required/></div><div class='form-group'><label class='form-label'>Currency Sold</label><select name='currencysold[]' id='currencysold"+i+"' class='form-control' required> <option value=''>-- Currency Sold -- </option> <?php foreach ($currency as $row) : ?> <option value='<?php echo $row['currency_id'] ?>' <?php echo (!empty($query[0]->role_id) && $query[0]->role_id == $row['currency_id']) ? 'selected' : '' ?>><?php echo $row['Currency'] ?></option> <?php endforeach; ?> </select> </div><div class='heighsvnrem'></div></td>";
data += "<td><label class='form-label'>Underlying Exposure Ref.</label><div class='form-group'><select class='form-control select2 refno"+i+" w-100' name='refno[]' id='refno"+i+"'> <option>Select Underlying Exposure Ref No.</option> <?php foreach ($exposuretype as $row) : ?> <option value='<?php echo $row['transaction_id'] ?>' <?php echo (!empty($query[0]->role_id) && $query[0]->role_id == $row['transaction_id']) ? 'selected' : '' ?>><?php echo $row['exposurereInfo'] ?></option> <?php endforeach; ?> </select></div><div class='form-group'><label class='form-label'>Amount (FC)</label><input id='targetrat' type='number' name='amountFC[]' placeholder='Amount FC' class='form-control' required=''></div><div class='heighsvnrem'></div></td></tr>";
$('#plansec').append(data);
$('.refno'+i).select2({  width: '100%'});
var dateObject = pikadayResponsive(document.getElementById("dealdate"+i));
var dateObject = pikadayResponsive(document.getElementById("expirydate"+i));
i++;
});

</script>