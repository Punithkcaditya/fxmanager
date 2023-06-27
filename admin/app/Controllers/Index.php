<?php

namespace App\Controllers;

defined('BASEPATH') or exit('No direct script access allowed');

use App\Controllers\BaseController;
use Config\Database;

class Index extends BaseController
{
    protected $request;

    public function __construct()
    {
        parent::__construct();
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
        $data = [];
        $session = session();
        $user_id = $session->get("userdata");
        $input = $this->validate([
            "password" => "required|min_length[3]",
            "user_name" => "required",
        ]);

        if (!empty($input)) {
            $login_detail = (array) $this->admin_users_model->loginnew(
                $this->request->getPost()
            );

            if (!empty($login_detail)) {
                extract($this->request->getPost());

                $checkRole = $this->admin_users_model
                ->where("user_name", $user_name)
                ->where("role_id", 4)
                ->countAllResults(); 

                if ($checkRole > 0) {   
                $employee = $this->admin_users_model->select('user_id')->where("user_name", $user_name)
                ->where("role_id", 4)
                ->first();

                 $checkCondition = $this->introductionform_model
                ->where("ssjl_employee_id", $employee['user_id'])
                ->where("ssjl_isapproved", 1)
                ->countAllResults();

                 $waitCondition = $this->introductionform_model
                ->where("ssjl_employee_id", $employee['user_id'])
                ->where("ssjl_isapproved", 0)
                ->countAllResults();
                if($waitCondition > 0){
                    $session->setFlashdata("warning", "Waiting For Approval");
                    $data["session"] = $session;
                    return redirect()->to("/");
                }

                if($checkCondition == 0){
                    $data = urlencode($employee['user_id']);
                    return redirect()->to("introduction?employee=". $data);
                }
      
                }
                unset($login_detail["logged_session_id"]);
                $user_session_id = rand("2659748135965", "088986555510245579");

                $this->admin_users_model->data["user_session_id"] = $user_session_id;

                $login_detail["logged_session_id"] = md5($user_session_id);

                $session->set("userdata", $login_detail);
                $pot = json_decode(json_encode($session->userdata), true);
                $this->admin_users_model->primary_key = [
                    "user_id" => $pot["user_id"],
                ];
                $this->admin_users_model->updateData();
                return redirect()->to("Admindashboard");
                //return view('welcome_message');
            } else {
                $session->setFlashdata("error", "Incorrect Email and Password");
                $data["session"] = $session;
                return redirect()->to("/");
            }
        }
    }


    public function Introduction()
    {
        $employee = !empty($_GET['employee']) ? $_GET['employee'] : '';
        if (empty($employee)) {
            return redirect()->to("/");
        }
        $data['employee_id'] = $employee;
        return view('admin/introduction', $data);
    }



