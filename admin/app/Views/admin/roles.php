
					<div class="container-fluid pt-8">
						<div class="page-header mt-0 shadow p-3">
							<ol class="breadcrumb mb-sm-0">
								<li class="breadcrumb-item"><a href="#">Tables</a></li>
								<li class="breadcrumb-item active" aria-current="page">Data Tables</li>
							</ol>
							<div class="btn-group mb-0">
							<a href="<?php echo base_url($link); ?>">
									<button type="button" class="btn btn-primary btn-sm">Add New Roles</button>
								</a>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="card shadow">
									<div class="card-header">
										<h2 class="mb-0">Data Table</h2>
									</div>
									<div class="card-body">
										<div class="table-responsive">
											<table id="example" class="table table-striped table-bordered w-100 text-nowrap">
												<thead>
													<tr>
														<th class="wd-15p">S No</th>
														<th class="wd-15p">Admin Roles Name</th>
														<th class="wd-20p">Modified Date</th>
														<th class="wd-15p">Modified By</th>
														<th class="wd-10p">Status</th>
														<!-- <th class="wd-10p">Created Date</th> -->
														<th class="wd-25p">Actions</th>
													</tr>
												</thead>
												<tbody>

													<?php

														foreach ($roles as $key => $row): ?>
														<tr>
															<th><?=$key?></th>
															<td><?=$row['role_name']?></td>
															<td><?=$row['modified_date'] == "" ? $row['created_date'] : $row['modified_date']?></td>
															<td><?=$row['last_modified_by'] == 0 ? 'Superadmin' : 'Admin'?></td>

															<td>
																<p class="text-muted"><?php echo (!empty($row['status_ind'])) ? '<i class="fa fa-check" aria-hidden="true" style="color:lightgreen;"></i>' : '<i class="fa fa-times" aria-hidden="true" style="color:red;"></i>'; ?></p>
															</td>
															<!-- <td>
                                                <p class="text-muted"><?php echo $row['created_date'] ?></p>
                                            </td> -->
															<td>
															<div class="btn-group mb-0">
									<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
									<div class="dropdown-menu">
									<a class="dropdown-item" href="<?=base_url($user_rolesedit . '/' . $row['role_id'])?>" class="mx-2 text-decoration-none text-primary"><i class="fas fa-edit mr-2"></i>Edit</a>
									<?php
										if (!empty($q)) {
										} else {?>
									<a class="dropdown-item" href="<?=base_url($user_delete . '/' . $row['role_id'])?>" class="mx-2 text-decoration-none text-danger" onclick="if(confirm('Are you sure to delete  - <?=$row['role_name']?> from list?') !== true) event.preventDefault()"><i class="fa fa-trash mr-2"></i>Delete</a>
									<?php }?>
									<a class="dropdown-item" href="<?=base_url('access/' . $row['role_id'])?>" class="mx-2 text-decoration-none text-danger"><i class="fas fa-eye mr-2"></i>Access</a>
									<a class="dropdown-item" href="<?=base_url('permission/' . $row['role_id'])?>" class="mx-2 text-decoration-none text-danger"><i class="fas fa-eye mr-2"></i>Permissions</a>

									</div>
								</div>



															</td>
														</tr>
													<?php endforeach;?>
													<?php if (count($roles) <= 0): ?>
														<tr>
															<td class="p-1 text-center" colspan="4">No result found</td>
														</tr>
													<?php endif?>
												</tbody>
											</table>
										</div>
									</div>
								</div>

							</div>
						</div>

					</div>
