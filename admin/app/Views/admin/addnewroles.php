

                    <!-- Page content -->
                    <div class="container-fluid pt-8">
                       
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card shadow">
                                    <div class="card-header">
                                        <h2 class="mb-0"><?= $page_title ?></h2>
                                
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsives">
                                        <form  id="user_form" name="user_form" action="<?= base_url('savenewroles') ?>" method="POST" enctype="multipart/form-data">
                                        <?= $this->include('message/message') ?>
                                        <div class="row">
                                                    <div class="col-md-12">
                                                    <input type="hidden" name="user_id_hidd" value="<?php echo (!empty($query[0]->role_id)) ? $query[0]->role_id : "" ?>"/>
                                                        <div class="form-group">
                                                         
                                                            <label class="form-label">ADMIN ROLES NAME</label>
                                                            <input type="text" class="form-control" name="role_name" id="role_name" placeholder="Enter Role Name" value="<?= !empty($query[0]->role_name) ? $query[0]->role_name : '' ?>" required>
                                                            <p id="error-message"></p>
                                                        </div>
                                                       
                                                        <div class="form-group">
                                                        <select name="status_ind" id="status_ind" class="form-control" data-validation="required" required>
                                                                <option value="">-- User Status --</option>

                                                                <option value="1">Active</option>
                                                                <option value="0">Inactive</option>
                                                            </select>
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
        
<script>
$(document).ready(function() {
$('#role_name').on('input', function() {
var input = $(this).val();
var regex = /^[a-zA-Z_ ]*$/; // allows only alphanumeric characters, spaces, and underscores
if (!regex.test(input)) {
$(this).addClass('error');
$('#error-message').text('Special characters and Numbers are not allowed.');
} else {
$(this).removeClass('error');
$('#error-message').text('');
}
});
});

</script>