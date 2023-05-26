<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CounterpartyModel as Counterparty_Model;
class Counterparty extends BaseController
{
    public function __construct() {
        parent::__construct();
        $this->counterparty_model = new Counterparty_Model();
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
        $data["title"] = "Counter Details";
		$data["page_title"] = "Counter Details";
        $data["counterparty"] = $this->counterparty_model->orderBy('counterParty_id', 'DESC')->findAll();
        $data["page_heading"] = "Add New Counter";

        $data["request"] = $this->request;
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        if($this->permission[0]>0){
            $data["link"] = "addNewCounterList";
          }else{
            $data["link"] = "#";
          }
        if($this->permission[1]>0){
            $data["edit_counterparty"] = "edit_counterparty";
          }else{
            $data["edit_counterparty"] = "#";
          }
        if($this->permission[2]>0){
            $data["delete_counterparty"] = "delete_counterparty";
          }else{
            $data["delete_counterparty"] = "#";
          }
        $data["view"] = "Counterparty/counterpartylist";
        return view('templates/default', $data);
    }


    public function addNewCounterpartyList(){
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data['session'] = $session;
        $data['title'] = 'Add Counterparty Details';
        $data['pade_title1'] = 'Enter Counter Party';
        $data['pade_title3'] = 'Choose Counterparty Type Status';
        $data['menuslinks'] = $this->request->uri->getSegment(1);
        $data["view"] = "Counterparty/counterpartysave";
        return view('templates/default', $data);
    }

    public function savenewCounterparty(){
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
            
            $data = ['counterPartyName' => $counterPartyName , 'status' => $counter_status, 'created_at' => date('Y-m-d') ] ;
        }
       
     
        if (!empty($counterParty_id_hid_id))
        {
            $update =  $this->counterparty_model->where('counterParty_id', $counterParty_id_hid_id)->set($data)->update();
            if ($update) {
                $session->setFlashdata("success","Updated Successfully");
            }else{
                $session->setFlashdata("error","Update Failed");
            }
            return redirect()->to("counter_party");
        }else{
            $save =  $this->counterparty_model->save($data);
            if ($save) {
                $session->setFlashdata("success","Saved Successfully");
            }else{
                $session->setFlashdata("error","Failed To Save");
            }
            return redirect()->to("counter_party");
        }


   
    }
}

public function edit_counterparty($id = ''){
    if ($id == null) {
        return redirect()->to("Admindashboard");
    }
    $this->loadUser();
    $session = session();
    $pot = json_decode(json_encode($session->get("userdata")), true);
    if (empty($pot)) {
        return redirect()->to("/");
    }
    $data['query'] = $this->counterparty_model->where("counterParty_id = '{$id}'")->first();
   $data['pade_title1'] = 'Edit Counter Type';
    $data['session'] = $session;
    $data['title'] = 'Edit Counter Details';
    $data['pade_title3'] = 'Choose Counter Type Status';
    $data['menuslinks'] = $this->request->uri->getSegment(1);
    $data["view"] = "Counterparty/counterpartysave";
    return view('templates/default', $data);
}


public function delete_counterparty($id = ''){
    if ($id == null) {
        return redirect()->to("Admindashboard");
    }
    $this->loadUser();
    $session = session();
    $pot = json_decode(json_encode($session->get("userdata")), true);
    if (empty($pot)) {
        return redirect()->to("/");
    }
    $delete = $this->counterparty_model
    ->where('counterParty_id', $id)->delete();

    if($delete){
        $session->setFlashdata('success', 'Counter Type  Deleted Successfully');
    }else{
        $session->setFlashdata('error', 'Counter Type Failed to  Deleted');
    }
    return redirect()->to("counter_party");
}



}
