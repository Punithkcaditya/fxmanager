<?php

namespace App\Controllers;

defined('BASEPATH') or exit('No direct script access allowed');

use App\Controllers\BaseController;
use Config\Database;
use App\Models\ForwardCancellation as ForwardCancellation_Model;
use App\Models\ForwardCoverdetails as ForwardCoverdetails_Model;
use App\Models\CurrencyModel as Currency_Model;
class ForwardCancellation extends BaseController
{
    protected $request;

    public function __construct()
    {
        parent::__construct();
        $request = \Config\Services::request();
        helper(['form', 'url', 'string']);
        $session = session();
		$this->forwardcancellation_model = new ForwardCancellation_Model();
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
        $data["view"] = "ForwardCancellation/ForwardCancellationdetails";
        $data["page_title"] = "Forward Cancellation/Utilization Details";
        $data["session"] = $session;
        if ($this->permission[0] > 0) {
            $data["link"] = "addnewroles";
        } else {
            $data["link"] = "#";
        }
         $data['title'] = 'Forward Cancellation/Utilization Details';
		 $data['pade_title1'] = 'Bank Name';
		 $data['i'] = 1;
		 $data["forwardcoverdetails"] = $this->forwardcoverdetails_model ->select('deal_no')->select('forward_coverdetails_id')->orderBy('forward_coverdetails_id', 'DESC')->find();
		
	     $data["currency"] = $this->currency_model->orderBy('currency_id', 'DESC')->findAll();
		 $data['pade_title5'] = 'Cancelled Forward Amount (FC)';
		 $data['pade_title6'] = 'Utilised Forward Amount (FC)';
		 $data['pade_title1'] = 'Deal No';
		 $data['pade_title2'] = 'Cancellation Rate';
		 $data['pade_title3'] = 'Select Time';
		 $data['pade_title4'] = 'Cancellation Date';
		 $data['pade_title7'] = 'Utilization Date';
		 $data['pade_title8'] = 'Amount (FC)';
		 $data['pade_title9'] = 'Utilization Rate';
		$data['pade_title11'] = 'Contracted rate';
        $data["page_heading"] = "Forward Cancellation/Utilization Details";
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        return view('templates/default', $data);
	}
	
	
            public function saveforwardcancellationdetails()
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
			
            $input = $this->validate(['deal_no' => 'required', 'utilisedforwardamount' => 'required', 'utilizationrate' => 'required', 'utilizationdate' => 'required', 'cancelledforwardamounthid' => 'required', 'cancellationrate' => 'required', 'cancellationdate' => 'required']);
            if (!empty($input)) {
            $result = array_map(null, $deal_no, $utilisedforwardamount, $utilizationrate, $utilizationdate, $cancelledforwardamounthid, $cancellationrate, $cancellationdate);
          
			foreach ($result as $key => $sid) {
            if (empty($result[$key])) {
            unset($result[$key]);
            }
            }
            foreach ($result as $key => $sid) {
            $data = [
            'deal_no' => $sid[0],
            'utilised_forward_amount' => $sid[1],
            'utilization_rate' => $sid[2],
            'utilization_date' => $sid[3],
            'cancelled_forward_amount' => $sid[4],
            'cancellation_rate' => $sid[5],
            'cancellation_date' => $sid[6],
            'created_date' => date('Y-m-d'),
            ];

            $saved = $this->forwardcancellation_model->save($data);
            }
            
            (empty($saved)) ? $session->setFlashdata('error', 'Failed To Save') : $session->setFlashdata('success', 'Saved Successfully');
            } else {
            $session->setFlashdata('error', 'Fill All Fields');
            }
            return redirect()->to('forwardcancellationutilizationdetails');
            }
}