<?php

namespace App\Controllers;

defined('BASEPATH') or exit('No direct script access allowed');

use App\Controllers\BaseController;
use Config\Database;
use App\Models\TransactionModel as Transaction_Model;
use App\Models\ForwardCoverdetails as ForwardCoverdetails_Model;
use App\Models\CurrencyModel as Currency_Model;
use App\Models\PaymentreceiptdetailsModel as Paymentreceiptdetails_Model;
class PaymentReceiptdetailsdependant extends BaseController
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
			if(!empty($exposurerefno)){
				$transaction = $this->transaction_model->getdependantData($exposurerefno);
				echo json_encode($transaction);
			}else if($dealnoref){
		$forwardcoverdetails = $this->forwardcoverdetails_model->select('deal_no')->select('amount_FC')->select('contracted_Rate')->where("forward_coverdetails_id  = '{$dealnoref}'")->first();
				echo json_encode($forwardcoverdetails);
			}
				
	}
	}
	
	 public function dependantdropdowns()
    {
		if ($this->request->getMethod() == 'post') {
            extract($this->request->getPost());
			//return $forwardcoverdetailsid;
			$forwardcoverdetails = $this->forwardcoverdetails_model ->where("underlying_exposure_ref  = '{$forwardcoverdetailsid}'")->findAll();
			$data = '';
			if (count($forwardcoverdetails) > 0){
				$data.="<option value=''>Deal No</option>";
				foreach ($forwardcoverdetails as $row) :
					$data.="<option value='".$row['forward_coverdetails_id']."'>".$row['deal_no']."</option>";
				endforeach;
			}else{
				$data.= "<option value=''>No Deal No Present</option>";
			}
		echo $data;
		}
	}

}