<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/calendar/page.css'); ?>">

<div class="container-fluid pt-8">
<?=$this->include('bottomtopbar/topbar')?>
<div class="card-body">
<div class="nav-wrapper p-0">
<?=$this->include('message/message')?>
</div>
</div>
<div class="card shadow ">
<div class="card-body">

<div class="tab-content" id="myTabContent">

<!-- strat -->
<!-- end -->
<form action="<?php echo base_url("savetransactiondetails") ?>" method="POST" enctype="multipart/form-data">
<h2 class="mb-5"><?php echo $title ?></h2>

<div class="row">
<div class="col-md-3 col-sm-12">
<div class="form-group"><label class="form-label"><?php echo $pade_title1 ?></label>
<input id="exposureref<?php echo $i ?>" type="text" name="exposureref[]" class="form-control"  required/>
</div>
    <input type="hidden" name="count_items" id="count_items" value="<?php echo $i ?>" />
    <div class="form-group"><label class="form-label"><?php echo $pade_title10 ?></label>
    <input id="counterPartycountry<?php echo $i ?>" type="text" name="counterPartycountry[]" placeholder="Counter Party Country" class="form-control"  required/>
    </div>


</div>
<div class="col-md-3 col-sm-12">
        <div class="form-group"><label class="form-label"><?php echo $pade_title4 ?></label>
        <select name="currency[]"
        id="currency<?=$i?>"
        class="form-control" data-currencyid="<?=$i?>" required>
        <option value="">-- Choose Currency --
        </option>
        <?php foreach ($currency as $row): ?>
        <option value="<?php echo $row['currency_id'] ?>"><?php echo $row['Currency'] ?></option>
        <?php endforeach;?>
        </select>
    </div>
<div class="form-group"><label class="form-label"><?php echo $pade_title6 ?></label>
        <select name="exposure[]"
        id="exposure<?=$i?>"
        class="form-control" required>
        <option value="">-- Exposure Type --
        </option>
        <?php foreach ($exposuretype as $row): ?>
        <option value="<?php echo $row['exposure_type_id'] ?>"><?php echo $row['exposure_type'] ?></option>
        <?php endforeach;?>
        </select>
    </div>
</div>
    <div class="col-md-3 col-sm-12">

        <div class="form-group"><label class="form-label"><?php echo $pade_title2 ?></label>
        <input id="date<?=$i?>" name="date[]" type="text" class="form-control datepicker" placeholder="MM/DD/YYYY" required/>
        </div>

        <div class="form-group"><label class="form-label"><?php echo $pade_title7 ?></label>
        <input id="amountinfc" type="number" name="amountinfc[]" step=".01" placeholder="Amount in FC" class="form-control"  required/>
        </div>
</div>
<div class="col-md-3 col-sm-12">
            <div class="form-group"><label class="form-label"><?php echo $pade_title5 ?></label>
                <select name="counter[]"
                id="currency<?=$i?>"
                class="form-control" required>
                <option value="">-- Counter Party --
                </option>
                <?php foreach ($counterparty as $row): ?>
                <option value="<?php echo $row['counterParty_id'] ?>"><?php echo $row['counterPartyName'] ?></option>
                <?php endforeach;?>
                </select>
    </div>

        <div class="form-group"><label class="form-label"><?php echo $pade_title9 ?></label>
        <input id="duedate<?=$i?>" name="duedate[]" placeholder="MM/DD/YYYY" class="form-control datepicker"  type="text"  required/>
    </div>
</div>

<div class="col-md-3 col-sm-12">
<div class="form-group"><label class="form-label"><?php echo $pade_title8 ?></label>
<input id="targetrat" type="number" name="targetrat[]" min="0" value="0" step=".0001" placeholder="Target Rat" oninput="validatedigit(this)" class="form-control"  required/>
</div>
</div>

<div class="col-md-3 col-sm-12">
<div class="form-group"><label class="form-label"><?php echo $pade_title11 ?></label>
<select name="bank[]"
id="bank<?=$i?>"
class="form-control" required>
<option value="">-- Choose Bank --
</option>
<?php foreach ($bank as $row): ?>
<option value="<?php echo $row['bank_id'] ?>"><?php echo $row['bank_name'] ?></option>
<?php endforeach;?>
</select>

</div>
</div>

<div class="col-md-3 col-sm-12">
<div class="form-group" id="inrinput<?=$i?>" style="display: none;"><label class="form-label" id="inrinputlabelvalue<?=$i?>">INR Target Value</label>
        <input type="number" id="inr_field_valueid<?=$i?>" min="0" value="0" step=".0001" oninput='validatedigit(this)' name="inr_field_value[]"  placeholder="INR Value" class="form-control">
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
<button type="button" class="btn btn-info mt-1 mb-1" id="addmealplans"><i
    class="fas fa-plus-circle"></i> Add More Transactions</button>
<button type="button" class="btn hideing btn-danger mt-1 mb-1" id="deletemealplans"><i
    class="fas fa-minus"></i> Delete Transactions</button>
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
<script type="text/javascript" src="<?php echo base_url('assets/calendar/calendar.js'); ?>"></script>
<script>

