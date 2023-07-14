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
                        <form action="<?php echo  base_url("savenewcurrency") ?>" method="POST" enctype="multipart/form-data">
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
                                                        <label class="form-label"><?php  echo   $pade_title1 ?></label>
                                                        <input type="hidden" class="form-control" name="currency_hid_id"
                                                            id="currency_hid_id"  value="<?php echo !empty( $query['currency_id'])? $query['currency_id']: ""; ?>" />
                                                                        <select name="currencyName"
                                                                        id="currencyName"
                                                                        class="form-control" required>
                                                                        <option value="">-- Select Currency --
                                                                        </option>

                                                                        <?php $currencies = array("USDINR","EURINR","GBPINR","JPYINR","CHFINR","CADINR","AUDINR","SGDINR","EURUSD","GBPUSD","USDJPY","USDCHF","USDCAD","AUDUSD","USDSGD");
                                                                        foreach ($currencies as $currency) {
                                                                                    if (in_array($currency, $arraycurrency)) {
                                                                                    continue; // Skip the currency if it's in the hidden currencies array
                                                                                    }
                                                                                    echo '<option value="' . $currency . '"';
                                                                                    echo '>' . $currency . '</option>';
                                                                                    } ?>
                                                                        </select>
                                                    </div>
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

