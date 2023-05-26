<!-- Page content -->
<div class="container-fluid pt-8">

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="mb-0"><?php echo  $title ?></h2>

                </div>
                <div class="card-body">
                    <div class="table-responsives">
                        <form action="<?php echo  base_url("savenewcounterparty") ?>" method="POST" enctype="multipart/form-data">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <!-- <h2 class="mb-0"></h2> -->
                                        </div>

                                        <div class="card-body">
                                            <?php echo  $this->include('message/message') ?>

                                            <div class="row">
                                                <div class="col-md-12">


                                                    <div class="form-group">
                                                        <label class="form-label"><?php echo  $pade_title1 ?></label>
                                                        <input type="hidden" class="form-control" name="counterParty_id_hid_id"
                                                            id="counterParty_id_hid_id"  value="<?php echo !empty( $query['counterParty_id'])? $query['counterParty_id']: ""; ?>" />
                                                        <input type="text" class="form-control" name="counterPartyName"
                                                            id="counterPartyName" placeholder="Enter Counter Party Name" value="<?php echo !empty( $query['counterPartyName'])? $query['counterPartyName']: ""; ?>"
                                                            required />
                                                    </div>
    
                                                  


                                                    <div class="form-group">
                                                        <label class="form-label"><?php echo $pade_title3 ?></label>
                                                        <select name="counter_status" id="counter_status" class="form-control"
                                                            required>
                                                            <option value="">-- Exposure Status --
                                                            </option>

                                                            <option value="1" <?php echo !empty(
                                                                                        $query['status']) &&
                                                                                    $query['status'] ==1 ? "selected": ""; ?>>
                                                                Active</option>
                                                            <option value="0" <?php echo !isset(
                                                                                        $query['status']) || empty(
                                                                                        $query['status']) ? "selected": ""; ?>>
                                                                Inactive</option>
                                                        </select>
                                                    </div>


                                               

                                                    <div class="col-md-12" style="text-align: center;">
                                                        <div class="d-grid gap-1">
                                                            <button
                                                                class="btn rounded-0 btn-primary bg-gradient">Save</button>
                                                        </div>
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

