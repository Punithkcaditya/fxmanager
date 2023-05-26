

                    <!-- Page content -->
                    <div class="container-fluid pt-8">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card shadow">
                                    <div class="card-header">
                                        <h2 class="mb-0"><?= $page_heading ?></h2>
                                        <div class="col-md-12">
                                            <input type="button" class="btn btn-primary mt-1 mb-1" id="selectstwo" value="Select All" />
                                            <input type="button" class="btn btn-primary mt-1 mb-1" id="deSelectstwo" value="Deselect All" />
                                            <input type="button" class="btn btn-primary mt-1 mb-1" id="onlyadd" value="Only Add" />
                                            <input type="button" class="btn btn-primary mt-1 mb-1" id="onlyedit" value="Only Edit" />
                                            <input type="button" class="btn btn-primary mt-1 mb-1" id="onlydelete" value="Only Delete" />
                                        </div>
                                    </div>
                                    <form action="<?= base_url('savepermission') ?>" method="POST" enctype="multipart/form-data">
                                    <?= $this->include('message/message') ?>  

                                        <input type="hidden" name="role_id" value="<?php echo (!empty($role_id)) ? $role_id : "" ?>" />
                                        <?php $i = 0; ?>
                                        <?php foreach ($query as $row) : ?>
                                            <?php if (empty($row->parent_menuitem_id)) : ?>
                                                <div class="col-xs-12 col-sm-12 col-md-12 mainmenus">
                                                <?php endif; ?>
                                                <div class="col-xs-12 col-sm-12 col-md-12 submenus" style="
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: space-between;
                                                            align-content: flex-end;
                                                            padding: 16px;">
                                                    <ul class="cbp_tmtimeline" style="width: 100%;">
                                                        <li>
                                                            <div class="cbp_tmlabel <?php echo (!empty($row->parent_menuitem_id) ? 'padding-left' : 'main-heading') ?>">

                                                                <?php if (!empty($row->parent_menuitem_id)) { ?>
                                                                    <ul style="list-style: none;padding-left: 0px;">
                                                                        <li>
                                                                            <div class="col-xs-5 col-sm-5 col-md-5 ">
                                                                                <p style="margin: 0 0 2px;"><span class="lbl"></span> <?php echo $row->menuitem_text; ?></p>
                                                                            </div>
                                                                            <div class="col-xs-2 col-sm-2 col-md-2">
                                                                                <input class="childs" type="hidden" name="menuitem_id[]" value="<?php echo $row->menuitem_id; ?>" />
                                                                                <span>Add</span> <input class="childs allcheckbox add_permission" type="checkbox" name="add_permission[<?php echo $i; ?>]" value="1" <?php echo (!empty($row->add_permission)) ? 'checked' : ''; ?> />
                                                                            </div>
                                                                            <div class="col-xs-2 col-sm-2 col-md-2">
                                                                                <span>Edit</span> <input class="childs allcheckbox edit_permission" type="checkbox" name="edit_permission[<?php echo $i; ?>]" value="1" <?php echo (!empty($row->edit_permission)) ? 'checked' : ''; ?> />
                                                                            </div>
                                                                            <div class="col-xs-3 col-sm-3 col-md-3">
                                                                                <span>Delete</span> <input class="childs  allcheckbox delete_permission" type="checkbox" name="delete_permission[<?php echo $i; ?>]" value="1" <?php echo (!empty($row->delete_permission)) ? 'checked' : ''; ?> />
                                                                            </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                <?php } else { ?> <div class="col-xs-5 col-sm-5 col-md-5 <?php echo (!empty($row->parent_menuitem_id) ? 'padding-left' : 'main-heading') ?>">
                                                        <p style="margin: 0 0 2px;"><span class="lbl"></span> <?php echo $row->menuitem_text; ?></p>
                                                    </div>
                                                    <div class="col-xs-2 col-sm-2 col-md-2">
                                                        <input class="childs" type="hidden" name="menuitem_id[]" value="<?php echo $row->menuitem_id; ?>" />
                                                        <span>Add</span> <input class="childs allcheckbox add_permission" type="checkbox" name="add_permission[<?php echo $i; ?>]" value="1" <?php echo (!empty($row->add_permission)) ? 'checked' : ''; ?> />
                                                    </div>
                                                    <div class="col-xs-2 col-sm-2 col-md-2">
                                                        <span>Edit</span> <input class="childs allcheckbox edit_permission" type="checkbox" name="edit_permission[<?php echo $i; ?>]" value="1" <?php echo (!empty($row->edit_permission)) ? 'checked' : ''; ?> />
                                                    </div>
                                                    <div class="col-xs-3 col-sm-3 col-md-3">
                                                        <span>Delete</span> <input class="childs  allcheckbox delete_permission" type="checkbox" name="delete_permission[<?php echo $i; ?>]" value="1" <?php echo (!empty($row->delete_permission)) ? 'checked' : ''; ?> />
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            </li>
                                            </ul>

                                                </div>
                                                <?php if (empty($row->parent_menuitem_id)) : ?>
                                </div>
                            <?php endif; ?>
                            <?php $i++; ?>
                        <?php endforeach ?>
                        <input type="hidden" name="max_i" id="max_i" value="<?php echo $i; ?>" />
                        <div class="col-md-12 text-center" style="padding:15px;"> <button type="submit" class="btn btn-info pull-right">Save</button></div>
                        </form>
                            </div>
                        </div>

                    </div>
   