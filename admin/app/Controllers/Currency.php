<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CurrencyModel as Currency_Model;
class Currency extends BaseController
{
    public function __construct() {
        parent::__construct();
        $this->currency_model = new Currency_Model();
        $request = \Config\Services::request();
        helper(['form', 'url', 'string']);
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        } else {
            $role_id = $pot["role_id"];
        }
        $menutext = $this->request->uri->getSegment(2);
        if(isset($_SESSION['sidebar_menuitems'])){
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
   
  
        $data["session"] = $session;
        $data["title"] = "Currency Details";
		$data["page_title"] = "Currency Details";
        $data["currency"] = $this->currency_model->orderBy('currency_id', 'DESC')->findAll();
        $data["page_heading"] = "Add New Currency";
        $data["request"] = $this->request;
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        if($this->permission[0]>0){
            $data["link"] = "addNewCurrencyList";
          }else{
            $data["link"] = "#";
          }
        if($this->permission[1]>0){
            $data["edit_currency"] = "edit_currency";
          }else{
            $data["edit_currency"] = "#";
          }
        if($this->permission[2]>0){
            $data["delete_currency"] = "delete_currency";
          }else{
            $data["delete_currency"] = "#";
          }
        $data["view"] = "currency/currencylist";
        return view('templates/default', $data);
    }
    public function addNewCurrency(){
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data['session'] = $session;
        $data['title'] = 'Add Currency Details';
        $data['pade_title1'] = 'Enter Currency';
        $data['pade_title2'] = 'Currency Symbol';
        $data['pade_title3'] = 'Choose Currency Status';
        $data['pade_title4'] = 'Enter Sort Order';
        $data['pade_title5'] = 'Choose Default Value';
        $data['menuslinks'] = $this->request->uri->getSegment(1);
        $data["view"] = "currency/currencysave";
        return view('templates/default', $data);
    }

    public function savenewcurrency(){
        $this->loadUser();
        helper(['form', 'url']);
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }else {
            $user_id = $pot['user_id'];
            $role_id = $pot['role_id'];
        }

        if ($this->request->getMethod() == 'post')
       {
        extract($this->request->getPost());{
            
            $data = ['Currency' => $currencyName , 'status' => $currency_status, 'created_at' => date('Y-m-d') ] ;
        }
       
     
        if (!empty($currency_hid_id))
        {
            $update =  $this->currency_model->where('currency_id', $currency_hid_id)->set($data)->update();
            if ($update) {
                $session->setFlashdata("success","Updated Successfully");
            }else{
                $session->setFlashdata("error","Update Failed");
            }
            return redirect()->to("currencylist");
        }else{
            $save =  $this->currency_model->save($data);
            if ($save) {
                $session->setFlashdata("success","Saved Successfully");
            }else{
                $session->setFlashdata("error","Failed To Save");
            }
            return redirect()->to("currencylist");
        }


   
    }
}

public function edit_currency($id = ''){
    if ($id == null) {
        return redirect()->to("Admindashboard");
    }
    $this->loadUser();
    $session = session();
    $pot = json_decode(json_encode($session->get("userdata")), true);
    if (empty($pot)) {
        return redirect()->to("/");
    }
    $data['query'] = $this->currency_model->where("currency_id = '{$id}'")->first();
   
    $data['session'] = $session;
    $data['title'] = 'Edit Currency Details';
    $data['pade_title1'] = 'Edit Currency';
    $data['pade_title2'] = 'Currency Symbol';
    $data['pade_title3'] = 'Choose Currency Status';
    $data['pade_title4'] = 'Enter Sort Order';
    $data['pade_title5'] = 'Choose Default Value';
    $data['menuslinks'] = $this->request->uri->getSegment(1);
    $data["view"] = "currency/currencysave";
    return view('templates/default', $data);
}


public function delete_currency($id = ''){
    if ($id == null) {
        return redirect()->to("Admindashboard");
    }
    $this->loadUser();
    $session = session();
    $pot = json_decode(json_encode($session->get("userdata")), true);
    if (empty($pot)) {
        return redirect()->to("/");
    }
    $delete = $this->currency_model
    ->where('currency_id', $id)->delete();

    if($delete){
        $session->setFlashdata('success', 'Currency  Deleted Successfully');
    }else{
        $session->setFlashdata('error', 'Currency Failed to  Deleted');
    }
    return redirect()->to("currencylist");
}

public function updatedefaultcurrencyid(){
    helper(['form', 'url']);
    $session = session();
    if ($this->request->getMethod() == "post") {
        extract($this->request->getPost());
        $currencyid = $currencyid;
        $data = ['defaultCurrency' => 2];
        $update = $this->currency_model->where('defaultCurrency', 1)->set($data)->update();
        if($update){
            $newdata = ['defaultCurrency' => 1];
            $updatenew = $this->currency_model->where('currencyId', $currencyid)->set($newdata)->update();
            if($updatenew){
                $msg['status'] = 1;
                echo json_encode($msg);
                exit();
            }else{
                $msg['status'] = 0;
                echo json_encode($msg);
                exit(); 
            }
        }
    }
}

}
