

					<!-- Page content -->
					<div class="container-fluid pt-8">
                    		<?php echo  $this->include('bottomtopbar/topbar') ?>

						<div class="row">
							<div class="col-md-12">
								<div class="card shadow">
									<div class="card-header">
										<h2 class="mb-0"><?php echo  $title ?></h2>
									</div>
									<div class="card-body">
										<div class="table-responsive">

                                        <?php echo  $this->include('message/message') ?> 

											<table id="example" class="table table-striped table-bordered w-100 text-nowrap">
												<thead>
													<tr>
														<th class="wd-5p">CounterParty No</th>
														<th class="wd-10p">Counter Party Name</th>
														<th class="wd-25p">Actions</th>
													</tr>
												</thead>
												<tbody>
													<?php $i = 1;
													
													?>
													<?php foreach ($counterparty as $key => $sid) : ?>
													
														<tr>
															<th><?php echo  $i++ ?></th>
															<td><?php echo  $sid['counterPartyName']  ?></td>
															<td>

																<a href="<?php echo  base_url($edit_counterparty.'/' . $sid['counterParty_id'] ) ?>" class="mx-2 text-decoration-none text-primary"><i class="fa fa-edit"></i></a>

																<a href="<?php echo  base_url($delete_counterparty.'/' .  $sid['counterParty_id'] ) ?>" class="mx-2 text-decoration-none text-danger" onclick="if(confirm('Are you sure to delete  - <?php echo  $sid['counterPartyName']  ?> from list?') !== true) event.preventDefault()"><i class="fa fa-trash"></i></a>
															</td>
														</tr>
													<?php endforeach; ?>
													<?php if (count($counterparty) <= 0) : ?>
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
