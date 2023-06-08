
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
<select name="currencyieshelicopterview" 
class="form-control" required>
<option value="" selected>INR SELECTED </option>
<?php foreach ($transaction as $row) : ?>
<option value="<?php echo $row['currency'] ?>" <?php echo ((!empty($row['currency'])&& isset($_GET['currencyieshelicopterview'])) && $row['currency'] == $_GET['currencyieshelicopterview']) ? 'selected' : '' ?>><?php echo $row['Currency'] ?></option>
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

<div class="col-md-12" style="display:<?php echo $style ?>">
<div class="card shadow">
<div class="card-header">
	<h2 class="mb-0">MTM - Operating Risk</h2>
</div>
<div class="card-body">


<!-- export start -->
<div class="accordion">
<div class="accordion-header " data-toggle="collapse" data-target="#panel-body-2">
<h4>EXPORTS</h4>
</div>
<div class="accordion-body collapse show border border-top-0 text-sm" id="panel-body-2" data-parent="#accordion">
	<?php 				
	echo "<div class='table-responsive'><table id='example1' class='table table-striped table-bordered w-100 text-nowrap'>";
	echo "<thead><tr>";
	echo "<th>-</th>";
	echo "<th>Exposure</th>";
	echo "<th>Target Value</th>";
	echo "<th>Target Rate</th>";
	echo "<th>Hedged Amount</th>";
	echo "<th>Hedged  %</th>";
	echo "<th>Hedged Rate</th>";
	echo "<th>Open Amount FC</th>";
	echo "<th>MTM Rate</th>";
	echo "<th>Open Details - Gain / Loss</th>";
	echo "<th>Potential Gain / Loss on the Portfolio</th>";
	echo "</tr></thead><tbody>";
	$expotrate = isset($spotrateexports) ?  $spotrateexports : 1;
	foreach($helicoptertabsexport as $row => $value) {
	$exports_sum = 0;
	$targetvalue_sum = 0;
	$targetrate_sum = 0;
	$hedged_sum = 0;
	$hedged_amount_sum = 0;
	$openamountfc_sum = 0;
	$gainloss_sum = 0;
	$hedgedperctage_sum = 0;
	$mtm_sum = 0;
	$potentialgainloss_sum = 0;
	$potential_portfolio_sum = 0;

	
	// first value
	$hedgedpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[3] : 0;
	$hedgedpositionrate = $value["Q1"] != "" ? explode(",", $value["Q1"])[1] : 0;
	$openpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : 0;
	$mtmrate_1 = $value["Q1"] != "" ? $expotrate : 1;

	$potential_portfolio_1 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_1);
	$exports_1 = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[0] : "0");
	$target_value_1 = ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] * explode(",", $value["Q1"])[2] : "0");
	$target_rate_1 = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[2] : "0");
	$hedged_1  = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[1] : "0");
	$hedged_amount_1   = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[3] : "0");
	$openamountfc_1 = ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : "0");
	$hedgedperctage_1 = ($value["Q1"] != "" ? (explode(",", $value["Q1"])[3] / explode(",", $value["Q1"])[0])*100  : "0");
	$gainloss_1 = $target_value_1-(($hedged_amount_1*$hedgedpositionrate)+($mtmrate_1*$openamountfc_1));

	
	// second value
	$hedgedpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[3] : 0;
	$hedgedpositionrate = $value["Q2"] != "" ? explode(",", $value["Q2"])[1] : 0;
	$openpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : 0;
	$mtmrate_2 = $value["Q2"] != "" ? $expotrate : 1;
	$potential_portfolio_2 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_2);
	$exports_2 = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[0] : "0");
	$target_value_2 = ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] * explode(",", $value["Q2"])[2] : "0");
	$target_rate_2 = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[2] : "0");
	$hedged_2  = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[1] : "0");
	$hedged_amount_2   = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[3] : "0");
	$openamountfc_2 = ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : "0");
	$hedgedperctage_2 = ($value["Q2"] != "" ? (explode(",", $value["Q2"])[3] / explode(",", $value["Q2"])[0])*100  : "0");
	$gainloss_2 = $target_value_2-(($hedged_amount_2*$hedgedpositionrate)+($mtmrate_2*$openamountfc_2));

	
	// third value
	$hedgedpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[3] : 0;
	$hedgedpositionrate = $value["Q3"] != "" ? explode(",", $value["Q3"])[1] : 0;
	$openpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : 0;
	$mtmrate_3 = $value["Q3"] != "" ? $expotrate : 1;
	$potential_portfolio_3 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_3);
	
	$exports_3 = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[0] : "0");
	$target_value_3 = ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] * explode(",", $value["Q3"])[2] : "0");
	$target_rate_3 = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[2] : "0");
	$hedged_3  = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[1] : "0");
	$hedged_amount_3   = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[3] : "0");
	$openamountfc_3 = ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : "0");
	$hedgedperctage_3 = ($value["Q3"] != "" ? (explode(",", $value["Q3"])[3] / explode(",", $value["Q3"])[0])*100  : "0");
	$gainloss_3 = $target_value_3-(($hedged_amount_3*$hedgedpositionrate)+($mtmrate_3*$openamountfc_3));

	
	// fourth value
	$hedgedpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[3] : 0;
	$hedgedpositionrate = $value["Q4"] != "" ? explode(",", $value["Q4"])[1] : 0;
	$openpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : 0;
	$mtmrate_4 = $value["Q4"] != "" ? $expotrate : 1;
	$potential_portfolio_4 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_4);
	$exports_4 = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[0] : "0");
	$target_value_4 = ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] * explode(",", $value["Q4"])[2] : "0");
	$target_rate_4 = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[2] : "0");
	$hedged_4  = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[1] : "0");
	$hedged_amount_4   = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[3] : "0");
	$openamountfc_4 = ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : "0");
	$hedgedperctage_4 = ($value["Q4"] != "" ? (explode(",", $value["Q4"])[3] / explode(",", $value["Q4"])[0])*100  : "0");
	$gainloss_4 = $target_value_4-(($hedged_amount_4*$hedgedpositionrate)+($mtmrate_4*$openamountfc_4));


	$exports_sum += $exports_1 + $exports_2 + $exports_3 + $exports_4;
	$targetvalue_sum += $target_value_1 + $target_value_2 + $target_value_3 + $target_value_4;
	$targetrate_sum += $target_rate_1 + $target_rate_2 + $target_rate_3 + $target_rate_4;
	$hedged_sum += $hedged_1 + $hedged_2 + $hedged_3 + $hedged_4;
	$hedged_amount_sum += $hedged_amount_1 + $hedged_amount_2 + $hedged_amount_3 + $hedged_amount_4;
	$openamountfc_sum += $openamountfc_1 + $openamountfc_2 + $openamountfc_3 + $openamountfc_4;
	$gainloss_sum += $gainloss_1 + $gainloss_2 + $gainloss_3 + $gainloss_4;
	$hedgedperctage_sum += $hedgedperctage_1 + $hedgedperctage_2 + $hedgedperctage_3 + $hedgedperctage_4;
	$potential_portfolio_sum += $potential_portfolio_1 + $potential_portfolio_2 + $potential_portfolio_3 + $potential_portfolio_4;


	
	
	echo "<tr>";	
	echo "<td>Exports</td><td>" . $exports_sum  . "</td>
	<td>" . $targetvalue_sum  . "</td><td>"
	. number_format($targetrate_sum, 4) . "</td><td>"
	. $hedged_amount_sum  . "</td><td>"
	. number_format($hedgedperctage_sum, 4)  . "</td><td>"
	. number_format($hedged_sum, 4)  . "</td><td>"
	. $openamountfc_sum  . "</td><td>"
	. $expotrate  . "</td><td>"
	. $gainloss_sum  . "</td><td>"
	. $potential_portfolio_sum  . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[3] : 0;
	$hedgedpositionrate = $value["Q1"] != "" ? explode(",", $value["Q1"])[1] : 0;
	$openpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : 0;
	$mtmrate_1 = $value["Q1"] != "" ? $expotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_1);

	echo "<tr>";	
	echo "<td>Q1</td><td>" . ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] : "-")  . "</td>
	<td>" . ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] * explode(",", $value["Q1"])[2] : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? number_format(explode(",", $value["Q1"])[2], 4) : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? explode(",", $value["Q1"])[3] : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? number_format((explode(",", $value["Q1"])[3] / explode(",", $value["Q1"])[0])*100, 4) : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? number_format(explode(",", $value["Q1"])[1], 4) : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? $expotrate  : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? $gainloss_1  : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[3] : 0;
	$hedgedpositionrate = $value["Q2"] != "" ? explode(",", $value["Q2"])[1] : 0;
	$openpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : 0;
	$mtmrate_2 = $value["Q2"] != "" ? $expotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_2);

	echo "<tr>";
	echo "<td>Q2</td><td>" . ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] : "-")  . "</td>
	<td>" . ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] * explode(",", $value["Q2"])[2] : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? number_format(explode(",", $value["Q2"])[2], 4) : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? explode(",", $value["Q2"])[3] : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? number_format((explode(",", $value["Q2"])[3] / explode(",", $value["Q2"])[0])*100, 4) : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? number_format(explode(",", $value["Q2"])[1], 4) : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? $expotrate  : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? $gainloss_2 : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[3] : 0;
	$hedgedpositionrate = $value["Q3"] != "" ? explode(",", $value["Q3"])[1] : 0;
	$openpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : 0;
	$mtmrate_3 = $value["Q3"] != "" ? $expotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_3);

	echo "<tr>";	
	echo "<td>Q3</td><td>" . ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] : "-")  . "</td>
	<td>" . ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] * explode(",", $value["Q3"])[2] : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? number_format(explode(",", $value["Q3"])[2], 4) : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? explode(",", $value["Q3"])[3] : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? number_format((explode(",", $value["Q3"])[3] / explode(",", $value["Q3"])[0])*100, 4) : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? number_format(explode(",", $value["Q3"])[1], 4) : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? $expotrate  : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? $gainloss_3  : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[3] : 0;
	$hedgedpositionrate = $value["Q4"] != "" ? explode(",", $value["Q4"])[1] : 0;
	$openpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : 0;
	$mtmrate_4 = $value["Q4"] != "" ? $expotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_4);

	echo "<tr>";	
	echo "<td>Q4</td><td>" . ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] : "-")  . "</td>
	<td>" . ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] * explode(",", $value["Q4"])[2] : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? number_format(explode(",", $value["Q4"])[2], 4) : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? explode(",", $value["Q4"])[3] : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? number_format((explode(",", $value["Q4"])[3] / explode(",", $value["Q4"])[0])*100, 4) : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? number_format(explode(",", $value["Q4"])[1], 4) : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? $expotrate : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? $gainloss_4  : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";
	}if (count($helicoptertabsexport) <= 0) :
			echo "<tr>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
			echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
			echo "</tr>";
		endif;
	echo "</tbody></table></div>";
	?>

