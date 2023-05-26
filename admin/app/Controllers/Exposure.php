<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ExposureModel as Exposure_Model;
class Exposure extends BaseController
{
    public function __construct() {
        parent::__construct();
        $this->exposure_model = new Exposure_Model();
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
        $data["title"] = "Exposure Details";
		$data["page_title"] = "Exposure Details";
        $data["exposure"] = $this->exposure_model->orderBy('exposure_type_id', 'DESC')->findAll();
        $data["page_heading"] = "Add New Exposure";
        $data["request"] = $this->request;
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        if($this->permission[0]>0){
            $data["link"] = "addNewExposureList";
          }else{
            $data["link"] = "#";
          }
        if($this->permission[1]>0){
            $data["edit_exposure"] = "edit_exposure";
          }else{
            $data["edit_exposure"] = "#";
          }
        if($this->permission[2]>0){
            $data["delete_exposure"] = "delete_exposure";
          }else{
            $data["delete_exposure"] = "#";
          }
        $data["view"] = "Exposure/exposurelist";
        return view('templates/default', $data);
    }


    public function addNewExposureList(){
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data['session'] = $session;
        $data['title'] = 'Add Exposure Details';
        $data['pade_title1'] = 'Enter Exposure Type';
        $data['pade_title3'] = 'Choose Exposure Type Status';
        $data['menuslinks'] = $this->request->uri->getSegment(1);
        $data["view"] = "Exposure/exposuresave";
        return view('templates/default', $data);
    }

    public function savenewexposure(){
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
            
            $data = ['exposure_type' => $exposureName , 'status' => $exposure_status, 'created_at' => date('Y-m-d') ] ;
        }
       
     
        if (!empty($exposure_type_hid_id))
        {
            $update =  $this->exposure_model->where('exposure_type_id', $exposure_type_hid_id)->set($data)->update();
            if ($update) {
                $session->setFlashdata("success","Updated Successfully");
            }else{
                $session->setFlashdata("error","Update Failed");
            }
            return redirect()->to("exposure_type_list");
        }else{
            $save =  $this->exposure_model->save($data);
            if ($save) {
                $session->setFlashdata("success","Saved Successfully");
            }else{
                $session->setFlashdata("error","Failed To Save");
            }
            return redirect()->to("exposure_type_list");
        }


   
    }
}

public function edit_exposure($id = ''){
    if ($id == null) {
        return redirect()->to("Admindashboard");
    }
    $this->loadUser();
    $session = session();
    $pot = json_decode(json_encode($session->get("userdata")), true);
    if (empty($pot)) {
        return redirect()->to("/");
    }
    $data['query'] = $this->exposure_model->where("exposure_type_id = '{$id}'")->first();
   $data['pade_title1'] = 'Edit Exposure Type';
    $data['session'] = $session;
    $data['title'] = 'Edit Exposure Details';
    $data['pade_title3'] = 'Choose Exposure Type Status';
    $data['menuslinks'] = $this->request->uri->getSegment(1);
    $data["view"] = "Exposure/exposuresave";
    return view('templates/default', $data);
}


public function delete_exposure($id = ''){
    if ($id == null) {
        return redirect()->to("Admindashboard");
    }
    $this->loadUser();
    $session = session();
    $pot = json_decode(json_encode($session->get("userdata")), true);
    if (empty($pot)) {
        return redirect()->to("/");
    }
    $delete = $this->exposure_model
    ->where('exposure_type_id', $id)->delete();

    if($delete){
        $session->setFlashdata('success', 'Exposure Type  Deleted Successfully');
    }else{
        $session->setFlashdata('error', 'Exposure Type Failed to  Deleted');
    }
    return redirect()->to("exposure_type_list");
}



}
