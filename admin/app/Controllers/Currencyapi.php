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
            $data["totaloutwrds"] = $this->transaction_model->totaloutwrds($_GET['currency']);


            $data["totalinwrds"] = $this->transaction_model->totalinwrds($_GET['currency']);


            $data["totalinwrdscurrentquarter"] = $this->transaction_model->totalinwrdscurrentquarter($_GET['currency']);

            $data["totaloutwrdscurrentquarter"] = $this->transaction_model->totaloutwrdscurrentquarter($_GET['currency']);


            $data["expodetimp"] = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType !=', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->findAll();


            $data["expodetexp"] = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->findAll();


            $data["currentportfoliovalueimp"] = $this->transaction_model->currentportfoliovalueimp($_GET['currency']);


            $data["currentportfoliovalueexp"] = $this->transaction_model->currentportfoliovalueexp($_GET['currency']);


            $data["currentmonthexpodetimp"] = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType !=', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('MONTH(transactiondetails.dueDate)', date('n'))
            ->findAll();


            $data["currentmonthexpodetexp"] = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('MONTH(transactiondetails.dueDate)', date('n'))
            ->findAll();

            $data["currentquarterexpodetexp"] = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(CURDATE())', false)
            ->findAll();

            $data["currentquarterexpodetimp"] = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType !=', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(CURDATE())', false)
            ->findAll();

            $data["lastquarterexpodetimp"] = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType !=', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(DATE_SUB(CURDATE(), INTERVAL 1 QUARTER))')
            ->findAll();


            $data["lastquarterexpodetexp"] = $this->transaction_model
            ->select("SUM(transactiondetails.amountinFC) AS sum_amountinFC, AVG(transactiondetails.targetRate) as avgtarget, AVG(forward_coverdetails.contracted_Rate) as avghedge, SUM(forward_coverdetails.amount_FC) AS sum_amount_FC")
            ->join('forward_coverdetails', "forward_coverdetails.underlying_exposure_ref = transactiondetails.transaction_id", 'left')
            ->join('currency', "currency.currency_id = transactiondetails.currency", 'left')
            ->where('transactiondetails.exposureType', 1)
            ->where('currency.Currency', $_GET['currency'])
            ->where('QUARTER(transactiondetails.dueDate)', 'QUARTER(DATE_SUB(CURDATE(), INTERVAL 1 QUARTER))')
            ->findAll();


            $data["currentquarterportfoliovalueexp"] = $this->transaction_model->currentquarterportfoliovalueexp($_GET['currency']);
            $data["currentquarterportfoliovaluimp"] = $this->transaction_model->currentquarterportfoliovaluimp($_GET['currency']);
            $data["lastquarterportfoliovaluimp"] = $this->transaction_model->lastquarterportfoliovaluimp($_GET['currency']);
            $data["lastquarterportfoliovaluexp"] = $this->transaction_model->lastquarterportfoliovaluexp($_GET['currency']);


            return $this->respond($data, 200);
        }else{
            return $this->fail('No Currency Found !!');
        }

    }
}
