<?php

namespace App\Controllers;

defined('BASEPATH') or exit('No direct script access allowed');

use App\Controllers\BaseController;
use Config\Database;
use App\Models\TransactionModel as Transaction_Model;
use App\Models\ForwardCoverdetails as ForwardCoverdetails_Model;
use App\Models\CurrencyModel as Currency_Model;
use App\Controllers\MtmOperatingrisk;

class Helicopterview extends BaseController
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
	$mtmOperatingrisk = new MtmOperatingrisk();
	$curid = isset($_GET['currencyieshelicopterview']) ? $_GET['currencyieshelicopterview'] : 2; 
	$curren = $this->currency_model->select("Currency")->where('currency_id', $curid)->first();
	if (!isset($curren['Currency'])) {
		return redirect()->to("adminlogout");
	}
	$data['style'] = isset($_GET['currencyieshelicopterview']) ? 'block' : 'none'; 
	$session = session();
	$pot = json_decode(json_encode($session->get("userdata")), true);
	if (empty($pot)) {
	return redirect()->to("/");
	}
	$this->loadUser();
	$data["view"] = "Helicopterview/helicopterview";
	$data["page_title"] = "Helicopterview Summary";
	$data["session"] = $session;
	if ($this->permission[0] > 0) {
	$data["link"] = "#";
	} else {
	$data["link"] = "#";
	}
	$data["transaction"] = $this->transaction_model
	->distinct()
	->select("transactiondetails.currency, currency.Currency")
	->join('currency', "transactiondetails.currency = currency.currency_id", 'left')
	->findAll();
	$data["helicoptertabs"] = $this->transaction_model->helicopterviewimport($curid);
	$data["helicoptertabsexport"] = $this->transaction_model->helicopterviewexport($curid);
	$data["helicoptertabsbuyersCredit"] = $this->transaction_model->helicopterviewbuyersCredit($curid);
	$data["helicoptertabsbuyersmisc"] = $this->transaction_model->helicopterviewbuyersmisc($curid);
	$data["helicoptertabscapitalpaymnts"] = $this->transaction_model->helicoptertabscapitalpaymnts($curid);
	$data['title'] = isset($_GET['currencyieshelicopterview']) ? 'Helicopter View as on '.date('d-M-y') : 'Select Currency To View Helicopter View';
	$data['pade_title1'] = 'Currency';
	$data['i'] = 1;
	$currentDate = new \DateTime();  // Create a DateTime object representing the current date
	$futureDate = $currentDate->modify('+30 days');  // Add 30 days to the current date
	$futureDate = $currentDate->format('Y-m-d');
	$spotrateimportsval = $mtmOperatingrisk->forrwardCalculator(2, $curid, $futureDate);
	$data['spotrateimports']  = floatval(json_decode($spotrateimportsval)->result->spot_rate);
	$spotratexportsval = $mtmOperatingrisk->forrwardCalculator( 1, $curid,  $futureDate);
	$data['spotrateexports']  = floatval(json_decode($spotratexportsval)->result->spot_rate);
	$data['pade_title5'] = 'Forward/ Option';
	$data["page_heading"] = "Helicopterview";
	$data["table_heading"] = "Amount outstanding in USD";
	$data["table_second_heading"] = "All Months";
	$data["menuslinks"] = $this->request->uri->getSegment(1);
	return view('templates/default', $data);
	}


}