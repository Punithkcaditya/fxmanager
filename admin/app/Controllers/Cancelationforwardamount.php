<?php

namespace App\Controllers;

defined('BASEPATH') or exit('No direct script access allowed');

use App\Controllers\BaseController;
use Config\Database;
use App\Models\TransactionModel as Transaction_Model;
use App\Models\ForwardCoverdetails as ForwardCoverdetails_Model;
use App\Models\CurrencyModel as Currency_Model;
use App\Models\PaymentreceiptdetailsModel as Paymentreceiptdetails_Model;
class Cancelationforwardamount extends BaseController
{

 protected $request;

    public function __construct()
    {
        parent::__construct();
		 $request = \Config\Services::request();
		$this->transaction_model = new Transaction_Model();
		$this->forwardcoverdetails_model = new ForwardCoverdetails_Model();
		$db = Database::connect();
	}

 public function index()
    {
		if ($this->request->getMethod() == 'post') {
            extract($this->request->getPost());
			if(!empty($deal_no)){
			$forwardcoverdetails = $this->forwardcoverdetails_model->select('amount_FC')->where("forward_coverdetails_id   = '{$deal_no}'")->first();
				echo json_encode($forwardcoverdetails);
			}			
	}
}

}