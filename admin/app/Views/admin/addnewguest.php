<!-- Page content -->
<div class="container-fluid pt-8">

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="mb-0"><?php echo $pade_title ?></h2>
                </div>
                <div class="card-body">
                    <div class="table-responsives">
                        <form action="<?php echo base_url('addguestlist') ?>" method="POST" enctype="multipart/form-data">
                            <?= $this->include('message/message') ?>
                            <input type="hidden" name="guest_list_id" id="guest_list_id" value="<?php echo (!empty($guestinfo['guest_list_id'])) ? $guestinfo['guest_list_id'] : "" ?>" />

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Enter Name</label>
                                        <input type="text" class="form-control" name="guest_name" id="guest_name"
                                            placeholder="Enter First Name" value="<?php echo (!empty($guestinfo['name'])) ? $guestinfo['name'] : "" ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Enter Phone Number</label>
                                        <input type="text" class="form-control" name="guest_phone_number"
                                            id="guest_phone_number" placeholder="Enter Phone Number" value="<?php echo (!empty($guestinfo['phone'])) ? $guestinfo['phone'] : "" ?>" required>
                                    </div>

                                    <div class="form-group">

                                        <label class="form-label"> Ladies Mehendi</label>

                                        <select name="Ladies_Mehendi" id="Ladies_Mehendi" class="form-control">
                                            <option value="">-- Select Type --</option>

                                            <option value="Yes"  <?php echo (!empty($guestinfo['Ladies_Mehendi']) && $guestinfo['Ladies_Mehendi'] == 'Yes') ? 'selected' : '' ?>>Yes</option>
                                            <option value="No"  <?php echo (!empty($guestinfo['Ladies_Mehendi']) && $guestinfo['Ladies_Mehendi'] == 'No') ? 'selected' : '' ?>>No</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">No of Guest</label>
                                        <input type="number" class="form-control" name="no_of_guest" id="no_of_guest"
                                            placeholder="No of Guest" value="<?php echo (!empty($guestinfo['no_of_guest'])) ? $guestinfo['no_of_guest'] : "" ?>">
                                    </div>

                                    <div class="form-group">
                                    <label class="form-label">Guest Comment</label>
												<textarea class="form-control" id="guest_comment" name="guest_comment" rows="5" placeholder="Guest Comment"><?php echo (!empty($guestinfo['guest_comment'])) ? $guestinfo['guest_comment'] : "" ?></textarea>
											</div>

                                </div>

                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label class="form-label">Enter Email Address</label>
                                        <input type="email" class="form-control" name="guest_email" id="guest_email"
                                            placeholder="Enter Email Address" value="<?php echo (!empty($guestinfo['email'])) ? $guestinfo['email'] : "" ?>" required>
                                    </div>





                                    <div class="form-group">

                                        <label class="form-label"> Sangeet</label>

                                        <select name="Sangeet" id="Sangeet" class="form-control">
                                            <option value="">--Select Type --</option>

                                            <option value="Yes"  <?php echo (!empty($guestinfo['Sangeet']) && $guestinfo['Sangeet'] == 'Yes') ? 'selected' : '' ?>>Yes</option>
                                            <option value="No"  <?php echo (!empty($guestinfo['Sangeet']) && $guestinfo['Sangeet'] == 'No') ? 'selected' : '' ?>>No</option>
                                        </select>
                                    </div>



                                    <div class="form-group">

                                        <label class="form-label"> Tel Baan</label>

                                        <select name="Tel_Baan" id="Tel_Baan" class="form-control">
                                            <option value="">--Select Type --</option>

                                            <option value="Yes" <?php echo (!empty($guestinfo['Tel_Baan']) && $guestinfo['Tel_Baan'] == 'Yes') ? 'selected' : '' ?>>Yes</option>
                                            <option value="No"  <?php echo (!empty($guestinfo['Tel_Baan']) && $guestinfo['Tel_Baan'] == 'No') ? 'selected' : '' ?>>No</option>
                                        </select>
                                    </div>


                                    <div class="form-group">

                                        <label class="form-label"> Baraat Wedding Reception</label>

                                        <select name="Baraat_Wedding_Reception" id="Baraat_Wedding_Reception" class="form-control">
                                            <option value="">--Select Type --</option>

                                            <option value="Yes" <?php echo (!empty($guestinfo['Baraat_Wedding_Reception']) && $guestinfo['Baraat_Wedding_Reception'] == 'Yes') ? 'selected' : '' ?>>Yes</option>
                                            <option value="No" <?php echo (!empty($guestinfo['Baraat_Wedding_Reception']) && $guestinfo['Baraat_Wedding_Reception'] == 'No') ? 'selected' : '' ?>>No</option>
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