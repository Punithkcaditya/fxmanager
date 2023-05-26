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
        $request = \Config\Services::request();
        helper(['form', 'url', 'string']);
    }

    public function index()
    {   
        $session = session();
        $data=[];
        $data['page_title']="Login";
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
