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
use App\Models\ForwardCover as ForwardCover_Model;

class ForwardCover extends BaseController
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
    $this->forwardCover_model = new ForwardCover_Model();
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
        $data["view"] = "Forwardcover/forwardcover";
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
        $data['pade_title18'] = 'Deal Date';
        $data['pade_title4'] = 'Currency Sold';
        $data['pade_title6'] = 'Due Date (From)';
        $data['pade_title7'] = 'Due Date (To)';
        $data['pade_title8'] = 'Spot Rate';
        $data['pade_title9'] = 'Premium';
        $data['pade_title10'] = 'Margin';
        $data['pade_title11'] = 'Contracted Rate';
        $data['pade_title12'] = 'Forward Amount O/S';
        $data['pade_title13'] = 'Current Fwd Premium';
        $data['pade_title14'] = 'Current Fwd Rate';
        $data['pade_title15'] = 'Wash Rate';
        $data['pade_title16'] = 'MTM';
        $data["page_heading"] = "Forward Cover";
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        return view('templates/default', $data);
    }

    public function forwardcoverdependant(){
    if ($this->request->getMethod() == 'post') {
    extract($this->request->getPost());
    if((!empty($formattedDate) && !empty($type)) && !empty($currency)){
        $firstresponse = $this->forrwardCalculator($type , $currency , $formattedDate );
        if($this->json_validator($firstresponse)){
            $resultfirst = json_decode($firstresponse);
            $response =  isset($resultfirst->result->forward_rate) ?  $resultfirst->result->forward_rate : 1;
        }else{
            $response = 1;
        }
        echo json_encode($response);
    }
    }
    }

    public function forwardcovermtm(){
        if ($this->request->getMethod() == 'post') {
            extract($this->request->getPost());
            if((!empty($formattedDate) && !empty($type)) && !empty($currency)){
                $curren = $this->currency_model->select("Currency")->where('currency_id', $currency)->first();
                    if (strpos($curren['Currency'], 'INR') !== false) {
                    $response = 1;
                    }elseif($curren['Currency'] == 'EURUSD' || $curren['Currency'] == 'GBPUSD'){
                    $currency = 'USDINR';
                    $firstresponse = $this->forrwardCalculatorCurrencywise($type , $currency , $formattedDate );
                    if($this->json_validator($firstresponse)){
                        $resultfirst = json_decode($firstresponse);
                        $response =  isset($resultfirst->result->forward_rate) ?  $resultfirst->result->forward_rate : 1;
                    }else{
                        $response = 1;
                    }
                    }elseif($curren['Currency'] == 'USDJPY'){
                    $currency = 'JPYINR';
                    $firstresponse = $this->forrwardCalculatorCurrencywise($type , $currency , $formattedDate );
                    if($this->json_validator($firstresponse)){
                        $resultfirst = json_decode($firstresponse);
                        $response =  isset($resultfirst->result->forward_rate) ?  $resultfirst->result->forward_rate : 1;
                    }else{
                        $response = 1;
                    }
                    }

                echo json_encode($response);
            } 
    }
}

public function json_validator($data) {
    if (!empty($data)) {
        return is_string($data) && 
          is_array(json_decode($data, true)) ? true : false;
    }
    return false;
}

public function forrwardCalculator($cover_type , $currency , $forward_date )
{
    try{
        $curren = $this->currency_model->select("Currency")->where('currency_id', $currency)->first();
        $covertype = !empty($cover_type) && $cover_type == 1 ? 1 : 2;
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://www.fxmanagers.in/ajax/ajaxbroken',
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


public function forrwardCalculatorCurrencywise($cover_type , $currency , $forward_date )
{
    try{
        $covertype = !empty($cover_type) && $cover_type == 1 ? 1 : 2;
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://www.fxmanagers.in/ajax/ajaxbroken',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array("cover_type" => $covertype,"currency" => $currency , "forward_date" => "$forward_date"), 
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    } catch (\Exception$e) {
        return "";
    }            
}

public function saveforwardcover(){
    $this->loadUser();
    $session = session();
    $pot = json_decode(json_encode($session->get("userdata")), true);
    if (empty($pot)) {
    return redirect()->to("/");
    }
    if ($this->request->getMethod() == 'post') {
        extract($this->request->getPost());

        $udata["bank"] = $bank;
        $udata["dealno"] = $dealno;
        $udata["dealdate"] = $dealdate;
        $udata["currency"] = $currency;
        $udata["buysell"] = $buysell;
        $udata["dealdatefrom"] = $dealdatefrom;
        $udata["dealdateto"] = $dealdateto;
        $udata["spotrate"] = $spotrate;
        $udata["premium"] = $premium;
        $udata["margin"] = $margin;
        $udata["contrctedrate"] = $contrctedrate;
        $udata["forwardamountos"] = $forwardamountos;
        $udata["currentforwardpremium"] = $currentforwardpremium;
        $udata["currentforwardrate"] = $currentforwardrate;
        $udata["washrate"] = $washrate;
        $udata["mtm"] = $mtm;
        $saved = $this->forwardCover_model->save($udata);
        (empty($saved)) ? $session->setFlashdata('error', 'Failed To Save') : $session->setFlashdata('success', 'Saved Successfully');
    
    }
    $input = $this->validate(['bank' => 'required', 'dealno' => 'required', 'dealdate' => 'required', 'currency' => 'required', 'buysell' => 'required', 'dealdatefrom' => 'required', 'dealdateto' => 'required', 'spotrate' => 'required' , 'premium' => 'required', 'margin' => 'required', 'contrctedrate' => 'required', 'forwardamountos' => 'required', 'currentforwardpremium' => 'required', 'currentforwardrate' => 'required', 'washrate' => 'required', 'mtm' => 'required']);
    if (!empty($input)) {
        $udata["bank"] = $bank;
        $udata["dealno"] = $dealno;
        $udata["dealdate"] = $dealdate;
        $udata["currency"] = $currency;
        $udata["buysell"] = $buysell;
        $udata["dealdatefrom"] = $dealdatefrom;
        $udata["dealdateto"] = $dealdateto;
        $udata["spotrate"] = $spotrate;
        $udata["premium"] = $premium;
        $udata["margin"] = $margin;
        $udata["contrctedrate"] = $contrctedrate;
        $udata["forwardamountos"] = $forwardamountos;
        $udata["currentforwardpremium"] = $currentforwardpremium;
        $udata["currentforwardrate"] = $currentforwardrate;
        $udata["washrate"] = $washrate;
        $udata["mtm"] = $mtm;
        $saved = $this->forwardCover_model->save($udata);
        (empty($saved)) ? $session->setFlashdata('error', 'Failed To Save') : $session->setFlashdata('success', 'Saved Successfully');
    }else {
    $session->setFlashdata('error', 'Fill All Fields');
    }
    return redirect()->to('forwardcover');
}

}
