<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/calendar/page.css'); ?>">

<div class="container-fluid pt-8">

    <div class="card-body">
        <div class="nav-wrapper p-0">

        </div>
    </div>
    <div class="card shadow ">
        <div class="card-body">

            <div class="tab-content" id="myTabContent">

                <!-- strat -->
                <!-- end -->
                <form action="<?php echo  base_url("editmealinfo")?>" method="POST" enctype="multipart/form-data">
                    <h2>Meal Plans</h2>
                
                    <input type="hidden" name="count_items" id="count_items" value="<?php echo  $i?>" />
                    <input type="hidden" name="meal_id_hidd" id="meal_id_hidd" value="<?php echo  $day_id ?>" />
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo  $date['user_id'] ?>" />
                  
                    <div class="form-group"><label class="form-label"><?php echo $pade_title1 ?></label>
                        <input id="date" name="date" class="form-control" value="<?php echo $date['date'] ?>"  required/>
                    </div>
                    <div class="row">
                        <?php foreach ($mealPlans as $value) {?>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group"><label class="form-label"><?php echo $pade_title2 ?></label> <input
                                    type="text" class="form-control" name="meal_name[]" id="meal_name<?php echo $i?>"
                                    placeholder="Enter Meal Title" value="<?php echo $value['meal_name'] ?>" required>
                            </div>
                            <div class="form-group"><label class="form-label"><?php echo $pade_title3 ?></label> 
                            <input type="time" class="form-control" id="time"  name="time[]" min="09:00" max="17:00" value="<?php echo $value['meal_time'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group"> <label
                                    for="formGroupExampleInput"><?php echo $pade_title6?></label>
                                <textarea class="form-control" id="meal_description<?php echo $i ?>" rows="2"
                                    placeholder="Add Meal Description..." name="meal_description[]" required><?php echo $value['meal_description'] ?></textarea>
                            </div>
                        </div>
                        <?php }?>
                        <div class="col-md-12">
                        <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap  align-items-center" id="plansec">
                            <thead class="thead-light">
                                <tr>
                                    <th><input class='check_all' type='checkbox' onclick="select_all()" /> Select All
                                    </th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>


                            </tbody>
                        </table>
                    </div>
                        </div>
                    </div>
                    <button type="submit" class="btn rounded-0 btn-primary bg-gradient">Submit</button>
                    <button type="button" class="btn btn-info mt-1 mb-1" id="addmealplans"><i
                            class="fas fa-plus-circle"></i> Add Fields</button>
                    <button type="button" class="btn hideing btn-danger mt-1 mb-1" id="deletemealplans"><i
                            class="fas fa-minus"></i> Delete Fields</button>
                </form>


            </div>

        </div>
    </div>
    <!-- Dynamic fields for plan desc -->
    <!-- Dynamic fields for plan desc -->
</div>







<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>
<!-- Don't forget to include Jquery also -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/calendar/calendar.js'); ?>"></script>