</div>

</div>


<!-- imports -->
<div id="accordion">
<div class="accordion">
<div class="accordion-header" data-toggle="collapse" data-target="#panel-body-1">
<h4>IMPORTS</h4>
</div>
<div class="accordion-body collapse  border border-top-0 text-sm" id="panel-body-1" data-parent="#accordion">
	<?php 				
	echo "<div class='table-responsive'><table id='example' class='table table-striped table-bordered w-100 text-nowrap'>";
	echo "<thead><tr>";
	echo "<th>-</th>";
	echo "<th>Exposure</th>";
	echo "<th>Target Value</th>";
	echo "<th>Target Rate</th>";
	echo "<th>Hedged Amount</th>";
	echo "<th>Hedged  %</th>";
	echo "<th>Hedged Rate</th>";
	echo "<th>Open Amount FC</th>";
	echo "<th>MTM Rate</th>";
	echo "<th>Open Details - Gain / Loss</th>";
	echo "<th>Potential Gain / Loss on the Portfolio</th>";
	echo "</tr></thead><tbody>";
	$importspotrate = isset($spotrateimports) ?  $spotrateimports : 1;
	foreach($helicoptertabs as $row => $value) {
	$exports_sum = 0;
	$targetvalue_sum = 0;
	$targetrate_sum = 0;
	$hedged_sum = 0;
	$hedged_amount_sum = 0;
	$openamountfc_sum = 0;
	$gainloss_sum = 0;
	$hedgedperctage_sum = 0;
	$mtm_sum = 0;
	$potentialgainloss_sum = 0;
	$potential_portfolio_sum = 0;
	// first value
	$hedgedpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[3] : 0;
	$hedgedpositionrate = $value["Q1"] != "" ? explode(",", $value["Q1"])[1] : 0;
	$openpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : 0;
	$mtmrate_1 = $value["Q1"] != "" ? $importspotrate : 1;
	$potential_portfolio_1 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_1);
	$exports_1 = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[0] : "0");
	$target_value_1 = ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] * explode(",", $value["Q1"])[2] : "0");
	$target_rate_1 = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[2] : "0");
	$hedged_1  = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[1] : "0");
	$hedged_amount_1   = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[3] : "0");
	$openamountfc_1 = ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : "0");
	$hedgedperctage_1 = ($value["Q1"] != "" ? (explode(",", $value["Q1"])[3] / explode(",", $value["Q1"])[0])*100  : "0");
	$gainloss_1 = $target_value_1-(($hedged_amount_1*$hedgedpositionrate)+($mtmrate_1*$openamountfc_1));

	
	// second value
	$hedgedpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[3] : 0;
	$hedgedpositionrate = $value["Q2"] != "" ? explode(",", $value["Q2"])[1] : 0;
	$openpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : 0;
	$mtmrate_2 = $value["Q2"] != "" ? $importspotrate : 1;
	$potential_portfolio_2 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_2);
	$exports_2 = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[0] : "0");
	$target_value_2 = ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] * explode(",", $value["Q2"])[2] : "0");
	$target_rate_2 = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[2] : "0");
	$hedged_2  = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[1] : "0");
	$hedged_amount_2   = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[3] : "0");
	$openamountfc_2 = ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : "0");
	$hedgedperctage_2 = ($value["Q2"] != "" ? (explode(",", $value["Q2"])[3] / explode(",", $value["Q2"])[0])*100  : "0");
	$gainloss_2 = $target_value_2-(($hedged_amount_2*$hedgedpositionrate)+($mtmrate_2*$openamountfc_2));

	// third value
	$hedgedpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[3] : 0;
	$hedgedpositionrate = $value["Q3"] != "" ? explode(",", $value["Q3"])[1] : 0;
	$openpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : 0;
	$mtmrate_3 = $value["Q3"] != "" ? $importspotrate : 1;
	$potential_portfolio_3 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_3);
	$exports_3 = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[0] : "0");
	$target_value_3 = ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] * explode(",", $value["Q3"])[2] : "0");
	$target_rate_3 = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[2] : "0");
	$hedged_3  = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[1] : "0");
	$hedged_amount_3   = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[3] : "0");
	$openamountfc_3 = ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : "0");
	$hedgedperctage_3 = ($value["Q3"] != "" ? (explode(",", $value["Q3"])[3] / explode(",", $value["Q3"])[0])*100  : "0");
	$gainloss_3 = $target_value_3-(($hedged_amount_3*$hedgedpositionrate)+($mtmrate_3*$openamountfc_3));

	
	// fourth value
	$hedgedpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[3] : 0;
	$hedgedpositionrate = $value["Q4"] != "" ? explode(",", $value["Q4"])[1] : 0;
	$openpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : 0;
	$mtmrate_4 = $value["Q4"] != "" ? $importspotrate : 1;
	$potential_portfolio_4 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_4);
	$exports_4 = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[0] : "0");
	$target_value_4 = ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] * explode(",", $value["Q4"])[2] : "0");
	$target_rate_4 = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[2] : "0");
	$hedged_4  = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[1] : "0");
	$hedged_amount_4   = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[3] : "0");
	$openamountfc_4 = ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : "0");
	$hedgedperctage_4 = ($value["Q4"] != "" ? (explode(",", $value["Q4"])[3] / explode(",", $value["Q4"])[0])*100  : "0");
	$gainloss_4 = $target_value_4-(($hedged_amount_4*$hedgedpositionrate)+($mtmrate_4*$openamountfc_4));



	$exports_sum += $exports_1 + $exports_2 + $exports_3 + $exports_4;
	$targetvalue_sum += $target_value_1 + $target_value_2 + $target_value_3 + $target_value_4;
	$targetrate_sum += $target_rate_1 + $target_rate_2 + $target_rate_3 + $target_rate_4;
	$hedged_sum += $hedged_1 + $hedged_2 + $hedged_3 + $hedged_4;
	$hedged_amount_sum += $hedged_amount_1 + $hedged_amount_2 + $hedged_amount_3 + $hedged_amount_4;
	$openamountfc_sum += $openamountfc_1 + $openamountfc_2 + $openamountfc_3 + $openamountfc_4;
	$gainloss_sum += $gainloss_1 + $gainloss_2 + $gainloss_3 + $gainloss_4;
	$hedgedperctage_sum += $hedgedperctage_1 + $hedgedperctage_2 + $hedgedperctage_3 + $hedgedperctage_4;
	$potential_portfolio_sum += $potential_portfolio_1 + $potential_portfolio_2 + $potential_portfolio_3 + $potential_portfolio_4;


	
	
	echo "<tr>";	
	echo "<td>Imports</td><td>" . $exports_sum  . "</td>
	<td>" . $targetvalue_sum  . "</td><td>"
	. number_format($targetrate_sum, 4) . "</td><td>"
	. $hedged_amount_sum  . "</td><td>"
	. number_format($hedgedperctage_sum, 4) . "</td><td>"
	. number_format($hedged_sum, 4)  . "</td><td>"
	. $openamountfc_sum  . "</td><td>"
	. $importspotrate  . "</td><td>"
	. $gainloss_sum  . "</td><td>"
	. $potential_portfolio_sum  . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[3] : 0;
	$hedgedpositionrate = $value["Q1"] != "" ? explode(",", $value["Q1"])[1] : 0;
	$openpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : 0;
	$mtmrate_1 = $value["Q1"] != "" ? $importspotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_1);

	echo "<tr>";	
	echo "<td>Q1</td><td>" . ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] : "-")  . "</td>
	<td>" . ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] * explode(",", $value["Q1"])[2] : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? number_format(explode(",", $value["Q1"])[2], 4) : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? explode(",", $value["Q1"])[3] : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? number_format((explode(",", $value["Q1"])[3] / explode(",", $value["Q1"])[0])*100, 4) : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? number_format(explode(",", $value["Q1"])[1], 4) : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? $importspotrate : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? $gainloss_1  : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[3] : 0;
	$hedgedpositionrate = $value["Q2"] != "" ? explode(",", $value["Q2"])[1] : 0;
	$openpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : 0;
	$mtmrate_2 = $value["Q2"] != "" ? $importspotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_2);

	echo "<tr>";
	echo "<td>Q2</td><td>" . ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] : "-")  . "</td>
	<td>" . ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] * explode(",", $value["Q2"])[2] : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? number_format(explode(",", $value["Q2"])[2], 4) : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? explode(",", $value["Q2"])[3] : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? number_format((explode(",", $value["Q2"])[3] / explode(",", $value["Q2"])[0])*100, 4) : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? number_format(explode(",", $value["Q2"])[1], 4) : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? $importspotrate : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? $gainloss_2 : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[3] : 0;
	$hedgedpositionrate = $value["Q3"] != "" ? explode(",", $value["Q3"])[1] : 0;
	$openpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : 0;
	$mtmrate_3 = $value["Q3"] != "" ? $importspotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_3);

	echo "<tr>";	
	echo "<td>Q3</td><td>" . ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] : "-")  . "</td>
	<td>" . ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] * explode(",", $value["Q3"])[2] : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? number_format(explode(",", $value["Q3"])[2], 4) : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? explode(",", $value["Q3"])[3] : "-")  . "</td><td>"
	. ($value["Q3"] != "" ?  number_format((explode(",", $value["Q3"])[3] / explode(",", $value["Q3"])[0])*100, 4): "-")  . "</td><td>"
	. ($value["Q3"] != "" ? number_format(explode(",", $value["Q3"])[1], 4): "-")  . "</td><td>"
	. ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? $importspotrate : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? $gainloss_3  : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[3] : 0;
	$hedgedpositionrate = $value["Q4"] != "" ? explode(",", $value["Q4"])[1] : 0;
	$openpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : 0;
	$mtmrate_4 = $value["Q4"] != "" ? $importspotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_4);

	echo "<tr>";	
	echo "<td>Q4</td><td>" . ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] : "-")  . "</td>
	<td>" . ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] * explode(",", $value["Q4"])[2] : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? number_format(explode(",", $value["Q4"])[2], 4): "-")  . "</td><td>"
	. ($value["Q4"] != "" ? explode(",", $value["Q4"])[3] : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? number_format((explode(",", $value["Q4"])[3] / explode(",", $value["Q4"])[0])*100, 4) : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? number_format(explode(",", $value["Q4"])[1], 4) : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? $importspotrate : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? $gainloss_4 : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";
	}if (count($helicoptertabs) <= 0) :
			echo "<tr>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
			echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
			echo "</tr>";
		endif;
	echo "</tbody></table></div>";
	?>