    public function addemployeedetails()
    {

        $session = session();
        $input = $this->validate([
            "ssjl_date" => "required",
            "ssjl_full_name" => "required",
            "ssjl_mobile" => "required",
            "ssjl_email" => "required",
            "ssjl_blood_group" => "required",
            "ssjl_adhaar_no" => "required",
            "ssjl_bank_name" => "required",
            "ssjl_marital_status" => "required",
            "ssjl_date_of_birth" => "required",
            "ssjl_pan_no" => "required",
            "employee_id" => "required",
            "ssjl_bank_ac_no" => "required",
            "ssjl_fathers_name" => "required",
            "ssjl_emergency_contact_no" => "required",
            "ssjl_contact_no" => "required",

        ]);

        if (!empty($input)) {

            if ($this->request->getMethod() == "post") {
                extract($this->request->getPost());
                $udata = [];

            $checkPan = $this->introductionform_model
            ->where("ssjl_pan_no", $ssjl_pan_no)
            ->countAllResults();

                if ($checkPan > 0) {
                     $session->setFlashdata("error", "PAN No Taken");
                    return redirect()->to("introduction");
                }


                $validated = $this->validate([
                    "file" => [
                        "uploaded[file]",
                        "mime_in[file,image/jpg,image/jpeg,image/gif,image/png]",
                        "max_size[file,4096]",
                    ],
                ]);

                $ssjl_education_documents = $this
                    ->request
                    ->getFile('ssjl_education_documents');
                $udata["ssjl_education_documents"]  =  $this->savefilesuploaded($ssjl_education_documents);

                $ssjl_pan_copy = $this
                    ->request
                    ->getFile('ssjl_pan_copy');
                 $udata["ssjl_pan_copy"]  =  $this->savefilesuploaded($ssjl_pan_copy);


                $ssjl_releiving_letter = $this
                    ->request
                    ->getFile('ssjl_releiving_letter');
                      $udata["ssjl_releiving_letter"]  =  $this->savefilesuploaded($ssjl_releiving_letter);

                $ssjl_resume = $this
                    ->request
                    ->getFile('ssjl_resume');
                      $udata["ssjl_resume"]  =  $this->savefilesuploaded($ssjl_resume);

                $ssl_profile_pic = $this
                    ->request
                    ->getFile('ssl_profile_pic');
                      $udata["ssl_profile_pic"]  =  $this->savefilesuploaded($ssl_profile_pic);


                $ssjl_adhaar_copy = $this
                    ->request
                    ->getFile('ssjl_adhaar_copy');
                    $udata["ssjl_adhaar_copy"]  =  $this->savefilesuploaded($ssjl_adhaar_copy);

            
                $ssjl_previous_pay_slips = $this
                    ->request
                    ->getFile('ssjl_previous_pay_slips');
                    $udata["ssjl_previous_pay_slips"]  =  $this->savefilesuploaded($ssjl_previous_pay_slips);

                $ssjl_rent_agreement = $this
                    ->request
                    ->getFile('ssjl_rent_agreement');
                     $udata["ssjl_rent_agreement"]  =  $this->savefilesuploaded($ssjl_rent_agreement);

           
                $date = strtotime($ssjl_date);
                $ssjl_date_conv = date("Y-m-d", $date);
                $ssjl_date_of_birth_conv = strtotime($ssjl_date_of_birth);
                $ssjl_date_of_birth_new = date("Y-m-d", $ssjl_date_of_birth_conv);
                $udata["ssjl_date"] = $ssjl_date_conv;
                $udata["ssjl_email"] = $ssjl_email;
                $udata["ssjl_employee_id"] = $employee_id;
                $udata["ssjl_full_name"] = $ssjl_full_name;
                $udata["ssjl_mobile"] = $ssjl_mobile;
                $udata["ssjl_blood_group"] = $ssjl_blood_group;
                $udata["ssjl_adhaar_no"] = $ssjl_adhaar_no;
                $udata["ssjl_bank_name"] = $ssjl_bank_name;
                $udata["ssjl_marital_status"] = $ssjl_marital_status;
                $udata["ssjl_emergency_contact_person"] = !empty($ssjl_emergency_contact_person) ? $ssjl_emergency_contact_person : 0;
                $udata["ssjl_reference_person"] = !empty($ssjl_reference_person) ? $ssjl_reference_person : 'N/A' ;
                $udata["ssjl_health_issue_any"] = !empty($ssjl_health_issue_any) ? $ssjl_health_issue_any : 'N/A';
                $udata["ssjl_date_of_birth"] = $ssjl_date_of_birth_new;
                $udata["ssjl_pan_no"] = $ssjl_pan_no;
                $udata["ssjl_fathers_name"] = $ssjl_fathers_name;
                $udata["ssjl_emergency_contact_no"] = $ssjl_emergency_contact_no;
                $udata["ssjl_contact_no"] = $ssjl_contact_no;
                $udata["ssjl_uan_no"] = $ssjl_uan_no;
                $udata["ssjl_bank_ac_no"] = $ssjl_bank_ac_no;
                $udata["ssjl_isapproved"] = 0;
                 $session->setFlashdata("success", "Information Saved Successfully");
                $save = $this->introductionform_model->save($udata);
                 return redirect()->to("/");
            }
        } else {
            $session->setFlashdata("error", "Incorrect Email and Password");
            $data["session"] = $session;
            return redirect()->to("introduction");
        }
    }

public function savefilesuploaded($ssjl_files){
  if ($ssjl_files->isValid() && !$ssjl_files->hasMoved()) {
                    $name = $ssjl_files->getRandomName();
                    $ext = $ssjl_files->getClientExtension();
                    $ssjl_files_pic =  $name;
                    $ssjl_files->move("admin/uploads/", $ssjl_files_pic);
                    $filepath = base_url() . "/uploads/" . $ssjl_files_pic;
                    session()->setFlashdata("filepath", $filepath);
                    session()->setFlashdata("extension", $ext);
                    return $ssjl_files_pic;
                }else{
                    return '';
                }
}

// view pdf files
     public function showPdf($filename)
    {
         // $filepath = base_url() . "/uploads/" . $ssjl_rent_agreement_pic;
         //            session()->setFlashdata("filepath", $filepath);
        $filepath = base_url() . '/uploads/' . $filename;
       
        $content = file_get_contents($filepath);
        header('Content-Type: application/pdf');
        header('Content-Length: ' . strlen($content));
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        ini_set('zlib.output_compression', '0');
        die($content);
    }


