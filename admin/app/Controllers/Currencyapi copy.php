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
                $data['totaloutwardsone'] = 0;
                $data['totalinwardsone'] = 0;
                $data['hedgeoutwardsone'] = 0;
                $data['hedgeinwardsone'] = 0;
                $data['totaloutwardstwo'] = 0;
                $data['hedgeoutwardstwo'] = 0;
                $data['totalinwardstwo'] = 0;
                $data['hedgeinwardstwo'] = 0;

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
                $data['totaloutwardsone'] = $row['sum_amountinFC'];
                $data['hedgeoutwardsone'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['sum_amountinFC']) * 100 : 0;
            }

            foreach( $datatableinw  as $row){
                $data['totalinwardsone'] = $row['sum_amountinFC'];
                $data['hedgeinwardsone'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['sum_amountinFC']) * 100 : 0;
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
                $data['totaloutwardstwo'] = $row['sum_amountinFC'];
                $data['hedgeoutwardstwo'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['sum_amountinFC']) * 100 : '';
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
                $data['totalinwardstwo'] = $row['sum_amountinFC'];
                $data['hedgeinwardstwo'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['sum_amountinFC']) * 100 : '';
            }


        
            return $this->respond($data, 200);
        }else{
            return $this->fail('No Currency Found !!');
        }

    }

    // Exposure Details
    public function exposuredetails(){
        if(isset($_GET['currency'])){
            $data['percentagehedgedtwo'] = array();
            $data['totalexposuretwo'] = array();
            $data['percentagehedgedone'] = array();
            $data['totalexposureone'] = array();
            $data['avghedgetwo'] = array();
            $data['avghedgeone'] = array();
            $data['avgtargettwo'] = array();
            $data['avgtargetone'] = array();
            $data["currentportfoliovaluetwo"] = 0; 
            $data["currentportfoliovalueone"] = 0; 
            $data["currentganorlosetwo"] = 0;
            $data["currentganorloseone"] = 0;
            $percentagehedgedoutwards = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType !=', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->findAll();
            foreach( $percentagehedgedoutwards  as $row){
                $data['totalexposuretwo'] = $row['totalexposure'];
                $data['avghedgetwo'] = $row['avghedge'];
                $data['avgtargettwo'] = $row['avgtarget'];
                $data['percentagehedgedtwo'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['totalexposure']) * 100 : '';
            }
            $percentagehedgedinwards = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->findAll();
            foreach( $percentagehedgedinwards  as $row){
                $data['avghedgeone'] = $row['avghedge'];
                $data['avgtargetone'] = $row['avgtarget'];
                $data['totalexposureone'] = $row['totalexposure'];
                $data['percentagehedgedone'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['totalexposure']) * 100 : '';
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
                $data["currentportfoliovaluetwo"] += $currentportfoliovalueimpval; // Sum the value in each iteration
                $data["currentganorlosetwo"] += $currentportfoliovalueimpval - $targetValueInr; // Sum the value in each iteration
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
                $data["currentportfoliovalueone"] += $currentportfoliovalueexpval; // Sum the value in each iteration
                $data["currentganorloseone"] += $currentportfoliovalueexpval - $targetValueInr; // Sum the value in each iteration
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
                $data['avghedgetwo'] = $row['avghedge'];
                $data['avgtargettwo'] = $row['avgtarget'];
                $data['currentmonthtotalexposuretwo'] = $row['totalexposure'];
                $data['currentmonthpercentagehedgedtwo'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['totalexposure']) * 100 : '';
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
                $data['avghedgeone'] = $row['avghedge'];
                $data['avgtargeone'] = $row['avgtarget'];
                $data['currentmonthtotalexposureone'] = $row['totalexposure'];
                $data['currentmonthpercentagehedgedone'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['totalexposure']) * 100 : '';
            }
            return $this->respond($data, 200);
        }else{
            return $this->fail('No Currency Found !!');
        }
    }

 // quarterwise Details

    public function quaterdetails(){
        if(isset($_GET['currency'])){
            $data["current-portfolio-valueone"] = 0;
            $data["last-portfolio-valueone"] = 0;
            $data["current-portfolio-valuetwo"] = 0;
            $data["last-portfolio-valuetwo"] = 0;
            $currentquarterexpodetimp = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType !=', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(CURDATE())', false)
            ->findAll();

            foreach( $currentquarterexpodetimp  as $row){
                $data['current-quarter-avghedgetwo'] = $row['avghedge'];
                $data['current-quarter-avgavgtargettwo'] = $row['avgtarget'];
                $data['current-quarter-totalexposuretwo'] = $row['totalexposure'];
                $data['current-quarter-percentagehedgedtwo'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['totalexposure']) * 100 : '';
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
                $data['last-quarter-avghedgetwo'] = $row['avghedge'];
                $data['last-quarter-avgavgtargettwo'] = $row['avgtarget'];
                $data['last-quarter-totalexposuretwo'] = $row['totalexposure'];
                $data['last-quarter-percentagehedgedtwo'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['totalexposure']) * 100 : '';
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
                $data['current-quarter-avghedgeone'] = $row['avghedge'];
                $data['current-quarter-avgavgtargetone'] = $row['avgtarget'];
                $data['current-quarter-totalexposureone'] = $row['totalexposure'];
                $data['current-quarter-percentagehedgedone'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['totalexposure']) * 100 : '';
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
                $data['last-quarter-avghedgeone'] = $row['avghedge'];
                $data['last-quarter-avgavgtargetone'] = $row['avgtarget'];
                $data['last-quarter-totalexposureone'] = $row['totalexposure'];
                $data['last-quarter-percentagehedgedone'] = isset($row['sum_amount_FC']) ? ($row['sum_amount_FC'] / $row['totalexposure']) * 100 : '';
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
                $data["current-portfolio-valueone"] += $currentportfoliovalueexpval; // Sum the value in each iteration
            }

            $lastportfoliovalueexp = $this->transaction_model->lastquarterportfoliovalueexp($_GET['currency']);
            foreach ($lastportfoliovalueexp as $row) {
                $resoval = $this->forrwardCalculator(1, $_GET['currency'], $row['dueDate']);
                $crntfrrate = json_decode($resoval);
                $currentForwardRate = isset($crntfrrate->result->forward_rate) ?  $crntfrrate->result->forward_rate : 1;
                $currencyinrSpotdRate = isset($crntfrrate->result->spot_rate) ?  $crntfrrate->result->spot_rate : 1;
                $inr_target_value = ($row['inr_target_value'] > 0.00) ? $row['inr_target_value'] : 1;
                $targetValueInr = ($row['targetRate']*$inr_target_value)*$row['amountinFC'];
                $openAmountFC = isset($row['isSettled']) ? $row['open_amount'] : ($row['amountinFC'] - $row['ToatalforwardAmount']);
                $openAmountINR = $openAmountFC * ($currentForwardRate * $currencyinrSpotdRate);
                $lastportfoliovalueexpval = isset($row['isSettled']) ? ($row['AvgspotamountRate'] + $row['Toatalallpayment']) : ($openAmountINR + ($row['ToatalforwardAmount'] * $row['Avgrate']));
                $data["last-portfolio-valueone"] += $lastportfoliovalueexp; // Sum the value in each iteration
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
                $data["current-portfolio-valuetwo"] += $currentportfoliovalueimpval; // Sum the value in each iteration
            }

            $lastportfoliovalueimp = $this->transaction_model->lastquarterportfoliovalueimp($_GET['currency']);
            foreach ($lastportfoliovalueimp as $row) {
                $resoval = $this->forrwardCalculator(2, $_GET['currency'], $row['dueDate']);
                $crntfrrate = json_decode($resoval);
                $currentForwardRate = isset($crntfrrate->result->forward_rate) ?  $crntfrrate->result->forward_rate : 1;
                $currencyinrSpotdRate = isset($crntfrrate->result->spot_rate) ?  $crntfrrate->result->spot_rate : 1;
                $inr_target_value = ($row['inr_target_value'] > 0.00) ? $row['inr_target_value'] : 1;
                $targetValueInr = ($row['targetRate']*$inr_target_value)*$row['amountinFC'];
                $openAmountFC = isset($row['isSettled']) ? $row['open_amount'] : ($row['amountinFC'] - $row['ToatalforwardAmount']);
                $openAmountINR = $openAmountFC * ($currentForwardRate * $currencyinrSpotdRate);
                $lastportfoliovalueimpval = isset($row['isSettled']) ? ($row['AvgspotamountRate'] + $row['Toatalallpayment']) : ($openAmountINR + ($row['ToatalforwardAmount'] * $row['Avgrate']));
                $data["last-portfolio-valuetwo"] += $lastportfoliovalueimpval; // Sum the value in each iteration
            }

            return $this->respond($data, 200);
        }else{
            return $this->fail('No Currency Found !!');
        }

    }

    // Details of Settled Invoices

    public function settledinvoices(){

        if(isset($_GET['currency'])){
            $data['current-quarter-settamtinFC_one'] = 0;
            $data['last-quarter-settamtinFC_one'] = 0;
            $data['current-quarter-settamtinFC_two'] = 0;
            $data['last-quarter-settamtinFC_two'] = 0;
            $data['current-quarter-settamtone'] = 0;
            $data['current-quarter-setrateone'] = 0;
            $data['current-quarter-actualgainlossone'] = 0;
            $data['current-quarter-settamttwo'] = 0;
            $data['current-quarter-setratetwo'] = 0;
            $data['current-quarter-actualgainlosstwo'] = 0;
            $data['last-quarter-settamttwo'] = 0;
            $data['last-quarter-setrateouttwo'] = 0;
            $data['last-quarter-actualgainlosstwo'] = 0;
            $data['last-quarter-settamtone'] = 0;
            $data['last-quarter-setrateone'] = 0;
            $data['last-quarter-actualgainlossone'] = 0;
            $currentquarterportfoliovalueexp = $this->transaction_model->currentquarterportfoliovalueexp($_GET['currency']);
            $currentquarterportfoliovaluimp = $this->transaction_model->currentquarterportfoliovaluimp($_GET['currency']);
            $lastquarterportfoliovaluimp = $this->transaction_model->lastquarterportfoliovaluimp($_GET['currency']);
            $lastquarterportfoliovaluexp = $this->transaction_model->lastquarterportfoliovaluexp($_GET['currency']);
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
            return $this->respond($data, 200);

        }else{
            return $this->fail('No Currency Found !!');
        }

    }

        public function currencyperformance(){
            $check = $this->authUser();
            if ($check) {
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
                "This Week" => [
                    "Open" => "72.3525",
                    "High" => "83.2888",
                    "Low" => "77.01"
                ],
                "This Month" => [
                    "Open" => "12.3525",
                    "High" => "23.2888",
                    "Low" => "67.01"
                ],
                "This Quarter" => [
                    "Open" => "82.3525",
                    "High" => "03.2888",
                    "Low" => "107.01"
                ],
            ]);
    
            // Set the appropriate headers
            $this->response
                ->setHeader('Content-Type', 'application/json')
                ->setStatusCode(200);
    
            // Send the JSON response
            return $this->respond($jsonResponse);
        }else {
            return $this->fail('Invalid Token !!');
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
              CURLOPT_URL => 'https://fxmanagers.in/ajax/ajaxbroken',
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


    public function fxmanagerdashboard(){
        return $this->respond(1);
    }
    
}