</div>
</div>



<!-- Buyer Credit -->


<div class="accordion">
<div class="accordion-header " data-toggle="collapse" data-target="#panel-body-3">
<h4>BUYERS CREDIT</h4>
</div>
<div class="accordion-body collapse border border-top-0 text-sm" id="panel-body-3" data-parent="#accordion">
	<?php 				
	echo "<div class='table-responsive'><table id='example3' class='table table-striped table-bordered w-100 text-nowrap'>";
	echo "<thead><tr>";
	echo "<th></th>";
	echo "<th>Exposure</th>";
	echo "<th>Target Value</th>";
	echo "<th>Target Rate</th>";
	echo "<th>Hedged Amount</th>";
	echo "<th>Hedged  %</th>";
	echo "<th>Hedged Rate</th>";
	echo "<th>Open Amount FC</th>";
	echo "<th>MTM Rate</th>";
	echo "<th>Open Details - Gain / Loss</th>";
	echo "<th>Potential Gain / Loss on the Portfolio</th>";
	echo "</tr></thead><tbody>";
	foreach($helicoptertabsbuyersCredit as $row => $value) {
	$exports_sum = 0;
	$targetvalue_sum = 0;
	$targetrate_sum = 0;
	$hedged_sum = 0;
	$hedged_amount_sum = 0;
	$openamountfc_sum = 0;
	$gainloss_sum = 0;
	$hedgedperctage_sum = 0;
	$mtm_sum = 0;
	$potentialgainloss_sum = 0;
	$potential_portfolio_sum = 0;

	
	// first value
	$hedgedpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[3] : 0;
	$hedgedpositionrate = $value["Q1"] != "" ? explode(",", $value["Q1"])[1] : 0;
	$openpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : 0;
	$mtmrate_1 = $value["Q1"] != "" ? $importspotrate : 1;
	$potential_portfolio_1 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_1);
	$exports_1 = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[0] : "0");
	$target_value_1 = ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] * explode(",", $value["Q1"])[2] : "0");
	$target_rate_1 = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[2] : "0");
	$hedged_1  = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[1] : "0");
	$hedged_amount_1   = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[3] : "0");
	$openamountfc_1 = ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : "0");
	$hedgedperctage_1 = ($value["Q1"] != "" ? (explode(",", $value["Q1"])[3] / explode(",", $value["Q1"])[0])*100  : "0");
	$gainloss_1 = $target_value_1-(($hedged_amount_1*$hedgedpositionrate)+($mtmrate_1*$openamountfc_1));

	
	// second value
	$hedgedpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[3] : 0;
	$hedgedpositionrate = $value["Q2"] != "" ? explode(",", $value["Q2"])[1] : 0;
	$openpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : 0;
	$mtmrate_2 = $value["Q2"] != "" ? $importspotrate : 1;
	$potential_portfolio_2 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_2);
	$exports_2 = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[0] : "0");
	$target_value_2 = ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] * explode(",", $value["Q2"])[2] : "0");
	$target_rate_2 = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[2] : "0");
	$hedged_2  = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[1] : "0");
	$hedged_amount_2   = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[3] : "0");
	$openamountfc_2 = ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : "0");
	$hedgedperctage_2 = ($value["Q2"] != "" ? (explode(",", $value["Q2"])[3] / explode(",", $value["Q2"])[0])*100  : "0");
	$gainloss_2 = $target_value_2-(($hedged_amount_2*$hedgedpositionrate)+($mtmrate_2*$openamountfc_2));	

	// third value
	$hedgedpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[3] : 0;
	$hedgedpositionrate = $value["Q3"] != "" ? explode(",", $value["Q3"])[1] : 0;
	$openpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : 0;
	$mtmrate_3 = $value["Q3"] != "" ? $importspotrate : 1;
	$potential_portfolio_3 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_3);
	$exports_3 = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[0] : "0");
	$target_value_3 = ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] * explode(",", $value["Q3"])[2] : "0");
	$target_rate_3 = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[2] : "0");
	$hedged_3  = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[1] : "0");
	$hedged_amount_3   = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[3] : "0");
	$openamountfc_3 = ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : "0");
	$hedgedperctage_3 = ($value["Q3"] != "" ? (explode(",", $value["Q3"])[3] / explode(",", $value["Q3"])[0])*100  : "0");
	$gainloss_3 = $target_value_3-(($hedged_amount_3*$hedgedpositionrate)+($mtmrate_3*$openamountfc_3));	

	
	// fourth value
	$hedgedpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[3] : 0;
	$hedgedpositionrate = $value["Q4"] != "" ? explode(",", $value["Q4"])[1] : 0;
	$openpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : 0;
	$mtmrate_4 = $value["Q4"] != "" ? $importspotrate : 1;
	$potential_portfolio_4 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_4);
	$exports_4 = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[0] : "0");
	$target_value_4 = ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] * explode(",", $value["Q4"])[2] : "0");
	$target_rate_4 = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[2] : "0");
	$hedged_4  = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[1] : "0");
	$hedged_amount_4   = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[3] : "0");
	$openamountfc_4 = ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : "0");
	$hedgedperctage_4 = ($value["Q4"] != "" ? (explode(",", $value["Q4"])[3] / explode(",", $value["Q4"])[0])*100  : "0");
	$gainloss_4 = $target_value_4-(($hedged_amount_4*$hedgedpositionrate)+($mtmrate_4*$openamountfc_4));	


	$exports_sum += $exports_1 + $exports_2 + $exports_3 + $exports_4;
	$targetvalue_sum += $target_value_1 + $target_value_2 + $target_value_3 + $target_value_4;
	$targetrate_sum += $target_rate_1 + $target_rate_2 + $target_rate_3 + $target_rate_4;
	$hedged_sum += $hedged_1 + $hedged_2 + $hedged_3 + $hedged_4;
	$hedged_amount_sum += $hedged_amount_1 + $hedged_amount_2 + $hedged_amount_3 + $hedged_amount_4;
	$openamountfc_sum += $openamountfc_1 + $openamountfc_2 + $openamountfc_3 + $openamountfc_4;
	$gainloss_sum += $gainloss_1 + $gainloss_2 + $gainloss_3 + $gainloss_4;
	$hedgedperctage_sum += $hedgedperctage_1 + $hedgedperctage_2 + $hedgedperctage_3 + $hedgedperctage_4;
	$potential_portfolio_sum += $potential_portfolio_1 + $potential_portfolio_2 + $potential_portfolio_3 + $potential_portfolio_4;


	
	
	echo "<tr>";	
	echo "<td>Buyers Credit</td><td>" . $exports_sum  . "</td>
	<td>" . $targetvalue_sum  . "</td><td>"
	. number_format($targetrate_sum, 4)  . "</td><td>"
	. $hedged_amount_sum  . "</td><td>"
	. number_format($hedgedperctage_sum, 4)  . "</td><td>"
	. number_format($hedged_sum, 4)  . "</td><td>"
	. $openamountfc_sum  . "</td><td>"
	. $importspotrate . "</td><td>"
	. $gainloss_sum  . "</td><td>"
	. $potential_portfolio_sum  . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[3] : 0;
	$hedgedpositionrate = $value["Q1"] != "" ? explode(",", $value["Q1"])[1] : 0;
	$openpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : 0;
	$mtmrate_1 = $value["Q1"] != "" ? $importspotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_1);

	echo "<tr>";	
	echo "<td>Q1</td><td>" . ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] : "-")  . "</td>
	<td>" . ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] * explode(",", $value["Q1"])[2] : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? number_format(explode(",", $value["Q1"])[2], 4) : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? explode(",", $value["Q1"])[3] : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? number_format((explode(",", $value["Q1"])[3] / explode(",", $value["Q1"])[0])*100, 4) : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? number_format(explode(",", $value["Q1"])[1], 4): "-")  . "</td><td>"
	. ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? $importspotrate : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? $gainloss_1 : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[3] : 0;
	$hedgedpositionrate = $value["Q2"] != "" ? explode(",", $value["Q2"])[1] : 0;
	$openpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : 0;
	$mtmrate_2 = $value["Q2"] != "" ? $importspotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_2);

	echo "<tr>";
	echo "<td>Q2</td><td>" . ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] : "-")  . "</td>
	<td>" . ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] * explode(",", $value["Q2"])[2] : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? number_format(explode(",", $value["Q2"])[2], 4) : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? explode(",", $value["Q2"])[3] : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? number_format((explode(",", $value["Q2"])[3] / explode(",", $value["Q2"])[0])*100, 4) : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? number_format(explode(",", $value["Q2"])[1], 4) : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? $importspotrate : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? $gainloss_2  : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[3] : 0;
	$hedgedpositionrate = $value["Q3"] != "" ? explode(",", $value["Q3"])[1] : 0;
	$openpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : 0;
	$mtmrate_3 = $value["Q3"] != "" ? $importspotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_3);

	echo "<tr>";	
	echo "<td>Q3</td><td>" . ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] : "-")  . "</td>
	<td>" . ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] * explode(",", $value["Q3"])[2] : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? number_format(explode(",", $value["Q3"])[2], 4) : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? explode(",", $value["Q3"])[3] : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? number_format((explode(",", $value["Q3"])[3] / explode(",", $value["Q3"])[0])*100, 4) : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? number_format(explode(",", $value["Q3"])[1], 4) : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? $importspotrate : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? $gainloss_3 : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[3] : 0;
	$hedgedpositionrate = $value["Q4"] != "" ? explode(",", $value["Q4"])[1] : 0;
	$openpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : 0;
	$mtmrate_4 = $value["Q4"] != "" ? $importspotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_4);

	echo "<tr>";	
	echo "<td>Q4</td><td>" . ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] : "-")  . "</td>
	<td>" . ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] * explode(",", $value["Q4"])[2] : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? number_format(explode(",", $value["Q4"])[2], 4) : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? explode(",", $value["Q4"])[3] : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? number_format((explode(",", $value["Q4"])[3] / explode(",", $value["Q4"])[0])*100, 4) : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? number_format(explode(",", $value["Q4"])[1], 4): "-")  . "</td><td>"
	. ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? $importspotrate : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? $gainloss_4 : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";
	}if (count($helicoptertabsbuyersCredit) <= 0) :
				echo "<tr>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
			echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
			echo "</tr>";
		endif;
	echo "</tbody></table></div>";
	?>