$("[name='currency[]']").change(function() {
    var selectedOption = this.options[this.selectedIndex];
    var selectedText = selectedOption.text;
    var datacurrencyid = $(this).data("currencyid");
    if (selectedText.includes("INR")) {
        document.getElementById("inrinput"+datacurrencyid).style.display = "none";
        $('#inr_field_valueid'+datacurrencyid).removeAttr("required");
    } else {
        switch (true) {
        case selectedText.includes("EURUSD") || selectedText.includes("GBPUSD"):
        document.getElementById("inrinputlabelvalue" + datacurrencyid).innerText = "USDINR Wash Rate";
        break;
        case selectedText.includes("USDJPY"):
        document.getElementById("inrinputlabelvalue" + datacurrencyid).innerText = "JPYINR Wash Rate";
        break;
        }
      document.getElementById("inrinput"+datacurrencyid).style.display = "block";
      var id =  $('#inr_field_value'+datacurrencyid);
      console.log(id);
      $('#inr_field_valueid' + datacurrencyid).prop("required", true);

      //$('#inr_field_value'+datacurrencyid).attr("required", "required");

    }
  // Handle the change event for the input field with name 'inr_field_value'
});

// adding more fields
var i=2;
numval = 2;

$("#addmealplans").on('click',function(){
$("#plansec").css("display", "block");
var counts = $("#count_items").val();
var counter = parseInt(counts);
$('#count_items').val(++counter);
document.getElementById("count_items").value = counter;
count=$('#plansec tr').length;
var data="<tr class='mobcheck'><td><input type='checkbox' class='case'/></td>";
data += "<td ><div class='form-group'><label class='form-label'>Exposure Ref. No</label><input id='exposureref"+i+"' type='text' placeholder='Exposure Ref. No' name='exposureref[]' class='form-control'  required/></div><div class='form-group'><label class='form-label'>Counter Party Country</label><input id='counterPartycountry' type='text' name='counterPartycountry[]' placeholder='Counter Party Country' class='form-control'  required/></div><div class='form-group'><label class='form-label'>Target Rate</label><input id='targetrat' type='number' min='0' value='0' step='.0001' name='targetrat[]' oninput='validatedigit(this)' placeholder='Target Rat' class='form-control'  required/></div></td>";
data += "<td ><div class='form-group'><label class='form-label'>Currency</label><select name='currency[]' data-currencyid="+i+" id='currency"+i+"' class='form-control' required> <option value=''>-- Choose Currency -- </option> <?php foreach ($currency as $row): ?> <option value='<?php echo $row['currency_id'] ?>' ><?php echo $row['Currency'] ?></option> <?php endforeach;?> </select></div><div class='form-group'><label class='form-label'>Exposure Type</label><select name='exposure[]' id='currency"+i+"'  class='form-control' required> <option value=''>-- Exposure Type -- </option> <?php foreach ($exposuretype as $row): ?> <option value='<?php echo $row['exposure_type_id'] ?>'><?php echo $row['exposure_type'] ?></option> <?php endforeach;?> </select></div><div class='form-group'><label class='form-label'>Choose Bank</label><select name='bank[]' id='bank"+i+"' class='form-control' required> <option value=''>-- Choose Bank -- </option> <?php foreach ($bank as $row): ?> <option value='<?php echo $row['bank_id'] ?>'><?php echo $row['bank_name'] ?></option> <?php endforeach;?> </select></div></td>";
data += "<td ><label class='form-label'>Date of Invoice</label><div class='form-group'><input id='date"+i+"' placeholder='MM/DD/YYYY' name='date[]' class='form-control datepicker' type='text' required/></div><div class='form-group'> <label class='form-label'>Amount in FC</label><input id='amountinfc' type='number' name='amountinfc[]' step='.01' placeholder='Amount in FC' class='form-control'  required/></div><div class='form-group mt-2' id='inrinput"+i+"' style='display: none;'><label class='form-label' id='inrinputlabelvalue"+i+"'>INR Target Value</label><input type='number' min='0' value='0' step='.0001' id='inr_field_valueid"+i+"' name='inr_field_value[]' oninput='validatedigit(this)' placeholder='INR Value' class='form-control'></div><div class='heighsixrem' id='marycold"+i+"'></div></td>";
data += "<td ><div class='form-group'><label class='form-label'>Counter Party</label><select name='counter[]' id='currency"+i+"' class='form-control' required> <option value=''>-- Counter Party -- </option> <?php foreach ($counterparty as $row): ?> <option value='<?php echo $row['counterParty_id'] ?>'><?php echo $row['counterPartyName'] ?></option> <?php endforeach;?> </select></div><div class='form-group'><label class='form-label'>Due Date</label><div class='form-group'><input id='duedate"+i+"' name='duedate[]' placeholder='MM/DD/YYYY' class='form-control datepicker' type='text'  required/></div></div><div class='heighsixrem'></div></td></tr>";
$('#plansec').append(data);
i++;
$("[name='currency[]']").change(function() {
    var selectedOption = this.options[this.selectedIndex];
    var selectedText = selectedOption.text;
    var datacurrencyid = $(this).data("currencyid");
    if (selectedText.includes("INR")) {
        document.getElementById("inrinput"+datacurrencyid).style.display = "none";
        document.getElementById("marycold"+datacurrencyid).style.display = "block";
        $('#inr_field_valueid'+datacurrencyid).removeAttr("required");
    } else {
        switch (true) {
        case selectedText.includes("EURUSD") || selectedText.includes("GBPUSD"):
        document.getElementById("inrinputlabelvalue" + datacurrencyid).innerText = "USDINR Wash Rate";
        break;
        case selectedText.includes("USDJPY"):
        document.getElementById("inrinputlabelvalue" + datacurrencyid).innerText = "JPYINR Wash Rate";
        break;
        }

      document.getElementById("inrinput"+datacurrencyid).style.display = "block";
      document.getElementById("marycold"+datacurrencyid).style.display = "none";
      $('#inr_field_valueid'+datacurrencyid).attr("required", "required");

    }
  // Handle the change event for the input field with name 'inr_field_value'
});
$('.datepicker').datepicker({
showOtherMonths: true,
selectOtherMonths: true,
format: "dd/mm/yyyy",
autoclose: true
});
});

</script>