<?php

namespace App\Controllers;

defined('BASEPATH') or exit('No direct script access allowed');

use App\Controllers\BaseController;
use Config\Database;
use App\Models\TransactionModel as Transaction_Model;
use App\Models\ForwardCoverdetails as ForwardCoverdetails_Model;
use App\Models\CurrencyModel as Currency_Model;
class Exposuresummary extends BaseController
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
        $data['style'] = isset($_GET['currency']) ? 'block' : 'none'; 
        $curid = isset($_GET['currency']) ? $_GET['currency'] : 2; 
        $data["transactiontabs"] = $this->transaction_model->tabsarrangement($curid);
        $data["transactiontabsexport"] = $this->transaction_model->tabsarrangementforexport($curid);
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $this->loadUser();
        $data["view"] = "Exposuresummary/exposuresummary";
        $data["page_title"] = "Exposure Summary";
        $data["session"] = $session;
        if ($this->permission[0] > 0) {
            $data["link"] = "addnewroles";
        } else {
            $data["link"] = "#";
        }
		$data["transaction"] = $this->transaction_model
		->distinct()
		->select("transactiondetails.currency, currency.Currency")
		->join('currency', "transactiondetails.currency = currency.currency_id", 'left')
		->findAll();
		$data_by_month = array();
		// Iterate over the transactions and group them by month
		foreach ($data["transactiontabs"] as $transaction) {
		$month = $transaction['month'];
		if (!isset($data_by_month[$month])) {
		$data_by_month[$month] = array();
		}
		$data_by_month[$month][] = $transaction;
		}
		$data_by_month_export = array();
		// Iterate over the transactions and group them by month
		foreach ($data["transactiontabsexport"] as $transactionexport) {
		$month = $transactionexport['month'];
		if (!isset($data_by_month_export[$month])) {
		$data_by_month_export[$month] = array();
		}
		$data_by_month_export[$month][] = $transactionexport;
		}
		$data['databymonthexport'] = $data_by_month_export;
		$data['databymonth'] = $data_by_month;
         $data['title'] = isset($_GET['currency']) ? 'FX Exposure Summary as on '.date('d-M-y') : 'Select Currency To View FX Exposure Summary';
		 $data['pade_title1'] = 'Currency';
		 $data['i'] = 1;
		 $data['pade_title5'] = 'Forward/ Option';
        $data["page_heading"] = "Exposure Summary";
		$data["table_heading"] = "Amount outstanding in USD";
		$data["table_second_heading"] = "All Months";
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        return view('templates/default', $data);
	}

}