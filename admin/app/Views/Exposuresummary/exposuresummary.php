
<div class="container-fluid pt-8 moblieviews">
<?= $this->include('bottomtopbar/topbar') ?>
<div class="card-body paddingtop-mobilestyle">
<div class="nav-wrapper p-0">
<?php echo $this->include('message/message') ?>  
</div>
</div>
<div class="card shadow ">
<div class="card-body newcardstyle">

<div class="tab-content" id="myTabContent">

<!-- strat -->
<!-- end -->
<h2 class="mb-5"><?php echo $title ?></h2>

<div class="row">
	<div class="col-md-6 col-sm-12">
	<div class="form-group"><label class="form-label"><?php echo $pade_title1 ?></label>
	<select name="currencyies" 
	class="form-control" required>
	<option value="" selected>SELECT CURRENCY</option>
	<?php foreach ($transaction as $row) : ?>
	<option value="<?php echo $row['currency'] ?>" <?php echo ((!empty($row['currency'])&& isset($_GET['currency'])) && $row['currency'] == $_GET['currency']) ? 'selected' : '' ?>><?php echo $row['Currency'] ?></option>
	<?php endforeach; ?>
	</select>
	</div>
	</div>
	<div class="col-md-3">
	<label class="form-label"></label>
	<div class="form-group mt-3">
	<label><strong>Spot Rate :</strong></label>
	<span><?php echo isset($spotrateExport) ? $spotrateExport : 1 ; ?><span>/</span><?php echo isset($spotrateImport) ? $spotrateImport : 1 ;?></span>
	</div>
	</div>
	<div class="col-md-3 d-sm-none">
	<label class="form-label"></label>
	<div class="form-group mt-3">
	</div>
	</div>


<!-- start -->

<div class="col-md-12 oklahoma" style="display:<?php echo $style ?>">
<div class="card shadow">
<div class="card-body cardbodynopadding">

<div class='table-responsive' style='overflow-y: hidden;'>
<table id='' class='table table-striped table-bordered w-100 text-nowrap'>
<thead>
	<tr>
		<th></th>
		<th>All Months</th>
		<?php if(!empty($databymonthexport)){ foreach ($databymonthexport as $key => $value) { ?>
			<th><?php echo date("M-y", strtotime($key)); ?></th>
		<?php }}else{ ?>
			<th>No Record Found</th>
		<?php } ?>
	</tr>
	<tr>
		<th>Exports</th>
		<?php for ($iu = 0; $iu < 13; $iu++) { ?>
		<th></th>
		<?php } ?>
	</tr>
</thead>

<?php 
foreach ($databymonthexport as $key => $value) {
$exposuresexpt[] = array_column($value, 'UnderlyingExposures');
$totalsexpt[] = array_column($value, 'ToatalforwardAmount');
$amountfcexpt[] = array_column($value, 'amountinFC');
}
$allmonthtotalsexpt = array(
"totalUnderlyingExposuresexpt" => 0,
"totalAmountinFCexpt" => 0,
"totalToatalforwardAmountexpt" => 0
);

foreach ($databymonthexport as $key => $value) {
foreach ($value as $innerValue) {
$allmonthtotalsexpt["totalUnderlyingExposuresexpt"] += $innerValue["UnderlyingExposures"];
$allmonthtotalsexpt["totalAmountinFCexpt"] += $innerValue["amountinFC"];
$allmonthtotalsexpt["totalToatalforwardAmountexpt"] += $innerValue["ToatalforwardAmount"];
}
}


$perctcoveredexpt = array();
if(!empty($totalsexpt)){
for ($i = 0; $i < count($totalsexpt); $i++) {
$perctcoveredexpt[$i] = array();
for ($j = 0; $j < count($totalsexpt[$i]); $j++) {
if (!empty($totalsexpt[$i][$j]) && $exposuresexpt[$i][$j] != 0) {
$perctcoveredexpt[$i][$j] =($totalsexpt[$i][$j]/$exposuresexpt[$i][$j])*100;
} else {
$perctcoveredexpt[$i][$j] = 0;
}
}
}
}