</div>

</div>

<!-- Capital Payments -->


<div class="accordion">
<div class="accordion-header " data-toggle="collapse" data-target="#panel-body-4">
<h4>CAPITAL PAYMENTS</h4>
</div>
<div class="accordion-body collapse border border-top-0 text-sm" id="panel-body-4" data-parent="#accordion">
	<?php 				
	echo "<div class='table-responsive'><table id='example4' class='table table-striped table-bordered w-100 text-nowrap'>";
	echo "<thead><tr>";
	echo "<th>-</th>";
	echo "<th>Exposure</th>";
	echo "<th>Target Value</th>";
	echo "<th>Target Rate</th>";
	echo "<th>Hedged Amount</th>";
	echo "<th>Hedged  %</th>";
	echo "<th>Hedged Rate</th>";
	echo "<th>Open Amount FC</th>";
	echo "<th>MTM Rate</th>";
	echo "<th>Open Details - Gain / Loss</th>";
	echo "<th>Potential Gain / Loss on the Portfolio</th>";
	echo "</tr></thead><tbody>";
	foreach($helicoptertabscapitalpaymnts as $row => $value) {
	$exports_sum = 0;
	$targetvalue_sum = 0;
	$targetrate_sum = 0;
	$hedged_sum = 0;
	$hedged_amount_sum = 0;
	$openamountfc_sum = 0;
	$gainloss_sum = 0;
	$hedgedperctage_sum = 0;
	$mtm_sum = 0;
	$potentialgainloss_sum = 0;
	$potential_portfolio_sum = 0;

	
	// first value
	$hedgedpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[3] : 0;
	$hedgedpositionrate = $value["Q1"] != "" ? explode(",", $value["Q1"])[1] : 0;
	$openpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : 0;
	$mtmrate_1 = $value["Q1"] != "" ? $importspotrate : 1;
	$potential_portfolio_1 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_1);
	$exports_1 = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[0] : "0");
	$target_value_1 = ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] * explode(",", $value["Q1"])[2] : "0");
	$target_rate_1 = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[2] : "0");
	$hedged_1  = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[1] : "0");
	$hedged_amount_1   = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[3] : "0");
	$openamountfc_1 = ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : "0");
	$hedgedperctage_1 = ($value["Q1"] != "" ? (explode(",", $value["Q1"])[3] / explode(",", $value["Q1"])[0])*100  : "0");
	$gainloss_1 = $target_value_1-(($hedged_amount_1*$hedgedpositionrate)+($mtmrate_1*$openamountfc_1));

	
	// second value
	$hedgedpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[3] : 0;
	$hedgedpositionrate = $value["Q2"] != "" ? explode(",", $value["Q2"])[1] : 0;
	$openpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : 0;
	$mtmrate_2 = $value["Q2"] != "" ? $importspotrate : 1;
	$potential_portfolio_2 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_2);
	$exports_2 = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[0] : "0");
	$target_value_2 = ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] * explode(",", $value["Q2"])[2] : "0");
	$target_rate_2 = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[2] : "0");
	$hedged_2  = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[1] : "0");
	$hedged_amount_2   = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[3] : "0");
	$openamountfc_2 = ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : "0");
	$hedgedperctage_2 = ($value["Q2"] != "" ? (explode(",", $value["Q2"])[3] / explode(",", $value["Q2"])[0])*100  : "0");
	$gainloss_2 = $target_value_2-(($hedged_amount_2*$hedgedpositionrate)+($mtmrate_2*$openamountfc_2));

	
	// third value
	$hedgedpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[3] : 0;
	$hedgedpositionrate = $value["Q3"] != "" ? explode(",", $value["Q3"])[1] : 0;
	$openpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : 0;
	$mtmrate_3 = $value["Q3"] != "" ? $importspotrate : 1;
	$potential_portfolio_3 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_3);
	$exports_3 = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[0] : "0");
	$target_value_3 = ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] * explode(",", $value["Q3"])[2] : "0");
	$target_rate_3 = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[2] : "0");
	$hedged_3  = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[1] : "0");
	$hedged_amount_3   = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[3] : "0");
	$openamountfc_3 = ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : "0");
	$hedgedperctage_3 = ($value["Q3"] != "" ? (explode(",", $value["Q3"])[3] / explode(",", $value["Q3"])[0])*100  : "0");
	$gainloss_3 = $target_value_3-(($hedged_amount_3*$hedgedpositionrate)+($mtmrate_3*$openamountfc_3));

	
	// fourth value
	$hedgedpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[3] : 0;
	$hedgedpositionrate = $value["Q4"] != "" ? explode(",", $value["Q4"])[1] : 0;
	$openpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : 0;
	$mtmrate_4 = $value["Q4"] != "" ? $importspotrate : 1;
	$potential_portfolio_4 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_4);
	$exports_4 = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[0] : "0");
	$target_value_4 = ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] * explode(",", $value["Q4"])[2] : "0");
	$target_rate_4 = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[2] : "0");
	$hedged_4  = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[1] : "0");
	$hedged_amount_4   = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[3] : "0");
	$openamountfc_4 = ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : "0");
	$hedgedperctage_4 = ($value["Q4"] != "" ? (explode(",", $value["Q4"])[3] / explode(",", $value["Q4"])[0])*100  : "0");
	$gainloss_4 = $target_value_4-(($hedged_amount_4*$hedgedpositionrate)+($mtmrate_4*$openamountfc_4));


	$exports_sum += $exports_1 + $exports_2 + $exports_3 + $exports_4;
	$targetvalue_sum += $target_value_1 + $target_value_2 + $target_value_3 + $target_value_4;
	$targetrate_sum += $target_rate_1 + $target_rate_2 + $target_rate_3 + $target_rate_4;
	$hedged_sum += $hedged_1 + $hedged_2 + $hedged_3 + $hedged_4;
	$hedged_amount_sum += $hedged_amount_1 + $hedged_amount_2 + $hedged_amount_3 + $hedged_amount_4;
	$openamountfc_sum += $openamountfc_1 + $openamountfc_2 + $openamountfc_3 + $openamountfc_4;
	$gainloss_sum += $gainloss_1 + $gainloss_2 + $gainloss_3 + $gainloss_4;
	$hedgedperctage_sum += $hedgedperctage_1 + $hedgedperctage_2 + $hedgedperctage_3 + $hedgedperctage_4;
	$potential_portfolio_sum += $potential_portfolio_1 + $potential_portfolio_2 + $potential_portfolio_3 + $potential_portfolio_4;


	
	
	echo "<tr>";	
	echo "<td>Capital Payments</td><td>" . $exports_sum  . "</td>
	<td>" . $targetvalue_sum  . "</td><td>"
	. number_format($targetrate_sum, 4) . "</td><td>"
	. $hedged_amount_sum  . "</td><td>"
	. number_format($hedgedperctage_sum, 4)  . "</td><td>"
	. number_format($hedged_sum, 4) . "</td><td>"
	. $openamountfc_sum  . "</td><td>"
	. $importspotrate . "</td><td>"
	. $gainloss_sum  . "</td><td>"
	. $potential_portfolio_sum  . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[3] : 0;
	$hedgedpositionrate = $value["Q1"] != "" ? explode(",", $value["Q1"])[1] : 0;
	$openpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : 0;
	$mtmrate_1 = $value["Q1"] != "" ? $importspotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_1);

	echo "<tr>";	
	echo "<td>Q1</td><td>" . ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] : "-")  . "</td>
	<td>" . ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] * explode(",", $value["Q1"])[2] : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? number_format(explode(",", $value["Q1"])[2], 4) : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? explode(",", $value["Q1"])[3] : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? number_format((explode(",", $value["Q1"])[3] / explode(",", $value["Q1"])[0])*100, 4) : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? number_format(explode(",", $value["Q1"])[1], 4) : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? $importspotrate : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? $gainloss_1 : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[3] : 0;
	$hedgedpositionrate = $value["Q2"] != "" ? explode(",", $value["Q2"])[1] : 0;
	$openpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : 0;
	$mtmrate_2 = $value["Q2"] != "" ? $importspotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_2);

	echo "<tr>";
	echo "<td>Q2</td><td>" . ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] : "-")  . "</td>
	<td>" . ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] * explode(",", $value["Q2"])[2] : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? number_format(explode(",", $value["Q2"])[2], 4) : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? explode(",", $value["Q2"])[3] : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? number_format((explode(",", $value["Q2"])[3] / explode(",", $value["Q2"])[0])*100, 4) : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? number_format(explode(",", $value["Q2"])[1], 4) : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? $importspotrate : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? $gainloss_2 : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[3] : 0;
	$hedgedpositionrate = $value["Q3"] != "" ? explode(",", $value["Q3"])[1] : 0;
	$openpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : 0;
	$mtmrate_3 = $value["Q3"] != "" ? $importspotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_3);

	echo "<tr>";	
	echo "<td>Q3</td><td>" . ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] : "-")  . "</td>
	<td>" . ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] * explode(",", $value["Q3"])[2] : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? number_format(explode(",", $value["Q3"])[2], 4) : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? explode(",", $value["Q3"])[3] : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? number_format((explode(",", $value["Q3"])[3] / explode(",", $value["Q3"])[0])*100, 4) : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? number_format(explode(",", $value["Q3"])[1], 4): "-")  . "</td><td>"
	. ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? $importspotrate : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? $gainloss_3 : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[3] : 0;
	$hedgedpositionrate = $value["Q4"] != "" ? explode(",", $value["Q4"])[1] : 0;
	$openpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : 0;
	$mtmrate_4 = $value["Q4"] != "" ? $importspotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_4);

	echo "<tr>";	
	echo "<td>Q4</td><td>" . ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] : "-")  . "</td>
	<td>" . ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] * explode(",", $value["Q4"])[2] : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? number_format(explode(",", $value["Q4"])[2], 4) : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? explode(",", $value["Q4"])[3] : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? number_format((explode(",", $value["Q4"])[3] / explode(",", $value["Q4"])[0])*100, 4): "-")  . "</td><td>"
	. ($value["Q4"] != "" ? number_format(explode(",", $value["Q4"])[1], 4) : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? $importspotrate : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? $gainloss_4  : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";
	}if (count($helicoptertabscapitalpaymnts) <= 0) :
			echo "<tr>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
			echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
			echo "</tr>";
		endif;
	echo "</tbody></table></div>";
	?>

