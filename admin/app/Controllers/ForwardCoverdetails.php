<?php

namespace App\Controllers;

defined('BASEPATH') or exit('No direct script access allowed');

use App\Controllers\BaseController;
use Config\Database;
use App\Models\TransactionModel as Transaction_Model;
use App\Models\ForwardCoverdetails as ForwardCoverdetails_Model;
use App\Models\CurrencyModel as Currency_Model;
use App\Models\OpenDetailsModel as OpenDetails_Model;
use App\Models\BankModel as Bank_Model;
class ForwardCoverdetails extends BaseController
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
            $this->opendetails_model = new OpenDetails_Model();
            $this->bank_model = new Bank_Model();
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
            $data["view"] = "Forwardcoverdetails/forwardcoverdetails";
            $data["page_title"] = "Forward Cover Details";
            $data["session"] = $session;
            if ($this->permission[0] > 0) {
            $data["link"] = "addnewroles";
            } else {
            $data["link"] = "#";
            }
            $data['title'] = 'Forward Cover Details';
            $data['pade_title1'] = 'Bank Name';
            $data['i'] = 1;
            $data["exposuretype"] = $this->transaction_model ->select('exposurereInfo')->select('transaction_id')->orderBy('transaction_id', 'DESC')->find();
            $data["currency"] = $this->currency_model->orderBy('currency_id', 'DESC')->findAll();
            $data["bank"] = $this->bank_model->orderBy('bank_id', 'DESC')->findAll();
            $data['pade_title5'] = 'Forward/ Option';
            $data['pade_title6'] = 'Deal No';
            $data['pade_title1'] = 'Bank Name';
            $data['pade_title2'] = 'Currency Bought';
            $data['pade_title3'] = 'Select Time';
            $data['pade_title4'] = 'Currency Sold';
            $data['pade_title7'] = 'Underlying Exposure Ref.';
            $data['pade_title8'] = 'Amount (FC)';
            $data['pade_title9'] = 'Deal Date';
            $data['pade_title10'] = 'Amount (FC)';
            $data['pade_title11'] = 'Contracted rate';
            $data['pade_title12'] = 'Expiry Date';
            $data['pade_title13'] = 'Choose Bank';
            $data["page_heading"] = "Forward Cover Details";
            $data["menuslinks"] = $this->request->uri->getSegment(1);
            return view('templates/default', $data);
            }
	
	
            public function saveforwardcoverdetails()
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
            $input = $this->validate(['dealno' => 'required', 'dealdate' => 'required', 'refno' => 'required', 'fordwardoption' => 'required', 'currencybought' => 'required', 'currencysold' => 'required', 'amountFC' => 'required', 'contractedrate' => 'required' , 'expirydate' => 'required']);
            if (!empty($input)) {
            $result = array_map(null,  $dealno, $dealdate, $refno, $fordwardoption, $currencybought, $currencysold, $amountFC, $contractedrate, $expirydate, $bank);
          
			foreach ($result as $key => $sid) {
            if (empty($result[$key])) {
            unset($result[$key]);
            }
            }
            foreach ($result as $key => $sid) {
            $data = [
            'deal_no' => $sid[0],
            'deal_date' => $this->convertDateFormat($sid[1]),
            'underlying_exposure_ref' => $sid[2],
            'fordward_option' => $sid[3],
            'currencybought' => $sid[4],
            'currencysold' => $sid[5],
            'amount_FC' => $sid[6],
			'contracted_Rate' => $sid[7],
			'expiry_date' => $this->convertDateFormat($sid[8]),
			'bank_id' => $sid[9],
            'created_date' => date('Y-m-d'),
            ];
            $AmountInFc = $this->transaction_model->select('amountinFC')->where('transaction_id',  $sid[2])->first();
            $totalAmount = $this->opendetails_model->select('open_amount')->where('transactionforeing_id',  $sid[2])->first();
            if (isset($totalAmount['open_amount'])) {
                // If a record exists, update the total_amount by adding the new value
                $totalAmount['open_amount'] -= $sid[6];
                $dataopendetails = ['open_amount' => $totalAmount['open_amount']];
                $update =  $this->opendetails_model->where('transactionforeing_id', $sid[2])->set($dataopendetails)->update();
            }else {
                $AmountInFc['amountinFC'] -= $sid[6];
                $dataopendetails = ['open_amount' => $AmountInFc['amountinFC'], 'transactionforeing_id' => $sid[2] ];
                $this->opendetails_model->save($dataopendetails);
            }
            
            $saved = $this->forwardcoverdetails_model->save($data);
        }

            (empty($saved)) ? $session->setFlashdata('error', 'Failed To Save') : $session->setFlashdata('success', 'Saved Successfully');
            } else {
            $session->setFlashdata('error', 'Fill All Fields');
            }
            return redirect()->to('forwardcoverdetails');
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


            public function dependantcurrency()
            {
                if ($this->request->getMethod() == 'post') {
                    extract($this->request->getPost());
                    if(!empty($selectedValue)){
                       $transactiondata = $this->transaction_model
                       ->select("transactiondetails.exposureType, transactiondetails.currency, currency.Currency, transactiondetails.bank_id")
                       ->join('currency',"transactiondetails.currency = currency.currency_id",'left')
                       ->join('bank_master',"bank_master.bank_id = transactiondetails.bank_id",'left')
                       ->where("transactiondetails.transaction_id = $selectedValue")->first();
                       echo json_encode($transactiondata);
                    }        
            }
            }

            public function forwardCover(){
                $session = session();
                $pot = json_decode(json_encode($session->get("userdata")), true);
                if (empty($pot)) {
                return redirect()->to("/");
                }
                $this->loadUser();
                $data["view"] = "Forwardcoverdetails/forwardcover";
                $data["page_title"] = "Forward Cover";
                $data["session"] = $session;
                if ($this->permission[0] > 0) {
                $data["link"] = "addnewroles";
                } else {
                $data["link"] = "#";
                }
                $data['title'] = 'Forward Cover';
                $data['pade_title1'] = 'Bank Name';
                $data['i'] = 1;
                $data["exposuretype"] = $this->transaction_model ->select('exposurereInfo')->select('transaction_id')->orderBy('transaction_id', 'DESC')->find();
                $data["currency"] = $this->currency_model->orderBy('currency_id', 'DESC')->findAll();
                $data["bank"] = $this->bank_model->orderBy('bank_id', 'DESC')->findAll();
                $data['pade_title5'] = 'Buy/Sell';
                $data['pade_title2'] = 'Deal No';
                $data['pade_title1'] = 'Bank Name';
                $data['pade_title4'] = 'Currency';
                $data['pade_title3'] = 'Select Time';
                $data['pade_title4'] = 'Currency Sold';
                $data['pade_title6'] = 'Due Date (From)';
                $data['pade_title7'] = 'Due Date (To)';
                $data['pade_title8'] = 'Spot Rate';
                $data['pade_title9'] = 'Premium';
                $data['pade_title10'] = 'Margin';
                $data['pade_title11'] = 'Contracted Date';
                $data['pade_title12'] = 'Forward Amount O/S';
                $data['pade_title13'] = 'Current Fwd Premium';
                $data['pade_title14'] = 'Current Fwd Rate';
                $data['pade_title15'] = 'Wash Rate';
                $data['pade_title16'] = 'MTM';
                $data["page_heading"] = "Forward Cover";
                $data["menuslinks"] = $this->request->uri->getSegment(1);
                return view('templates/default', $data);
            }
}