?>
<tbody>
	<tr>
		<td>Forward sales</td>
		<td><?php echo number_format($allmonthtotalsexpt["totalToatalforwardAmountexpt"], 2, '.', ',') ?></td>
		<?php if(!empty($totalsexpt)){ foreach ($totalsexpt as $k => $v) { ?>
			<td><?php echo number_format($v[0], 2, '.', ','); ?></td>
		<?php }}else{ ?>
			<td>No Record Found</td>
		<?php } ?>
	</tr>
	<tr>
		<td> Put Options</td>
		<td><?php echo '-' ?></td>
		<?php if(!empty($totalsexpt)){ foreach ($totalsexpt as $k => $v) { ?>
			<td><?php echo '-' ?></td>
		<?php }}else{ ?>
			<td>No Record Found</td>
		<?php } ?>
	</tr>
	<tr>
		<td>Underlying Exposures</td>
		<td><?php echo number_format($allmonthtotalsexpt['totalUnderlyingExposuresexpt'], 2, '.', ','); ?></td>
		<?php if(!empty($exposuresexpt)){ foreach ($exposuresexpt as $k => $v) { ?>
			<td><?php echo number_format($v[0], 2, '.', ','); ?></td>
		<?php }}else{ ?>
			<td>No Record Found</td>
		<?php } ?>
	</tr>

	<tr>
		<td>% covered</td>
		<td><?php echo (!empty($allmonthtotalsexpt['totalToatalforwardAmountexpt']) && $allmonthtotalsexpt['totalToatalforwardAmountexpt'] != 0 ? number_format(($allmonthtotalsexpt['totalToatalforwardAmountexpt'] / $allmonthtotalsexpt['totalUnderlyingExposuresexpt']) * 100, 4, '.', ',') : 0); ?></td>
		<?php if(!empty($perctcoveredexpt)){ foreach ($perctcoveredexpt as $k => $v) { ?>
			<td><?php echo number_format($v[0], 4, '.', ','); ?></td>
		<?php }}else{ ?>
			<td>No Record Found</td>
		<?php } ?>
	</tr>
</div>



