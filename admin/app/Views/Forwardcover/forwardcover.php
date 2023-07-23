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
<!-- Underlying Exposure strat -->
<div class="form-group"><label class="form-label"><?php echo $pade_title1 ?></label>
<select name="bank" id="bank" class="form-control" required>
<option value="">-- Choose Bank --</option>
<?php foreach ($bank as $row): ?>
<option value="<?php echo $row['bank_id'] ?>"><?php echo $row['bank_name'] ?></option>
<?php endforeach;?>
</select>
</div>

<div class="form-group"><label class="form-label"><?php echo $pade_title5 ?></label>
<select name="buysell" id="buysell" class="form-control" data-currencyid="<?=$i?>" required>
<option value="">-- Buy / Sell --
<option value="2">Buy</option>
<option value="1">Sell</option>
</select>
</div>

<div class="form-group"><label class="form-label"><?php echo $pade_title9 ?></label> 
<input id="premium" type="text" name="premium"  value=''  placeholder="Premium" class="form-control"  required/>
</div>

<div class="form-group"><label class="form-label"><?php echo $pade_title13 ?></label> 
<input id="currentforwardpremium" type="text" name="currentforwardpremium"  value=''  placeholder="Current Forward Premium" class="form-control"  required readonly/>
</div>

</div>
<div class="col-md-3 col-sm-12">

<!-- Forward/ Option Start -->
<input type="hidden" name="count_items" id="count_items" value="<?php echo  $i?>" />

<div class="form-group"><label class="form-label"><?php echo $pade_title2 ?></label> 
<input id="dealno" type="text" name="dealno"  value=''  placeholder="Deal No" class="form-control"  required/>
</div>


<!-- Forward/ Option end -->

<!-- Currency Bought Start -->
<div class="form-group"><label class="form-label"><?php echo $pade_title6 ?></label>
<input id="dealdatefrom" name="dealdatefrom" class="form-control datepicker" type="text" placeholder="MM/DD/YYYY" required/>
</div>

<!-- Currency Bought End -->
<!-- expirydate -->
<div class="form-group"><label class="form-label"><?php echo $pade_title10 ?></label>
<input id="margin" type="text" name="margin"  value=''  placeholder="Margin" class="form-control"  required/>
</div>


<div class="form-group"><label class="form-label"><?php echo $pade_title14 ?></label> 
<input id="currentforwardrate" name="currentforwardrate" step=".0001" type="text" name="pade_title14"  value=''  placeholder="Current Forward Rate" class="form-control"  required readonly/>
</div>

</div>
<div class="col-md-3 col-sm-12">

<!-- Deal No Start -->
<div class="form-group"><label class="form-label"><?php echo $pade_title18 ?></label> 
<input id="dealdate" name="dealdate" class="form-control datepicker" type="text" placeholder="MM/DD/YYYY" required/>
</div>
<!-- Deal No End -->

<!-- Currency Sold Start -->

<div class="form-group"><label class="form-label"><?php echo $pade_title7 ?></label>
<input id="dealdateto" name="dealdateto" class="form-control datepicker" type="text" placeholder="MM/DD/YYYY" required/>
</div>

<!-- Contrcted Rate-->

<div class="form-group"><label class="form-label"><?php echo $pade_title11 ?></label>
<input id="contrctedrate" name="contrctedrate" step=".0001" class="form-control" type="text" placeholder="Contrcted Rate" required readonly/>
</div>

<div class="form-group"><label class="form-label"><?php echo $pade_title15 ?></label>
<input id="washrate" name="washrate" step=".0001" class="form-control" type="text" placeholder="Wash Rate" required readonly/>
</div>

</div>


<div class="col-md-3 col-sm-12">
<!-- Deal Date Start -->
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
<!-- Deal Date End  -->

<!-- Amount (FC) Start -->
<div class="form-group"><label class="form-label"><?php echo $pade_title8 ?></label>
<input id="spotrate" type="number" name="spotrate" step=".0001" placeholder="Spot Rate" class="form-control"  required/>
</div>
<!-- Amount (FC) End -->

<div class="form-group"><label class="form-label"><?php echo $pade_title12 ?></label>
<input id="forwardamountos" type="number" name="forwardamountos" step=".0001" placeholder="Forward Amount OS" class="form-control"  required/>
</div>

<div class="form-group"><label class="form-label"><?php echo $pade_title16 ?></label>
<input id="mtm" name="mtm" step=".0001" class="form-control" type="text" placeholder="MTM Rate" required readonly/>
</div>
</div>

</div>
<div class="col-md-12 text-center">
<button type="button" id="calculate" class="btn rounded-0 btn-warning bg-gradient">Calculate</button>
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
<script src="assets/js/select2.js"></script>

<script type="text/javascript" src="<?php echo base_url('assets/calendar/calendar.js'); ?>"></script>

<script>

$("#calculate").click(function() {
    var i = parseFloat($("#spotrate").val());
    var type = parseFloat($("#buysell").val());
    var j = parseFloat($("#premium").val());
    var k = parseFloat($("#margin").val());
    var currency = parseFloat($("#currency").val());
    if (isNaN(i) || isNaN(j) || isNaN(k)) {
        $("#contrctedrate").val("Invalid Input").css("color", "red");
    } else {
        var ans = (j + i) - k;
        $("#contrctedrate").val(ans);
    }
    var selectedDate = $("#dealdatefrom").datepicker("getDate");
        var day = selectedDate.getDate();
        var month = selectedDate.getMonth() + 1; // JavaScript months are 0-based (0 to 11)
        var year = selectedDate.getFullYear();
        var formattedDate = ("0" + day).slice(-2) + "-" + ("0" + month).slice(-2) + "-" + year;
    if((formattedDate && type) && currency){
        $.ajax({
        url: '<?php echo base_url("/forwardcoverdependant"); ?>',
        type: "POST",
        data: {'formattedDate':formattedDate,
            'type':type,
            'currency':currency
        },
        success: function(data) {
        var forwardRate = $.parseJSON(data);
        // console.log('inn:', response)
        // var jsonResponse = JSON.parse(response);
        // var forwardRate = jsonResponse.result.forward_rate;
        $("#currentforwardpremium").val(forwardRate);
        $("#currentforwardrate").val(forwardRate);
        },
        error: function(xhr, status, error) {
        // Handle the error
        $("#currentforwardpremium").val("Invalid Input").css("color", "red");
        console.log('Error:', error);
        }
        });

        $.ajax({
        url: '<?php echo base_url("/forwardcovermtm"); ?>',
        type: "POST",
        data: {'formattedDate':formattedDate,
            'type':type,
            'currency':currency
        },
        success: function(data) {
            var forwardRate = $.parseJSON(data);
            $("#washrate").val(forwardRate);
            var O =  $("#currentforwardpremium").val();
            var L = $("#contrctedrate").val();
            var M = $("#forwardamountos").val();
            var P = forwardRate;
            if (isNaN(O) || isNaN(L) || isNaN(M) || isNaN(P)) {
                alert('No Valid Data !!');
            }else{
                if(type === 1){
                    var ansTWO =  (L - O) * M * P;
                }else if(type === 2){
                    var ansTWO =  (O - L) * M * P;
                }
                $("#mtm").val(ansTWO);
            }
        },
        error: function(xhr, status, error) {
            console.log('Error:', error);
        }
        });
    }

			
});



// adding more fields


</script>