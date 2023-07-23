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


class ForwardAllocation extends BaseController
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
        //$ee = $this->forwardCover_model->orderBy('bank', 'DESC')->findAll();
        $data["view"] = "ForwardAllocation/forwardallocation";
        $data["page_title"] = "Forward Allocation";
        $data["session"] = $session;
        if ($this->permission[0] > 0) {
        $data["link"] = "addnewroles";
        } else {
        $data["link"] = "#";
        }
        $data['title'] = 'Forward Allocation';
        $data['pade_title1'] = 'Exposure Ref No';
        $data['i'] = 1;
        $data["exposuretype"] = $this->transaction_model ->select('exposurereInfo')->select('transaction_id')->orderBy('transaction_id', 'DESC')->find();
        $data["currency"] = $this->currency_model->orderBy('currency_id', 'DESC')->findAll();
        $data["bank"] = $this->bank_model->orderBy('bank_id', 'DESC')->findAll();
        $data['pade_title5'] = 'Currency';
        $data['pade_title3'] = 'Deal No';
        $data['pade_title33'] = 'Deal Date';
        $data['pade_title2'] = 'Bank Name';
        $data['pade_title4'] = 'Currency';
        $data['pade_title5'] = 'Buy/Sell';
        $data['pade_title6'] = 'Mature Date';
        $data['pade_title8'] = 'Forward Amount O/S';
        $data['pade_title9'] = 'Contracted Rate';
        $data['pade_title10'] = 'Date Of Allocation';
        $data['pade_title11'] = 'Amount In FC';
        $data['pade_title12'] = 'Amount Allocated';
        $data["pade_title13"] = "Free Forward Amount";
        $data["pade_title14"] = "Unallocated Amount In Fc";
        $data["page_heading"] = "Forward Allocation Details";
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        return view('templates/default', $data);
    }


    public function forwardallocationdependantdata(){
            if ($this->request->getMethod() == 'post') {
                extract($this->request->getPost());
                if(!empty($selectedValue)){
                   $transactiondata = $this->transaction_model
                   ->select("transactiondetails.exposureType, bank_master.bank_name, transactiondetails.currency, currency.Currency, transactiondetails.bank_id")
                   ->join('currency',"transactiondetails.currency = currency.currency_id",'left')
                   ->join('bank_master',"bank_master.bank_id = transactiondetails.bank_id",'left')
                   ->where("transactiondetails.transaction_id = $selectedValue")->first();
                   echo json_encode($transactiondata);
                }        
        }
    }

    public function dependantdealno(){
        if ($this->request->getMethod() == 'post') {
            extract($this->request->getPost());
            if(!empty($bank_id)){
                $transactiondata = $this->forwardCover_model
                ->select("dealno")
                ->where("forwardcovers.bank = $bank_id")->findAll();
                echo json_encode($transactiondata);
            }
        }
    }
    
}
