<?php

namespace App\Controllers;

defined('BASEPATH') or exit('No direct script access allowed');

use App\Controllers\BaseController;
use Config\Database;
use App\Models\TransactionModel as Transaction_Model;
use App\Models\CurrencyModel as Currency_Model;

class Index extends BaseController
{
    protected $request;

    public function __construct()
    {
        parent::__construct();
        $request = \Config\Services::request();
        helper(['form', 'url', 'string']);
        $session = session();
        $this->transaction_model = new Transaction_Model();
        $this->currency_model = new Currency_Model();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        } else {
            $role_id = $pot["role_id"];
        }
        
        $menutext = $this->request->uri->getSegment(2);
   
        if (isset($_SESSION['sidebar_menuitems'])) {
            foreach ($_SESSION['sidebar_menuitems'] as $main_menus) :
                if (strtolower($main_menus->menuitem_link) == strtolower($menutext)) {
                    $permissions = $this->admin_roles_accesses_model->get_permisions($role_id, $main_menus->menuitem_id);
                    $this->permission = array($permissions->add_permission, $permissions->edit_permission, $permissions->delete_permission);
                } else {
                    if (!empty($main_menus->submenus)) :
                        foreach ($main_menus->submenus as $submenus) :
                            if (strtolower($submenus->menuitem_link) == strtolower($menutext)) {
                                $permissions = $this->admin_roles_accesses_model->get_permisions($role_id, $submenus->menuitem_id);
                                $this->permission = array($permissions->add_permission, $permissions->edit_permission, $permissions->delete_permission);
                            }
                        endforeach;
                    endif;
                }
            endforeach;
        }
    }

    public function index()
    {
        $data = [];
        $session = session();
        $user_id = $session->get("userdata");
        $input = $this->validate([
            "password" => "required|min_length[3]",
            "user_name" => "required",
        ]);

        if (!empty($input)) {
            $login_detail = (array) $this->admin_users_model->loginnew(
                $this->request->getPost()
            );


            if (!empty($login_detail)) {
                extract($this->request->getPost());

           
                unset($login_detail["logged_session_id"]);
                $user_session_id = rand("2659748135965", "088986555510245579");

                $this->admin_users_model->data["user_session_id"] = $user_session_id;

                $login_detail["logged_session_id"] = md5($user_session_id);

                $session->set("userdata", $login_detail);
                $pot = json_decode(json_encode($session->userdata), true);
                $this->admin_users_model->primary_key = [
                    "user_id" => $pot["user_id"],
                ];
                $this->admin_users_model->updateData();
                return redirect()->to("Admindashboard");
                //return view('welcome_message');
            } else {
                $session->setFlashdata("error", "Incorrect Email and Password");
                $data["session"] = $session;
                return redirect()->to("/");
            }
        }
    }








    public function dashboard()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $curid = isset($_GET['currency']) ? $_GET['currency'] : 2; 
        $curren = $this->currency_model->select("Currency")->where('currency_id', $curid)->first();
        $data['totaldetails'] = $this->totaldetails($curid);
        $data['exposuredetails'] = $this->exposuredetails($curid , $curren);
        $data['currentmonthdetails'] = $this->currentmonthdetails($curid);
        $data['quaterdetails'] = $this->quaterdetails($curid,  $curren);
        $data['settledinvoices'] = $this->settledinvoices($curid,  $curren);
        $data['currencyperformance'] = json_decode($this->currencyperformance(), true);
        $data["transaction"] = $this->transaction_model
		->distinct()
		->select("transactiondetails.currency, currency.Currency")
		->join('currency', "transactiondetails.currency = currency.currency_id", 'left')
		->findAll();
        $data["page_title"] =
            "Welcome - " .
            ucfirst($pot["first_name"]) .
            " " .
            $data["page_heading"] =
            "Welcome - " .
            ucfirst($pot["first_name"]) .
            " " .
            $data["sub_heading"] =
            "Welcome - " .
            ucfirst($pot["first_name"]);
        $data["session"] = $session;
        $data["breadcrumb"] = "Admindashboard";
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data['view'] = 'admin/dashboard';
        return view('templates/default', $data);
    }


    // settledinvoices

    public function settledinvoices($curid = '', $curren = ''){
 
        if(isset($curren['Currency'])){
        $data['current-quarter-settamtinFC_one'] = 0;
        $data['last-quarter-settamtinFC_one'] = 0;
        $data['current-quarter-settamtinFC_two'] = 0;
        $data['current-quarter-actualgainone'] = 0;
        $data['last-quarter-settamtinFC_two'] = 0;
        $data['current-quarter-settamtone'] = 0;
        $data['current-quarter-setrateone'] = 0;
        $data['current-quarter-actualgainlossone'] = 0;
        $data["last-quarter-avgavgtargettwo"] = 0;
        $data['current-quarter-settamttwo'] = 0;
        $data['current-quarter-setratetwo'] = 0;
        $data['current-quarter-actualgainlosstwo'] = 0;
        $data['last-quarter-settamttwo'] = 0;
        $data['last-quarter-setrateouttwo'] = 0;
        $data['last-quarter-actualgainlosstwo'] = 0;
        $data['last-quarter-settamtone'] = 0;
        $data['last-quarter-setrateone'] = 0;
        $data['last-quarter-actualgainlossone'] = 0;
        $data["current-quarter-avgavgtargettwo"] = 0;
        $currentquarterportfoliovalueexp = $this->transaction_model->currentquarterportfoliovalueexp($curren['Currency']);
        $currentquarterportfoliovaluimp = $this->transaction_model->currentquarterportfoliovaluimp($curren['Currency']);
        $lastquarterportfoliovaluimp = $this->transaction_model->lastquarterportfoliovaluimp($curren['Currency']);
        $lastquarterportfoliovaluexp = $this->transaction_model->lastquarterportfoliovaluexp($curren['Currency']);
        foreach( $currentquarterportfoliovalueexp  as $row){
            $inr_target_value = ($row['inr_target_value'] > 0.00) ? $row['inr_target_value'] : 1;
            $targetValueInr = ($row['targetRate']*$inr_target_value)*$row['amountinFC'];
            $currentquartersettamtinwardsval = $row['Toatalallpayment'] + $row['AvgspotamountRate'];
            $data['current-quarter-settamtone'] += $currentquartersettamtinwardsval; 
            $data['current-quarter-setrateone'] += $currentquartersettamtinwardsval / $row['amountinFC'];
            $data['current-quarter-actualgainone'] += $currentquartersettamtinwardsval - $targetValueInr;
        }
        foreach( $currentquarterportfoliovaluimp  as $row){
            $inr_target_value = ($row['inr_target_value'] > 0.00) ? $row['inr_target_value'] : 1;
            $targetValueInr = ($row['targetRate']*$inr_target_value)*$row['amountinFC'];
            $currentquartersettamtoutwardsval = $row['Toatalallpayment'] + $row['AvgspotamountRate'];
            $data['current-quarter-settamttwo'] += $currentquartersettamtoutwardsval; 
            $data['current-quarter-setratetwo'] += $currentquartersettamtoutwardsval / $row['amountinFC'];
            $data['current-quarter-actualgainlosstwo'] += $currentquartersettamtoutwardsval - $targetValueInr;
        }
        foreach( $lastquarterportfoliovaluimp  as $row){
            $inr_target_value = ($row['inr_target_value'] > 0.00) ? $row['inr_target_value'] : 1;
            $targetValueInr = ($row['targetRate']*$inr_target_value)*$row['amountinFC'];
            $lastquartersettamtoutwardsval = $row['Toatalallpayment'] + $row['AvgspotamountRate'];
            $data['last-quarter-settamttwo'] += $lastquartersettamtoutwardsval; 
            $data['last-quarter-setrateouttwo'] += $lastquartersettamtoutwardsval / $row['amountinFC'];
            $data['last-quarter-actualgainlosstwo'] += $lastquartersettamtoutwardsval - $targetValueInr;
        }
        foreach( $lastquarterportfoliovaluexp  as $row){
            $inr_target_value = ($row['inr_target_value'] > 0.00) ? $row['inr_target_value'] : 1;
            $targetValueInr = ($row['targetRate']*$inr_target_value)*$row['amountinFC'];
            $lastquartersettamtinwardsval = $row['Toatalallpayment'] + $row['AvgspotamountRate'];
            $data['last-quarter-settamtone'] += $lastquartersettamtinwardsval; 
            $data['last-quarter-setrateone'] += $lastquartersettamtinwardsval / $row['amountinFC'];
            $data['last-quarter-actualgainlossone'] += $lastquartersettamtinwardsval - $targetValueInr;
        }
        return $data;
        }
        
    }


    // cyrrency performance
    public function currencyperformance(){
        $jsonResponse = json_encode([
            "status" => 1,
            "Today" => [
                "Open" => "57.3525",
                "High" => "58.2888",
                "Low" => "7.01"
            ],
            "Yesterday" => [
                "Open" => "50.3525",
                "High" => "52.2888",
                "Low" => "76.01"
            ],
            "ThisWeek" => [
                "Open" => "72.3525",
                "High" => "83.2888",
                "Low" => "77.01"
            ],
            "ThisMonth" => [
                "Open" => "12.3525",
                "High" => "23.2888",
                "Low" => "67.01"
            ],
            "ThisQuarter" => [
                "Open" => "82.3525",
                "High" => "03.2888",
                "Low" => "107.01"
            ],
        ]);
        return $jsonResponse;
    }

    // Quarter details
    public function quaterdetails($curid = '',  $curren = ''){
            $data["current-portfolio-valueone"] = 0;
            $data["last-portfolio-valueone"] = 0;
            $data["current-portfolio-valuetwo"] = 0;
            $data["last-portfolio-valuetwo"] = 0;
            $data["current-quarter-avghedgetwo"] = 0;
            $data["current-quarter-avgavgtargettwo"] = 0;
            $data["current-quarter-totalexposuretwo"] = 0;
            $data["current-quarter-percentagehedgedtwo"] = 0;
            $data["last-quarter-avghedgetwo"] = 0;
            $data["last-quarter-avgavgtargettwo"] = 0;
            $data["last-quarter-totalexposuretwo"] = 0;
            $data["last-quarter-percentagehedgedtwo"] = 0;
            $data["current-quarter-avghedgeone"] = 0;
            $data["current-quarter-totalexposureone"] = 0;
            $data["current-quarter-percentagehedgedone"] = 0;
            $data["current-quarter-avgavgtargetone"] = 0;
            $data["last-quarter-avghedgeone"] = 0;
            $data["last-quarter-totalexposureone"] = 0;
            $data["last-quarter-percentagehedgedone"] = 0;
            $data["last-quarter-avgavgtargetone"] = 0;


            $querycurrentquarter = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->where('transactiondetails.exposureType !=', 1)
            ->where('transactiondetails.currency', $curid)
            ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(CURDATE())', false)
            ->get();

   

            if (is_object($querycurrentquarter)) {
                $currentquarterexpodetimp = $querycurrentquarter->getResultArray();
                }

                if(isset($currentquarterexpodetimp)){
           
                    foreach( $currentquarterexpodetimp  as $row){
                        $data['current-quarter-avghedgetwo'] += $row['avghedge'];
                        $data['current-quarter-avgavgtargettwo'] += $row['avgtarget'];
                        $data['current-quarter-totalexposuretwo'] += $row['totalexposure'];
                        $data['current-quarter-percentagehedgedtwo'] += $this->exposoredethedgecalc($row['sum_amount_FC'], $row['totalexposure']);
                    }
                }


            $querlastquarter = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->where('transactiondetails.exposureType !=', 1)
            ->where('transactiondetails.currency', $curid)
            ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(DATE_SUB(CURDATE(), INTERVAL 1 QUARTER))')
            ->get();

            if (is_object($querlastquarter)) {
            $lastquarterexpodetimp = $querlastquarter->getResultArray();
            }

                if(isset($lastquarterexpodetimp)){
                foreach( $lastquarterexpodetimp  as $row){
                $data['last-quarter-avghedgetwo'] += $row['avghedge'];
                $data['last-quarter-avgavgtargettwo'] += $row['avgtarget'];
                $data['last-quarter-totalexposuretwo'] += $row['totalexposure'];
                $data['last-quarter-percentagehedgedtwo'] += $this->exposoredethedgecalc($row['sum_amount_FC'], $row['totalexposure']);
                }
                }

            $querexpodetexp = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('transactiondetails.currency', $curid)
            ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(CURDATE())', false)
            ->get();

            if (is_object($querexpodetexp)) {
                $currentquarterexpodetexp = $querexpodetexp->getResultArray();
                }

                if(isset($currentquarterexpodetexp)){
                foreach( $currentquarterexpodetexp  as $row){
                $data['current-quarter-avghedgeone'] += $row['avghedge'];
                $data['current-quarter-avgavgtargetone'] += $row['avgtarget'];
                $data['current-quarter-totalexposureone'] += $row['totalexposure'];
                $data['current-quarter-percentagehedgedone'] += $this->exposoredethedgecalc($row['sum_amount_FC'], $row['totalexposure']);
                }
                }

            $querylastquarter = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('transactiondetails.currency')
            ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(DATE_SUB(CURDATE(), INTERVAL 1 QUARTER))')
            ->get();

            if (is_object($querylastquarter)) {
                $lastquarterexpodexp = $querylastquarter->getResultArray();
                }

                if(isset($lastquarterexpodexp)){
                    foreach($lastquarterexpodexp  as $row){
                        $data['last-quarter-avghedgeone'] += $row['avghedge'];
                        $data['last-quarter-avgavgtargetone'] += $row['avgtarget'];
                        $data['last-quarter-totalexposureone'] += $row['totalexposure'];
                        $data['last-quarter-percentagehedgedone'] += $this->exposoredethedgecalc($row['sum_amount_FC'], $row['totalexposure']);
                    }
                }

           
      
            $currentportfoliovalueexp = $this->transaction_model->quarterportfoliovalueexp($curren['Currency']);

            foreach ($currentportfoliovalueexp as $row) {
                $resoval = $this->forrwardCalculator(1, $curid, $row['dueDate']);
                $data["current-portfolio-valueone"] += $this->quarterdetailscalc($resoval, $row['inr_target_value'], $row['amountinFC'], $row['targetRate'], $row['open_amount'], $row['isSettled'], $row['ToatalforwardAmount'], $row['AvgspotamountRate'], $row['Toatalallpayment'], $row['Avgrate']);
            }


            $lastportfoliovalueexp = $this->transaction_model->lastquarterportfoliovalueexp($curren['Currency']);
            foreach ($lastportfoliovalueexp as $row) {
                $resoval = $this->forrwardCalculator(1, $curid, $row['dueDate']);
                $data["last-portfolio-valueone"] += $this->quarterdetailscalc($resoval, $row['inr_target_value'], $row['amountinFC'], $row['targetRate'], $row['open_amount'], $row['isSettled'], $row['ToatalforwardAmount'], $row['AvgspotamountRate'], $row['Toatalallpayment'], $row['Avgrate']);
            }

            $currentportfoliovalueimp = $this->transaction_model->quarterportfoliovalueimp($curren['Currency']);

            foreach ($currentportfoliovalueimp as $row) {
                $resoval = $this->forrwardCalculator(2, $curid, $row['dueDate']);
                $data["current-portfolio-valuetwo"] += $this->quarterdetailscalc($resoval, $row['inr_target_value'], $row['amountinFC'], $row['targetRate'], $row['open_amount'], $row['isSettled'], $row['ToatalforwardAmount'], $row['AvgspotamountRate'], $row['Toatalallpayment'], $row['Avgrate']);
            }

            $lastportfoliovalueimp = $this->transaction_model->lastquarterportfoliovalueimp($curren['Currency']);

            foreach ($lastportfoliovalueimp as $row) {
                $resoval = $this->forrwardCalculator(2, $curid, $row['dueDate']);
                $data["last-portfolio-valuetwo"] += $this->quarterdetailscalc($resoval, $row['inr_target_value'], $row['amountinFC'], $row['targetRate'], $row['open_amount'], $row['isSettled'], $row['ToatalforwardAmount'], $row['AvgspotamountRate'], $row['Toatalallpayment'], $row['Avgrate']);
                
            }

            return $data;
    }



    public function quarterdetailscalc($resoval, $inr_target_value, $amountinFC, $targetRate, $open_amount, $isSettled, $ToatalforwardAmount, $AvgspotamountRate, $Toatalallpayment, $Avgrate)
{
    $crntfrrate = json_decode($resoval);
    $currentForwardRate = isset($crntfrrate->result->forward_rate) ? $crntfrrate->result->forward_rate : 1;
    $currencyinrSpotdRate = isset($crntfrrate->result->spot_rate) ? $crntfrrate->result->spot_rate : 1;
    $inr_target_value = ($inr_target_value > 0.00) ? $inr_target_value : 1;
    $targetValueInr = ($targetRate * $inr_target_value) * $amountinFC;
    $openAmountFC = isset($isSettled) && !empty($isSettled) ? $open_amount : ($amountinFC - $ToatalforwardAmount);
    $openAmountINR = $openAmountFC * ($currentForwardRate * $currencyinrSpotdRate);
    $currentportfoliovalue = isset($isSettled) && !empty($isSettled) ? ($AvgspotamountRate + $Toatalallpayment) : ($openAmountINR + ($ToatalforwardAmount * $Avgrate));
    return $currentportfoliovalue;
}


    // Current Month Details

    public function currentmonthdetails($curid = ''){
        $data['avghedgetwo'] = 0;
        $data['avgtargettwo'] = 0;
        $data['currentmonthtotalexposuretwo'] = 0;
        $data['currentmonthpercentagehedgedtwo'] = 0;
        $data['avghedgeone'] = 0;
        $data['avgtargeone'] = 0;
        $data['currentmonthtotalexposureone'] = 0;
        $data['currentmonthpercentagehedgedone'] = 0;

        $querycurrentimp = $this->transaction_model
        ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
        ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
        ->where('transactiondetails.exposureType !=', 1)
        ->where('transactiondetails.currency', $curid)
        ->where('MONTH(transactiondetails.dueDate)', date('n'))
        ->get();

        if (is_object($querycurrentimp)) {
        $currentmonthexpodetimp = $querycurrentimp->getResultArray();
        }

        if(isset($currentmonthexpodetimp)){
        foreach( $currentmonthexpodetimp  as $row){
            $data['avghedgetwo'] += $row['avghedge'];
            $data['avgtargettwo'] += $row['avgtarget'];
            $data['currentmonthtotalexposuretwo'] += $row['totalexposure'];
            $data['currentmonthpercentagehedgedtwo'] += $this->exposoredethedgecalc($row['sum_amount_FC'], $row['totalexposure']);
        }

        $querycurrentexp = $this->transaction_model
        ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
        ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
        ->where('transactiondetails.exposureType', 1)
        ->where('transactiondetails.currency', $curid)
        ->where('MONTH(transactiondetails.dueDate)', date('n'))
        ->findAll();

        if (is_object($querycurrentexp)) {
        $currentmonthexpodetexp = $querycurrentexp->getResultArray();
        }

        if(isset($currentmonthexpodetexp)){
            foreach( $currentmonthexpodetexp  as $row){
                $data['avghedgeone'] += $row['avghedge'];
                $data['avgtargeone'] += $row['avgtarget'];
                $data['currentmonthtotalexposureone'] += $row['totalexposure'];
                $data['currentmonthpercentagehedgedone'] += $this->exposoredethedgecalc($row['sum_amount_FC'], $row['totalexposure']);
            }
        }
    }
    return $data;

    }

    // exposuredetails

    public function exposuredetails($curid = '', $curren= ''){
        $data['percentagehedgedtwo'] = 0;
        $data['totalexposuretwo'] = 0;
        $data['percentagehedgedone'] = 0;
        $data['totalexposureone'] = 0;
        $data['avghedgetwo'] = 0;
        $data['avghedgeone'] = 0;
        $data['avgtargettwo'] = 0;
        $data['avgtargetone'] = 0;
        $data["currentportfoliovaluetwo"] = 0; 
        $data["currentportfoliovalueone"] = 0; 
        $data["currentganorlosetwo"] = 0;
        $data["currentganorloseone"] = 0;

        $queryhedgedoutwards = $this->transaction_model
        ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
        ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
        ->where('transactiondetails.exposureType !=', 1)
        ->where('transactiondetails.currency', $curid)
        ->get();

        if (is_object($queryhedgedoutwards)) {
            $percentagehedgedoutwards = $queryhedgedoutwards->getResultArray();
            }

            if(isset($percentagehedgedoutwards)){
            foreach( $percentagehedgedoutwards  as $row){
            $data['totalexposuretwo'] += $row['totalexposure'];
            $data['avghedgetwo'] += $row['avghedge'];
            $data['avgtargettwo'] += $row['avgtarget'];
            $data['percentagehedgedtwo'] += $this->exposoredethedgecalc($row['sum_amount_FC'], $row['totalexposure']);
            }
            }


            $queryhedgedinwards = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('transactiondetails.currency', $curid)
            ->get();

            if (is_object($queryhedgedinwards)) {
                $percentagehedgedinwards = $queryhedgedinwards->getResultArray();
                }

            if(isset($percentagehedgedinwards)){
            foreach( $percentagehedgedinwards  as $row){
                $data['avghedgeone'] += $row['avghedge'];
                $data['avgtargetone'] += $row['avgtarget'];
                $data['totalexposureone'] += $row['totalexposure'];
                $data['percentagehedgedone'] += $this->exposoredethedgecalc($row['sum_amount_FC'], $row['totalexposure']);
            }
        }

        

        $currentportfoliovalueimp = $this->transaction_model->currentportfoliovalueimp($curren['Currency']);
        if(isset($currentportfoliovalueimp)){
            foreach ($currentportfoliovalueimp as $row) {
                $resoval = $this->forrwardCalculator(2, $curid, $row['dueDate']);
                $curdata = $this->calculateportfoliovalue($resoval, $row['inr_target_value'], $row['targetRate'], $row['open_amount'], $row['amountinFC'], $row['ToatalforwardAmount'], $row['Toatalallpayment'], $row['Avgrate'], $row['isSettled'], $row['AvgspotamountRate']);
                $data["currentportfoliovaluetwo"] += $curdata['currentportfoliovalue']; // Sum the value in each iteration
                $data["currentganorlosetwo"] += $curdata['currentganorlose']; // Sum the value in each iteration
            }
        }

        $currentportfoliovalueexp = $this->transaction_model->currentportfoliovalueexp($curren['Currency']);
        if(isset($currentportfoliovalueexp)){
        foreach ($currentportfoliovalueexp as $row) {
            $resoval = $this->forrwardCalculator(1, $curid, $row['dueDate']);
            $curdata = $this->calculateportfoliovalue($resoval, $row['inr_target_value'], $row['targetRate'], $row['open_amount'], $row['amountinFC'], $row['ToatalforwardAmount'], $row['Toatalallpayment'], $row['Avgrate'], $row['isSettled'], $row['AvgspotamountRate']);
            $data["currentportfoliovalueone"] += $curdata['currentportfoliovalue'];
            $data["currentganorloseone"] += $curdata['currentganorlose'];
        }
    }
    return $data;
    }

    public function calculateportfoliovalue($resoval, $inr_target_value, $targetRate, $open_amount, $amountinFC, $ToatalforwardAmount, $Toatalallpayment, $Avgrate, $isSettled, $AvgspotamountRate){
        $crntfrrate = json_decode($resoval);
        $currentForwardRate = isset($crntfrrate->result->forward_rate) ?  $crntfrrate->result->forward_rate : 1;
        $currencyinrSpotdRate = isset($crntfrrate->result->spot_rate) ?  $crntfrrate->result->spot_rate : 1;
        $inr_target_value = ($inr_target_value > 0.00) ? $inr_target_value : 1;
        $targetValueInr = ($targetRate*$inr_target_value)*$amountinFC;
        $openAmountFC = isset($isSettled) && !empty($isSettled) ? $open_amount : ($amountinFC - $ToatalforwardAmount);
        $openAmountINR = $openAmountFC * ($currentForwardRate * $currencyinrSpotdRate);
        $currentportfoliovalueexpval = isset($isSettled) && !empty($isSettled) ? ($AvgspotamountRate + $Toatalallpayment) : ($openAmountINR + ($ToatalforwardAmount * $Avgrate));
        $data["currentportfoliovalue"] = $currentportfoliovalueexpval; // Sum the value in each iteration
        $data["currentganorlose"] = $currentportfoliovalueexpval - $targetValueInr; 
        return $data;
    }

        // dashboard total Details

        public function totaldetails($curid = ''){
        $data['totaloutwardsone'] = 0;
        $data['totalinwardsone'] = 0;
        $data['hedgeoutwardsone'] = 0;
        $data['hedgeinwardsone'] = 0;
        $data['totaloutwardstwo'] = 0;
        $data['hedgeoutwardstwo'] = 0;
        $data['totalinwardstwo'] = 0;
        $data['hedgeinwardstwo'] = 0;

        $querytotalout = $this->transaction_model
        ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
        ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
        ->where('transactiondetails.exposureType !=', 1)
        ->where('transactiondetails.currency', $curid)
        ->where('MONTH(transactiondetails.dueDate)', date('n'))
        ->get();

        if (is_object($querytotalout)) {
        $datatableout = $querytotalout->getResultArray();
        }

        $querytotalinw = $this->transaction_model
        ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
        ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
        ->where('transactiondetails.exposureType', 1)
        ->where('transactiondetails.currency', $curid)
        ->where('MONTH(transactiondetails.dueDate)', date('n'))
        ->get();

        if (is_object($querytotalinw)) {
        $datatableinw = $querytotalinw->getResultArray();
        }

        if(isset($datatableout)){
        foreach( $datatableout  as $row){
        $data['totaloutwardsone'] += $row['sum_amountinFC'];
        $data['hedgeoutwardsone'] += $this->calculatehedgeper($row['sum_amount_FC'], $row['sum_amountinFC']);
        }
        }

        if(isset($datatableinw)){
        foreach( $datatableinw  as $row){
        $data['totalinwardsone'] += $row['sum_amountinFC'];
        $data['hedgeinwardsone'] += $this->calculatehedgeper($row['sum_amount_FC'], $row['sum_amountinFC']);
        }
        }

        $querytertotaloutwards = $this->transaction_model
        ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
        ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
        ->where('transactiondetails.exposureType !=', 1)
        ->where('transactiondetails.currency', $curid)
        ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(CURDATE())', false)
        ->get();


        if (is_object($querytertotaloutwards)) {
        $currentquartertotaloutwards = $querytertotaloutwards->getResultArray();
        }

        if(isset($currentquartertotaloutwards)){
        foreach( $currentquartertotaloutwards  as $row){
        $data['totaloutwardstwo'] += $row['sum_amountinFC'];
        $data['hedgeoutwardstwo'] += $this->calculatehedgeper($row['sum_amount_FC'], $row['sum_amountinFC']);
        }
        }

        $querytertotalinwards = $this->transaction_model
        ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
        ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
        ->where('transactiondetails.exposureType', 1)
        ->where('transactiondetails.currency', $curid)
        ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(CURDATE())', false)
        ->get();

        if (is_object($querytertotalinwards)) {
        $currentquartertotalinwards = $querytertotalinwards->getResultArray();
        }

        if(isset($currentquartertotalinwards)){
        foreach( $currentquartertotalinwards  as $row){
        $data['totalinwardstwo'] += $row['sum_amountinFC'];
        $data['hedgeinwardstwo'] += $this->calculatehedgeper($row['sum_amount_FC'], $row['sum_amountinFC']);
        }
        }
        return $data;
        }




        public function forrwardCalculator($cover_type , $currency , $forward_date )
        {

        try{
        $date = date("Y-m-d", strtotime($forward_date));
        $curren = $this->currency_model->select("Currency")->where('currency_id', $currency)->first();
        $covertype = !empty($cover_type) && $cover_type == 1 ? 1 : 2;
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://fxmanagers.in/ajax/ajaxbroken',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array("cover_type" => $covertype,"currency" => $curren['Currency'] , "forward_date" => $date), 
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
        } catch (\Exception$e) {
        return "";
        }            
        }

        public function exposoredethedgecalc($sum_amount_FC, $totalexposure){
            $value =  isset($sum_amount_FC) && isset($totalexposure)  ? ($sum_amount_FC / $totalexposure) * 100 : 0;
            return $value;
        }

        public function calculatehedgeper($sum_amount_FC, $sum_amountinFC){
        $value = isset($sum_amount_FC) ? ($sum_amount_FC / $sum_amountinFC) * 100 : 0;
        return $value;
        }

    public function addrole()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }

        $this->loadUser();

        $role_id = $pot["role_id"];
        $data["view"] = "admin/roles";
        $data["page_title"] = "Add New Roles";
        $data["session"] = $session;
        if ($this->permission[0] > 0) {
            $data["link"] = "addnewroles";
        } else {
            $data["link"] = "#";
        }
        if ($this->permission[1] > 0) {
            $data["user_rolesedit"] = "user_rolesedit";
        } else {
            $data["user_rolesedit"] = "#";
        }
        if ($this->permission[2] > 0) {
            $data["user_delete"] = "user_delete";
        } else {
            $data["user_delete"] = "#";
        }
        // $data['breadcrumb'] = "<a href=User/$this->class_name>Roles</a> &nbsp;&nbsp; > &nbsp;&nbsp; Add Role";
        $data["page_heading"] = "Add  Roles";
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["q"] = $this->admin_users_model->findroles($role_id);
        $data["roles"] = $this->admin_roles_model
            ->orderBy("role_id", "ASCE")
            ->findAll();
        return view('templates/default', $data);
    }

    public function addnewroles()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $this->loadUser();
        $data["session"] = $session;
        $data["page_title"] = "Add New Roles";
        $data["page_heading"] = "Add New Users";
        $data["request"] = $this->request;
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["view"] = "admin/addnewroles";
        return view('templates/default', $data);
    }

        public function approvelogin($id = "")
        {
            if ($id == null) {
            return redirect("Admindashboard");
            }
            $session = session();
            $pot = json_decode(json_encode($session->get("userdata")), true);
            if (empty($pot)) {
            return redirect()->to("/");
            }
            $udata["ssjl_isapproved"] = 1;
            $update = $this->introductionform_model
            ->where("ssjl_into_id", $id)
            ->set($udata)
            ->update();
            if ($update) {
            $session->setFlashdata(
            "success",
            "Approved Successfully!!"
            );
            return redirect()->to("approvedemployees");
            } else {
            $session->setFlashdata("error", "Failed To Approve");
            return redirect()->to("isapproved");
            }
        }


        public function disapprovelogin($id = "")
        {
            if ($id == null) {
            return redirect("Admindashboard");
            }
            $session = session();
            $pot = json_decode(json_encode($session->get("userdata")), true);
            if (empty($pot)) {
            return redirect()->to("/");
            }
            $udata["ssjl_isapproved"] = 0;
            $update = $this->introductionform_model
            ->where("ssjl_into_id", $id)
            ->set($udata)
            ->update();
            if ($update) {
            $session->setFlashdata(
            "success",
            "Disapproved Successfully!!"
            );
            return redirect()->to("isapproved");
            } else {
            $session->setFlashdata("error", "Failed To Disapprove");
            return redirect()->to("approvedemployees");
            }
        }

    public function user_rolesedit($id = "")
    {
        if ($id == null) {
            return redirect("Admindashboard");
        }
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data["userInfo"] = $this->admin_roles_model
            ->where("role_id= '{$id}'")
            ->findAll();
        $this->loadUser();
        $data["session"] = $session;
        $data["page_title"] = "Edit New Roles";
        $data["page_heading"] = "Edit New Users";
        $data["request"] = $this->request;
        $data["query"] = $this->admin_roles_model->get_row($id);
        $data["roles"] = $this->admin_roles_model
            ->orderBy("role_id", "DESC")
            ->findAll();
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["view"] = "admin/editnewroles";
        return view('templates/default', $data);
    }

    public function savenewroles()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        // echo '<pre>';
        // print_r($pot['user_id'] );
        // exit;

        $input = $this->validate([
            "role_name" => "required|min_length[3]",
            "status_ind" => "required",
        ]);

        if (!empty($input)) {
            if ($this->request->getMethod() == "post") {
                extract($this->request->getPost());
                if (!preg_match('/^[a-zA-Z_ ]*$/', $role_name)) {
                    $session->setFlashdata("error", "Special characters and Numbers are not allowed");
                    return redirect()->to("addnewroles");
                }
                $udata = [];
                $udata["role_name"] = $role_name;
                $udata["status_ind"] = $status_ind;
                $udata["created_date"] = date("Y-m-d");
                $udata["created_by"] = $pot["user_id"];
                $udata["last_modified_by"] = $pot["user_id"];
                // echo '<pre>';
                // print_r($udata);
                // exit;
                $save = $this->admin_roles_model->save($udata);
                if ($save) {
                    $session->setFlashdata("success", "Saved Successfully");
                    return redirect()->to("addnewroles");
                } else {
                    $session->setFlashdata("error", "Failed to save");
                    return redirect()->to("addnewroles");
                }
            }
        } else {
            $session->setFlashdata("error", "Enter All Fields");
            return redirect()->to("addnewroles");
        }
    }

    public function editnewroles()
    {

        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        // echo '<pre>';
        // print_r($pot['user_id'] );
        // exit;
        $input = $this->validate([
            "role_name" => "required|min_length[3]",
            "status_ind" => "required",
        ]);

        if (!empty($input)) {
            if ($this->request->getMethod() == "post") {
                extract($this->request->getPost());

                if (!empty($user_id_hidd)) {
                    $udata = [];
                    $udata["role_name"] = $role_name;
                    $udata["status_ind"] = $status_ind;
                    $udata["modified_date"] = date("Y-m-d");
                    $udata["modified_by"] = $pot["user_id"];

                    $update = $this->admin_roles_model
                        ->where("role_id", $user_id_hidd)
                        ->set($udata)
                        ->update();

                    //     echo '<pre>';
                    //     print_r($update);
                    //     exit;
                    if ($update) {
                        $session->setFlashdata(
                            "success",
                            "Updated Successfully!!"
                        );
                        return redirect()->to("user_rolesedit/$user_id_hidd");
                    } else {
                        $session->setFlashdata("success", "Failed To Update");
                        return redirect()->to("user_rolesedit/$user_id_hidd");
                    }
                }
            }
        } else {
            $session->setFlashdata("error", "Enter All Fields");
            return redirect()->to("user_rolesedit/$user_id_hidd");
        }
    }

    public function user_delete($id = "")
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }

        if (empty($id)) {
            $this->session->setFlashdata(
                "error",
                "user Deletion failed due to unknown ID."
            );
            return redirect()->to("Admindashboard");
        }
        $delete = $this->admin_users_model->where("user_id", $id)->delete();
        if ($delete) {
            $session->setFlashdata(
                "success",
                "User has been deleted successfully."
            );
        } else {
            $session->setFlashdata(
                "error",
                "User Deletion failed due to unknown ID."
            );
        }

        return redirect()->to("addemployee");
    }

    public function access($id = "")
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if (empty($id)) {
            $this->session->setFlashdata(
                "error",
                "user Access failed due to unknown ID."
            );
            return redirect()->to("Admindashboard");
        }
        $this->loadUser();
        $accesses = [];
        $data["query"] = $this->admin_menuitems_model->view();
        $roles_accesses = $this->admin_roles_accesses_model->view($id);
        foreach ($roles_accesses as $row) {
            $accesses[] = $row->menuitem_id;
        }
        $data["session"] = $session;
        $data["title"] = "Administrator Dashboard - ";
        $data["request"] = $this->request;
        $data["role_id"] = $id;
        $data["admin_users_accesses"] = $accesses;
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["view"] = "admin/access";
        return view('templates/default', $data);
    }

    public function saveaccess()
    {
        $session = session();
        $request = \Config\Services::request();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        } else {
            $user_id = $pot["user_id"];
            $role_id = $pot["role_id"];
        }
        $input = $this->validate([
            "menuitem_id" => "required",
        ]);

        if (!empty($input)) {

            if ($user_id == 1 || $user_id == 2) {
                if ($this->request->getMethod() == "post") {
                    extract($this->request->getPost());
                    $status = true;
                    $role_id = $request->getPost("role_id");

                    $this->admin_roles_accesses_model->primary_key = [
                        "role_id" => $role_id,
                    ];
                    $delete = $this->admin_roles_accesses_model
                        ->where("role_id", $role_id)
                        ->delete();
                    if ($delete) {
                        $menuitem_ids = $request->getPost("menuitem_id");

                        foreach ($menuitem_ids as $menuitem_id) {
                            $data = [
                                "menuitem_id" => $menuitem_id,
                                "role_id" => $role_id,
                            ];

                            $save = $this->admin_roles_accesses_model->save($data);
                            if ($save) {
                                $status = true;
                            }
                        }
                    }

                    if ($status) {
                        $this->session->setFlashdata("success", "user Access saved.");
                    } else {
                        $this->session->setFlashdata(
                            "error",
                            "user Access failed due to unknown ID."
                        );
                    }
                }
            } else {
                $this->session->setFlashdata(
                    "error",
                    "Sorry! You do not have the permission."
                );
            }
        } else {
            if ($this->request->getMethod() == "post") {
                extract($this->request->getPost());
                $delete = $this->admin_roles_accesses_model
                    ->where("role_id", $role_id)
                    ->delete();
            }
            $this->session->setFlashdata("success", "user Access saved.");
        }
        //     $this->session->set_flashdata('msg', $msg);
        return redirect()->to("Admindashboard");
    }

    // permission

    public function permission($id)
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if (empty($id)) {
            $this->session->setFlashdata(
                "error",
                "user Permission failed due to unknown ID."
            );
            return redirect()->to("Admindashboard");
        }
        $this->loadUser();

        if (!empty($id)) {
            $accesses = [];
            $roles_accesses = $this->admin_roles_accesses_model->view_access($id);
            foreach ($roles_accesses as $row) {
                $accesses[] = $row->menuitem_id;
            }
            $data["session"] = $session;
            $data["role_id"] = $id;
            $data["query"] = $roles_accesses; //$_SESSION['sidebar_menuitems'];
            $data["title"] = "Role Access  Permission ";
            $data["page_heading"] = "Role Access Permission";
            $data["request"] = $this->request;
            $data["menuslinks"] = $this->request->uri->getSegment(1);
            $data["view"] = "admin/permission";
            return view('templates/default', $data);
        } else {

            $this->session->setFlashdata(
                "error",
                "Sorry! You do not have the permission."
            );
            return redirect()->to("Admindashboard");
        }
    }

    // savepermission

    public function savepermission()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }

        $request = \Config\Services::request();
        $this->loadUser();
        if (empty($pot)) {
            return redirect()->to("/");
        } else {
            $user_id = $pot["user_id"];
            $role_id = $pot["role_id"];
        }

        if ($user_id == 1 || $role_id == 2) {
            $status = true;
            $role_id = $request->getPost("role_id");
            $i = 0;
            $menuitem_ids = $request->getPost("menuitem_id");

            foreach ($menuitem_ids as $menuitem_id) {
                $add_permission = $request->getPost("add_permission");

                if (!empty($request->getPost("add_permission")[$i])) {
                    // echo '<pre>';
                    // print_r($add_permission[$i]);
                    // exit;
                    $add_permission = $request->getPost("add_permission")[$i];
                } else {
                    $add_permission = 0;
                }
                if (!empty($request->getPost("edit_permission")[$i])) {
                    $edit_permission = $request->getPost("edit_permission")[$i];
                } else {
                    $edit_permission = 0;
                }
                if (!empty($request->getPost("delete_permission")[$i])) {
                    $delete_permission = $request->getPost("delete_permission")[$i];
                } else {
                    $delete_permission = 0;
                }
                $udata["add_permission"] = $add_permission;
                $udata["role_id"] = $role_id;
                $udata["edit_permission"] = $edit_permission;
                $udata["delete_permission"] = $delete_permission;

                $update = $this->admin_roles_accesses_model
                    ->where("menuitem_id", $menuitem_id)
                    ->where("role_id", $role_id)
                    ->set($udata)
                    ->update();

                if ($update) {
                    $status = true;
                    $udata = [];
                }
                $i++;
            }

            if ($status) {
                $this->session->setFlashdata("success", "user Permission saved.");
                return redirect()->to("Admindashboard");
            } else {
                $this->session->setFlashdata("error", "user Permission failed saved.");
                return redirect()->to("Admindashboard");
            }
        } else {
            $this->session->setFlashdata("error", "Sorry! You do not have the permission.");
            return redirect()->to("Admindashboard");
        }
    }

    public function addemployee()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data["user_data"] = [];
        $this->loadUser();
        $data["session"] = $session;
        $data["title"] = "Administrator Dashboard - ";
        // $data['breadcrumb'] = "<a href=User/$this->class_name>Roles</a> &nbsp;&nbsp; > &nbsp;&nbsp; Add Role";
        $data["page_heading"] = "Add New User";
        if ($this->permission[0] > 0) {
            $data["link"] = "addNew";
        } else {
            $data["link"] = "#";
        }
        if ($this->permission[1] > 0) {
            $data["user_edit"] = "user_edit";
        } else {
            $data["user_edit"] = "#";
        }
        if ($this->permission[2] > 0) {
            $data["user_delete"] = "user_delete";
        } else {
            $data["user_delete"] = "#";
        }
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["users"] = $this->admin_users_model
            ->orderBy("user_id", "ASCE")
            ->findAll();
        $data["view"] = "admin/addusers";
        return view('templates/default', $data);
    }

    // Approve User

     public function isapproved()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data["user_data"] = [];
        $this->loadUser();
        $data["session"] = $session;
        $data["title"] = "Administrator Dashboard - ";
        // $data['breadcrumb'] = "<a href=User/$this->class_name>Roles</a> &nbsp;&nbsp; > &nbsp;&nbsp; Add Role";
        $data["page_heading"] = "Approve Login";
        if ($this->permission[0] > 0) {
            $data["link"] = "#";
        } else {
            $data["link"] = "#";
        }
        if ($this->permission[1] > 0) {
            $data["user_view"] = "view_details";
        } else {
            $data["user_view"] = "#";
        }
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["users"] = $this->introductionform_model->where("ssjl_isapproved", 0)
            ->orderBy("ssjl_into_id", "DESC")
            ->findAll();
        $data["view"] = "admin/approval";
        return view('templates/default', $data);
    }

     public function approvedemployees()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data["user_data"] = [];
        $this->loadUser();
        $data["session"] = $session;
        $data["title"] = "Administrator Dashboard - ";
        // $data['breadcrumb'] = "<a href=User/$this->class_name>Roles</a> &nbsp;&nbsp; > &nbsp;&nbsp; Add Role";
        $data["page_heading"] = "Approved Employees";
        if ($this->permission[0] > 0) {
            $data["link"] = "#";
        } else {
            $data["link"] = "#";
        }
        if ($this->permission[1] > 0) {
            $data["user_view"] = "view_details";
        } else {
            $data["user_view"] = "#";
        }
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["users"] = $this->introductionform_model->where("ssjl_isapproved", 1)
            ->orderBy("ssjl_into_id", "DESC")
            ->findAll();

        $data["view"] = "admin/approvedemployees";
        return view('templates/default', $data);
    }

    // View User
     public function view_details($id = "", $link="", $text="", $class="")
    {
        
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if ($id == null) {
            return redirect("Admindashboard");
        } else {
            $data["query"] = $this->introductionform_model
                ->where("ssjl_into_id= '{$id}'")
                ->first();
            $this->loadUser();
            $this->global["pageTitle"] = "View Details";
            $session = session();
            $data["title"] = "View Details";
            $data["session"] = $session;
            $data["link"] = $link;
            $data["text"] = $text;
             $data["class"] = $class;
            $data["page_heading"] = "View Details";
            $data["request"] = $this->request;
            $data["menuslinks"] = $this->request->uri->getSegment(1);
            $data["view"] = "admin/view_details";
            return view('templates/default', $data);
        }
    }




    // add new user

    public function addNew()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data["user_data"] = [];
        $this->loadUser();
        $data["session"] = $session;
        $data["pade_title"] = "Admin New User";
        $data["link"] = "addNew";
        $data["roles"] = $this->admin_roles_model
            ->where('status_ind', 1)
            ->orderBy("role_id", "DESC")
            ->findAll();
        $data["page_heading"] = "Add New Users";
        $data["request"] = $this->request;
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["view"] = "admin/addnewusers";
        return view('templates/default', $data);
    }

    public function addnewuser()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        helper(["form", "url", "string"]);
        $input = $this->validate([
            "first_name" => "required",
            "user_name" => "required",
            "role_id" => "required",
            "password" => "required",
        ]);

        if (!empty($input)) {

            if ($this->request->getMethod() == "post") {
                extract($this->request->getPost());
                $udata = [];
                $udata["first_name"] = $first_name;
                $udata["email"] = $email;
                $udata["role_id"] = $role_id;
                 $udata["user_name"] = $user_name;
                $udata["employee_id"] = $user_name;
                $udata["created_date"] = date("Y-m-d");
                $udata["created_by"] = $pot["user_id"];
                $udata["last_active"] = date("Y-m-d");
                if (!empty($password)) {
                    $udata["password"] = md5($password);
                }
                $checkMail = $this->admin_users_model
                    ->where("user_name", $user_name)
                    ->countAllResults();
                $checkEmployee_id = $this->admin_users_model
                    ->where("employee_id", $udata["employee_id"])
                    ->countAllResults();
                if ($checkMail > 0 || $checkEmployee_id > 0) {
                    $this->session->setFlashdata(
                        "error",
                        "PAN No Already Taken."
                    );
                } else {
                    $save = $this->admin_users_model->save($udata);
                    // for seller details company table
                    if ($save) {
                        $session->setFlashdata(
                            "success",
                            "Saved Successfully"
                        );
                        return redirect()->to("addNew");
                    } else {
                        $session->setFlashdata(
                            "error",
                            "User Details has failed to save."
                        );
                        return redirect()->to("addNew");
                    }
                }
            }
            // $session->setFlashdata('success', 'All Fine');
        } else {
            $session->setFlashdata("error", "Enter All Fields");
            return redirect()->to("addNew");
        }

        //return view('Modules\Admin\Views\pages\addnew', $data);
        // return view('pages/users/add', $this->data);
    }

    // useredit

    public function user_edit($id = "")
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if ($id == null) {
            return redirect("Admindashboard");
        } else {
            $data["userInfo"] = $this->admin_users_model
                ->where("user_id= '{$id}'")
                ->findAll();
            $this->loadUser();
            $this->global["pageTitle"] = "Edit User";
            $session = session();
            $data["title"] = "Edit User";
            $data["query"] = $this->admin_users_model->get_row($id);

            $data["roles"] = $this->admin_roles_model
                ->orderBy("role_id", "DESC")
                ->findAll();
            $data["session"] = $session;
            // $data['breadcrumb'] = "<a href=User/$this->class_name>Roles</a> &nbsp;&nbsp; > &nbsp;&nbsp; Add Role";
            $data["page_heading"] = "edit  Users";
            $data["request"] = $this->request;
            $data["menuslinks"] = $this->request->uri->getSegment(1);
            $returnArr = [];
            foreach ($data["userInfo"] as $k => $v) {
                $returnArr = array_merge($returnArr, $v);
            }
            $a = (object) $returnArr;
            $data["view"] = "admin/editnew";
            return view('templates/default', $data);
        }
    }

    // edit new user

    public function editnewuser()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }

        $sellername = 'Seller';
        $sellerId = $this->admin_roles_model->getsellerid($sellername);
        $input = $this->validate([
            "first_name" => "required",
            "email" => "required",
            "role_id" => "required",
             "user_name" => "required",
        ]);

        if (!empty($input)) {

            if ($this->request->getMethod() == "post") {
                extract($this->request->getPost());

                if (!empty($user_id_hidd)) {
                    $udata = [];
                    $udata["first_name"] = $first_name;
                    $udata["role_id"] = $role_id;
                    $udata["email"] = $email;
                    $udata["created_date"] = date("Y-m-d");
                    $udata["created_by"] = $pot["user_id"];
                    $udata["user_name"] = $user_name;
                    if (!empty($password)) {
                        $udata["password"] = md5($password);
                    }

                    $checkMail = $this->admin_users_model
                        ->where("user_name", $user_name)
                        ->where("user_id!=", $user_id_hidd)
                        ->countAllResults();

                    if ($checkMail > 0) {
                        $session->setFlashdata(
                            "error",
                            "PAN No Already Taken."
                        );
                        return redirect()->to("user_edit/$user_id_hidd");
                    } else {
                        $update = $this->admin_users_model
                            ->where("user_id", $user_id_hidd)
                            ->set($udata)
                            ->update();

                        if ($update) {
                            $session->setFlashdata(
                                "success",
                                "Updated Successfully"
                            );
                            return redirect()->to("user_edit/$user_id_hidd");
                        } else {
                            $session->setFlashdata(
                                "error",
                                "Failed to Update"
                            );
                            return redirect()->to("user_edit/$user_id_hidd");
                        }
                    }
                }
            }

            // $session->setFlashdata('success', 'All Fine');
        } else {
            $session->setFlashdata("error", "Enter All Fields");
            return redirect()->to("addNew");
        }
    }

    public function guestlist()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data["session"] = $session;
        $data['page_heading'] = "Guest List";
        $data['link'] = "addguestlist";
        $data["breadcrumb"] = "Admindashboard";
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["guest"] = $this->guest_model
            ->orderBy("guest_list_id", "DESC")
            ->findAll();
        $data['view'] = 'admin/guestlist';
        return view('templates/default', $data);
    }
    public function addguestlist()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $this->loadUser();
        $data["session"] = $session;
        $udata = [];
        if ($this->request->getMethod() == "post") {
            extract($this->request->getPost());
            if (!empty($guest_list_id)) {

                $udata["name"] = $guest_name;
                $udata["phone"] = $guest_phone_number;
                $udata["email"] = $guest_email;
                $udata["Ladies_Mehendi"] = $Ladies_Mehendi;
                $udata["no_of_guest"] = $no_of_guest;
                $udata["guest_comment"] = $guest_comment;
                $udata["Sangeet"] = $Sangeet;
                $udata["Tel_Baan"] = $Tel_Baan;
                $udata["Baraat_Wedding_Reception"] = $Baraat_Wedding_Reception;
                $udata["phone"] = $guest_phone_number;
                $udata["created_at"] = date("Y-m-d");
                $update = $this->guest_model
                    ->where("guest_list_id", $guest_list_id)
                    ->set($udata)
                    ->update();
                if ($update) {
                    $session->setFlashdata("success", "Updated Successfully!!");
                    return redirect()->to("guest_edit/$guest_list_id");
                } else {
                    $session->setFlashdata("success", "Failed To Update");
                    return redirect()->to("guest_edit/$guest_list_id");
                }
            } else {

                $udata["name"] = $guest_name;
                $udata["phone"] = $guest_phone_number;
                $udata["email"] = $guest_email;
                $udata["Ladies_Mehendi"] = $Ladies_Mehendi;
                $udata["no_of_guest"] = $no_of_guest;
                $udata["guest_comment"] = $guest_comment;
                $udata["Sangeet"] = $Sangeet;
                $udata["Tel_Baan"] = $Tel_Baan;
                $udata["Baraat_Wedding_Reception"] = $Baraat_Wedding_Reception;
                $udata["phone"] = $guest_phone_number;
                $udata["created_at"] = date("Y-m-d");

                $save = $this->guest_model->save($udata);
                if ($save) {
                    $session->setFlashdata("success", "Saved Successfully");
                    return redirect()->to("guestlist");
                } else {
                    $session->setFlashdata("error", "Failed to save");
                    return redirect()->to("guestlist");
                }
            }
        }

        $data["pade_title"] = "Admin New Guest";
        $data["link"] = "addGuest";
        $data["page_heading"] = "Add New Guest";
        $data["request"] = $this->request;
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["view"] = "admin/addnewguest";
        return view('templates/default', $data);
    }

    public function guest_edit($id = '')
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if ($id == null) {
            return redirect()->to("Admindashboard");
        } else {
            $guestinfo = $this->guest_model
                ->where("guest_list_id= '{$id}'")
                ->findAll();
            foreach ($guestinfo as $val) {
                $data["guestinfo"] = $val;
            }
            $this->loadUser();
            $data["pade_title"] = "Admin New Guest";
            $data["title"] = "Edit User";
            $data["session"] = $session;
            $data["page_heading"] = "edit  Users";
            $data["request"] = $this->request;
            $data["menuslinks"] = $this->request->uri->getSegment(1);
            $data["view"] = "admin/addnewguest";
            return view('templates/default', $data);
        }
    }

    public function guest_delete($id = '')
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }

        if (empty($id)) {
            $this->session->setFlashdata(
                "error",
                "Guest Deletion failed due to unknown ID."
            );
            return redirect()->to("guestlist");
        }
        $delete = $this->guest_model->where("guest_list_id", $id)->delete();
        if ($delete) {
            $session->setFlashdata(
                "success",
                "Guest has been deleted successfully."
            );
        } else {
            $session->setFlashdata(
                "error",
                "Guest Deletion failed due to unknown ID."
            );
        }
        return redirect()->to("guestlist");
    }
}

// echo '<pre>';
// print_r($data["guestinfo"]);
// exit;