    public function dashboard()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }

        //          echo"<pre>";
        // var_dump($session->userdata["logged_session_id"], md5($user_session_id));
        // exit;

        $data["page_title"] =
            "Welcome - " .
            ucfirst($pot["first_name"]) .
            " " .
            $data["page_heading"] =
            "Welcome - " .
            ucfirst($pot["first_name"]) .
            " " .
            $data["sub_heading"] =
            "Welcome - " .
            ucfirst($pot["first_name"]);
        $data["session"] = $session;
        $data["breadcrumb"] = "Admindashboard";
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data['view'] = 'admin/dashboard';
        return view('templates/default', $data);
    }

    public function addrole()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }

        $this->loadUser();

        $role_id = $pot["role_id"];
        $data["view"] = "admin/roles";
        $data["page_title"] = "Add New Roles";
        $data["session"] = $session;
        if ($this->permission[0] > 0) {
            $data["link"] = "addnewroles";
        } else {
            $data["link"] = "#";
        }
        if ($this->permission[1] > 0) {
            $data["user_rolesedit"] = "user_rolesedit";
        } else {
            $data["user_rolesedit"] = "#";
        }
        if ($this->permission[2] > 0) {
            $data["user_delete"] = "user_delete";
        } else {
            $data["user_delete"] = "#";
        }
        // $data['breadcrumb'] = "<a href=User/$this->class_name>Roles</a> &nbsp;&nbsp; > &nbsp;&nbsp; Add Role";
        $data["page_heading"] = "Add  Roles";
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["q"] = $this->admin_users_model->findroles($role_id);
        $data["roles"] = $this->admin_roles_model
            ->orderBy("role_id", "ASCE")
            ->findAll();
        return view('templates/default', $data);
    }

    public function addnewroles()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $this->loadUser();
        $data["session"] = $session;
        $data["page_title"] = "Add New Roles";
        $data["page_heading"] = "Add New Users";
        $data["request"] = $this->request;
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["view"] = "admin/addnewroles";
        return view('templates/default', $data);
    }

        public function approvelogin($id = "")
        {
            if ($id == null) {
            return redirect("Admindashboard");
            }
            $session = session();
            $pot = json_decode(json_encode($session->get("userdata")), true);
            if (empty($pot)) {
            return redirect()->to("/");
            }
            $udata["ssjl_isapproved"] = 1;
            $update = $this->introductionform_model
            ->where("ssjl_into_id", $id)
            ->set($udata)
            ->update();
            if ($update) {
            $session->setFlashdata(
            "success",
            "Approved Successfully!!"
            );
            return redirect()->to("approvedemployees");
            } else {
            $session->setFlashdata("error", "Failed To Approve");
            return redirect()->to("isapproved");
            }
        }


        public function disapprovelogin($id = "")
        {
            if ($id == null) {
            return redirect("Admindashboard");
            }
            $session = session();
            $pot = json_decode(json_encode($session->get("userdata")), true);
            if (empty($pot)) {
            return redirect()->to("/");
            }
            $udata["ssjl_isapproved"] = 0;
            $update = $this->introductionform_model
            ->where("ssjl_into_id", $id)
            ->set($udata)
            ->update();
            if ($update) {
            $session->setFlashdata(
            "success",
            "Disapproved Successfully!!"
            );
            return redirect()->to("isapproved");
            } else {
            $session->setFlashdata("error", "Failed To Disapprove");
            return redirect()->to("approvedemployees");
            }
        }

    public function user_rolesedit($id = "")
    {
        if ($id == null) {
            return redirect("Admindashboard");
        }
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data["userInfo"] = $this->admin_roles_model
            ->where("role_id= '{$id}'")
            ->findAll();
        $this->loadUser();
        $data["session"] = $session;
        $data["page_title"] = "Edit New Roles";
        $data["page_heading"] = "Edit New Users";
        $data["request"] = $this->request;
        $data["query"] = $this->admin_roles_model->get_row($id);
        $data["roles"] = $this->admin_roles_model
            ->orderBy("role_id", "DESC")
            ->findAll();
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["view"] = "admin/editnewroles";
        return view('templates/default', $data);
    }

    public function savenewroles()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        // echo '<pre>';
        // print_r($pot['user_id'] );
        // exit;

        $input = $this->validate([
            "role_name" => "required|min_length[3]",
            "status_ind" => "required",
        ]);

        if (!empty($input)) {
            if ($this->request->getMethod() == "post") {
                extract($this->request->getPost());
                if (!preg_match('/^[a-zA-Z_ ]*$/', $role_name)) {
                    $session->setFlashdata("error", "Special characters and Numbers are not allowed");
                    return redirect()->to("addnewroles");
                }
                $udata = [];
                $udata["role_name"] = $role_name;
                $udata["status_ind"] = $status_ind;
                $udata["created_date"] = date("Y-m-d");
                $udata["created_by"] = $pot["user_id"];
                $udata["last_modified_by"] = $pot["user_id"];
                // echo '<pre>';
                // print_r($udata);
                // exit;
                $save = $this->admin_roles_model->save($udata);
                if ($save) {
                    $session->setFlashdata("success", "Saved Successfully");
                    return redirect()->to("addnewroles");
                } else {
                    $session->setFlashdata("error", "Failed to save");
                    return redirect()->to("addnewroles");
                }
            }
        } else {
            $session->setFlashdata("error", "Enter All Fields");
            return redirect()->to("addnewroles");
        }
    }

    public function editnewroles()
    {

        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        // echo '<pre>';
        // print_r($pot['user_id'] );
        // exit;
        $input = $this->validate([
            "role_name" => "required|min_length[3]",
            "status_ind" => "required",
        ]);

        if (!empty($input)) {
            if ($this->request->getMethod() == "post") {
                extract($this->request->getPost());

                if (!empty($user_id_hidd)) {
                    $udata = [];
                    $udata["role_name"] = $role_name;
                    $udata["status_ind"] = $status_ind;
                    $udata["modified_date"] = date("Y-m-d");
                    $udata["modified_by"] = $pot["user_id"];

                    $update = $this->admin_roles_model
                        ->where("role_id", $user_id_hidd)
                        ->set($udata)
                        ->update();

                    //     echo '<pre>';
                    //     print_r($update);
                    //     exit;
                    if ($update) {
                        $session->setFlashdata(
                            "success",
                            "Updated Successfully!!"
                        );
                        return redirect()->to("user_rolesedit/$user_id_hidd");
                    } else {
                        $session->setFlashdata("success", "Failed To Update");
                        return redirect()->to("user_rolesedit/$user_id_hidd");
                    }
                }
            }
        } else {
            $session->setFlashdata("error", "Enter All Fields");
            return redirect()->to("user_rolesedit/$user_id_hidd");
        }
    }

    public function user_delete($id = "")
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }

        if (empty($id)) {
            $this->session->setFlashdata(
                "error",
                "user Deletion failed due to unknown ID."
            );
            return redirect()->to("Admindashboard");
        }
        $delete = $this->admin_users_model->where("user_id", $id)->delete();
        if ($delete) {
            $session->setFlashdata(
                "success",
                "User has been deleted successfully."
            );
        } else {
            $session->setFlashdata(
                "error",
                "User Deletion failed due to unknown ID."
            );
        }

        return redirect()->to("addemployee");
    }

    public function access($id = "")
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if (empty($id)) {
            $this->session->setFlashdata(
                "error",
                "user Access failed due to unknown ID."
            );
            return redirect()->to("Admindashboard");
        }
        $this->loadUser();
        $accesses = [];
        $data["query"] = $this->admin_menuitems_model->view();
        $roles_accesses = $this->admin_roles_accesses_model->view($id);
        foreach ($roles_accesses as $row) {
            $accesses[] = $row->menuitem_id;
        }
        $data["session"] = $session;
        $data["title"] = "Administrator Dashboard - ";
        $data["request"] = $this->request;
        $data["role_id"] = $id;
        $data["admin_users_accesses"] = $accesses;
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["view"] = "admin/access";
        return view('templates/default', $data);
    }

    public function saveaccess()
    {
        $session = session();
        $request = \Config\Services::request();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        } else {
            $user_id = $pot["user_id"];
            $role_id = $pot["role_id"];
        }
        $input = $this->validate([
            "menuitem_id" => "required",
        ]);

        if (!empty($input)) {

            if ($user_id == 1 || $user_id == 2) {
                if ($this->request->getMethod() == "post") {
                    extract($this->request->getPost());
                    $status = true;
                    $role_id = $request->getPost("role_id");

                    $this->admin_roles_accesses_model->primary_key = [
                        "role_id" => $role_id,
                    ];
                    $delete = $this->admin_roles_accesses_model
                        ->where("role_id", $role_id)
                        ->delete();
                    if ($delete) {
                        $menuitem_ids = $request->getPost("menuitem_id");

                        foreach ($menuitem_ids as $menuitem_id) {
                            $data = [
                                "menuitem_id" => $menuitem_id,
                                "role_id" => $role_id,
                            ];

                            $save = $this->admin_roles_accesses_model->save($data);
                            if ($save) {
                                $status = true;
                            }
                        }
                    }

                    if ($status) {
                        $this->session->setFlashdata("success", "user Access saved.");
                    } else {
                        $this->session->setFlashdata(
                            "error",
                            "user Access failed due to unknown ID."
                        );
                    }
                }
            } else {
                $this->session->setFlashdata(
                    "error",
                    "Sorry! You do not have the permission."
                );
            }
        } else {
            if ($this->request->getMethod() == "post") {
                extract($this->request->getPost());
                $delete = $this->admin_roles_accesses_model
                    ->where("role_id", $role_id)
                    ->delete();
            }
            $this->session->setFlashdata("success", "user Access saved.");
        }
        //     $this->session->set_flashdata('msg', $msg);
        return redirect()->to("Admindashboard");
    }

    // permission

    public function permission($id)
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if (empty($id)) {
            $this->session->setFlashdata(
                "error",
                "user Permission failed due to unknown ID."
            );
            return redirect()->to("Admindashboard");
        }
        $this->loadUser();

        if (!empty($id)) {
            $accesses = [];
            $roles_accesses = $this->admin_roles_accesses_model->view_access($id);
            foreach ($roles_accesses as $row) {
                $accesses[] = $row->menuitem_id;
            }
            $data["session"] = $session;
            $data["role_id"] = $id;
            $data["query"] = $roles_accesses; //$_SESSION['sidebar_menuitems'];
            $data["title"] = "Role Access  Permission ";
            $data["page_heading"] = "Role Access Permission";
            $data["request"] = $this->request;
            $data["menuslinks"] = $this->request->uri->getSegment(1);
            $data["view"] = "admin/permission";
            return view('templates/default', $data);
        } else {

            $this->session->setFlashdata(
                "error",
                "Sorry! You do not have the permission."
            );
            return redirect()->to("Admindashboard");
        }
    }

    // savepermission

    public function savepermission()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }

        $request = \Config\Services::request();
        $this->loadUser();
        if (empty($pot)) {
            return redirect()->to("/");
        } else {
            $user_id = $pot["user_id"];
            $role_id = $pot["role_id"];
        }

        if ($user_id == 1 || $role_id == 2) {
            $status = true;
            $role_id = $request->getPost("role_id");
            $i = 0;
            $menuitem_ids = $request->getPost("menuitem_id");

            foreach ($menuitem_ids as $menuitem_id) {
                $add_permission = $request->getPost("add_permission");

                if (!empty($request->getPost("add_permission")[$i])) {
                    // echo '<pre>';
                    // print_r($add_permission[$i]);
                    // exit;
                    $add_permission = $request->getPost("add_permission")[$i];
                } else {
                    $add_permission = 0;
                }
                if (!empty($request->getPost("edit_permission")[$i])) {
                    $edit_permission = $request->getPost("edit_permission")[$i];
                } else {
                    $edit_permission = 0;
                }
                if (!empty($request->getPost("delete_permission")[$i])) {
                    $delete_permission = $request->getPost("delete_permission")[$i];
                } else {
                    $delete_permission = 0;
                }
                $udata["add_permission"] = $add_permission;
                $udata["role_id"] = $role_id;
                $udata["edit_permission"] = $edit_permission;
                $udata["delete_permission"] = $delete_permission;

                $update = $this->admin_roles_accesses_model
                    ->where("menuitem_id", $menuitem_id)
                    ->where("role_id", $role_id)
                    ->set($udata)
                    ->update();

                if ($update) {
                    $status = true;
                    $udata = [];
                }
                $i++;
            }

            if ($status) {
                $this->session->setFlashdata("success", "user Permission saved.");
                return redirect()->to("Admindashboard");
            } else {
                $this->session->setFlashdata("error", "user Permission failed saved.");
                return redirect()->to("Admindashboard");
            }
        } else {
            $this->session->setFlashdata("error", "Sorry! You do not have the permission.");
            return redirect()->to("Admindashboard");
        }
    }

    public function addemployee()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data["user_data"] = [];
        $this->loadUser();
        $data["session"] = $session;
        $data["title"] = "Administrator Dashboard - ";
        // $data['breadcrumb'] = "<a href=User/$this->class_name>Roles</a> &nbsp;&nbsp; > &nbsp;&nbsp; Add Role";
        $data["page_heading"] = "Add New User";
        if ($this->permission[0] > 0) {
            $data["link"] = "addNew";
        } else {
            $data["link"] = "#";
        }
        if ($this->permission[1] > 0) {
            $data["user_edit"] = "user_edit";
        } else {
            $data["user_edit"] = "#";
        }
        if ($this->permission[2] > 0) {
            $data["user_delete"] = "user_delete";
        } else {
            $data["user_delete"] = "#";
        }
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["users"] = $this->admin_users_model
            ->orderBy("user_id", "ASCE")
            ->findAll();
        $data["view"] = "admin/addusers";
        return view('templates/default', $data);
    }

    // Approve User

     public function isapproved()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data["user_data"] = [];
        $this->loadUser();
        $data["session"] = $session;
        $data["title"] = "Administrator Dashboard - ";
        // $data['breadcrumb'] = "<a href=User/$this->class_name>Roles</a> &nbsp;&nbsp; > &nbsp;&nbsp; Add Role";
        $data["page_heading"] = "Approve Login";
        if ($this->permission[0] > 0) {
            $data["link"] = "#";
        } else {
            $data["link"] = "#";
        }
        if ($this->permission[1] > 0) {
            $data["user_view"] = "view_details";
        } else {
            $data["user_view"] = "#";
        }
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["users"] = $this->introductionform_model->where("ssjl_isapproved", 0)
            ->orderBy("ssjl_into_id", "DESC")
            ->findAll();
        $data["view"] = "admin/approval";
        return view('templates/default', $data);
    }

     public function approvedemployees()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data["user_data"] = [];
        $this->loadUser();
        $data["session"] = $session;
        $data["title"] = "Administrator Dashboard - ";
        // $data['breadcrumb'] = "<a href=User/$this->class_name>Roles</a> &nbsp;&nbsp; > &nbsp;&nbsp; Add Role";
        $data["page_heading"] = "Approved Employees";
        if ($this->permission[0] > 0) {
            $data["link"] = "#";
        } else {
            $data["link"] = "#";
        }
        if ($this->permission[1] > 0) {
            $data["user_view"] = "view_details";
        } else {
            $data["user_view"] = "#";
        }
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["users"] = $this->introductionform_model->where("ssjl_isapproved", 1)
            ->orderBy("ssjl_into_id", "DESC")
            ->findAll();

        $data["view"] = "admin/approvedemployees";
        return view('templates/default', $data);
    }

    // View User
     public function view_details($id = "", $link="", $text="", $class="")
    {
        
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if ($id == null) {
            return redirect("Admindashboard");
        } else {
            $data["query"] = $this->introductionform_model
                ->where("ssjl_into_id= '{$id}'")
                ->first();
            $this->loadUser();
            $this->global["pageTitle"] = "View Details";
            $session = session();
            $data["title"] = "View Details";
            $data["session"] = $session;
            $data["link"] = $link;
            $data["text"] = $text;
             $data["class"] = $class;
            $data["page_heading"] = "View Details";
            $data["request"] = $this->request;
            $data["menuslinks"] = $this->request->uri->getSegment(1);
            $data["view"] = "admin/view_details";
            return view('templates/default', $data);
        }
    }




    // add new user

    public function addNew()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data["user_data"] = [];
        $this->loadUser();
        $data["session"] = $session;
        $data["pade_title"] = "Admin New User";
        $data["link"] = "addNew";
        $data["roles"] = $this->admin_roles_model
            ->where('status_ind', 1)
            ->orderBy("role_id", "DESC")
            ->findAll();
        $data["page_heading"] = "Add New Users";
        $data["request"] = $this->request;
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["view"] = "admin/addnewusers";
        return view('templates/default', $data);
    }

    public function addnewuser()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        helper(["form", "url", "string"]);
        $input = $this->validate([
            "first_name" => "required",
            "user_name" => "required",
            "role_id" => "required",
            "password" => "required",
        ]);

        if (!empty($input)) {

            if ($this->request->getMethod() == "post") {
                extract($this->request->getPost());
                $udata = [];
                $udata["first_name"] = $first_name;
                $udata["email"] = $email;
                $udata["role_id"] = $role_id;
                 $udata["user_name"] = $user_name;
                $udata["employee_id"] = $user_name;
                $udata["created_date"] = date("Y-m-d");
                $udata["created_by"] = $pot["user_id"];
                $udata["last_active"] = date("Y-m-d");
                if (!empty($password)) {
                    $udata["password"] = md5($password);
                }
                $checkMail = $this->admin_users_model
                    ->where("user_name", $user_name)
                    ->countAllResults();
                $checkEmployee_id = $this->admin_users_model
                    ->where("employee_id", $udata["employee_id"])
                    ->countAllResults();
                if ($checkMail > 0 || $checkEmployee_id > 0) {
                    $this->session->setFlashdata(
                        "error",
                        "PAN No Already Taken."
                    );
                } else {
                    $save = $this->admin_users_model->save($udata);
                    // for seller details company table
                    if ($save) {
                        $session->setFlashdata(
                            "success",
                            "Saved Successfully"
                        );
                        return redirect()->to("addNew");
                    } else {
                        $session->setFlashdata(
                            "error",
                            "User Details has failed to save."
                        );
                        return redirect()->to("addNew");
                    }
                }
            }
            // $session->setFlashdata('success', 'All Fine');
        } else {
            $session->setFlashdata("error", "Enter All Fields");
            return redirect()->to("addNew");
        }

        //return view('Modules\Admin\Views\pages\addnew', $data);
        // return view('pages/users/add', $this->data);
    }

    // useredit

    public function user_edit($id = "")
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if ($id == null) {
            return redirect("Admindashboard");
        } else {
            $data["userInfo"] = $this->admin_users_model
                ->where("user_id= '{$id}'")
                ->findAll();
            $this->loadUser();
            $this->global["pageTitle"] = "Edit User";
            $session = session();
            $data["title"] = "Edit User";
            $data["query"] = $this->admin_users_model->get_row($id);

            $data["roles"] = $this->admin_roles_model
                ->orderBy("role_id", "DESC")
                ->findAll();
            $data["session"] = $session;
            // $data['breadcrumb'] = "<a href=User/$this->class_name>Roles</a> &nbsp;&nbsp; > &nbsp;&nbsp; Add Role";
            $data["page_heading"] = "edit  Users";
            $data["request"] = $this->request;
            $data["menuslinks"] = $this->request->uri->getSegment(1);
            $returnArr = [];
            foreach ($data["userInfo"] as $k => $v) {
                $returnArr = array_merge($returnArr, $v);
            }
            $a = (object) $returnArr;
            $data["view"] = "admin/editnew";
            return view('templates/default', $data);
        }
    }

    // edit new user

    public function editnewuser()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }

        $sellername = 'Seller';
        $sellerId = $this->admin_roles_model->getsellerid($sellername);
        $input = $this->validate([
            "first_name" => "required",
            "email" => "required",
            "role_id" => "required",
             "user_name" => "required",
        ]);

        if (!empty($input)) {

            if ($this->request->getMethod() == "post") {
                extract($this->request->getPost());

                if (!empty($user_id_hidd)) {
                    $udata = [];
                    $udata["first_name"] = $first_name;
                    $udata["role_id"] = $role_id;
                    $udata["email"] = $email;
                    $udata["created_date"] = date("Y-m-d");
                    $udata["created_by"] = $pot["user_id"];
                    $udata["user_name"] = $user_name;
                    if (!empty($password)) {
                        $udata["password"] = md5($password);
                    }

                    $checkMail = $this->admin_users_model
                        ->where("user_name", $user_name)
                        ->where("user_id!=", $user_id_hidd)
                        ->countAllResults();

                    if ($checkMail > 0) {
                        $session->setFlashdata(
                            "error",
                            "PAN No Already Taken."
                        );
                        return redirect()->to("user_edit/$user_id_hidd");
                    } else {
                        $update = $this->admin_users_model
                            ->where("user_id", $user_id_hidd)
                            ->set($udata)
                            ->update();

                        if ($update) {
                            $session->setFlashdata(
                                "success",
                                "Updated Successfully"
                            );
                            return redirect()->to("user_edit/$user_id_hidd");
                        } else {
                            $session->setFlashdata(
                                "error",
                                "Failed to Update"
                            );
                            return redirect()->to("user_edit/$user_id_hidd");
                        }
                    }
                }
            }

            // $session->setFlashdata('success', 'All Fine');
        } else {
            $session->setFlashdata("error", "Enter All Fields");
            return redirect()->to("addNew");
        }
    }

    public function guestlist()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data["session"] = $session;
        $data['page_heading'] = "Guest List";
        $data['link'] = "addguestlist";
        $data["breadcrumb"] = "Admindashboard";
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["guest"] = $this->guest_model
            ->orderBy("guest_list_id", "DESC")
            ->findAll();
        $data['view'] = 'admin/guestlist';
        return view('templates/default', $data);
    }
    public function addguestlist()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $this->loadUser();
        $data["session"] = $session;
        $udata = [];
        if ($this->request->getMethod() == "post") {
            extract($this->request->getPost());
            if (!empty($guest_list_id)) {

                $udata["name"] = $guest_name;
                $udata["phone"] = $guest_phone_number;
                $udata["email"] = $guest_email;
                $udata["Ladies_Mehendi"] = $Ladies_Mehendi;
                $udata["no_of_guest"] = $no_of_guest;
                $udata["guest_comment"] = $guest_comment;
                $udata["Sangeet"] = $Sangeet;
                $udata["Tel_Baan"] = $Tel_Baan;
                $udata["Baraat_Wedding_Reception"] = $Baraat_Wedding_Reception;
                $udata["phone"] = $guest_phone_number;
                $udata["created_at"] = date("Y-m-d");
                $update = $this->guest_model
                    ->where("guest_list_id", $guest_list_id)
                    ->set($udata)
                    ->update();
                if ($update) {
                    $session->setFlashdata("success", "Updated Successfully!!");
                    return redirect()->to("guest_edit/$guest_list_id");
                } else {
                    $session->setFlashdata("success", "Failed To Update");
                    return redirect()->to("guest_edit/$guest_list_id");
                }
            } else {

                $udata["name"] = $guest_name;
                $udata["phone"] = $guest_phone_number;
                $udata["email"] = $guest_email;
                $udata["Ladies_Mehendi"] = $Ladies_Mehendi;
                $udata["no_of_guest"] = $no_of_guest;
                $udata["guest_comment"] = $guest_comment;
                $udata["Sangeet"] = $Sangeet;
                $udata["Tel_Baan"] = $Tel_Baan;
                $udata["Baraat_Wedding_Reception"] = $Baraat_Wedding_Reception;
                $udata["phone"] = $guest_phone_number;
                $udata["created_at"] = date("Y-m-d");

                $save = $this->guest_model->save($udata);
                if ($save) {
                    $session->setFlashdata("success", "Saved Successfully");
                    return redirect()->to("guestlist");
                } else {
                    $session->setFlashdata("error", "Failed to save");
                    return redirect()->to("guestlist");
                }
            }
        }

        $data["pade_title"] = "Admin New Guest";
        $data["link"] = "addGuest";
        $data["page_heading"] = "Add New Guest";
        $data["request"] = $this->request;
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["view"] = "admin/addnewguest";
        return view('templates/default', $data);
    }

    public function guest_edit($id = '')
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if ($id == null) {
            return redirect()->to("Admindashboard");
        } else {
            $guestinfo = $this->guest_model
                ->where("guest_list_id= '{$id}'")
                ->findAll();
            foreach ($guestinfo as $val) {
                $data["guestinfo"] = $val;
            }
            $this->loadUser();
            $data["pade_title"] = "Admin New Guest";
            $data["title"] = "Edit User";
            $data["session"] = $session;
            $data["page_heading"] = "edit  Users";
            $data["request"] = $this->request;
            $data["menuslinks"] = $this->request->uri->getSegment(1);
            $data["view"] = "admin/addnewguest";
            return view('templates/default', $data);
        }
    }

    public function guest_delete($id = '')
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }

        if (empty($id)) {
            $this->session->setFlashdata(
                "error",
                "Guest Deletion failed due to unknown ID."
            );
            return redirect()->to("guestlist");
        }
        $delete = $this->guest_model->where("guest_list_id", $id)->delete();
        if ($delete) {
            $session->setFlashdata(
                "success",
                "Guest has been deleted successfully."
            );
        } else {
            $session->setFlashdata(
                "error",
                "Guest Deletion failed due to unknown ID."
            );
        }
        return redirect()->to("guestlist");
    }
}

// echo '<pre>';
// print_r($data["guestinfo"]);
// exit;
