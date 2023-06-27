<?php

namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use App\Models\TransactionModel as Transaction_Model;
use App\Models\ExposureType as ExposureType_Model;
use App\Models\CurrencyModel as Currency_Model;
class Currencyapi extends ResourceController
{ 
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    // Methods for handling different HTTP methods (GET, POST, PUT, DELETE) will go here
    public function __construct()
    {
        parent::__construct();
        $this->transaction_model = new Transaction_Model();
		$this->exposuretype_model = new ExposureType_Model();
		$this->currency_model = new Currency_Model();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
       
        if(isset($_GET['currency'])){
            
            // Data for     1st part
                // current month
                $data['totaloutwardscurrentmonth'] = array();
                $data['totalinwardscurrentmonth'] = array();
                $data['hedgeoutwardscurrentmonth'] = array();
                $data['hedgeinwardscurrentmonth'] = array();
                $data['totalquartertotaloutwards'] = array();
                $data['hedgequartertotaloutwards'] = array();
                $data['totalquartertotalinwards'] = array();
                $data['hedgequartertotalinwards'] = array();

            $datatableout = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType !=', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('MONTH(transactiondetails.dueDate)', date('n'))
            ->findAll();

            $datatableinw = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('MONTH(transactiondetails.dueDate)', date('n'))
            ->findAll();

            foreach( $datatableout  as $row){
                $data['totaloutwardscurrentmonth'] = $row['sum_amountinFC'];
                $data['hedgeoutwardscurrentmonth'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['sum_amountinFC']) * 100 : '';
            }

            foreach( $datatableinw  as $row){
                $data['totalinwardscurrentmonth'] = $row['sum_amountinFC'];
                $data['hedgeinwardscurrentmonth'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['sum_amountinFC']) * 100 : '';
            }

            $currentquartertotaloutwards = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType !=', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(CURDATE())', false)
            ->findAll();

            foreach( $currentquartertotaloutwards  as $row){
                $data['totalquartertotaloutwards'] = $row['sum_amountinFC'];
                $data['hedgequartertotaloutwards'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['sum_amountinFC']) * 100 : '';
            }

            $currentquartertotalinwards = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(CURDATE())', false)
            ->findAll();

            foreach( $currentquartertotalinwards  as $row){
                $data['totalquartertotalinwards'] = $row['sum_amountinFC'];
                $data['hedgequartertotalinwards'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['sum_amountinFC']) * 100 : '';
            }


        
            return $this->respond($data, 200);
        }else{
            return $this->fail('No Currency Found !!');
        }

    }

    // Exposure Details
    public function exposuredetails(){
        if(isset($_GET['currency'])){
            $data['percentagehedgedoutwards'] = array();
            $data['totalexposureoutwards'] = array();
            $data['percentagehedgedinwards'] = array();
            $data['totalexposureinwards'] = array();
            $data['avghedgeoutwards'] = array();
            $data['avghedgeinwards'] = array();
            $data['avgavgtargetoutwards'] = array();
            $data['avgavgtargetinwards'] = array();
            $data["currentportfoliovalueimp"] = 0; 
            $data["currentportfoliovalueexp"] = 0; 
            $data["currentganorloseimp"] = 0;
            $data["currentganorloseexp"] = 0;
            $percentagehedgedoutwards = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType !=', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->findAll();
            foreach( $percentagehedgedoutwards  as $row){
                $data['totalexposureoutwards'] = $row['totalexposure'];
                $data['avghedgeoutwards'] = $row['avghedge'];
                $data['avgavgtargetoutwards'] = $row['avgtarget'];
                $data['percentagehedgedoutwards'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['totalexposure']) * 100 : '';
            }
            $percentagehedgedinwards = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->findAll();
            foreach( $percentagehedgedinwards  as $row){
                $data['avghedgeinwards'] = $row['avghedge'];
                $data['avgavgtargetinwards'] = $row['avgtarget'];
                $data['totalexposureinwards'] = $row['totalexposure'];
                $data['percentagehedgedinwards'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['totalexposure']) * 100 : '';
            }

            $currentportfoliovalueimp = $this->transaction_model->currentportfoliovalueimp($_GET['currency']);

            foreach ($currentportfoliovalueimp as $row) {
                $resoval = $this->forrwardCalculator(2, $_GET['currency'], $row['dueDate']);
                $crntfrrate = json_decode($resoval);
                $currentForwardRate = isset($crntfrrate->result->forward_rate) ?  $crntfrrate->result->forward_rate : 1;
                $currencyinrSpotdRate = isset($crntfrrate->result->spot_rate) ?  $crntfrrate->result->spot_rate : 1;
                $inr_target_value = ($row['inr_target_value'] > 0.00) ? $row['inr_target_value'] : 1;
                $targetValueInr = ($row['targetRate']*$inr_target_value)*$row['amountinFC'];
                $openAmountFC = isset($row['isSettled']) ? $row['open_amount'] : ($row['amountinFC'] - $row['ToatalforwardAmount']);
                $openAmountINR = $openAmountFC * ($currentForwardRate * $currencyinrSpotdRate);
                $currentportfoliovalueimpval = isset($row['isSettled']) ? ($row['AvgspotamountRate'] + $row['Toatalallpayment']) : ($openAmountINR + ($row['ToatalforwardAmount'] * $row['Avgrate']));
                $data["currentportfoliovalueimp"] += $currentportfoliovalueimpval; // Sum the value in each iteration
                $data["currentganorloseimp"] += $currentportfoliovalueimpval - $targetValueInr; // Sum the value in each iteration
            }

            $currentportfoliovalueexp = $this->transaction_model->currentportfoliovalueexp($_GET['currency']);
            foreach ($currentportfoliovalueexp as $row) {
                $resoval = $this->forrwardCalculator(1, $_GET['currency'], $row['dueDate']);
                $crntfrrate = json_decode($resoval);
                $currentForwardRate = isset($crntfrrate->result->forward_rate) ?  $crntfrrate->result->forward_rate : 1;
                $currencyinrSpotdRate = isset($crntfrrate->result->spot_rate) ?  $crntfrrate->result->spot_rate : 1;
                $inr_target_value = ($row['inr_target_value'] > 0.00) ? $row['inr_target_value'] : 1;
                $targetValueInr = ($row['targetRate']*$inr_target_value)*$row['amountinFC'];
                $openAmountFC = isset($row['isSettled']) ? $row['open_amount'] : ($row['amountinFC'] - $row['ToatalforwardAmount']);
                $openAmountINR = $openAmountFC * ($currentForwardRate * $currencyinrSpotdRate);
                $currentportfoliovalueexpval = isset($row['isSettled']) ? ($row['AvgspotamountRate'] + $row['Toatalallpayment']) : ($openAmountINR + ($row['ToatalforwardAmount'] * $row['Avgrate']));
                $data["currentportfoliovalueexp"] += $currentportfoliovalueexpval; // Sum the value in each iteration
                $data["currentganorloseexp"] += $currentportfoliovalueexpval - $targetValueInr; // Sum the value in each iteration
            }

            return $this->respond($data, 200);
        }else{
            return $this->fail('No Currency Found !!');
        }

    }

      // Current Month Details


    public function currentmonthdetails(){
        if(isset($_GET['currency'])){
            $currentmonthexpodetimp = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType !=', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('MONTH(transactiondetails.dueDate)', date('n'))
            ->findAll();
            foreach( $currentmonthexpodetimp  as $row){
                $data['currentmontheravghedgeoutwards'] = $row['avghedge'];
                $data['currentmontheravgavgtargetoutwards'] = $row['avgtarget'];
                $data['currentmonthertotalexposureoutwards'] = $row['totalexposure'];
                $data['currentmontherpercentagehedgedoutwards'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['totalexposure']) * 100 : '';
            }
            $currentmonthexpodetexp = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('MONTH(transactiondetails.dueDate)', date('n'))
            ->findAll();
            foreach( $currentmonthexpodetexp  as $row){
                $data['currentmontheravghedgeinwards'] = $row['avghedge'];
                $data['currentmontheravgavgtargetinwards'] = $row['avgtarget'];
                $data['currentmonthertotalexposureinwards'] = $row['totalexposure'];
                $data['currentmontherpercentagehedgedinwards'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['totalexposure']) * 100 : '';
            }
            return $this->respond($data, 200);
        }else{
            return $this->fail('No Currency Found !!');
        }
    }

 // quarterwise Details

    public function quaterdetails(){
        if(isset($_GET['currency'])){
            $currentquarterexpodetimp = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType !=', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(CURDATE())', false)
            ->findAll();

            foreach( $currentquarterexpodetimp  as $row){
                $data['currentquarteravghedgeoutwards'] = $row['avghedge'];
                $data['currentquarteravgavgtargetoutwards'] = $row['avgtarget'];
                $data['currentquartertotalexposureoutwards'] = $row['totalexposure'];
                $data['currentquarterpercentagehedgedoutwards'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['totalexposure']) * 100 : '';
            }

            $lastquarterexpodetimp = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType !=', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(DATE_SUB(CURDATE(), INTERVAL 1 QUARTER))')
            ->findAll();

            foreach( $lastquarterexpodetimp  as $row){
                $data['lastquarteravghedgeoutwards'] = $row['avghedge'];
                $data['lastquarteravgavgtargetoutwards'] = $row['avgtarget'];
                $data['lastquartertotalexposureoutwards'] = $row['totalexposure'];
                $data['lastquarterpercentagehedgedoutwards'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['totalexposure']) * 100 : '';
            }

            $currentquarterexpodetexp = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(CURDATE())', false)
            ->findAll();

            foreach( $currentquarterexpodetexp  as $row){
                $data['currentquarteravghedgeinwards'] = $row['avghedge'];
                $data['currentquarteravgavgtargetinwards'] = $row['avgtarget'];
                $data['currentquartertotalexposureinwards'] = $row['totalexposure'];
                $data['currentquarterpercentagehedgedinwards'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['totalexposure']) * 100 : '';
            }

            $lastquarterexpodexp = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(DATE_SUB(CURDATE(), INTERVAL 1 QUARTER))')
            ->findAll();

            foreach( $lastquarterexpodexp  as $row){
                $data['lastquarteravghedgeinwards'] = $row['avghedge'];
                $data['lastquarteravgavgtargetinwards'] = $row['avgtarget'];
                $data['lastquartertotalexposureinwards'] = $row['totalexposure'];
                $data['lastquarterpercentagehedgedinwards'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['totalexposure']) * 100 : '';
            }

            $currentportfoliovalueexp = $this->transaction_model->quarterportfoliovalueexp($_GET['currency']);
            foreach ($currentportfoliovalueexp as $row) {
                $resoval = $this->forrwardCalculator(1, $_GET['currency'], $row['dueDate']);
                $crntfrrate = json_decode($resoval);
                $currentForwardRate = isset($crntfrrate->result->forward_rate) ?  $crntfrrate->result->forward_rate : 1;
                $currencyinrSpotdRate = isset($crntfrrate->result->spot_rate) ?  $crntfrrate->result->spot_rate : 1;
                $inr_target_value = ($row['inr_target_value'] > 0.00) ? $row['inr_target_value'] : 1;
                $targetValueInr = ($row['targetRate']*$inr_target_value)*$row['amountinFC'];
                $openAmountFC = isset($row['isSettled']) ? $row['open_amount'] : ($row['amountinFC'] - $row['ToatalforwardAmount']);
                $openAmountINR = $openAmountFC * ($currentForwardRate * $currencyinrSpotdRate);
                $currentportfoliovalueexpval = isset($row['isSettled']) ? ($row['AvgspotamountRate'] + $row['Toatalallpayment']) : ($openAmountINR + ($row['ToatalforwardAmount'] * $row['Avgrate']));
                $data["currentportfoliovalueexp"] += $currentportfoliovalueexpval; // Sum the value in each iteration
                $data["currentganorloseexp"] += $currentportfoliovalueexpval - $targetValueInr; // Sum the value in each iteration
            }

            $currentportfoliovalueimp = $this->transaction_model->quarterportfoliovalueimp($_GET['currency']);
            foreach ($currentportfoliovalueimp as $row) {
                $resoval = $this->forrwardCalculator(2, $_GET['currency'], $row['dueDate']);
                $crntfrrate = json_decode($resoval);
                $currentForwardRate = isset($crntfrrate->result->forward_rate) ?  $crntfrrate->result->forward_rate : 1;
                $currencyinrSpotdRate = isset($crntfrrate->result->spot_rate) ?  $crntfrrate->result->spot_rate : 1;
                $inr_target_value = ($row['inr_target_value'] > 0.00) ? $row['inr_target_value'] : 1;
                $targetValueInr = ($row['targetRate']*$inr_target_value)*$row['amountinFC'];
                $openAmountFC = isset($row['isSettled']) ? $row['open_amount'] : ($row['amountinFC'] - $row['ToatalforwardAmount']);
                $openAmountINR = $openAmountFC * ($currentForwardRate * $currencyinrSpotdRate);
                $currentportfoliovalueimpval = isset($row['isSettled']) ? ($row['AvgspotamountRate'] + $row['Toatalallpayment']) : ($openAmountINR + ($row['ToatalforwardAmount'] * $row['Avgrate']));
                $data["currentportfoliovalueimp"] += $currentportfoliovalueimpval; // Sum the value in each iteration
                $data["currentganorloseimp"] += $currentportfoliovalueimpval - $targetValueInr; // Sum the value in each iteration
            }

            return $this->respond($data, 200);
        }else{
            return $this->fail('No Currency Found !!');
        }

    }

    // Details of Settled Invoices

    public function settledinvoices(){

        if(isset($_GET['currency'])){
            $data['currentquartersettamtinwards'] = 0;
            $data['currentquartersetrateinwards'] = 0;
            $data['currentquarteractualgainlossinwards'] = 0;
            $data['currentquartersettamtoutwards'] = 0;
            $data['currentquartersetrateoutwards'] = 0;
            $data['currentquarteractualgainlossoutwards'] = 0;
            $data['lastquartersettamtoutwards'] = 0;
            $data['lastquartersetrateoutwards'] = 0;
            $data['lastquarteractualgainlossoutwards'] = 0;
            $data['lastquartersettamtinwards'] = 0;
            $data['lastquartersetrateinwards'] = 0;
            $data['lastquarteractualgainlossinwards'] = 0;
            $currentquarterportfoliovalueexp = $this->transaction_model->currentquarterportfoliovalueexp($_GET['currency']);
            $currentquarterportfoliovaluimp = $this->transaction_model->currentquarterportfoliovaluimp($_GET['currency']);
            $lastquarterportfoliovaluimp = $this->transaction_model->lastquarterportfoliovaluimp($_GET['currency']);
            $lastquarterportfoliovaluexp = $this->transaction_model->lastquarterportfoliovaluexp($_GET['currency']);
            foreach( $currentquarterportfoliovalueexp  as $row){
                $inr_target_value = ($row['inr_target_value'] > 0.00) ? $row['inr_target_value'] : 1;
                $targetValueInr = ($row['targetRate']*$inr_target_value)*$row['amountinFC'];
                $currentquartersettamtinwardsval = $row['Toatalallpayment'] + $row['AvgspotamountRate'];
                $data['currentquartersettamtinwards'] += $currentquartersettamtinwardsval; 
                $data['currentquartersetrateinwards'] += $currentquartersettamtinwardsval / $row['amountinFC'];
                $data['currentquarteractualgainlossinwards'] += $currentquartersettamtinwardsval - $targetValueInr;
            }
            foreach( $currentquarterportfoliovaluimp  as $row){
                $inr_target_value = ($row['inr_target_value'] > 0.00) ? $row['inr_target_value'] : 1;
                $targetValueInr = ($row['targetRate']*$inr_target_value)*$row['amountinFC'];
                $currentquartersettamtoutwardsval = $row['Toatalallpayment'] + $row['AvgspotamountRate'];
                $data['currentquartersettamtoutwards'] += $currentquartersettamtoutwardsval; 
                $data['currentquartersetrateoutwards'] += $currentquartersettamtoutwardsval / $row['amountinFC'];
                $data['currentquarteractualgainlossoutwards'] += $currentquartersettamtoutwardsval - $targetValueInr;
            }
            foreach( $lastquarterportfoliovaluimp  as $row){
                $inr_target_value = ($row['inr_target_value'] > 0.00) ? $row['inr_target_value'] : 1;
                $targetValueInr = ($row['targetRate']*$inr_target_value)*$row['amountinFC'];
                $lastquartersettamtoutwardsval = $row['Toatalallpayment'] + $row['AvgspotamountRate'];
                $data['lastquartersettamtoutwards'] += $lastquartersettamtoutwardsval; 
                $data['lastquartersetrateoutwards'] += $lastquartersettamtoutwardsval / $row['amountinFC'];
                $data['lastquarteractualgainlossoutwards'] += $lastquartersettamtoutwardsval - $targetValueInr;
            }
            foreach( $lastquarterportfoliovaluexp  as $row){
                $inr_target_value = ($row['inr_target_value'] > 0.00) ? $row['inr_target_value'] : 1;
                $targetValueInr = ($row['targetRate']*$inr_target_value)*$row['amountinFC'];
                $lastquartersettamtinwardsval = $row['Toatalallpayment'] + $row['AvgspotamountRate'];
                $data['lastquartersettamtinwards'] += $lastquartersettamtinwardsval; 
                $data['lastquartersetrateinwards'] += $lastquartersettamtinwardsval / $row['amountinFC'];
                $data['lastquarteractualgainlossinwards'] += $lastquartersettamtinwardsval - $targetValueInr;
            }
            return $this->respond($data, 200);

        }else{
            return $this->fail('No Currency Found !!');
        }

    }

    public function forrwardCalculator($cover_type , $currency , $forward_date )
    {
        
        try{
            $date = date("Y-m-d", strtotime($forward_date));
            $curren = $currency;
            $covertype = !empty($cover_type) && $cover_type == 1 ? 1 : 2;
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://www.phillipforex.in/ajax/ajaxbroken',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array("cover_type" => $covertype,"currency" => $curren , "forward_date" => $date), 
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } catch (\Exception$e) {
            return "";
        }            
    }

}
