<?php

namespace App\Controllers;
defined('BASEPATH') OR exit('No direct script access allowed');
use App\Controllers\BaseController;
use App\Models\Settings as Settings_Model;
use App\Models\Currency as Currency_Model;
class Settings extends BaseController
{


    public function __construct() {
        parent::__construct();
        $request = \Config\Services::request();
        helper(['form', 'url', 'string']);
        $this->currency_model = new Currency_Model();
        $this->settings_model = new Settings_Model();
    }



    public function index()
    {
       
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        } 
     

        $data["query"] = $this->settings_model->view();
        $data["currency"] = $this->currency_model->where('status',1)->orderBy('currencyId', 'DESC')->findAll();
        $data["session"] = $session;
        $data["title"] = "Settings";
        $data["page_heading"] = "Settings";
        $data["breadcrumb"] = "Settings";
        $data["sub_heading"] = "Configuration";
        $data["request"] = $this->request;
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["view"] = "settings/settings";
        return view('templates/default', $data);
    
    }

    public function settingsupdate()
    {
        $session = session();
        $data_all = [];
        $data = [];
        $request = \Config\Services::request();
        $pot = json_decode(json_encode($session->get("userdata")), true);

        if (empty($pot)) {
            return redirect()->to("/Auth/index");
        } else {
            $user_id = $pot["user_id"];
            $role_id = $pot["role_id"];
        }
        if ($this->request->getMethod() == "post") {
            extract($this->request->getPost());
            $data_all["setting_value"] = $setting_value;
            $data_all["setting_id"] = $setting_id;
        }

        foreach ($data_all["setting_id"] as $key => $sid) {
            $this->settings_model->data = [
                "setting_id" => $sid,
                "setting_value" => '',
            ];
            $this->settings_model->primary_key = ["setting_id" => $sid];
            $this->settings_model->update_data();
        }
    
        foreach ($data_all["setting_id"] as $key => $sid) {
            if (empty($data_all["setting_value"][$key])) {
                unset($data_all["setting_id"][$key]);
            }
        }
    

        foreach ($data_all["setting_id"] as $key => $sid) {
            $status = true;

            $this->settings_model->data = [
                "setting_id" => $sid,
                "setting_value" => $data_all["setting_value"][$key],
            ];
      
            // foreach ($languages as $lang_id) {
            $this->settings_model->primary_key = ["setting_id" => $sid];
            if (!$this->settings_model->is_exist()) {
                $this->db
                    ->query("INSERT INTO `settings`( `setting_id`,  `type`, `setting_key`, `setting_name`, `setting_value`, `status_ind`)
                                        SELECT `setting_id`, `type`, `setting_key`, `setting_name`, `setting_value`, `status_ind` FROM `settings` WHERE `setting_id` = $sid ");
            }
            if (!$this->settings_model->update_data()) {
                $status = false;
            }
            // }
        }

        if ($status) {
            $session->setFlashdata(
                "success",
                "Save Changes Updated Successfully."
            );
        } else {
            $session->setFlashdata("error", "Sorry! Unable to Delete.");
        }

        return redirect()->to("settings");
    }
}
