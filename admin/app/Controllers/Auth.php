<?php

namespace App\Controllers;
defined('SYSTEMPATH') OR exit('No direct script access allowed'); 

use App\Controllers\BaseController;
class Auth extends BaseController
{
    protected $request;


    public function __construct()
    {
        parent::__construct();
        $this->request = \Config\Services::request();
        helper(['form', 'url', 'string']);
  
    }

    public function index()
    {         
      
        $session = session();
        $data=[];
        $data['page_title']="Login";
        $login_user = json_decode(urldecode($this->request->getGet('login_user')), true);
        if(isset($login_user)){
            $login_detail = (array) $this->admin_users_model->loginviaweb($login_user);
            if (!empty($login_detail)) {
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
            }
        }
       
        $logged_in = $session->get("userdata");
        if($logged_in)
        {
            return redirect()->to("Admindashboard");
        }
        $data['session'] = $session;
        $data['view'] = 'login/login';
        $data['title'] = 'Login Page - ' . SITE_TITLE;
        return view('templates/defaultv2', $data);
    }
   

    public function logout(){
        $session = session();
        $session->destroy();
        $session = session();
        return redirect()->to('/');
    }

  



 
}