</div>

</div>


<!-- Misc. -->


<div class="accordion">
<div class="accordion-header " data-toggle="collapse" data-target="#panel-body-5">
<h4>MISC</h4>
</div>
<div class="accordion-body collapse border border-top-0 text-sm" id="panel-body-5" data-parent="#accordion">
	<?php 				
	echo "<div class='table-responsive'><table id='example5' class='table table-striped table-bordered w-100 text-nowrap'>";
	echo "<thead><tr>";
	echo "<th>-</th>";
	echo "<th>Exposure</th>";
	echo "<th>Target Value</th>";
	echo "<th>Target Rate</th>";
	echo "<th>Hedged Amount</th>";
	echo "<th>Hedged  %</th>";
	echo "<th>Hedged Rate</th>";
	echo "<th>Open Amount FC</th>";
	echo "<th>MTM Rate</th>";
	echo "<th>Open Details - Gain / Loss</th>";
	echo "<th>Potential Gain / Loss on the Portfolio</th>";
	echo "</tr></thead><tbody>";
	foreach($helicoptertabsbuyersmisc as $row => $value) {
	$exports_sum = 0;
	$targetvalue_sum = 0;
	$targetrate_sum = 0;
	$hedged_sum = 0;
	$hedged_amount_sum = 0;
	$openamountfc_sum = 0;
	$gainloss_sum = 0;
	$hedgedperctage_sum = 0;
	$mtm_sum = 0;
	$potentialgainloss_sum = 0;
	$potential_portfolio_sum = 0;

	
	// first value
	$hedgedpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[3] : 0;
	$hedgedpositionrate = $value["Q1"] != "" ? explode(",", $value["Q1"])[1] : 0;
	$openpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : 0;
	$mtmrate_1 = $value["Q1"] != "" ? $importspotrate : 1;
	$potential_portfolio_1 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_1);
	$exports_1 = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[0] : "0");
	$target_value_1 = ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] * explode(",", $value["Q1"])[2] : "0");
	$target_rate_1 = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[2] : "0");
	$hedged_1  = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[1] : "0");
	$hedged_amount_1   = ($value["Q1"] != "" ?  explode(",", $value["Q1"])[3] : "0");
	$openamountfc_1 = ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : "0");
	$hedgedperctage_1 = ($value["Q1"] != "" ? (explode(",", $value["Q1"])[3] / explode(",", $value["Q1"])[0])*100  : "0");
	$gainloss_1 = $target_value_1-(($hedged_amount_1*$hedgedpositionrate)+($mtmrate_1*$openamountfc_1));


	
	// second value
	$hedgedpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[3] : 0;
	$hedgedpositionrate = $value["Q2"] != "" ? explode(",", $value["Q2"])[1] : 0;
	$openpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : 0;
	$mtmrate_2 = $value["Q2"] != "" ? $importspotrate : 1;
	$potential_portfolio_2 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_2);
	$exports_2 = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[0] : "0");
	$target_value_2 = ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] * explode(",", $value["Q2"])[2] : "0");
	$target_rate_2 = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[2] : "0");
	$hedged_2  = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[1] : "0");
	$hedged_amount_2   = ($value["Q2"] != "" ?  explode(",", $value["Q2"])[3] : "0");
	$openamountfc_2 = ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : "0");
	$hedgedperctage_2 = ($value["Q2"] != "" ? (explode(",", $value["Q2"])[3] / explode(",", $value["Q2"])[0])*100  : "0");
	$gainloss_2 = $target_value_2-(($hedged_amount_2*$hedgedpositionrate)+($mtmrate_2*$openamountfc_2));
	
	// third value
	$hedgedpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[3] : 0;
	$hedgedpositionrate = $value["Q3"] != "" ? explode(",", $value["Q3"])[1] : 0;
	$openpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : 0;
	$mtmrate_3 = $value["Q3"] != "" ? $importspotrate : 1;
	$potential_portfolio_3 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_3);
	$exports_3 = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[0] : "0");
	$target_value_3 = ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] * explode(",", $value["Q3"])[2] : "0");
	$target_rate_3 = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[2] : "0");
	$hedged_3  = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[1] : "0");
	$hedged_amount_3   = ($value["Q3"] != "" ?  explode(",", $value["Q3"])[3] : "0");
	$openamountfc_3 = ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : "0");
	$hedgedperctage_3 = ($value["Q3"] != "" ? (explode(",", $value["Q3"])[3] / explode(",", $value["Q3"])[0])*100  : "0");
	$gainloss_3 = $target_value_3-(($hedged_amount_3*$hedgedpositionrate)+($mtmrate_3*$openamountfc_3));
	
	// fourth value
	$hedgedpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[3] : 0;
	$hedgedpositionrate = $value["Q4"] != "" ? explode(",", $value["Q4"])[1] : 0;
	$openpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : 0;
	$mtmrate_4 = $value["Q4"] != "" ? $importspotrate : 1;
	$potential_portfolio_4 = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_4);
	$exports_4 = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[0] : "0");
	$target_value_4 = ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] * explode(",", $value["Q4"])[2] : "0");
	$target_rate_4 = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[2] : "0");
	$hedged_4  = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[1] : "0");
	$hedged_amount_4   = ($value["Q4"] != "" ?  explode(",", $value["Q4"])[3] : "0");
	$openamountfc_4 = ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : "0");
	$hedgedperctage_4 = ($value["Q4"] != "" ? (explode(",", $value["Q4"])[3] / explode(",", $value["Q4"])[0])*100  : "0");
	$gainloss_4 = $target_value_4-(($hedged_amount_4*$hedgedpositionrate)+($mtmrate_4*$openamountfc_4));

	$exports_sum += $exports_1 + $exports_2 + $exports_3 + $exports_4;
	$targetvalue_sum += $target_value_1 + $target_value_2 + $target_value_3 + $target_value_4;
	$targetrate_sum += $target_rate_1 + $target_rate_2 + $target_rate_3 + $target_rate_4;
	$hedged_sum += $hedged_1 + $hedged_2 + $hedged_3 + $hedged_4;
	$hedged_amount_sum += $hedged_amount_1 + $hedged_amount_2 + $hedged_amount_3 + $hedged_amount_4;
	$openamountfc_sum += $openamountfc_1 + $openamountfc_2 + $openamountfc_3 + $openamountfc_4;
	$gainloss_sum += $gainloss_1 + $gainloss_2 + $gainloss_3 + $gainloss_4;
	$hedgedperctage_sum += $hedgedperctage_1 + $hedgedperctage_2 + $hedgedperctage_3 + $hedgedperctage_4;
	$potential_portfolio_sum += $potential_portfolio_1 + $potential_portfolio_2 + $potential_portfolio_3 + $potential_portfolio_4;
	

	
	
	echo "<tr>";	
	echo "<td>Misc</td><td>" . $exports_sum  . "</td>
	<td>" . $targetvalue_sum  . "</td><td>"
	. number_format($targetrate_sum, 4)  . "</td><td>"
	. $hedged_amount_sum  . "</td><td>"
	. number_format($hedgedperctage_sum, 4)  . "</td><td>"
	. number_format($hedged_sum, 4) . "</td><td>"
	. $openamountfc_sum  . "</td><td>"
	. $importspotrate  . "</td><td>"
	. $gainloss_sum  . "</td><td>"
	. $potential_portfolio_sum  . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[3] : 0;
	$hedgedpositionrate = $value["Q1"] != "" ? explode(",", $value["Q1"])[1] : 0;
	$openpositionamount = $value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : 0;
	$mtmrate_1 = $value["Q1"] != "" ? $importspotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_1);

	echo "<tr>";	
	echo "<td>Q1</td><td>" . ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] : "-")  . "</td>
	<td>" . ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] * explode(",", $value["Q1"])[2] : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? number_format(explode(",", $value["Q1"])[2], 4) : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? explode(",", $value["Q1"])[3] : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? number_format((explode(",", $value["Q1"])[3] / explode(",", $value["Q1"])[0])*100, 4) : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? number_format(explode(",", $value["Q1"])[1], 4) : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? explode(",", $value["Q1"])[0] - explode(",", $value["Q1"])[3] : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? $importspotrate : "-")  . "</td><td>"
	. ($value["Q1"] != "" ? $gainloss_1 : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[3] : 0;
	$hedgedpositionrate = $value["Q2"] != "" ? explode(",", $value["Q2"])[1] : 0;
	$openpositionamount = $value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : 0;
	$mtmrate_2 = $value["Q2"] != "" ? $importspotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_2);

	echo "<tr>";
	echo "<td>Q2</td><td>" . ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] : "-")  . "</td>
	<td>" . ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] * explode(",", $value["Q2"])[2] : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? number_format(explode(",", $value["Q2"])[2], 4) : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? explode(",", $value["Q2"])[3] : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? number_format((explode(",", $value["Q2"])[3] / explode(",", $value["Q2"])[0])*100, 4) : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? number_format(explode(",", $value["Q2"])[1], 4) : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? explode(",", $value["Q2"])[0] - explode(",", $value["Q2"])[3] : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? $importspotrate : "-")  . "</td><td>"
	. ($value["Q2"] != "" ? $gainloss_2 : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[3] : 0;
	$hedgedpositionrate = $value["Q3"] != "" ? explode(",", $value["Q3"])[1] : 0;
	$openpositionamount = $value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : 0;
	$mtmrate_3 = $value["Q3"] != "" ? $importspotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_3);

	echo "<tr>";	
	echo "<td>Q3</td><td>" . ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] : "-")  . "</td>
	<td>" . ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] * explode(",", $value["Q3"])[2] : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? number_format(explode(",", $value["Q3"])[2], 4) : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? explode(",", $value["Q3"])[3] : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? number_format((explode(",", $value["Q3"])[3] / explode(",", $value["Q3"])[0])*100, 4) : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? number_format(explode(",", $value["Q3"])[1], 4): "-")  . "</td><td>"
	. ($value["Q3"] != "" ? explode(",", $value["Q3"])[0] - explode(",", $value["Q3"])[3] : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? $importspotrate : "-")  . "</td><td>"
	. ($value["Q3"] != "" ? $gainloss_3  : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";

	$hedgedpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[3] : 0;
	$hedgedpositionrate = $value["Q4"] != "" ? explode(",", $value["Q4"])[1] : 0;
	$openpositionamount = $value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : 0;
	$mtmrate_4 = $value["Q4"] != "" ? $importspotrate : 1;
	$potential_portfolio = ($hedgedpositionamount * $hedgedpositionrate) + ($openpositionamount * $mtmrate_4);

	echo "<tr>";	
	echo "<td>Q4</td><td>" . ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] : "-")  . "</td>
	<td>" . ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] * explode(",", $value["Q4"])[2] : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? number_format(explode(",", $value["Q4"])[2], 4) : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? explode(",", $value["Q4"])[3] : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? number_format((explode(",", $value["Q4"])[3] / explode(",", $value["Q4"])[0])*100, 4) : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? number_format(explode(",", $value["Q4"])[1], 4) : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? explode(",", $value["Q4"])[0] - explode(",", $value["Q4"])[3] : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? $importspotrate : "-")  . "</td><td>"
	. ($value["Q4"] != "" ? $gainloss_4  : "-")  . "</td><td>"
	.  $potential_portfolio . "</td>";
	echo "</tr>";
	}if (count($helicoptertabsbuyersmisc) <= 0) :
				echo "<tr>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
			echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
            echo "<td>No result found</td>";
			echo "</tr>";
		endif;
	echo "</tbody></table></div>";
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
var selectEl = $('select[name="currencyieshelicopterview"]');
var currentVal = selectEl.val(); // initialize current value

selectEl.on('change', function() {
var selectedVal = $(this).val();
if(selectedVal && selectedVal !== currentVal) { // check if value has changed
currentVal = selectedVal; // update current value
window.location.href = 'helicopterview?currencyieshelicopterview=' + selectedVal; // update URL
}
});
});

</script>
