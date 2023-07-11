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
                  <option value="2" selected>DEFAULT CURRENCY EURUSD</option>
                  <?php foreach ($transaction as $row): ?>
                        <option value="<?php echo $row['currency'] ?>" <?php echo ((!empty($row['currency']) && isset($_GET['currency'])) && $row['currency'] == $_GET['currency']) ? 'selected' : '' ?>><?php echo $row['Currency'] ?></option>
                  <?php endforeach;?>
               </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group mt-2">
               <label>Spot Rate Export :</label>
               <span><?php echo $spotrateExport ?></span>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group mt-2">
            <label>Spot Rate Import :</label>
            <span><?php echo $spotrateImport ?></span>
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
                           <div class="col-md-6">
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
                                             <h4><?php echo number_format( $totaldetails['hedgeinwardsone'], 4)?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo  number_format($totaldetails['hedgeoutwardsone'], 4) ?></h4>
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
                                             <h4><?php echo  number_format($totaldetails['hedgeinwardstwo'], 4); ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format( $totaldetails['hedgeoutwardstwo'], 4 ); ?></h4>
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
                           <div class="col-md-6">
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
                                             <h4><?php echo $exposuredetails['totalexposureone'] ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($exposuredetails['totalhedgexpdone'], 4); ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($exposuredetails['percentagehedgedone'], 4); ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($exposuredetails['avghedgeone'], 4); ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($exposuredetails['avgtargetone'], 4); ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($exposuredetails['currentportfoliovalueone'], 4); ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($exposuredetails['currentganorloseone'], 4); ?></h4>
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
                                             <h4><?php echo $exposuredetails['totalexposuretwo'] ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($exposuredetails['totalhedgexpdtwo'], 4);?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($exposuredetails['percentagehedgedtwo'], 4); ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($exposuredetails['avghedgetwo'], 4); ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($exposuredetails['avgtargettwo'], 4);  ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($exposuredetails['currentportfoliovaluetwo'], 4);  ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($exposuredetails['currentganorlosetwo'], 4);  ?></h4>
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
                           <div class="col-md-6">
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
                                             <h4><?php echo $currentmonthdetails['currentmonthtotalexposureone'] ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo $currentmonthdetails['currentmonthpercentagehedgedone'] ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format( $currentmonthdetails['avghedgeone'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format( $currentmonthdetails['avgtargeone'], 4) ?></h4>
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
                                             <h4><?php echo $currentmonthdetails['currentmonthtotalexposuretwo'] ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo $currentmonthdetails['currentmonthpercentagehedgedtwo'] ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($currentmonthdetails['avghedgetwo'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($currentmonthdetails['avgtargettwo'], 4) ?></h4>
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
                           <div class="col-md-4">
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
                                             <h4></h4>
                                          </td>
                                       </tr>
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
                           <div class="col-md-4">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p text-center">Inwards</th>
                                       </tr>
                                    </thead>
                                    <tbody class="col-lg-3 p-2">
                                    <table class="table table-striped table-bordered w-100 text-nowrap">
                                       <thead>
                                       <tr>
                                       <th class="wd-50p">Current Quarter</th>
                                       <th class="wd-50p">Last Quarter</th>
                                       </tr>
                                       </thead>
                                       <tbody>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['current-quarter-totalexposureone'], 4) ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['last-quarter-totalexposureone'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['current-quarter-percentagehedgedone'], 4) ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['last-quarter-percentagehedgedone'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['current-quarter-avghedgeone'], 4) ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['last-quarter-avghedgeone'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['current-quarter-avgavgtargetone'], 4) ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['last-quarter-avgavgtargetone'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['current-portfolio-valueone'], 4) ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['last-portfolio-valueone'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       </tbody>
                                       </table>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p text-center">Outwards</th>
                                       </tr>
                                    </thead>
                                    <tbody class="col-lg-3 p-2">
                                    <table class="table table-striped table-bordered w-100 text-nowrap">
                                       <thead>
                                       <tr>
                                       <th class="wd-50p">Current Quarter</th>
                                       <th class="wd-50p">Last Quarter</th>
                                       </tr>
                                       </thead>
                                       <tbody>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['current-quarter-totalexposuretwo'], 4) ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['last-quarter-totalexposuretwo'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['current-quarter-percentagehedgedtwo'], 4) ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['last-quarter-percentagehedgedtwo'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['current-quarter-avghedgetwo'], 4) ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['last-quarter-avghedgetwo'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['current-quarter-avgavgtargettwo'], 4) ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['last-quarter-avgavgtargettwo'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['current-portfolio-valuetwo'], 4) ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo number_format($quaterdetails['last-portfolio-valuetwo'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       </tbody>
                                       </table>
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
                           <div class="col-md-4">
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
                                             <h4></h4>
                                          </td>
                                       </tr>
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
                                             <h4>Actual Gain/Loss :</h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="table-responsive border ">
                              <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p text-center">Inwards</th>
                                       </tr>
                                    </thead>
                                    <tbody class="col-lg-3 p-2">
                                    <table class="table table-striped table-bordered w-100 text-nowrap">
                                       <thead>
                                       <tr>
                                       <th class="wd-50p">Current Quarter</th>
                                       <th class="wd-50p">Last Quarter</th>
                                       </tr>
                                       </thead>
                                       <tbody>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($settledinvoices['current-quarter-settamtinFC_one'], 4) ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo number_format($settledinvoices['last-quarter-settamtinFC_one'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($settledinvoices['current-quarter-settamtone'], 4) ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo number_format($settledinvoices['last-quarter-settamtone'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($settledinvoices['current-quarter-setrateone'], 4) ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo number_format($settledinvoices['last-quarter-setrateone'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($settledinvoices['current-quarter-actualgainone'], 4) ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo number_format($settledinvoices['last-quarter-actualgainlossone'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       </tbody>
                                       </table>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="table-responsive border ">
                              <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p text-center">Outwards</th>
                                       </tr>
                                    </thead>
                                    <tbody class="col-lg-3 p-2">
                                    <table class="table table-striped table-bordered w-100 text-nowrap">
                                       <thead>
                                       <tr>
                                       <th class="wd-50p">Current Quarter</th>
                                       <th class="wd-50p">Last Quarter</th>
                                       </tr>
                                       </thead>
                                       <tbody>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($settledinvoices['current-quarter-settamtinFC_two'], 4) ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo number_format($settledinvoices['last-quarter-settamtinFC_two'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($settledinvoices['current-quarter-settamttwo'], 4) ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo number_format($settledinvoices['last-quarter-settamttwo'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($settledinvoices['current-quarter-setratetwo'], 4) ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo number_format($settledinvoices['last-quarter-settamttwo'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4><?php echo number_format($settledinvoices['current-quarter-actualgainlosstwo'], 4) ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo number_format($settledinvoices['last-quarter-actualgainlosstwo'], 4) ?></h4>
                                          </td>
                                       </tr>
                                       </tbody>
                                       </table>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>

                     <!-- tab 6 -->

                     <div aria-labelledby="tabs-icons-text-5-tab" class="tab-pane fade" id="tabs-icons-text-5" role="tabpanel">
                        <h2 class="text-center mb-5">Currency Performance (Spot)</h2>
                        <div class="row">
                           <div class="col-md-4">
                              <div class="table-responsive border ">
                                 <table class="table table-striped table-bordered w-100 text-nowrap ">
                                    <thead>
                                       <tr>
                                          <th class="wd-15p"><h4>&nbsp;</h4></th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>
                                             <h4>Today :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>Yesterday :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>This Week :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>This Month :</h4>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <h4>This Quarter:</h4>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="col-md-8">
                              <div class="table-responsive border ">
                              <table class="table table-striped table-bordered w-100 text-nowrap ">
                            
                                    <tbody class="col-lg-3 p-2">
                                    <table class="table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                    <tr>
                                    <th class="wd-50p"><h4>Open</h4></th>
                                    <th class="wd-50p"><h4>High</h4></th>
                                    <th class="wd-50p"><h4>Low</h4></th>
                                    </tr>
                                    </thead>
                                       <tbody>
                                       <tr>
                                          <td>
                                             <h4><?php echo $currencyperformance['Today']['Open']; ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo $currencyperformance['Today']['High']; ?></h4>
                                          </td>
                                          <td>
                                          <h4><?php echo $currencyperformance['Today']['Low']; ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                       <td>
                                             <h4><?php echo $currencyperformance['Yesterday']['Open']; ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo $currencyperformance['Yesterday']['High']; ?></h4>
                                          </td>
                                          <td>
                                          <h4><?php echo $currencyperformance['Yesterday']['Low']; ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                       <td>
                                             <h4><?php echo $currencyperformance['ThisWeek']['Open']; ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo $currencyperformance['ThisWeek']['High']; ?></h4>
                                          </td>
                                          <td>
                                          <h4><?php echo $currencyperformance['ThisWeek']['Low']; ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                       <td>
                                             <h4><?php echo $currencyperformance['ThisMonth']['Open']; ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo $currencyperformance['ThisMonth']['High']; ?></h4>
                                          </td>
                                          <td>
                                          <h4><?php echo $currencyperformance['ThisMonth']['Low']; ?></h4>
                                          </td>
                                       </tr>
                                       <tr>
                                       <td>
                                             <h4><?php echo $currencyperformance['ThisQuarter']['Open']; ?></h4>
                                          </td>
                                          <td>
                                             <h4><?php echo $currencyperformance['ThisQuarter']['High']; ?></h4>
                                          </td>
                                          <td>
                                          <h4><?php echo $currencyperformance['ThisQuarter']['Low']; ?></h4>
                                          </td>
                                       </tr>
                                       </tbody>
                                       </table>
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