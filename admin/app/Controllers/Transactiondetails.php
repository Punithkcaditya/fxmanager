<?php

namespace App\Controllers;

defined('BASEPATH') or exit('No direct script access allowed');

use App\Controllers\BaseController;
use Config\Database;
use App\Models\TransactionModel as Transaction_Model;
use App\Models\ExposureType as ExposureType_Model;
use App\Models\CurrencyModel as Currency_Model;
use App\Models\CounterpartyModel as Counterparty_Model;
use App\Models\BankModel as Bank_Model;
class Transactiondetails extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
        $request = \Config\Services::request();
        helper(['form', 'url', 'string']);
        $this->transaction_model = new Transaction_Model();
        $this->bank_model = new Bank_Model();
		$this->exposuretype_model = new ExposureType_Model();
		$this->currency_model = new Currency_Model();
		 $this->counterparty_model = new Counterparty_Model();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        } else {
            $role_id = $pot["role_id"];
        }
        $menutext = $this->request->uri->getSegment(2);
        if (isset($_SESSION['sidebar_menuitems'])) {
            foreach ($_SESSION['sidebar_menuitems'] as $main_menus):
                if (strtolower($main_menus->menuitem_link) == strtolower($menutext)) {
                    $permissions = $this->admin_roles_accesses_model->get_permisions($role_id, $main_menus->menuitem_id);
                    $this->permission = array($permissions->add_permission, $permissions->edit_permission, $permissions->delete_permission);
                } else {
                    if (!empty($main_menus->submenus)):
                        foreach ($main_menus->submenus as $submenus):
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
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if ($this->permission[0] > 0) {
            $data["link"] = "addNewBanner";
        } else {
            $data["link"] = "#";
        }
		$data["exposuretype"] = $this->exposuretype_model->orderBy('exposure_type_id', 'ASC')->findAll();
		$data["currency"] = $this->currency_model->orderBy('currency_id', 'DESC')->findAll();
        $data["bank"] = $this->bank_model->orderBy('bank_id', 'DESC')->findAll();
        $data["counterparty"] = $this->counterparty_model->orderBy('counterParty_id', 'ASC')->findAll();
		$data['i'] = 1;
		$data['user_id'] = 1;
        $data['session'] = $session;
        $data['title'] = 'Transaction Details';
		 $data["page_title"] = "Transaction Details";
        $data['page_heading'] = 'Transaction Details';
        $data['pade_title1'] = 'Exposure Ref. No';
        $data['pade_title2'] = 'Date of Invoice';
        $data['pade_title3'] = 'Select Time';
        $data['pade_title4'] = 'Currency';
        $data['pade_title5'] = 'Counter Party';
        $data['pade_title6'] = 'Exposure Type';
        $data['pade_title7'] = 'Amount in FC';
        $data['pade_title8'] = 'Target Rate';
        $data['pade_title9'] = 'Due Date';
		$data['pade_title10'] = 'Counter Party Country';
		$data['pade_title11'] = 'Choose Bank';
        $data['request'] = $this->request;
        $data["link"] = "#";
        $data['menuslinks'] = $this
            ->request
            ->uri
            ->getSegment(1);
        $data["view"] = "Transaction/transactiondetails";
        return view('templates/default', $data);
    }

  


            public function savetransactiondetails()
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
            $input = $this->validate(['exposureref' => 'required', 'counterPartycountry' => 'required', 'counter' => 'required', 'exposure' => 'required', 'date' => 'required', 'duedate' => 'required', 'currency' => 'required', 'targetrat' => 'required', 'amountinfc' => 'required', 'bank' => 'required']);
            if (!empty($input)) {
            $result = array_map(null, $exposureref, $counterPartycountry, $counter, $exposure, $date, $duedate, $currency, $targetrat, $amountinfc, $bank, $inr_field_value);
            foreach ($result as $key => $sid) {
            if (empty($result[$key])) {
            unset($result[$key]);
            }
            }
            foreach ($result as $key => $sid) {
                $formatedduedate = $this->convertDateFormat($sid[5]);
            $resoval = $this->forrwardCalculator($sid[3], $sid[6], $formatedduedate);
            $response = json_decode($resoval);
            try {           
            $data = [
            'exposurereInfo' => $sid[0],
			'counterPartycountry' => $sid[1],
            'counterParty' => $sid[2],
            'exposureType' => $sid[3],
            'dateofInvoice' => $this->convertDateFormat($sid[4]),
            'dueDate' => $formatedduedate,
            'currency' => $sid[6],
            'targetRate' => $sid[7],
            'spot_rate' => $response->result->spot_rate,
            'forward_rate' => $response->result->forward_rate,
            'amountinFC' => $sid[8],
            'bank_id' => $sid[9],
            'inr_target_value' => $sid[10],
            'created_date' => date('Y-m-d'),
            ];
            $saved = $this->transaction_model->save($data);
            } catch (\Exception$e) {
                $session->setFlashdata('error', 'No Data for Selected Due Date');
                return redirect()->to('transactiondetails');
            }
        }
            
            (empty($saved)) ? $session->setFlashdata('error', 'Failed To Save') : $session->setFlashdata('success', 'Saved Successfully');
            } else {
            $session->setFlashdata('error', 'Fill All Fields');
            }
            return redirect()->to('transactiondetails');
            }
			
			
			public function transactionlist(){
			$this->loadUser();
            $session = session();
            $pot = json_decode(json_encode($session->get("userdata")), true);
            if (empty($pot)) {
            return redirect()->to("/");
            }
			$role_id = $pot["role_id"];
			$data["view"] = "Transaction/transactionlist";
			$data["page_title"] = "Add New Transaction";
			$data["session"] = $session;
			if ($this->permission[0] > 0) {
			$data["link"] = "transactiondetails";
			} else {
			$data["link"] = "#";
			}
			if ($this->permission[1] > 0) {
			$data["transaction_edit"] = "transactionlistedit";
			} else {
			$data["transaction_edit"] = "#";
			}
			if ($this->permission[2] > 0) {
			$data["transaction_delete"] = "transactionlistdelete";
			} else {
			$data["transaction_delete"] = "#";
			}
			 $data["transaction"] = $this->transaction_model
                ->orderBy("transaction_id", "DESC")
                ->findAll();
			$data["page_heading"] = "Add New Transaction";
			$data["menuslinks"] = $this->request->uri->getSegment(1);
			return view('templates/default', $data);
			}


            public function forrwardCalculator($cover_type , $currency , $forward_date )
            {
                try{
                    $curren = $this->currency_model->select("Currency")->where('currency_id', $currency)->first();
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
                      CURLOPT_POSTFIELDS => array("cover_type" => $covertype,"currency" => $curren['Currency'] , "forward_date" => "$forward_date"), 
                    ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                    return $response;
                } catch (\Exception$e) {
                    return "";
                }            
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