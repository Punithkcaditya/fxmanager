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
	$helicoptertabsimport = $this->transaction_model->helicopterviewcommon($curid, 2);

	// $helicoptertabsexport = $this->transaction_model->helicopterviewcommonold($curid, 1);
	// echo '<pre>';
	// print_r($helicoptertabsexport);
	// exit;
	$helicoptertabsexport = $this->transaction_model->helicopterviewcommon($curid, 1);
	echo '<pre>';
	print_r($helicoptertabsexport);
	exit;
	$helicopterArrayexport  = $this->groupByQuarter($helicoptertabsexport);
	$helicopterArray  = $this->convertArrayByQuarter($helicopterArrayexport);
	echo '<pre>';
	print_r($helicopterArray);
	exit;
	

	
	$helicoptertabsbuyersCredit = $this->transaction_model->helicopterviewcommon($curid, 3);
	$helicoptertabsbuyersmisc = $this->transaction_model->helicopterviewcommon($curid, 5);
	$helicoptertabscapitalpaymnts = $this->transaction_model->helicopterviewcommon($curid, 4);



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




	public function convertArrayByQuarter($array)
	{
		$output = [];
		foreach ($array as $row) {
			$quarter =  $row['Quarter'];
			
			// Initialize the quarter values if not set
			if (!isset($output[$quarter])) {
				$output[$quarter] = [
					'Q1' => '',
					'Q2' => '',
					'Q3' => '',
					'Q4' => '',
				];
			}
			// Append the values for the corresponding quarter
			$output[$quarter][$quarter] .= implode(',', [
				$row['TotalAmountinFC'],
				$row['TotalContractedRate'],
				$row['TotalCalculatedTargetRate'],
				$row['TotalAmountFC'],
				$row['TotalSpotRate'],
			]) . ',';
		}
		// Remove the trailing comma from each quarter value
		foreach ($output as &$row) {
			foreach ($row as $key => &$value) {
				$value = rtrim($value, ',');
			}
		}
		return $output;
	}
	

public function groupByQuarter($inputArray) {
    $resultArray = array();
    
    foreach ($inputArray as $subArray) {
        $quarter = $subArray['quarter_name'];
        
        if (!isset($resultArray[$quarter])) {
            $resultArray[$quarter] = array(
                'Quarter' => $quarter,
                'TotalAmountinFC' => 0,
                'TotalContractedRate' => 0,
                'TotalAmountFC' => 0,
                'TotalSpotRate' => 0,
                'TotalCalculatedTargetRate' => 0
            );
        }
        
        $resultArray[$quarter]['TotalAmountinFC'] += (float) $subArray['amountinFC'];
        $resultArray[$quarter]['TotalContractedRate'] += (float) $subArray['contracted_Rate'];
        $resultArray[$quarter]['TotalAmountFC'] += (float) $subArray['amount_FC'];
        $resultArray[$quarter]['TotalSpotRate'] += (float) $subArray['spot_rate'];
        $resultArray[$quarter]['TotalCalculatedTargetRate'] += (float) $subArray['calculated_targetRate'];
    }
    
    return array_values($resultArray);
}


}


