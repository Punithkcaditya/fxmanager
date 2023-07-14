

<!-- Page content -->
<div class="container-fluid pt-8">
<!-- bootom top bar -->
<?= $this->include('bottomtopbar/topbar') ?>
<!-- bottom top bar -->
<div class="row">
	<div class="col-md-12">
		<div class="card shadow">
			<div class="card-header">
				<h2 class="mb-0">Add New Transaction</h2>
			</div>
			<div class="card-body">
				<div class="table-responsive">

				<?= $this->include('message/message') ?>  

					<table id="example" class="table table-striped table-bordered w-100 text-nowrap">
						<thead>
							<tr>
								<th class="wd-15p">Invoice No</th>
								<th class="wd-20p">Amount In FC</th>
								<th class="wd-20p">Counter Party</th>
								<th class="wd-10p">Target Rate</th>
								<th class="wd-25p">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($transaction as $row) : ?>
								<tr>
									<th><?= $row['exposurereInfo'] ?></th>
			
									<td><?= $row['amountinFC'] ?></td>
								  <td><?= $row['counterPartycountry'] ?></td>	
								<td><?= $row['targetRate'] ?></td>														  
									<td>

										<a href="<?= base_url($transaction_edit.'/' . $row['transaction_id']) ?>" class="mx-2 text-decoration-none text-primary"><i class="fa fa-edit"></i></a>

										<a href="<?=  base_url($transaction_delete.'/' . $row['transaction_id']) ?>" class="mx-2 text-decoration-none text-danger" onclick="if(confirm('Are you sure to delete  - <?= $row['exposurereInfo'] ?> from list?') !== true) event.preventDefault()"><i class="fa fa-trash"></i></a>
									</td>
								</tr>
							<?php endforeach; ?>
							<?php if (count($transaction) <= 0) : ?>
								<tr>
									<td class="p-1 text-center" colspan="4">No result found</td>
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
