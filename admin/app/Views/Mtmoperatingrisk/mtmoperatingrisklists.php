
<style>
	#example_wrapper {width: fit-content;}
</style>

<!-- Page content -->
<div class="container-fluid pt-8">
<!-- bootom top bar -->

<!-- bottom top bar -->
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
								<th class="wd-15p">Exposure <br/>Ref. No</th>
								<th class="wd-20p">Currency</th>
								<th class="wd-20p">Exposure<br/>Identification <br/>Date (EID)</th>
								<th class="wd-10p">Spot Rate <br/>as on EID</th>
								<th class="wd-25p">Date of <br/>Invoice</th>
								<th class="wd-15p">Counter <br/>Party</th>
								<th class="wd-20p">Counter Party<br/> Country</th>
								<th class="wd-20p">Exposure <br/>Type</th>
								<th class="wd-10p">Amount<br/> in FC</th>
								<th class="wd-25p">Due Date</th>
								<th class="wd-15p">Forward <br/>Rate (Day 1)</th>
								<th class="wd-20p">Target <br/>Rate</th>
								<th class="wd-20p">Target <br/>Value (INR)</th>
								<th class="wd-20p">Risk <br/>Limit</th>
								<th class="wd-10p">Open <br/>Amount (FC)</th>
								<th class="wd-25p">Current <br/> Forward Rate</th>
								<th class="wd-15p">Open <br/>Amount (INR)</th>
								<th class="wd-20p">Gain / Loss</th>
								<th class="wd-20p">Risk Limit</th>
								<th class="wd-10p">Excess over  <br/>the Limit(SL, TP)</th>
								<th class="wd-25p">Hedge<br/> Amount (FC)</th>
								<th class="wd-25p">Hedge<br/> Rate</th>
								<th class="wd-25p">Hedge<br/> Amount (INR)</th>
								<th class="wd-25p">Portfolio <br/>Value (INR)</th>
								<th class="wd-25p">Portfolio <br/>Rate</th>
								<th class="wd-25p">Gain<br/>Loss</th>
								<th class="wd-25p">Risk<br/>Limit</th>
								<th class="wd-25p">Excess over<br/>the Limit (SL, TP)</th>
								<th class="wd-25p">Settlement<br/>(Amt)</th>
								<th class="wd-25p">Settlement<br/>(Rate)</th>
								<th class="wd-25p">Gain<br/>/Loss</th>
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
								$targetValueInr = $targetRate*$amountinFC;
								$Avgrate = $row->Avgrate;
								$ToatalforwardAmount = $row->ToatalforwardAmount;
								$Toatalallpayment = $row->Toatalallpayment;
								$AvgspotamountRate = $row->AvgspotamountRate;
								$openAmountFC = $amountinFC-$ToatalforwardAmount;
								$currentForwardRate = isset($crntfrrate->result->forward_rate) ?  $crntfrrate->result->forward_rate : 1;
								$openAmountINR =  ($openAmountFC*$currentForwardRate);
								$portfoliovalue = $openAmountINR + ($ToatalforwardAmount*$Avgrate);
								$portfoliorate = $portfoliovalue / $amountinFC;
								$ganorloseopendetails = $openAmountINR -($openAmountFC*$targetRate);
								$ganorlose = $portfoliovalue - $targetValueInr;
								?>

								<tr>
									<th><?php echo $exp_ref  ?></th>
									<td><?php echo $curr ?></td>
								  <td><?php echo $exp_idt ?></td>	
								<td><?php echo $spot_rate ?></td>														  
								<td><?php echo $dateofInvoice ?></td>
								<td><?php echo $counterPartyName ?></td>
								<td><?php echo $counterPartycountry ?></td>
								<td><?php echo $exposure_type ?></td>
								<td><?php echo $amountinFC ?></td>
								<td><?php echo $dueDate ?></td>
								<td><?php echo $forward_rate ?></td>
								<td><?php echo $targetRate ?></td>
								<td><?php echo $targetRate*$amountinFC ?></td>
								<td>-</td>
								<td><?php echo $openAmountFC ?></td>
								<td><?php echo $currentForwardRate ?></td>
								<td><?php echo $openAmountINR  ?> </td>
								<td><?php echo number_format($ganorloseopendetails, 4)     ?></td>
								<td>-</td>
								<td>-</td>
								<td><?php echo $ToatalforwardAmount ?></td>
								<td><?php echo  number_format($Avgrate, 4) ?></td>
								<td><?php echo number_format($ToatalforwardAmount*$Avgrate , 2) ?></td>
								<td><?php echo number_format($portfoliovalue, 4) ?></td>
								<td><?php echo number_format($portfoliorate, 4)  ?></td>
								<td><?php echo  $ganorlose ?></td>
								<td>-</td>
								<td>-</td>
								<td><?php echo $Toatalallpayment + $AvgspotamountRate  ?></td>
								<td><?php echo ($Toatalallpayment + $AvgspotamountRate) /$amountinFC  ?></td>
								<td><?php echo ($Toatalallpayment + $AvgspotamountRate)-($targetRate*$amountinFC)  ?></td>
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
