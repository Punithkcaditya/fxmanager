<?php

namespace App\Controllers;
defined('BASEPATH') OR exit('No direct script access allowed');
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\Adminusers as Admin_Users_Model;
use App\Models\AdminrolesaccessesModel as Admin_Roles_Accesses_Model;
use App\Models\Adminroles as Admin_Roles_Model;
use App\Models\Adminmenuitems as Admin_Menuitems_Model;






/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Constructor.
     */
    public function __construct() {
        helper(['form', 'url', 'validation', 'string']);
        $this->admin_users_model = new Admin_Users_Model();
        $this->admin_roles_accesses_model = new Admin_Roles_Accesses_Model();
        $this->admin_roles_model = new Admin_Roles_Model();
        $this->admin_menuitems_model = new Admin_Menuitems_Model();
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
    
    }
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }
    public function loadUser(){
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        } else {
            $user_id = $pot["user_id"];
            $role_id = $pot["role_id"];
        }
        $this->admin_users_model->primary_key = ["user_id" => $user_id];
        $this->admin_roles_model->primary_key = ["role_id" => $role_id];
        $user_session_id = $this->admin_users_model->session_id();
        if ($session->userdata["logged_session_id"] != md5($user_session_id) ) {
            return redirect("/");
        }
        else{
            $this->sideMenu();

        }
    }

    public function sideMenu()
        {
            $session = session();
            $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        } else {
            $role_id = $pot["role_id"];
        }
            $side_menu_roles = $this->admin_roles_accesses_model->get_role_access(
                $role_id
            );
            $_SESSION["sidebar_menuitems"] = !empty(
                $_SESSION["sidebar_menuitems"]
            )
                ? $_SESSION["sidebar_menuitems"]
                : $side_menu_roles;
        
    }

 
}
