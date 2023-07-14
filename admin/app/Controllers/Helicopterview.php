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
	$spotrates = $this->adminSpotrates($curren['Currency']);
	$jsondataspotrates = $this->json_validator($spotrates);
	if($jsondataspotrates){
		$decodedspotrates = json_decode($spotrates, true);
	if(!empty($decodedspotrates)){
		$length = count($decodedspotrates['data']);
		if($length == 2){
			$data['spotrateExport'] = $decodedspotrates['data']['2']['B'];
			$data['spotrateImport'] = $decodedspotrates['data']['3']['B'];
		}
	}
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
	$helicopterArrayimport = $this->groupByQuarter($helicoptertabsimport);
	$data["helicoptertabs"] = $this->convertArrayByQuarter($helicopterArrayimport);

	// $helicoptertabsexport = $this->transaction_model->helicopterviewcommon1($curid, 1);
	// echo '<pre>';
	// print_r($helicoptertabsexport);
	// exit;
	$helicoptertabsexport = $this->transaction_model->helicopterviewcommon($curid, 1);
	$helicopterArrayexport  = $this->groupByQuarter($helicoptertabsexport);
	$data["helicoptertabsexport"]   = $this->convertArrayByQuarter($helicopterArrayexport);
	
	
		
	$helicoptertabsbuyersCredit = $this->transaction_model->helicopterviewcommon($curid, 3);
	$helicopterArraybuyersCredit  = $this->groupByQuarter($helicoptertabsbuyersCredit);
	$data["helicoptertabsbuyersCredit"] = $this->convertArrayByQuarter($helicopterArraybuyersCredit);

	$helicoptertabsbuyersmisc = $this->transaction_model->helicopterviewcommon($curid, 5);
	$helicopterArraybuyersmisc  = $this->groupByQuarter($helicoptertabsbuyersmisc);
	$data["helicoptertabsbuyersmisc"] = $this->convertArrayByQuarter($helicopterArraybuyersmisc);

	$helicoptertabscapitalpaymnts = $this->transaction_model->helicopterviewcommon($curid, 4);
	$helicopterArrayCpaymnts  = $this->groupByQuarter($helicoptertabscapitalpaymnts);
	$data["helicoptertabscapitalpaymnts"] = $this->convertArrayByQuarter($helicopterArrayCpaymnts);




	$data['title'] = isset($_GET['currencyieshelicopterview']) ? 'Helicopter View as on '.date('d-M-y') : 'Select Currency To View Helicopter View';
	$data['pade_title1'] = 'Currency';
	$data['i'] = 1;
	$currentDate = new \DateTime();  // Create a DateTime object representing the current date
	$futureDate = $currentDate->modify('+30 days');  // Add 30 days to the current date
	$futureDate = $currentDate->format('Y-m-d');
	$spotrateimportsval = $mtmOperatingrisk->forrwardCalculator(2, $curid, $futureDate);
	$jsondataimports = $this->json_validator($spotrateimportsval);
	if($jsondataimports){
	$data['spotrateimports']  = floatval(json_decode($spotrateimportsval)->result->spot_rate);
	}else {
	$data['spotrateimports'] = 1.0; 
	}

	$spotratexportsval = $mtmOperatingrisk->forrwardCalculator( 1, $curid,  $futureDate);
	$jsondataexports = $this->json_validator($spotratexportsval);
	if($jsondataexports){
	$data['spotrateexports']  = floatval(json_decode($spotratexportsval)->result->spot_rate);
	}else {
	$data['spotrateexports'] = 1.0;
	}
	$data['pade_title5'] = 'Forward/ Option';
	$data["page_heading"] = "Helicopterview";
	$data["table_heading"] = "Amount outstanding in USD";
	$data["table_second_heading"] = "All Months";
	$data["menuslinks"] = $this->request->uri->getSegment(1);
	return view('templates/default', $data);
	}


	function json_validator($data) {
        if (!empty($data)) {
            return is_string($data) && 
              is_array(json_decode($data, true)) ? true : false;
        }
        return false;
    }

	public function adminSpotrates($curren = ''){

		try {
			$query_params = http_build_query(array(
				"type" => $curren,
			));
			$url = 'https://fxmanagers.in/ajax/adminSpotrate?' . $query_params;
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_HTTPGET => true,
			));
			$response = curl_exec($curl);
			curl_close($curl);
			return $response;
		} catch (Exception $e) {
			return '';
			// Handle the exception
		}
}


	function convertArrayByQuarter($inputArray) {
		$outputArray = array();
		
		// Initialize the output array with Year key and empty quarter values
		$outputArray[1] = array(
			'Year' => date('Y'),
			'Q1' => null,
			'Q2' => null,
			'Q3' => null,
			'Q4' => null
		);
		
		foreach ($inputArray as $item) {
			$quarter = $item['Quarter'];
			$totalAmountinFC = number_format($item['TotalAmountinFC'], 2, '.', '');
			$totalContractedRate = number_format($item['TotalContractedRate'], 2, '.', '');
			$totalCalculatedTargetRate = number_format($item['TotalCalculatedTargetRate'], 2, '.', '');
			$totalAmountFC = number_format($item['TotalAmountFC'], 2, '.', '');
			$totalSpotRate = number_format($item['TotalSpotRate'], 2, '.', '');
			$targetRate = number_format($item['TargetRate'], 2, '.', '');
			
			$outputArray[1][$quarter] = "{$totalAmountinFC},{$totalContractedRate},{$totalCalculatedTargetRate},{$totalAmountFC},{$totalSpotRate},{$targetRate}";
		}
		
		return $outputArray;
	}
	
	

	public function convertArrayByQuarteru($array)
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
				$row['TargetRate'],
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
                'TotalCalculatedTargetRate' => 0,
				'TargetRate' => 0,
            );
        }
        
        $resultArray[$quarter]['TotalAmountinFC'] += (float) $subArray['amountinFC'];
        $resultArray[$quarter]['TotalContractedRate'] += (float) $subArray['contracted_Rate'];
        $resultArray[$quarter]['TotalCalculatedTargetRate'] += (float) $subArray['calculated_targetRate'];
        $resultArray[$quarter]['TotalAmountFC'] += (float) $subArray['amount_FC'];
        $resultArray[$quarter]['TotalSpotRate'] += (float) $subArray['spot_rate'];
		$resultArray[$quarter]['TargetRate'] += (float) $subArray['targetRate'];

    }
    
    return array_values($resultArray);
}


}