<?php 				
echo "<div class='table-responsive'>";
		echo "<thead><tr>";
		echo "<th>IMPORTS</th>";
		if(!empty($databymonth)){
		foreach ($databymonth as $key => $value) {
		 "<th></th>";
		}}else{
		echo "<td>No Record Found</td>";	
		}
		echo "</tr></thead>";
		
		$allmonthtotals = array(
		"totalUnderlyingExposures" => 0,
		"totalAmountinFC" => 0,
		"totalImports" => 0,
		"totalBuyersCredit" => 0,
		"totalOtherPayments" => 0,
		"totalCapitalPayments" => 0,
		"totalToatalforwardAmount" => 0
		);
		
	
		foreach ($databymonth as $key => $value) {
		$exposures[] = array_column($value, 'UnderlyingExposures');
		$imports[] = array_column($value, 'SumImportsType');
		$buyerscredit[] = array_column($value, 'SumBuyersCreditType');
		$totals[] = array_column($value, 'ToatalforwardAmount');
		$amountfc[] = array_column($value, 'amount_FC');
		$otherpayments[] = array_column($value, 'OtherPaymentsType');
		$capitalpayments[] = array_column($value, 'CapitalPaymentsType');
		}
	
		foreach ($databymonth as $key => $value) {
		foreach ($value as $innerValue) {
		$allmonthtotals["totalUnderlyingExposures"] +=  isset($innerValue["UnderlyingExposures"]) ? $innerValue["UnderlyingExposures"] : 0;
		$allmonthtotals["totalAmountinFC"] +=  isset($innerValue["amount_FC"]) ? $innerValue["amount_FC"] : 0 ;
		$allmonthtotals["totalToatalforwardAmount"] += $innerValue["ToatalforwardAmount"];
		$allmonthtotals["totalImports"] += $innerValue["SumImportsType"];
		$allmonthtotals["totalBuyersCredit"] += $innerValue["SumBuyersCreditType"];
		$allmonthtotals["totalOtherPayments"] += $innerValue["OtherPaymentsType"];
		$allmonthtotals["totalCapitalPayments"] += $innerValue["CapitalPaymentsType"];
		}
		}
		
		$resultexp = array();
		if(!empty($amountfc)){
		for ($i = 0; $i < count($amountfc); $i++) {
		$resultexp[$i] = array();
		for ($j = 0; $j < count($amountfc[$i]); $j++) {
		if (isset($amountfc[$i][$j])) {
		$resultexp[$i][$j] = $amountfc[$i][$j] - $totals[$i][$j];
		} else {
		$resultexp[$i][$j] = $totals[$i][$j];
		}
		}
		}
		}

		
		
		$perctcovered = array();
		if(!empty($totals)){
		for ($i = 0; $i < count($totals); $i++) {
		$perctcovered[$i] = array();
		for ($j = 0; $j < count($totals[$i]); $j++) {
		if ( isset($totals[$i][$j]) && $exposures[$i][$j] != 0 ) {
		$perctcovered[$i][$j] =($totals[$i][$j]/$exposures[$i][$j])*100;
		} else {
		$perctcovered[$i][$j] = 0;
		}
		}
		}
		}

		

		echo "<tbody><tr><td>Forward purchases</td>";
		echo "<td>". number_format($allmonthtotals['totalToatalforwardAmount'], 2, '.', ',')."</td>";
		if(!empty($totals)){
		foreach ($totals as $k => $v) {
		echo "<td>". number_format($v[0], 2, '.', ',')."</td>";
		}}else{
		echo "<td>No Record Found</td>";	
		}
		echo "</tr>";
		echo "<tr><td>Call options</td>";
		echo "<td>-</td>";
		if(!empty($exposures)){
		foreach ($exposures as $k => $v) {
		echo "<td>-</td>";
		}}else{
		echo "<td>No Record Found</td>";	
		}
		echo "</tr>";
		echo "<tr><td>Underlying Exposures</td>";
		echo "<td>". number_format($allmonthtotals['totalUnderlyingExposures'], 2, '.', ',')."</td>";
		if(!empty($exposures)){
		foreach ($exposures as $k => $v) {
		echo "<td>". number_format($v[0], 2, '.', ',') ."</td>";
		}}else{
		echo "<td>No Record Found</td>";	
		}
		echo "</tr>";

		echo "<tr><td> --- Imports</td>";
		echo "<td>". number_format($allmonthtotals['totalImports'], 2, '.', ',') ."</td>";
		if(!empty($imports)){
		foreach ($imports as $k => $v) {
		echo "<td>". number_format($v[0], 2, '.', ',') ."</td>";
		}}else{
			echo "<td>No Record Found</td>";
		}
		echo "</tr>";
		
		echo "<tr><td>   --- Buyers' Credit</td>";
		echo "<td>". number_format($allmonthtotals['totalBuyersCredit'], 2, '.', ',') ."</td>";
		if(!empty($buyerscredit)){
		foreach ($buyerscredit as $k => $v) {
		echo "<td>". number_format($v[0], 2, '.', ',') ."</td>";
		}}else{
			echo "<td>No Record Found</td>";
		}
		echo "</tr>";
		
		echo "<tr><td>-- Other Payments</td>";
		echo "<td>". number_format($allmonthtotals['totalOtherPayments'], 2, '.', ',') ."</td>";
		if(!empty($otherpayments)){
		foreach ($otherpayments as $k => $v) {
		echo "<td>". number_format($v[0], 2, '.', ',') ."</td>";
		}}else{
		echo "<td>No Record Found</td>";	
		}
		echo "</tr>";
		
		echo "<tr><td>   --- Capital Payments</td>";
		echo "<td>". number_format($allmonthtotals['totalCapitalPayments'] , 2, '.', ',')."</td>";
		if(!empty($capitalpayments)){
		foreach ($capitalpayments as $k => $v) {
		echo "<td>". number_format($v[0], 2, '.', ',') ."</td>";
		}}else{
		echo "<td>No Record Found</td>";	
		}
		echo "</tr>";
		
		echo "<tr><td>% covered</td>";
		echo "<td>".(!empty($allmonthtotals['totalToatalforwardAmount']) && $allmonthtotals['totalToatalforwardAmount'] != 0 ? number_format(($allmonthtotals['totalToatalforwardAmount'] / $allmonthtotals['totalUnderlyingExposures']) * 100, 4, '.', ',') : 0.00) ."</td>";
		if(!empty($perctcovered)){
		foreach ($perctcovered as $k => $v) {
		echo "<td>".number_format($v[0], 4, '.', ',')."</td>";
		}}else{
		echo "<td>No Record Found</td>";	
		}
		echo "</tr>";
		echo "<tbody></table></div>";
		?>

</div>
</div>


</div>

</div>
</div>
</div>
<!-- end  --> 
</div>
</div>
</div>
</div>
<!-- Dynamic fields for plan desc -->
<!-- Dynamic fields for plan desc -->
</div>







<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>
<!-- Don't forget to include Jquery also -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>


<script>
$(document).ready(function() {
var selectEl = $('select[name="currencyies"]');
var currentVal = selectEl.val(); // initialize current value

selectEl.on('change', function() {
var selectedVal = $(this).val();
if(selectedVal && selectedVal !== currentVal) { // check if value has changed
currentVal = selectedVal; // update current value
window.location.href = 'exposure_summary?currency=' + selectedVal; // update URL
}
});
});

</script>
