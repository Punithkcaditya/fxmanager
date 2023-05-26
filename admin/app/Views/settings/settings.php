<!-- Page content -->
<div class="container-fluid pt-8">

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="mb-0"><?= $page_heading; ?></h2>

                </div>
                <div class="card-body">
                    <div class="table-responsives">

                        <form id="user_form" name="user_form" action="<?= base_url('settingsupdate') ?>" method="POST"
                            enctype="multipart/form-data">

                            <?= $this->include('message/message') ?>


                            <?php foreach ($query as $row) : ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                    <label class="form-label" style=" width: 100%;"><?php echo $row->setting_name; ?></label>
                                        <input type="hidden" name="setting_id[<?php echo $row->setting_id; ?>]"
                                            value="<?php echo $row->setting_id; ?>" />
                                        <?php if ($row->type == 'selectbox') : ?>
                                        <select name="setting_value[<?php echo $row->setting_id; ?>]"
                                            class="form-control mb-3" required>
                                            <option value="">-- Service Currency --
                                            </option>
                                            <?php foreach ($currency as $underrow) : ?>
                                            <option value="<?php echo $underrow['currencySymbol'] ?>"
                                            <?php echo (!empty($row->setting_value ) && $row->setting_value  == $underrow['currencySymbol']) ? 'selected' : '' ?>>
                                                <?php echo $underrow['currencyName']?>-(<?php echo $underrow['currencySymbol'] ?>)
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php elseif ($row->type == 'textarea') : ?>
                                        <textarea class="form-control"
                                            name="setting_value[<?php echo $row->setting_id; ?>]"
                                            placeholder="<?php echo $row->setting_name; ?>"><?php echo $row->setting_value; ?></textarea>
                                        <?php elseif ($row->type == 'radiobutton') : ?>
                                        <input name="setting_value[<?php echo $row->setting_id; ?>]" value="1"
                                            type="radio"
                                            <?php echo (!empty($row->setting_value) && $row->setting_value == 1) ? 'checked' : ''; ?>
                                            required />
                                        <span class="lbl">Classic</span>
                                        &nbsp; &nbsp; &nbsp;&nbsp;
                                        <input name="setting_value[<?php echo $row->setting_id; ?>]" value="2"
                                            type="radio"
                                            <?php echo (!empty($row->setting_value) && $row->setting_value == 2) ? 'checked' : ''; ?>
                                            required />
                                        <span class="lbl">Graphical</span>
                                        <?php elseif ($row->type == 'checkbox') : ?>
                                        <input name="setting_value[<?php echo $row->setting_id; ?>]" value="1"
                                            type="checkbox"
                                            <?php echo (!empty($row->setting_value) && $row->setting_value == 1) ? 'checked' : ''; ?> />
                                        <!-- <span class="lbl">Share</span> -->

                                        <?php else : ?>
                                        <input class="form-control"
                                            name="setting_value[<?php echo $row->setting_id; ?>]" type="text"
                                            placeholder="<?php echo $row->setting_name; ?>"
                                            value="<?php echo $row->setting_value; ?>" required />
                                        <?php endif ?>

                                    </div>


                                </div>



                            </div>
                            <?php endforeach ?>
                            <div class="col-md-12" style="text-align: center;">
                                <div class="d-grid gap-1">
                                    <button class="btn rounded-0 btn-primary bg-gradient">Save</button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>