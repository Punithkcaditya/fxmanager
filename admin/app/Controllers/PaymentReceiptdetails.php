<?php

namespace App\Controllers;

defined('BASEPATH') or exit('No direct script access allowed');

use App\Controllers\BaseController;
use Config\Database;
use App\Models\TransactionModel as Transaction_Model;
use App\Models\ForwardCoverdetails as ForwardCoverdetails_Model;
use App\Models\CurrencyModel as Currency_Model;
use App\Models\PaymentreceiptdetailsModel as Paymentreceiptdetails_Model;
use App\Models\OpenDetailsModel as OpenDetails_Model;
use CodeIgniter\Helpers\DateHelper;
class PaymentReceiptdetails extends BaseController
{
    protected $request;

    public function __construct()
    {
        parent::__construct();
        $request = \Config\Services::request();
        helper(['form', 'url', 'string']);
        $session = session();
		$this->transaction_model = new Transaction_Model();
		$this->forwardcoverdetails_model = new ForwardCoverdetails_Model();
		$this->currency_model = new Currency_Model();
		$this->paymentreceiptdetails_model = new Paymentreceiptdetails_Model();
        $this->opendetails_model = new OpenDetails_Model();
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
		 $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $this->loadUser();
        $data["view"] = "Paymentreceipt/payment_receipt_details";
        $data["page_title"] = "Payment Receipt Details";
        $data["session"] = $session;
        if ($this->permission[0] > 0) {
            $data["link"] = "addnewpaymentreceipt";
        } else {
            $data["link"] = "#";
        }
         $data['title'] = 'Payment Receipt Details';
		 $data['pade_title1'] = 'Bank Name';
		 $data['i'] = 1;
		 $data["exposuretype"] = $this->transaction_model ->select('exposurereInfo')->select('transaction_id')->orderBy('transaction_id', 'DESC')->find();
		 $data["forwardcoverdetails"] = $this->forwardcoverdetails_model ->select('deal_no')->select('forward_coverdetails_id')->orderBy('forward_coverdetails_id', 'DESC')->find();
		 $data["currency"] = $this->currency_model->orderBy('currency_id', 'DESC')->findAll();
		 $data['pade_title5'] = 'Exposure Currency';
		 $data['pade_title6'] = 'Underlying Exposure Ref';
		 $data['pade_title1'] = 'Bank Name';
		 $data['pade_title2'] = 'Currency Bought';
		 $data['pade_title3'] = 'Select Time';
		 $data['pade_title4'] = 'Date of Settlement';
		 $data['pade_title7'] = 'Target Value';
		 $data['pade_title8'] = 'Amount (FC)';
		 $data['pade_title9'] = 'Value INR';
		 $data['pade_title10'] = 'Amount (FC)';
		 $data['pade_title11'] = 'Contracted rate';
		 $data['pade_title12'] = 'Expiry Date';
		 $data['pade_title14'] = 'Deal Reference';
		 $data['pade_title15'] = 'Forward Amount';
		 $data['pade_title16'] = 'Rate';
		 $data['pade_title17'] = 'Spot Amount';
		 $data['pade_title18'] = 'Spot Amount Rate';
        $data["page_heading"] = "Forward Cover Details";
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        return view('templates/default', $data);
	}
	
	
            public function savepaymentreceiptdetails()
            {
            $this->loadUser();
            $session = session();
            $pot = json_decode(json_encode($session->get("userdata")), true);
            if (empty($pot)) {
            return redirect()->to("/");
            }
            if ($this->request->getMethod() == 'post') {
            extract($this->request->getPost());
            }
			
            $input = $this->validate([
			'banknamehidd' => 'required',
			'exposurerefno' => 'required',
			'value_INRhidd' => 'required', 
			'target_Valuehidd' => 'required',
			'exposurecurrencyhidd' => 'required',
			'dateof_Settlement' => 'required',
			'amountfchidd' => 'required', 
			'spotAmount' => 'required',
			'spotAmountrate' => 'required'
			]);
		
            if (!empty($input)) {
                $totalAmount = $this->opendetails_model->select('open_amount')->where('transactionforeing_id',  $exposurerefno)->first();
                if (!empty($totalAmount) && isset($forwardAmount)) {
                    echo '<pre>';
                    print_r('inn');
                    exit;
                $this->removemptyarray($forwardAmount);
                $this->removemptyarray($forwardamountRate);
                $mbinedresult = $this->aomineArrays($forwardAmount, $forwardamountRate,$dealnoref);
                foreach ($mbinedresult as $key => $sid) {
                $udata["amount_FC"] = $amountfchidd;
                $udata["value_INR"] = $value_INRhidd;
                $udata["target_Value"] = $target_Valuehidd;
                $udata["underlying_Exposure_ref"] = $exposurerefno;
                $udata["deal_Referenceno"] = $sid['dealnoref'];
                $udata["forward_Amount"] = $sid['forward_Amount'];
                $udata["forward_Rate"] = $sid['forward_Rate'];
                $udata["spot_Amount"] = $spotAmount;
                $udata["spotamount_Rate"] = $spotAmountrate;
                $udata["exposure_Currency"] = $exposurecurrencyhidd;
                $udata["bank_Name"] = $banknamehidd;
                $udata["dateof_Settlement"] = $this->convertDateFormat($dateof_Settlement);
                $saved = $this->paymentreceiptdetails_model->save($udata);
                }

                $totalAmount['open_amount'] -= $spotAmount;
                $dataopendetails = ['open_amount' => $totalAmount['open_amount'], 'isSettled' => 1];
                $update =  $this->opendetails_model->where('transactionforeing_id', $exposurerefno)->set($dataopendetails)->update();
                (empty($saved) && empty($update)) ? $session->setFlashdata('error', 'Failed To Save') : $session->setFlashdata('success', 'Saved Successfully'); 
               }else{
                $AmountInFc = $this->transaction_model->select('amountinFC')->where('transaction_id',  $exposurerefno)->first();
                $AmountInFc['amountinFC'] -= $spotAmount;
                $dataopendetails = ['open_amount' => $AmountInFc['amountinFC'], 'transactionforeing_id' => $exposurerefno, 'isSettled' => 1];
                $udata["amount_FC"] = $amountfchidd;
                $udata["value_INR"] = $value_INRhidd;
                $udata["target_Value"] = $target_Valuehidd;
                $udata["underlying_Exposure_ref"] = $exposurerefno;
                $udata["spot_Amount"] = $spotAmount;
                $udata["spotamount_Rate"] = $spotAmountrate;
                $udata["exposure_Currency"] = $exposurecurrencyhidd;
                $udata["bank_Name"] = $banknamehidd;
                $udata["dateof_Settlement"] = $this->convertDateFormat($dateof_Settlement);
                $saved = $this->paymentreceiptdetails_model->save($udata);
                $savedopendetails = $this->opendetails_model->save($dataopendetails);
                (empty($saved) && empty($savedopendetails)) ? $session->setFlashdata('error', 'Failed To Save') : $session->setFlashdata('success', 'Saved Successfully'); 
               }

            } else {
            $session->setFlashdata('error', 'Fill All Fields');
            }
            return redirect()->to('paymentreceiptdetails');
            }
			
			 public function removemptyarray($array)
            {
				foreach ($array as $key => $sid) {
				if (empty($array[$key])) {
				unset($array[$key]);
				}}								
			}
			

			public function aomineArrays($array1,$array2, $array3)
            {
			$result = array_map(function ($array1, $array2, $array3) {
			return array_combine(
			['forward_Amount', 'forward_Rate', 'dealnoref'],
			[$array1, $array2, $array3]
			);
			}, $array1, $array2, $array3);
			return $result;
			}


			function convertDateFormat($dateString)
            {
            $date = date_create_from_format('d/m/Y', $dateString);
            if ($date) {
            return date_format($date, 'Y-m-d');
            } else {
            return '1970-01-01'; // Return false if the date format is invalid
            }
            }
			
}