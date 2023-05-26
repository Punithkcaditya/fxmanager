<!-- Page content -->
<div class="container-fluid pt-8">

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="mb-0"><?php echo $title?></h2>

                </div>
                <div class="card-body">
                    <div class="table-responsives">
                        <form action="<?php echo base_url("savenewuserinfo")?>" method="POST"
                            enctype="multipart/form-data">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <!-- <h2 class="mb-0"></h2> -->
                                        </div>

                                        <div class="card-body">
                                            <?php echo $this->include("message/message")?>

                                            <div class="row">
                                                <div class="col-md-12">


                                                    <div class="form-group">
                                                        <input type="hidden" name="user_id_hidd" id="user_id_hidd"
                                                            value="<?php echo !empty($query["user_id"]) ? $query["user_id"] : ""; ?>" />
                                                        <label class="form-label"><?php echo $pade_title3?></label>
                                                        <input type="text" class="form-control" name="user_name"
                                                            id="user_name" placeholder="Enter User Name"
                                                            value="<?php echo !empty($query["user_name"]) ? $query["user_name"] : ""?>"
                                                            required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label"><?php echo $pade_title6 ?></label>
                                                        <input type="number" class="form-control" name="userphonenumber"
                                                            id="userphonenumber" placeholder="Enter Phone Number"
                                                            value="<?php echo !empty($query["user_phone"]) ? $query["user_phone"] : ""?>"
                                                            required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label"><?php echo $pade_title4 ?></label>
                                                        <input type="email" class="form-control" name="useremail"
                                                            id="useremail" placeholder="Enter Email Address"
                                                            value="<?php echo !empty($query["user_email"]) ? $query["user_email"] : ""?>"
                                                            required>
                                                    </div>

                                                    <div class="form-group">
                                                        <button type="submit"
                                                            class="btn rounded-0 btn-primary bg-gradient">Submit</button>
                                                    </div>



                                                </div>
                                            </div>
                                        </div>
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
// CKEDITOR.replace('editor1');
// CKEDITOR.replace('editor2');
// CKEDITOR.replace('editor3');

function displayDivDemo(id, elementValue) {

    document.getElementById(id).style.display = elementValue.value == 1 ? 'block' : 'none';
}
</script>