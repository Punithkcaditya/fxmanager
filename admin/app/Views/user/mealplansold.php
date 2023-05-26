<div class="container-fluid pt-8">

    <div class="card-body">
        <div class="nav-wrapper p-0">
            <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">

                <?php foreach ($meal as $row) : ?>
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0 mt-md-2 mt-0 mt-lg-0 <?php echo   ($row['day_id'] == 1) ? "show active" : "";  ?>"
                        id="tabs-icons-text-<?php echo  $row['day_id']  ?>-tab" data-toggle="tab"
                        href="#tabs-icons-text-<?php echo  $row['day_id']  ?>" role="tab"
                        aria-controls="tabs-icons-text-<?php echo  $row['day_id']  ?>"
                        aria-selected="false"><?php echo  $row['day_name'] ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="card shadow ">
        <div class="card-body">

            <div class="tab-content" id="myTabContent" style="display:<?php echo $style ?>">
                <?php foreach ($meal as $row) : ?>
                <div class="tab-pane fade <?php echo   ($row['day_id'] == 1) ? "show active" : "";  ?>"
                    id="tabs-icons-text-<?php echo  $row['day_id']  ?>" role="tabpanel"
                    aria-labelledby="tabs-icons-text-<?php echo  $row['day_id']  ?>-tab">
                    <form action="<?php echo  base_url("savemealinfo")?>" method="POST" enctype="multipart/form-data"
                        onsubmit="submitActiveTab()">
                        <h2>Meal Plans</h2>
                        <input type="hidden" name="meal_id_hidd" id="meal_id_hidd" value="<?php echo isset($query["meal_id"]) ? (!empty($query["meal_id"]) && $query["mealofweek"]==$row['day_id'] ? $query["meal_id"] : "") : ''; ?>" />
                        <input type="hidden" name="mealtype" id="mealtype" value="Breakfast" />
                        <input type="hidden" name="mealofweek" id="mealofweek" value="<?php echo $row['day_id']  ?>" />
                        <ul class="nav nav-pills nav-fill flex-column flex-sm-row mb-5" id="tabs-text" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 <?php echo isset($query["meal_id"]) ? (!empty($query["mealtype"]) && $query["mealtype"]== "Breakfast" ? "active" : "") : "active"; ?>" id="tabs-text-1-tab" data-toggle="tab"
                                    href="#tabs-text-1" role="tab" aria-selected="true" onclick='$("#mealtype").val("Breakfast");'>Breakfast</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0  <?php echo !empty($query["mealtype"]) && $query["mealtype"]== "Snacks" ? "active" : ""; ?>" id="tabs-text-2-tab" data-toggle="tab"
                                    href="#tabs-text-2" role="tab" aria-selected="false" onclick='$("#mealtype").val("Snacks");'>Snacks</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0  <?php echo !empty($query["mealtype"]) && $query["mealtype"]== "Lunch" ? "active" : ""; ?>" id="tabs-text-3-tab" data-toggle="tab"
                                    href="#tabs-text-3" role="tab" aria-selected="false" onclick='$("#mealtype").val("Lunch");'>Lunch</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0  <?php echo !empty($query["mealtype"]) && $query["mealtype"]== "Dinner" ? "active" : ""; ?>" id="tabs-text-4-tab" data-toggle="tab"
                                    href="#tabs-text-4" role="tab" aria-selected="false" onclick='$("#mealtype").val("Dinner");'>Dinner</a>
                            </li>
                        </ul>
                        <div class="table-responsive border padd-20">
                            <input type="hidden" name="user_id" id="user_id"
                                value="<?php echo  !empty($query["user_id"]) && $query["mealofweek"]==$row['day_id'] ? $query["user_id"] : $user_id; ?>" />
                            <div class="form-group"> <label
                                    for="formGroupExampleInput"><?php echo  $pade_title1?></label> <input type="file"
                                    name="file" class="form-control" id="file" onchange="showPreview(event);"
                                    accept=".png, .jpg, .jpeg"
                                    value="<?php echo !empty($query["meal_image"]) && $query["mealofweek"]==$row['day_id'] ? $query["meal_image"] : ""?>"
                                    <?php echo  !empty($query["meal_image"]) ? "" : "required"; ?> /> </div>
                        </div>
                        <div class="form-group">
                            <div class="preview"> <img id="file-ip-1-preview"> </div>
                        </div>
                        <div class="form-group"> <?php if (!empty($query["meal_image"]) && $query["mealofweek"]==$row['day_id'] ) {?> <img
                                style="width: 139px;" id="blah"
                                src="<?=base_url("uploads/" . $query["meal_image"])?>" /> <?php } else {?>
                            <div id="blah"></div> <?php }?>
                        </div>
                        <div class="form-group"><label class="form-label"><?php echo $pade_title2 ?></label> <input
                                type="text" class="form-control" name="meal_name" id="meal_name"
                                placeholder="Enter Meal Name"
                                value="<?php echo !empty($query["meal_name"]) && $query["mealofweek"]==$row['day_id'] ? $query["meal_name"] : ""?>" required>
                        </div>
                        <div class="form-group"> <label for="formGroupExampleInput"><?php echo $pade_title6?></label>
                            <textarea class="form-control" id="meal_description" rows="5"
                                placeholder="Add Meal Description..." name="meal_description"
                                required><?php echo  htmlspecialchars(!empty($query["meal_description"]) && $query["mealofweek"]==$row['day_id'] ? $query["meal_description"] : ""); ?></textarea>
                        </div>
                        <div class="form-group"> <label class="form-label"><?php echo $pade_title8 ?></label> <input
                                type="number" class="form-control" name="duration" id="duration"
                                placeholder="Enter Duration"
                                value="<?php echo !empty($query["duration"]) && $query["mealofweek"]==$row['day_id'] ? $query["duration"] : ""?>"  required>
                        </div>
                        <div class="form-group"> <label class="form-label"><?php echo $pade_title3  ?></label> <input
                                type="number" class="form-control" name="kcal" id="kcal" placeholder="Enter Kcal"
                                value="<?php echo !empty($query["duration"]) && $query["mealofweek"]==$row['day_id'] ? $query["duration"] : ""?>" step="0.1" required>
                        </div>
                        <div class="form-group"> <label class="form-label"><?php echo $pade_title4  ?></label> <input
                                type="number" class="form-control" name="fat" id="fat" placeholder="Enter Fat"
                                value="<?php echo !empty($query["fat"]) && $query["mealofweek"]==$row['day_id'] ? $query["fat"] : ""?>" step="0.1" required>
                        </div>
                        <div class="form-group"> <label class="form-label"><?php echo $pade_title5  ?></label> <input
                                type="number" class="form-control" name="protien" id="protien"
                                placeholder="Enter Protien"
                                value="<?php echo !empty($query["protien"]) && $query["mealofweek"]==$row['day_id'] ? $query["protien"] : ""?>" step="0.1" required>
                        </div>
                        <div class="form-group"> <label class="form-label"><?php echo $pade_title7 ?></label> <input
                                type="number" class="form-control" name="carbs" id="carbs" placeholder="Enter Carbs"
                                value="<?php echo !empty($query["carbs"]) && $query["mealofweek"]==$row['day_id'] ? $query["carbs"] : ""?>" step="0.1" required>
                        </div>
                        <button type="submit" class="btn rounded-0 btn-primary bg-gradient">Submit</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="tab-content" id="myTabContent">
                <?php foreach ($meal as $row) : ?>
                <div class="tab-pane fade <?php echo   ($row['day_id'] == 1) ? "show active" : "";  ?>"
                    id="tabs-icons-text-<?php echo  $row['day_id']  ?>" role="tabpanel"
                    aria-labelledby="tabs-icons-text-<?php echo  $row['day_id']  ?>-tab">
                    <form action="<?php echo  base_url("savemealinfo")?>" method="POST" enctype="multipart/form-data"
                        onsubmit="submitActiveTab()">
                        <h2>Meal Plans</h2>
                        <input type="hidden" name="meal_id_hidd" id="meal_id_hidd" value="<?php echo isset($query["meal_id"]) ? (!empty($query["meal_id"]) && $query["mealofweek"]==$row['day_id'] ? $query["meal_id"] : "") : ''; ?>" />
                        <input type="hidden" name="mealtype" id="mealtype" value="Breakfast" />
                        <input type="hidden" name="mealofweek" id="mealofweek" value="<?php echo $row['day_id']  ?>" />
                        <ul class="nav nav-pills nav-fill flex-column flex-sm-row mb-5" id="tabs-text" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 <?php echo isset($query["meal_id"]) ? (!empty($query["mealtype"]) && $query["mealtype"]== "Breakfast" ? "active" : "") : "active"; ?>" id="tabs-text-1-tab" data-toggle="tab"
                                    href="#tabs-text-1" role="tab" aria-selected="true" onclick='$("#mealtype").val("Breakfast");'>Breakfast</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0  <?php echo !empty($query["mealtype"]) && $query["mealtype"]== "Snacks" ? "active" : ""; ?>" id="tabs-text-2-tab" data-toggle="tab"
                                    href="#tabs-text-2" role="tab" aria-selected="false" onclick='$("#mealtype").val("Snacks");'>Snacks</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0  <?php echo !empty($query["mealtype"]) && $query["mealtype"]== "Lunch" ? "active" : ""; ?>" id="tabs-text-3-tab" data-toggle="tab"
                                    href="#tabs-text-3" role="tab" aria-selected="false" onclick='$("#mealtype").val("Lunch");'>Lunch</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0  <?php echo !empty($query["mealtype"]) && $query["mealtype"]== "Dinner" ? "active" : ""; ?>" id="tabs-text-4-tab" data-toggle="tab"
                                    href="#tabs-text-4" role="tab" aria-selected="false" onclick='$("#mealtype").val("Dinner");'>Dinner</a>
                            </li>
                        </ul>
                        <div class="table-responsive border padd-20">
                            <input type="hidden" name="user_id" id="user_id"
                                value="<?php echo  !empty($query["user_id"]) && $query["mealofweek"]==$row['day_id'] ? $query["user_id"] : $user_id; ?>" />
                            <div class="form-group"> <label
                                    for="formGroupExampleInput"><?php echo  $pade_title1?></label> <input type="file"
                                    name="file" class="form-control" id="file" onchange="showPreview(event);"
                                    accept=".png, .jpg, .jpeg"
                                    value="<?php echo !empty($query["meal_image"]) && $query["mealofweek"]==$row['day_id'] ? $query["meal_image"] : ""?>"
                                    <?php echo  !empty($query["meal_image"]) ? "" : "required"; ?> /> </div>
                        </div>
                        <div class="form-group">
                            <div class="preview"> <img id="file-ip-1-preview"> </div>
                        </div>
                        <div class="form-group"> <?php if (!empty($query["meal_image"]) && $query["mealofweek"]==$row['day_id'] ) {?> <img
                                style="width: 139px;" id="blah"
                                src="<?=base_url("uploads/" . $query["meal_image"])?>" /> <?php } else {?>
                            <div id="blah"></div> <?php }?>
                        </div>
                        <div class="form-group"><label class="form-label"><?php echo $pade_title2 ?></label> <input
                                type="text" class="form-control" name="meal_name" id="meal_name"
                                placeholder="Enter Meal Name"
                                value="<?php echo !empty($query["meal_name"]) && $query["mealofweek"]==$row['day_id'] ? $query["meal_name"] : ""?>" required>
                        </div>
                        <div class="form-group"> <label for="formGroupExampleInput"><?php echo $pade_title6?></label>
                            <textarea class="form-control" id="meal_description" rows="5"
                                placeholder="Add Meal Description..." name="meal_description"
                                required><?php echo  htmlspecialchars(!empty($query["meal_description"]) && $query["mealofweek"]==$row['day_id'] ? $query["meal_description"] : ""); ?></textarea>
                        </div>
                        <div class="form-group"> <label class="form-label"><?php echo $pade_title8 ?></label> <input
                                type="number" class="form-control" name="duration" id="duration"
                                placeholder="Enter Duration"
                                value="<?php echo !empty($query["duration"]) && $query["mealofweek"]==$row['day_id'] ? $query["duration"] : ""?>"  required>
                        </div>
                        <div class="form-group"> <label class="form-label"><?php echo $pade_title3  ?></label> <input
                                type="number" class="form-control" name="kcal" id="kcal" placeholder="Enter Kcal"
                                value="<?php echo !empty($query["duration"]) && $query["mealofweek"]==$row['day_id'] ? $query["duration"] : ""?>" step="0.1" required>
                        </div>
                        <div class="form-group"> <label class="form-label"><?php echo $pade_title4  ?></label> <input
                                type="number" class="form-control" name="fat" id="fat" placeholder="Enter Fat"
                                value="<?php echo !empty($query["fat"]) && $query["mealofweek"]==$row['day_id'] ? $query["fat"] : ""?>" step="0.1" required>
                        </div>
                        <div class="form-group"> <label class="form-label"><?php echo $pade_title5  ?></label> <input
                                type="number" class="form-control" name="protien" id="protien"
                                placeholder="Enter Protien"
                                value="<?php echo !empty($query["protien"]) && $query["mealofweek"]==$row['day_id'] ? $query["protien"] : ""?>" step="0.1" required>
                        </div>
                        <div class="form-group"> <label class="form-label"><?php echo $pade_title7 ?></label> <input
                                type="number" class="form-control" name="carbs" id="carbs" placeholder="Enter Carbs"
                                value="<?php echo !empty($query["carbs"]) && $query["mealofweek"]==$row['day_id'] ? $query["carbs"] : ""?>" step="0.1" required>
                        </div>
                        <button type="submit" class="btn rounded-0 btn-primary bg-gradient">Submit</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>

</div>

<script>
function submitActiveTab() {
    // get the active tab
    var activeTab = document.querySelector('.tab-pane.active');

    // get the form in the active tab
    var form = activeTab.querySelector('form');

    // submit the form
    form.submit();
}
</script>