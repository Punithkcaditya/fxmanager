
<style>
	#example_wrapper {width: fit-content;}
</style>

<!-- Page content -->
<div class="container-fluid pt-8">
<!-- bootom top bar -->

<!-- bottom top bar -->
<?php $subheadings = array("Exposure <br/>Ref. No", "Currency", "Exposure<br/>Identification <br/>Date (EID)", "Spot Rate <br/>as on EID", "Date of <br/>Invoice", "Counter <br/>Party", "Counter Party<br/> Country", "Exposure <br/>Type", "Amount<br/> in FC", "Due Date", "Forward <br/>Rate (Day 1)",  "Target <br/>Rate",
    "Target <br/>Value (INR)",
    "Risk <br/>Limit",
    "Open <br/>Amount (FC)",
    "Current <br/>Forward Rate",
    "Open <br/> Amount (INR)",
    "Gain / Loss",
    "Risk Limit",
    "Excess over <br/> the Limit(SL, TP)",
    "Hedge <br/> Amount (FC)",
    "Hedge <br/> Rate",
    "Hedge <br/> Amount (INR)",
    "Portfolio <br/> Value (INR)",
    "Portfolio <br/> Rate",
    "Gain/Loss",
    "Risk <br/> Limit",
    "Excess over the <br/> Limit (SL, TP)",
    "Settlement <br/> Amount (INR)",
    "Settlement <br/> (Rate)",
    "Gain/Loss"); 
	
	$mainheadings = array(
		array("colspan" => "14", "content" => ""),
		array("colspan" => "6", "content" => "Open Details"),
		array("colspan" => "3", "content" => "Cover Details"),
		array("colspan" => "5", "content" => "Portfolio Details"),
		array("colspan" => "3", "content" => "Settlement Details"),
	);
	
	?>
<div class="row">
	<div class="col-md-12">
		<div class="card shadow">
			<div class="card-header">
				<h2 class="mb-0">MTM - Operating Risk</h2>
			</div>
			<div class="card-body">
				<div class="table-responsive">

				<?= $this->include('message/message') ?>  

					<table id="example" class="table table-striped table-bordered w-100 text-nowrap">
						<thead>
							<tr>
							<?php foreach ($mainheadings as  $heading) {
							echo "<td colspan='" . $heading['colspan'] . "' class='text-center'><h3>" . $heading['content'] . "</h3></td>";
							} ?>
							</tr>
							<tr>
							<?php foreach ($subheadings as $heading) {
								echo "<th class='wd-15p'>".$heading."</th>";
							 } ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($transaction as $row) : 
								$dueDate = $row->dueDate;
								$resoval = $controller->forrwardCalculator($row->exposureType, $row->currency, $dueDate);
								$crntfrrate = json_decode($resoval);
								$exp_ref = $row->exposurereInfo;
								$curr = $row->Currency;
								$exp_idt = $row->exposureidentificationdate;
								$spot_rate = $row->spot_rate;
								$dateofInvoice = $row->dateofInvoice;
								$counterPartyName = $row->counterPartyName;
								$counterPartycountry = $row->counterPartycountry;
								$exposure_type = $row->exposure_type;
								$amountinFC = $row->amountinFC;
								$forward_rate = $row->forward_rate;
								$targetRate = $row->targetRate;
								$inr_target_value = ($row->inr_target_value > 0.00) ? $row->inr_target_value : 1;
								$targetValueInr = ($targetRate*$inr_target_value)*$amountinFC;
								$Avgrate =  (float)number_format($row->Avgrate, 4);
								$ToatalforwardAmount = $row->ToatalforwardAmount;
								$Toatalallpayment = $row->Toatalallpayment;
								$AvgspotamountRate = $row->AvgspotamountRate;
								$openAmountFC = $row->isSettled ?  $row->open_amount  : $amountinFC-$ToatalforwardAmount;
								$currentForwardRate = isset($crntfrrate->result->forward_rate) ?  $crntfrrate->result->forward_rate : 1;
								$currentSpotdRate = isset($crntfrrate->result->spot_rate) ?  $crntfrrate->result->spot_rate : 1;
								$currencyinrSpotdRate = ($row->inr_target_value > 0.00) ? $currentSpotdRate : 1;								
								$openAmountINR =  $openAmountFC*($currentForwardRate * $currencyinrSpotdRate);
								$portfoliovalue = $row->isSettled ? $AvgspotamountRate + $Toatalallpayment : $openAmountINR + ($ToatalforwardAmount*$Avgrate);
								$portfoliorate = $portfoliovalue / $amountinFC;
								$ganorloseopendetails = $openAmountINR -($openAmountFC*$targetRate);
								$ganorlose = $portfoliovalue - $targetValueInr;
								$settlementAmount = $Toatalallpayment + $AvgspotamountRate;
								$timestamp = strtotime($exp_idt);
								$newFormat = date("d-m-Y h:i A", $timestamp);
								$timedateofInvoice = strtotime($dateofInvoice);
								$newDateFormat = date("d-m-Y", $timedateofInvoice);
								$timedateofdueDate = strtotime($dueDate);
								$newDateFormatdueDate = date("d-m-Y", $timedateofdueDate);
								?>

								<tr>
									<th><?php echo $exp_ref ?></th>
									<td><?php echo $curr ?></td>
								  <td><?php 
								  echo $newFormat ?></td>	
								<td><?php echo $spot_rate ?></td>														  
								<td><?php echo $newDateFormat ?></td>
								<td><?php echo $counterPartyName ?></td>
								<td><?php echo $counterPartycountry ?></td>
								<td><?php echo $exposure_type ?></td>
								<td><?php echo number_format($amountinFC, 2) ?></td>
								<td><?php echo $newDateFormatdueDate ?></td>
								<td><?php echo $forward_rate ?></td>
								<td><?php echo $targetRate ?></td>
								<td><?php echo number_format($targetValueInr, 2) ?></td>
								<td>-</td>
								<td><?php echo number_format($openAmountFC, 2) ?></td>
								<td><?php echo $currentForwardRate ?></td>
								<td><?php echo number_format($openAmountINR, 2)  ?> </td>
								<td><?php echo number_format($ganorloseopendetails, 2)     ?></td>
								<td>-</td>
								<td>-</td>
								<td><?php echo number_format($ToatalforwardAmount, 2) ?></td>
								<td><?php echo number_format( $Avgrate, 2) ?></td>
								<td><?php echo number_format($ToatalforwardAmount * $Avgrate , 2) ?></td>
								<td><?php echo number_format($portfoliovalue, 2) ?></td>
								<td><?php echo number_format($portfoliorate, 4)  ?></td>
								<td><?php echo  number_format($ganorlose, 2) ?></td>
								<td>-</td>
								<td>-</td>
								<td><?php echo number_format($settlementAmount, 2)  ?></td>
								<td><?php echo  number_format($settlementAmount / $amountinFC, 4)   ?></td>
								<td><?php echo number_format($settlementAmount-$targetValueInr, 2)  ?></td>
								</tr>
							<?php endforeach; ?>
							<?php if (count($transaction) <= 0) : ?>
								<tr>
									<td class="p-1 text-center" colspan="120">No result found</td>
								</tr>
							<?php endif ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

	</div>
</div>

</div>
