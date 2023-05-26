
                    <!-- Page content -->
                    <div class="container-fluid pt-8">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card shadow">
                                    <div class="card-header">
                                        <h2 class="mb-0">Role Access</h2>
                                        <div class="col-md-12">
                                            <input type="button" class="btn btn-primary mt-1 mb-1" id="selects"
                                                value="Select All" />
                                            <input type="button" class="btn btn-primary mt-1 mb-1" id="deSelect"
                                                value="Deselect All" />
                                        </div>
                                    </div>
                                    <form action="<?= base_url('saveaccess') ?>" method="POST"
                                        enctype="multipart/form-data">
                                        <?= $this->include('message/message') ?> 
                                        <?php $i = 1; ?>
                                        <?php foreach ($query as $row) : ?>
                                        <div class="card-body">

                                            <div class="row">

                                                <input type="hidden" name="role_id"
                                                    value="<?php echo (!empty($role_id)) ? $role_id : "" ?>" />

                                                <div class="col-lg-12">
                                                    <ul>
                                                        <li>
                                                            <span><?php echo $i . ')'; ?></span>
                                                          

                                                                <label class="custom-switch">
                                                                    <input type="checkbox" name="menuitem_id[]"
                                                                        class="custom-switch-input" id="parent"
                                                                        value="<?php echo $row->menuitem_id; ?>"
                                                                        <?php echo (in_array($row->menuitem_id, $admin_users_accesses)) ? 'checked' : ''; ?>>
                                                                    <span class="custom-switch-indicator"></span>
                                                                </label><?php echo $row->menuitem_text; ?>
                                                                <ul style="list-style: none;">
                                                                    <?php if (!empty($row->submenus)) : ?>
                                                                    <?php foreach ($row->submenus as $row) : ?>
                                                                    <li>
                                                                        <label class="custom-switch">
                                                                            <input class="custom-switch-input"
                                                                                type="checkbox" name="menuitem_id[]"
                                                                                value="<?php echo $row->menuitem_id; ?>"
                                                                                <?php echo (in_array($row->menuitem_id, $admin_users_accesses)) ? 'checked' : ''; ?> />
                                                                            <span
                                                                                class="custom-switch-indicator"></span>
                                                                        </label><?php echo $row->menuitem_text; ?>
                                                                    </li>
                                                                    <?php endforeach ?>
                                                                    <?php endif; ?>
                                                                </ul>

                                                        </l1>
                                                    </ul>

                                                </div>
                                            </div>

                                        </div>
                                        <?php $i++; ?>
                                        <?php endforeach ?>
                                        <div class="col-md-12 text-center" style="padding:15px;"> <button type="submit"
                                                class="btn btn-info pull-right">Save</button></div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
            

<script>
$('input[type=checkbox]').change(function(e) {
    var checked = $(this).prop('checked');
    var isParent = !!$(this).closest('li').find(' > ul').length;
    
    if (isParent) {
        // if a parent level checkbox is changed, locate all children
        var children = $(this).closest('li').find('ul input[type=checkbox]');
        children.prop({
            checked
        }); // all children will have what parent has
    }else {
    // if a child checkbox is changed, locate parent and all children
    var parent = $(this).closest('ul').closest('li').find('>label input[type=checkbox]');
    var children = $(this).closest('ul').find('input[type=checkbox]');
   
    if (children.filter(':checked').length === 0) {
      // if all children are unchecked
      parent.prop({ checked: false, indeterminate: false });
    } else if (children.length === children.filter(':checked').length) {
      // if all children are checked
      parent.prop({ checked: true, indeterminate: false });
    } else {
      // if some of the children are checked
      parent.prop({ checked: true, indeterminate: false });
    }
  }

});


</script>
