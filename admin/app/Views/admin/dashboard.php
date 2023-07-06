<div class="container-fluid pt-8">
   <?=$this->include('message/message')?>
</div>
<!-- currency selection start -->
<div class="card shadow ">
   <div class="card-body newcardstyle">
      <div class="row mt-4">
         <div class="col-md-5 col-sm-12">
            <div class="form-group">
               <select name="currencyselection"
                  class="form-control" required>
                  <option value="" selected>SELECT CURRENCY</option>
                  <?php foreach ($transaction as $row): ?>
                  <option value="<?php echo $row['currency'] ?>" <?php echo ((!empty($row['currency']) && isset($_GET['currency'])) && $row['currency'] == $_GET['currency']) ? 'selected' : '' ?>><?php echo $row['Currency'] ?></option>
                  <?php endforeach;?>
               </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group mt-2">
               <label class="form-label card-title">Spot Rate</label>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group mt-2">
               <label class="form-label card-title">Rate</label>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- currency selection end -->
<!-- graph dashboard -->
<div class="row">
   <div class="col-md-12">
      <div class="card card-profile  overflow-hidden">
         <div class="card-body">
            <div class="nav-wrapper p-0">
               <ul class="nav nav-pills nav-fill flex-column flex-md-row mb-5" id="tabs-icons-text" role="tablist">
                  <li class="nav-item">
                     <a class="nav-link mb-sm-0 mb-md-0 active show mt-md-2 mt-0 mt-lg-0" id="tabs-icons-text-6-tab" data-toggle="tab" href="#tabs-icons-text-6" role="tab" aria-controls="tabs-icons-text-5" aria-selected="false">Total <br/> Details</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link mb-sm-3 mb-md-0  mt-md-2 mt-0 mt-lg-0" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="false">Exposure <br/> Details</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link mb-sm-3 mb-md-0 mt-md-2 mt-0 mt-lg-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">Current Month <br/> Details</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link mb-sm-3 mb-md-0  mt-md-2 mt-0 mt-lg-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="true">Quarterwise <br/> Details</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link mb-sm-3 mb-md-0 mt-md-2 mt-0 mt-lg-0" id="tabs-icons-text-4-tab" data-toggle="tab" href="#tabs-icons-text-4" role="tab" aria-controls="tabs-icons-text-4" aria-selected="false">Details of <br/> Settled Invoices</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link mb-sm-0 mb-md-0 mt-md-2 mt-0 mt-lg-0" id="tabs-icons-text-5-tab" data-toggle="tab" href="#tabs-icons-text-5" role="tab" aria-controls="tabs-icons-text-5" aria-selected="false">Currency <br/> Performance (Spot)</a>
                  </li>
               </ul>
            </div>
            <div class="card shadow ">
               <div class="card-body">
                  <div class="tab-content" id="myTabContent">
                     <!-- tab 1 -->
                     <div class="tab-pane fade show active" id="tabs-icons-text-6" role="tabpanel" aria-labelledby="tabs-icons-text-6-tab">
                        <h2 class="text-center mb-5">Total Details</h2>
                        <div class="row">
                           <div class="col-md-5">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p">Category</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>
                                             <h4>Total Inwards :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Total Outwards :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>% Hedged Inwards :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>% Hedged Outwards :</h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p">Current Month</th>
                                       </tr>
                                    </thead>
                                    <tbody class="col-lg-3 p-2">
                                       <tr>
                                          <td>
                                             <h4><?php echo  $totaldetails['totalinwardsone']?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo  $totaldetails['totaloutwardsone']?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo  $totaldetails['hedgeinwardsone']?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo  $totaldetails['hedgeoutwardsone']?></h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p">Current Quarter</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>
                                             <h4><?php echo  $totaldetails['totalinwardstwo']?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo  $totaldetails['totaloutwardstwo']?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo  $totaldetails['hedgeinwardstwo']?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo  $totaldetails['hedgeoutwardstwo']?></h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>

                     <!-- tab 2 -->

                     <div class="tab-pane fade" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                        <h2 class="text-center mb-5">Exposure Details</h2>
                        <div class="row">
                           <div class="col-md-5">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p">Category</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>
                                             <h4>Total Exposure :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Total Hedged Exposure :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Percentage Hedged :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Average Hedge Rate :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Average Target Rate :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Current Portfolio Value :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Potential Gain/Loss on Portfolio :</h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p">Inwards</th>
                                       </tr>
                                    </thead>
                                    <tbody class="col-lg-3 p-2">
                                       <tr>
                                          <td>
                                             <h4>Website :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Email :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p">Outwards</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>
                                             <h4>Website :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Email :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>

                     <!-- tab 3 -->


                     <div aria-labelledby="tabs-icons-text-2-tab" class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel">
                        <h2 class="text-center mb-5">Current Month Details</h2>
                        <div class="row">
                           <div class="col-md-5">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p">Category</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>
                                             <h4>Current Month Exposure :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Percentage Hedged :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Average Hedge Rate :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Average Target Rate :</h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p">Inwards</th>
                                       </tr>
                                    </thead>
                                    <tbody class="col-lg-3 p-2">
                                       <tr>
                                          <td>
                                             <h4>Website :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Email :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p">Outwards</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>
                                             <h4>Website :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Email :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                     
                     <!-- tab 4 -->

                     <div aria-labelledby="tabs-icons-text-3-tab" class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel">
                        <h2 class="text-center mb-5">Quarterwise Details</h2>
                        <div class="row">
                           <div class="col-md-5">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p">Category</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>
                                             <h4>Total Exposure :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Percentage Hedged :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Average Hedge Rate :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Average Target Rate :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Portfolio Value :</h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p">Inwards</th>
                                       </tr>
                                    </thead>
                                    <tbody class="col-lg-3 p-2">
                                       <tr>
                                          <td>
                                             <h4>Website :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Email :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p">Outwards</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>
                                             <h4>Website :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Email :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>

                     <!-- tab 5 -->

                     <div aria-labelledby="tabs-icons-text-4-tab" class="tab-pane fade" id="tabs-icons-text-4" role="tabpanel">
                        <h2 class="text-center mb-5">Details of Settled Invoices</h2>
                        <div class="row">
                           <div class="col-md-5">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p">Category</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>
                                             <h4>Settlement Amount In FC :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Settlement Amount In INR :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Settlement Rate :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Average Target Rate :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Actual Gain/Loss :</h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p">Inwards</th>
                                       </tr>
                                    </thead>
                                    <tbody class="col-lg-3 p-2">
                                       <tr>
                                          <td>
                                             <h4>Website :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Email :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p">Outwards</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>
                                             <h4>Website :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Email :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>

                     <!-- tab 6 -->

                     <div aria-labelledby="tabs-icons-text-5-tab" class="tab-pane fade" id="tabs-icons-text-5" role="tabpanel">
                        <h2 class="text-center mb-5">Details of Settled Invoices</h2>
                        <div class="row">
                           <div class="col-md-5">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p">Category</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>
                                             <h4>Settlement Amount In FC :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Settlement Amount In INR :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Settlement Rate :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Average Target Rate :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Actual Gain/Loss :</h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p">Inwards</th>
                                       </tr>
                                    </thead>
                                    <tbody class="col-lg-3 p-2">
                                       <tr>
                                          <td>
                                             <h4>Website :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Email :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p">Outwards</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>
                                             <h4>Website :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Email :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Phone :</h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>


                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>



<!-- script -->

<script>
$(document).ready(function() {
var selectEl = $('select[name="currencyselection"]');
var currentVal = selectEl.val(); // initialize current value
selectEl.on('change', function() {
var selectedVal = $(this).val();
if(selectedVal && selectedVal !== currentVal) { // check if value has changed
currentVal = selectedVal; // update current value
window.location.href = 'Admindashboard?currency=' + selectedVal; // update URL
}
});
});

</script>