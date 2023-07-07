<?php

namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use App\Controllers\BaseController;
use App\Models\TransactionModel as Transaction_Model;
use App\Models\ExposureType as ExposureType_Model;
use App\Models\CurrencyModel as Currency_Model;
class Currencyapi extends BaseController
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
        

                $querytotalout = $this->transaction_model
                ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
                ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
                ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
                ->where('transactiondetails.exposureType !=', 1)
                ->where('currency.Currency', $_GET['currency'])
                ->where('MONTH(transactiondetails.dueDate)', date('n'))
                ->get();
           
                if (is_object($querytotalout)) {
                $datatableout = $querytotalout->getResultArray();
                }
            
                if(isset($datatableout)){
                    foreach( $datatableout  as $row){
                        $data['totaloutwardsone'] += $row['sum_amountinFC'];
                        $data['hedgeoutwardsone'] += $this->calculatehedgeper($row['sum_amount_FC'], $row['sum_amountinFC']);
                    }
                }
            
            $queryinw = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('MONTH(transactiondetails.dueDate)', date('n'))
            ->get();

            if (is_object($queryinw)) {
                $datatableinw = $queryinw->getResultArray();
            }

        
            if(isset($datatableinw)){
            foreach( $datatableinw  as $row){
            $data['totalinwardsone'] += $row['sum_amountinFC'];
            $data['hedgeinwardsone'] += $this->calculatehedgeper($row['sum_amount_FC'], $row['sum_amountinFC']);
            }
            }


            $querytotaloutwards = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType !=', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(CURDATE())', false)
            ->get();

    

            if (is_object($querytotaloutwards)) {
                $currentquartertotaloutwards = $querytotaloutwards->getResultArray();
            }
          
            if(isset($currentquartertotaloutwards)){
            foreach( $currentquartertotaloutwards  as $row){
                $data['totaloutwardstwo'] += $row['sum_amountinFC'];
                $data['hedgeoutwardstwo'] += $this->calculatehedgeper($row['sum_amount_FC'], $row['sum_amountinFC']);
            }
        }
    

            $querytotalinwards = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(CURDATE())', false)
            ->get();

            if (is_object($querytotalinwards)) {
                $currentquartertotalinwards = $querytotalinwards->getResultArray();
            }

            if(isset($currentquartertotalinwards)){
            foreach( $currentquartertotalinwards  as $row){
                $data['totalinwardstwo'] += $row['sum_amountinFC'];
                $data['hedgeinwardstwo'] += $this->calculatehedgeper($row['sum_amount_FC'], $row['sum_amountinFC']);
            }
        }

            return $this->respond($data, 200);
        }else{
            return $this->fail('No Currency Found !!');
        }

    }




    public function calculatehedgeper($sum_amount_FC, $sum_amountinFC){
        $value = isset($sum_amount_FC) ? ($sum_amount_FC / $sum_amountinFC) * 100 : 0;
        return $value;
    }

    // Exposure Details
    public function exposuredetails(){
        if(isset($_GET['currency'])){
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
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType !=', 1)
            ->where('currency.Currency', $_GET['currency'])
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
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('currency.Currency', $_GET['currency'])
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

            $currentportfoliovalueimp = $this->transaction_model->currentportfoliovalueimp($_GET['currency']);

            foreach ($currentportfoliovalueimp as $row) {
                $resoval = $this->forrwardCalculator(2, $_GET['currency'], $row['dueDate']);
                $curdata = $this->calculateportfoliovalue($resoval, $row['inr_target_value'], $row['targetRate'], $row['open_amount'], $row['amountinFC'], $row['ToatalforwardAmount'], $row['Toatalallpayment'], $row['Avgrate'], $row['isSettled'], $row['AvgspotamountRate']);
                $data["currentportfoliovaluetwo"] += $curdata['currentportfoliovalue']; // Sum the value in each iteration
                $data["currentganorlosetwo"] += $curdata['currentganorlose']; // Sum the value in each iteration
            }

            $currentportfoliovalueexp = $this->transaction_model->currentportfoliovalueexp($_GET['currency']);
            foreach ($currentportfoliovalueexp as $row) {
                $resoval = $this->forrwardCalculator(1, $_GET['currency'], $row['dueDate']);
                $curdata = $this->calculateportfoliovalue($resoval, $row['inr_target_value'], $row['targetRate'], $row['open_amount'], $row['amountinFC'], $row['ToatalforwardAmount'], $row['Toatalallpayment'], $row['Avgrate'], $row['isSettled'], $row['AvgspotamountRate']);
                $data["currentportfoliovalueone"] += $curdata['currentportfoliovalue']; // Sum the value in each iteration
                $data["currentganorloseone"] += $curdata['currentganorlose']; // Sum the value in each iteration
            }

            return $this->respond($data, 200);
        }else{
            return $this->fail('No Currency Found !!');
        }

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




      public function exposoredethedgecalc($sum_amount_FC, $totalexposure){
        $value =  isset($sum_amount_FC) && isset($totalexposure) ? ($sum_amount_FC / $totalexposure) * 100 : 0;
        return $value;
        }

      // Current Month Details

    public function currentmonthdetails(){
        if(isset($_GET['currency'])){
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
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType !=', 1)
            ->where('currency.Currency', $_GET['currency'])
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
        }

            $querycurrentexp = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('MONTH(transactiondetails.dueDate)', date('n'))
            ->get();

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
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType !=', 1)
            ->where('currency.Currency', $_GET['currency'])
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
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType !=', 1)
            ->where('currency.Currency', $_GET['currency'])
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
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('currency.Currency', $_GET['currency'])
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
            }}

            $querylastquarter = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS totalexposure, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(DATE_SUB(CURDATE(), INTERVAL 1 QUARTER))')
            ->get();

            if (is_object($querylastquarter)) {
                $lastquarterexpodexp = $querylastquarter->getResultArray();
                }

                if(isset($lastquarterexpodexp)){
                foreach( $lastquarterexpodexp  as $row){
                $data['last-quarter-avghedgeone'] += $row['avghedge'];
                $data['last-quarter-avgavgtargetone'] += $row['avgtarget'];
                $data['last-quarter-totalexposureone'] += $row['totalexposure'];
                $data['last-quarter-percentagehedgedone'] += $this->exposoredethedgecalc($row['sum_amount_FC'], $row['totalexposure']);
                }}

            $currentportfoliovalueexp = $this->transaction_model->quarterportfoliovalueexp($_GET['currency']);
        
            foreach ($currentportfoliovalueexp as $row) {
                $resoval = $this->forrwardCalculator(1, $_GET['currency'], $row['dueDate']);
                $data["current-portfolio-valueone"] += $this->quarterdetailscalc($resoval, $row['inr_target_value'], $row['amountinFC'], $row['targetRate'], $row['open_amount'], $row['isSettled'], $row['ToatalforwardAmount'], $row['AvgspotamountRate'], $row['Toatalallpayment'], $row['Avgrate']);          
              }

            $lastportfoliovalueexp = $this->transaction_model->lastquarterportfoliovalueexp($_GET['currency']);
            foreach ($lastportfoliovalueexp as $row) {
                $resoval = $this->forrwardCalculator(1, $_GET['currency'], $row['dueDate']);
                $data["last-portfolio-valueone"] += $this->quarterdetailscalc($resoval, $row['inr_target_value'], $row['amountinFC'], $row['targetRate'], $row['open_amount'], $row['isSettled'], $row['ToatalforwardAmount'], $row['AvgspotamountRate'], $row['Toatalallpayment'], $row['Avgrate']);
            }

            $currentportfoliovalueimp = $this->transaction_model->quarterportfoliovalueimp($_GET['currency']);
            foreach ($currentportfoliovalueimp as $row) {
                $resoval = $this->forrwardCalculator(2, $_GET['currency'], $row['dueDate']);
                $data["current-portfolio-valuetwo"] += $this->quarterdetailscalc($resoval, $row['inr_target_value'], $row['amountinFC'], $row['targetRate'], $row['open_amount'], $row['isSettled'], $row['ToatalforwardAmount'], $row['AvgspotamountRate'], $row['Toatalallpayment'], $row['Avgrate']);
            }

            $lastportfoliovalueimp = $this->transaction_model->lastquarterportfoliovalueimp($_GET['currency']);
            foreach ($lastportfoliovalueimp as $row) {
                $resoval = $this->forrwardCalculator(2, $_GET['currency'], $row['dueDate']);
                $data["last-portfolio-valuetwo"] += $this->quarterdetailscalc($resoval, $row['inr_target_value'], $row['amountinFC'], $row['targetRate'], $row['open_amount'], $row['isSettled'], $row['ToatalforwardAmount'], $row['AvgspotamountRate'], $row['Toatalallpayment'], $row['Avgrate']);
            }

            return $this->respond($data, 200);
        }else{
            return $this->fail('No Currency Found !!');
        }

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

    // Details of Settled Invoices

    public function settledinvoices(){

        if(isset($_GET['currency'])){
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
            $check = $this->testauthUser();
        echo '<pre>';
        print_r($check);
        exit;
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

    public function authUser()
    {
       
        $secondDB = \Config\Database::connect('second_db');
        $header_values = getallheaders();
        file_put_contents(ROOTPATH."logs/stripe_log.txt", print_r($header_values, true), FILE_APPEND);
        $data = array();
        $response = array();
    //   return $this->respond($header_values['Auth-Token']);
        if ((isset($header_values['token']) && !empty($header_values['token']))) {
            $token = $header_values['token'];
            if (!empty($token)) {
                $data['user_data'] = $secondDB->table('users')->where('login_token', $token)->get()
                ->getRow();
                return $data['user_data'];
                $secondDB->close();
                if (!empty($data['user_data'])) {
                    $this->session->set($data);
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    public function testauthUser()
    {
       
        $secondDB = \Config\Database::connect('second_db');
        $data = array();
        $response = array();
    //   return $this->respond($header_values['Auth-Token']);
            $token = $_GET['token'];
            if (!empty($token)) {
                $data['user_data'] = $secondDB->table('users')->where('login_token', $token)->get()
                ->getRow();
                return $data['user_data'];
                $secondDB->close();
                if (!empty($data['user_data'])) {
                    $this->session->set($data);
                    return true;
                } else {
                    return false;
                }
            }
        
    }
    
}
