<?php

namespace App\Controllers;

defined('BASEPATH') or exit('No direct script access allowed');

use App\Controllers\BaseController;
use Config\Database;
use App\Models\TransactionModel as Transaction_Model;
use App\Models\ForwardCoverdetails as ForwardCoverdetails_Model;
use App\Models\CurrencyModel as Currency_Model;
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
            $result = array_map(null,  $dealno, $dealdate, $refno, $fordwardoption, $currencybought, $currencysold, $amountFC, $contractedrate, $expirydate);
          
			foreach ($result as $key => $sid) {
            if (empty($result[$key])) {
            unset($result[$key]);
            }
            }
            foreach ($result as $key => $sid) {
            $data = [
            'deal_no' => $sid[0],
            'deal_date' => $sid[1],
            'underlying_exposure_ref' => $sid[2],
            'fordward_option' => $sid[3],
            'currencybought' => $sid[4],
            'currencysold' => $sid[5],
            'amount_FC' => $sid[6],
			'contracted_Rate' => $sid[7],
			'expiry_date' => $sid[8],
            'created_date' => date('Y-m-d'),
            ];

            $saved = $this->forwardcoverdetails_model->save($data);
            }
            
            (empty($saved)) ? $session->setFlashdata('error', 'Failed To Save') : $session->setFlashdata('success', 'Saved Successfully');
            } else {
            $session->setFlashdata('error', 'Fill All Fields');
            }
            return redirect()->to('forwardcoverdetails');
            }
}