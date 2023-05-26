
<div class="container-fluid pt-8">
<?= $this->include('bottomtopbar/topbar') ?>
<div class="card-body">
<div class="nav-wrapper p-0">
<?php echo $this->include('message/message') ?>  
</div>
</div>
<div class="card shadow ">
<div class="card-body">

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
	<div class="col-md-3 col-sm-12">
	<label class="form-label"></label>
	<div class="form-group mt-3">
		<!-- <label class="form-label card-title">spot ref.</label> -->
	</div>
	</div>
	<div class="col-md-3 col-sm-12">
	<label class="form-label"></label>
	<div class="form-group mt-3">
		<!-- <label class="form-label card-title">76.7000</label> -->
	</div>
	</div>


<!-- start -->

<div class="col-md-12">
<div class="card shadow">
	<div class="card-header">
		<h2 class="mb-0">MTM - Operating Risk</h2>
	</div>
<div class="card-body">
<div id="accordion">
<div class="accordion">
<div class="accordion-header" data-toggle="collapse" data-target="#panel-body-1">
<h4>IMPORT</h4>
</div>
<div class="accordion-body collapse show border border-top-0 text-sm" id="panel-body-1" data-parent="#accordion">
<?php 				
echo "<div class='table-responsive'><table id='example' class='table table-striped table-bordered w-100 text-nowrap'>";
		echo "<thead><tr>";
		echo "<th>(Amount outstanding in USD)</th>";
		echo "<th>All Months</th>";
		if(!empty($databymonth)){
		foreach ($databymonth as $key => $value) {
		echo "<th>".date("M-y", strtotime($key))."</th>";
		}}else{
		echo "<td>No Record Found</td>";	
		}
		echo "</tr></thead>";
		
		$allmonthtotals = array(
		"totalUnderlyingExposures" => 0,
		"totalAmountinFC" => 0,
		"totalToatalforwardAmount" => 0
		);
		
		foreach ($databymonth as $key => $value) {
		$exposures[] = array_column($value, 'UnderlyingExposures');
		$totals[] = array_column($value, 'ToatalforwardAmount');
		$amountfc[] = array_column($value, 'amountinFC');
		}
		
		foreach ($databymonth as $key => $value) {
		foreach ($value as $innerValue) {
		$allmonthtotals["totalUnderlyingExposures"] += $innerValue["UnderlyingExposures"];
		$allmonthtotals["totalAmountinFC"] += $innerValue["amountinFC"];
		$allmonthtotals["totalToatalforwardAmount"] += $innerValue["ToatalforwardAmount"];
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
		if(!empty($amountfc)){
		for ($i = 0; $i < count($amountfc); $i++) {
		$perctcovered[$i] = array();
		for ($j = 0; $j < count($amountfc[$i]); $j++) {
		if (isset($amountfc[$i][$j])) {
		$perctcovered[$i][$j] =($exposures[$i][$j]/$amountfc[$i][$j])*100;
		} else {
		$perctcovered[$i][$j] = ($exposures[$i][$j]/1)*100;
		}
		}
		}
		}

		echo "<tbody><tr><td>Forward purchases</td>";
		echo "<td>".$allmonthtotals['totalUnderlyingExposures']."</td>";
		if(!empty($exposures)){
		foreach ($exposures as $k => $v) {
		echo "<td>".$v[0]."</td>";
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
		echo "<td>".$allmonthtotals['totalAmountinFC']."</td>";
		if(!empty($amountfc)){
		foreach ($amountfc as $k => $v) {
		echo "<td>".$v[0]."</td>";
		}}else{
		echo "<td>No Record Found</td>";	
		}
		echo "</tr>";

		echo "<tr><td> --- Imports</td>";
		echo "<td>".$allmonthtotals['totalToatalforwardAmount']."</td>";
		if(!empty($totals)){
		foreach ($totals as $k => $v) {
		echo "<td>".$v[0]."</td>";
		}}else{
			echo "<td>No Record Found</td>";
		}
		echo "</tr>";
		
		echo "<tr><td>   --- Buyers' Credit</td>";
		echo "<td>".($allmonthtotals['totalAmountinFC']-$allmonthtotals['totalToatalforwardAmount'])."</td>";
		if(!empty($resultexp)){
		foreach ($resultexp as $k => $v) {
		echo "<td>".$v[0]."</td>";
		}}else{
			echo "<td>No Record Found</td>";
		}
		echo "</tr>";
		
		echo "<tr><td>-- Other Payments</td>";
		echo "<td>-</td>";
		if(!empty($exposures)){
		foreach ($exposures as $k => $v) {
		echo "<td>-</td>";
		}}else{
		echo "<td>No Record Found</td>";	
		}
		echo "</tr>";
		
		echo "<tr><td>   --- Capital Payments</td>";
		echo "<td>-</td>";
		if(!empty($exposures)){
		foreach ($exposures as $k => $v) {
		echo "<td>-</td>";
		}}else{
		echo "<td>No Record Found</td>";	
		}
		echo "</tr>";
		
		echo "<tr><td>% covered</td>";
		echo "<td>".(!empty($allmonthtotals['totalAmountinFC']) && $allmonthtotals['totalAmountinFC'] != 0 ? ($allmonthtotals['totalUnderlyingExposures'] / $allmonthtotals['totalAmountinFC']) * 100 : 0) ."</td>";
		if(!empty($perctcovered)){
		foreach ($perctcovered as $k => $v) {
		echo "<td>".$v[0]."</td>";
		}}else{
		echo "<td>No Record Found</td>";	
		}
		echo "</tr>";
		echo "<tbody></table></div>";
		?>

</div>
</div>
<div class="accordion">
<div class="accordion-header " data-toggle="collapse" data-target="#panel-body-2">
<h4>EXPORT</h4>
</div>
<div class="accordion-body collapse border border-top-0 text-sm" id="panel-body-2" data-parent="#accordion">
<div class='table-responsive'>
<table id='example1' class='table table-striped table-bordered w-100 text-nowrap'>
	<thead>
		<tr>
			<th>(Amount outstanding in USD)</th>
			<th>All Months</th>
			<?php if(!empty($databymonthexport)){ foreach ($databymonthexport as $key => $value) { ?>
				<th><?php echo date("M-y", strtotime($key)); ?></th>
			<?php }}else{ ?>
				<th>No Record Found</th>
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
	if(!empty($amountfcexpt)){
	for ($i = 0; $i < count($amountfcexpt); $i++) {
	$perctcoveredexpt[$i] = array();
	for ($j = 0; $j < count($amountfcexpt[$i]); $j++) {
	if (isset($amountfcexpt[$i][$j])) {
	$perctcoveredexpt[$i][$j] =($exposuresexpt[$i][$j]/$amountfcexpt[$i][$j])*100;
	} else {
	$perctcoveredexpt[$i][$j] = ($exposuresexpt[$i][$j]/1)*100;
	}
	}
	}
	}


?>
	<tbody>
		<tr>
			<td>Forward sales</td>
			<td><?php echo $allmonthtotalsexpt['totalUnderlyingExposuresexpt']; ?></td>
			<?php if(!empty($exposuresexpt)){ foreach ($exposuresexpt as $k => $v) { ?>
				<td><?php echo $v[0]; ?></td>
			<?php }}else{ ?>
				<td>No Record Found</td>
			<?php } ?>
		</tr>
		<tr>
			<td>Underlying Exposures</td>
			<td><?php echo $allmonthtotalsexpt['totalAmountinFCexpt']; ?></td>
			<?php if(!empty($amountfcexpt)){ foreach ($amountfcexpt as $k => $v) { ?>
				<td><?php echo $v[0]; ?></td>
			<?php }}else{ ?>
				<td>No Record Found</td>
			<?php } ?>
		</tr>
		<tr>
			<td> ToatalforwardAmount</td>
			<td><?php echo $allmonthtotalsexpt['totalToatalforwardAmountexpt']; ?></td>
			<?php if(!empty($totalsexpt)){ foreach ($totalsexpt as $k => $v) { ?>
				<td><?php echo $v[0]; ?></td>
			<?php }}else{ ?>
				<td>No Record Found</td>
			<?php } ?>
		</tr>
		<tr>
			<td>% covered</td>
			<td><?php echo (!empty($allmonthtotalsexpt['totalAmountinFCexpt']) && $allmonthtotalsexpt['totalAmountinFCexpt'] != 0 ? ($allmonthtotalsexpt['totalUnderlyingExposuresexpt'] / $allmonthtotalsexpt['totalAmountinFCexpt']) * 100 : 0); ?></td>
			<?php if(!empty($perctcoveredexpt)){ foreach ($perctcoveredexpt as $k => $v) { ?>
				<td><?php echo $v[0]; ?></td>
			<?php }}else{ ?>
				<td>No Record Found</td>
			<?php } ?>
		</tr>
	</tbody>
</table>
</div>
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
