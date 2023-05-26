

                    <!-- Page content -->
                    <div class="container-fluid pt-8">
                       
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card shadow">
                                    <div class="card-header">
                                        <h2 class="mb-0">Edit User</h2>
                                        <!-- <?php echo $query[0]->first_name ?> -->
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsives">
                                        <?= $this->include('message/message') ?>
                                        <form action="<?= base_url('editnewuser') ?>" method="POST" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                    <input type="hidden" name="user_id_hidd" value="<?php echo (!empty($query[0]->user_id)) ? $query[0]->user_id : "" ?>"/>
                                                        <div class="form-group">
                                                         
                                                            <label class="form-label">Enter Name</label>
                                                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Enter  Name" value="<?= !empty($query[0]->first_name) ? $query[0]->first_name : '' ?>" required>
                                                        </div>
                                               
                                                
                                                        <div class="form-group">
                                                        <input type="hidden" name="role_id" id="role_id" class="form-control" value="4" />
                                                            <label class="form-label">Enter Password</label>
                                                            <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" value="">
                                                        </div>

                                                           <div class="form-group">
                                                            <label for="role">Role</label>
                                                            <select name="role_id" id="role_id" class="form-control" data-validation="required" required>
                                                                <option value="">-- User Type --</option>
                                                                <?php foreach ($roles as $row) : ?>
                                                                    <option value="<?php echo $row['role_id'] ?>" <?php echo (!empty($query[0]->role_id) && $query[0]->role_id == $row['role_id']) ? 'selected' : '' ?>><?php echo $row['role_name'] ?></option>
                                                                <?php endforeach; ?>
                                                            </select>

                                                        </div>
                                             
                                                    </div>

                                                    <div class="col-md-6">
                                                    
                                                        <div class="form-group">
                                                            <label class="form-label">Enter Email Address</label>
                                                            <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email Address" value="<?= !empty($query[0]->email) ? $query[0]->email : '' ?>" required>
                                                        </div>

                                                        <div class="form-group">
                                        <label class="form-label">Enter User Name - PAN No</label>
                                        <input type="text" class="form-control" name="user_name" id="user_name"
                                            placeholder="Enter User Name - PAN No" value="<?= !empty($query[0]->user_name) ? $query[0]->user_name : '' ?>" required>
                                    </div>
                                                    </div>
                                                    <div class="col-md-12" style="text-align: center;">
                                                        <div class="d-grid gap-1">
                                                            <button class="btn rounded-0 btn-primary bg-gradient">Save</button>
                                                        </div>
                                                    </div>

                                                </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                   
                    </div>
 