<?php

namespace App\Controllers;

defined('BASEPATH') or exit('No direct script access allowed');

use App\Controllers\BaseController;
use Config\Database;
use App\Models\TransactionModel as Transaction_Model;
use App\Models\ExposureType as ExposureType_Model;
use App\Models\CurrencyModel as Currency_Model;
use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Database\Query;
class MtmOperatingrisk extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
        $request = \Config\Services::request();
        helper(['form', 'url', 'string']);
        $this->transaction_model = new Transaction_Model();
		$this->exposuretype_model = new ExposureType_Model();
		$this->currency_model = new Currency_Model();
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
        $data["transaction"] = $this->transaction_model->mtmoperatingrisk();
		$data["exposuretype"] = $this->exposuretype_model->orderBy('exposure_type_id', 'DESC')->findAll();
		$data["currency"] = $this->currency_model->orderBy('currency_id', 'DESC')->findAll();
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
        $data['request'] = $this->request;
        $data["link"] = "#";
        $data['menuslinks'] = $this
            ->request
            ->uri
            ->getSegment(1);
            $data['controller'] = $this;
            // echo($this->forrwardCalculator());
        $data["view"] = "Mtmoperatingrisk/mtmoperatingrisklists";
        return view('templates/default', $data);

    }


 
    public function forrwardCalculator($cover_type , $currency , $forward_date )
    {
        
        try{
            $date = date("Y-m-d", strtotime($forward_date));
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
              CURLOPT_POSTFIELDS => array("cover_type" => $covertype,"currency" => $curren['Currency'] , "forward_date" => $date), 
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } catch (\Exception$e) {
            return "";
        }            
    }


}