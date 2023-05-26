<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BankModel as Bank_Model;
class Bank extends BaseController
{
    public function __construct() {
        parent::__construct();
        $this->bank_model = new Bank_Model();
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
        $data["title"] = "Bank Details";
		$data["page_title"] = "Bank Details";
        $data["bank"] = $this->bank_model->orderBy('bank_id', 'DESC')->findAll();
        $data["page_heading"] = "Add New Bank";
        $data["request"] = $this->request;
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        if($this->permission[0]>0){
            $data["link"] = "addNewBankList";
          }else{
            $data["link"] = "#";
          }
        if($this->permission[1]>0){
            $data["edit_bank"] = "edit_bank";
          }else{
            $data["edit_bank"] = "#";
          }
        if($this->permission[2]>0){
            $data["delete_bank"] = "delete_bank";
          }else{
            $data["delete_bank"] = "#";
          }
        $data["view"] = "bank/banklist";
        return view('templates/default', $data);
    }
   
    public function addNewBankList()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data['session'] = $session;
        $data['title'] = 'Add Bank Details';
        $data['pade_title1'] = 'Enter Bank';
        $data['pade_title2'] = 'Bank Symbol';
        $data['pade_title3'] = 'Choose Bank Status';
        $data['pade_title4'] = 'Enter Sort Order';
        $data['pade_title5'] = 'Choose Default Value';
        $data['menuslinks'] = $this->request->uri->getSegment(1);
        $data["view"] = "bank/banksave";
        return view('templates/default', $data);
    }


    public function savenewbank(){
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
            
            $data = ['bank_name' => $bankName , 'status' => $status, 'created_at' => date('Y-m-d') ] ;
        }
       
     
        if (!empty($bank_hid_id))
        {
            $update =  $this->bank_model->where('bank_id', $bank_hid_id)->set($data)->update();
            if ($update) {
                $session->setFlashdata("success","Updated Successfully");
            }else{
                $session->setFlashdata("error","Update Failed");
            }
            return redirect()->to("banklist");
        }else{
            $save =  $this->bank_model->save($data);
            if ($save) {
                $session->setFlashdata("success","Saved Successfully");
            }else{
                $session->setFlashdata("error","Failed To Save");
            }
            return redirect()->to("banklist");
        }


   
    }
}


public function edit_bank($id = ''){
    if ($id == null) {
        return redirect()->to("Admindashboard");
    }
    $this->loadUser();
    $session = session();
    $pot = json_decode(json_encode($session->get("userdata")), true);
    if (empty($pot)) {
        return redirect()->to("/");
    }
    $data['query'] = $this->bank_model->where("bank_id  = '{$id}'")->first();
   
    $data['session'] = $session;
    $data['title'] = 'Edit Bank Details';
    $data['pade_title1'] = 'Edit Bank';
    $data['pade_title2'] = 'Bank Symbol';
    $data['pade_title3'] = 'Choose Bank Status';
    $data['pade_title4'] = 'Enter Sort Order';
    $data['pade_title5'] = 'Choose Default Value';
    $data['menuslinks'] = $this->request->uri->getSegment(1);
    $data["view"] = "bank/banksave";
    return view('templates/default', $data);
}



public function delete_bank($id = ''){
    if ($id == null) {
        return redirect()->to("Admindashboard");
    }
    $this->loadUser();
    $session = session();
    $pot = json_decode(json_encode($session->get("userdata")), true);
    if (empty($pot)) {
        return redirect()->to("/");
    }
    $delete = $this->bank_model
    ->where('bank_id', $id)->delete();

    if($delete){
        $session->setFlashdata('success', 'Bank  Deleted Successfully');
    }else{
        $session->setFlashdata('error', 'Bank Failed to  Deleted');
    }
    return redirect()->to("banklist");
}


}
