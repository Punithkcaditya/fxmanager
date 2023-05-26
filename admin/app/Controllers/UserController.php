<?php

namespace App\Controllers;

defined('BASEPATH') or exit('No direct script access allowed');

use App\Controllers\BaseController;
use App\Models\Days as Days_Model;
use App\Models\MealInfo as MealInfo_Model;
use App\Models\MealPlans as MealPlans_Model;
use App\Models\UserInfoModel as Users_Model;
use Config\Database;

class UserController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->users_model = new Users_Model();
        $this->mealplans_model = new MealPlans_Model();
        $this->mealinfo_model = new MealInfo_Model();
        $this->days_model = new Days_Model();
        $this->db = Database::connect();
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
        $data['session'] = $session;
        $data['title'] = 'User Details';
        $data['users'] = $this->users_model->orderBy('user_id', 'DESC')->findAll();
        $data['page_heading'] = 'Add New User';
        $data['request'] = $this->request;
        $data['menuslinks'] = $this
            ->request
            ->uri
            ->getSegment(1);
        if ($this->permission[0] > 0) {
            $data["link"] = "addnewuser";
        } else {
            $data["link"] = "#";
        }
        if ($this->permission[1] > 0) {
            $data["edit_user"] = "edit_user";
            $data["meal_list"] = "meal_list";
        } else {
            $data["meal_list"] = "#";
        }
        if ($this->permission[2] > 0) {
            $data["delete_user"] = "delete_user";
        } else {
            $data["delete_user"] = "#";
        }
        $data["view"] = "user/userlist";
        return view('templates/default', $data);
    }

    public function addnewaddnewuser()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data['meal'] = $this->mealplans_model->orderBy('day_id', 'ASC')->findAll();

        $data['session'] = $session;
        $data['title'] = 'Add User Details';
        $data['pade_title1'] = 'Add New User';
        $data['pade_title2'] = 'Add Conditions';
        $data['pade_title6'] = 'Add User Phone Number';
        $data['pade_title3'] = 'Add User Name';
        $data['pade_title4'] = 'Add User Email';
        $data['request'] = $this->request;
        $data['menuslinks'] = $this
            ->request
            ->uri
            ->getSegment(1);
        $data["view"] = "user/addnewuser";
        return view('templates/default', $data);
    }

    public function savenewuserinfo()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        } else {
            $user_id = $pot['user_id'];
            $role_id = $pot['role_id'];
        }
        if ($this->request->getMethod() == 'post') {
            extract($this->request->getPost());
        }
        $login_token = $this->getToken(16);
        $password = $this->generateRandomPassword();

        $input = $this->validate(['user_name' => 'required|min_length[3]', 'userphonenumber' => 'required|min_length[10]', 'useremail' => 'required|min_length[5]']);
        if (!empty($input)) {
            if (!empty($user_id_hidd)) {
                $checkMail = $this->users_model
                    ->where("user_email", $useremail)
                    ->where("user_id!=", $user_id_hidd)
                    ->countAllResults();
                $checkPhone = $this->users_model
                    ->where("user_phone", $userphonenumber)
                    ->where("user_id!=", $user_id_hidd)
                    ->countAllResults();

                if ($checkMail > 0 || $checkPhone > 0) {
                    $session->setFlashdata(
                        "error",
                        "User Email Already Taken Or Phone Number Already Taken."
                    );
                    return redirect()->to('userlink');
                }
                $data = ['user_name' => $user_name, 'user_phone' => $userphonenumber, 'login_token' => $login_token, 'password' => password_hash($password, PASSWORD_BCRYPT), 'user_email' => $useremail, 'created_at' => date('Y-m-d h:m:s')];
                $update = $this
                    ->users_model
                    ->where('user_id', $user_id_hidd)->set($data)->update();

                (empty($update)) ? $session->setFlashdata('error', 'Failed To Update') : $session->setFlashdata('success', 'Updated Successfully');
            } else {
                $checkMail = $this->users_model
                    ->where("user_email", $useremail)
                    ->countAllResults();
                $checkPhone = $this->users_model
                    ->where("user_phone", $userphonenumber)
                    ->countAllResults();
                if ($checkMail > 0 || $checkPhone > 0) {
                    $this->session->setFlashdata(
                        "error",
                        "User Email Or Phone Number Already Taken."
                    );
                    return redirect()->to('userlink');
                }
                $data = ['user_name' => $user_name, 'user_phone' => $userphonenumber, 'login_token' => $login_token, 'password' => password_hash($password, PASSWORD_BCRYPT), 'user_email' => $useremail, 'created_at' => date('Y-m-d h:m:s')];
                $save = $this
                    ->users_model
                    ->insert($data);
                (empty($save)) ? $session->setFlashdata('error', 'Failed To Save') : $session->setFlashdata('success', 'Saved Successfully');

            }
        } else {
            $session->setFlashdata('error', 'Fill All Fields');
        }
        return redirect()->to('userlink');
    }

    public function edit_user($id = '')
    {
        if ($id == null) {
            return redirect()->to("Admindashboard");
        }
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data['session'] = $session;
        $data['title'] = 'Edit User Details';
        $data['pade_title1'] = 'Edit User';
        $data['pade_title2'] = 'Edit Conditions';
        $data['pade_title6'] = 'Edit User Phone Number';
        $data['pade_title3'] = 'Edit User Name';
        $data['pade_title4'] = 'Edit User Email';
        $data['request'] = $this->request;
        $data['query'] = $this
            ->users_model->where('user_id', $id)->first();
        $data['menuslinks'] = $this
            ->request
            ->uri
            ->getSegment(1);
        $data["view"] = "user/addnewuser";
        return view('templates/default', $data);
    }

    public function meal_plan($id = '')
    {
        if ($id == null) {
            return redirect()->to("Admindashboard");
        }
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data['page_heading'] = 'Add New User Meal Plan';
        $data['session'] = $session;
        $data['title'] = 'Edit User Details';
        $data['user_id'] = $id;
        $data['pade_title1'] = 'Select Date';
        $data['pade_title2'] = 'Add Meal Heading';
        $data['pade_title3'] = 'Select Time';
        $data['pade_title6'] = 'Add Meal Description';
        $data['request'] = $this->request;
        $data['page'] = 'page.css';
        $data['i'] = 1;
        $data['meal'] = $this->mealplans_model->orderBy('day_id', 'ASC')->findAll();
        $data['menuslinks'] = $this
            ->request
            ->uri
            ->getSegment(1);
        $data["view"] = "user/mealplan";
        return view('templates/default', $data);
    }

    public function mealedit($id = '')
    {
        if ($id == null) {
            return redirect()->to("Admindashboard");
        }
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data['mealPlans'] = $this->mealinfo_model->select('meal_description')->select('meal_name')->select('meal_time')
            ->where("day_id=", $id)
            ->findAll();
        $data['date'] = $this->days_model->select('date')->select('user_id')
            ->where("day_id=", $id)
            ->first();
        $data['title'] = $id;
        $data['page_heading'] = 'Edit New User Meal Plan';
        $data['session'] = $session;
        $data['title'] = 'Edit User Details';
        $data['day_id'] = $id;
        $data['pade_title1'] = 'Select Date';
        $data['pade_title2'] = 'Edit Meal Heading';
        $data['pade_title3'] = 'Edit Meal Timing';
        $data['pade_title6'] = 'Edit Meal Description';
        $data['request'] = $this->request;
        $data['page'] = 'page.css';
        $data['i'] = 1;
        $data['meal'] = $this->mealplans_model->orderBy('day_id', 'ASC')->findAll();
        $data['menuslinks'] = $this
            ->request
            ->uri
            ->getSegment(1);
        $data["view"] = "user/editmealplan";
        return view('templates/default', $data);
    }

    public function meal_list($id = '')
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $builder = $this->db->table('days_in_week');
        $builder->select('days_in_week.date, days_in_week.day_id, meal_info.user_id, GROUP_CONCAT(meal_info.meal_name SEPARATOR "/bk") as mealtitle_concatenated, GROUP_CONCAT(meal_info.meal_description SEPARATOR "/bu") as meal_description_concatenated, GROUP_CONCAT(meal_info.meal_time SEPARATOR "/ti") as mealtime_concatenated');
        $builder->join('meal_info', 'meal_info.day_id = days_in_week.day_id');
        $builder->where('meal_info.user_id', $id);
        $builder->groupBy('days_in_week.day_id');
        $builder->orderBy('days_in_week.date', 'DESC');
        $dataquery = $builder->get();
        $data['mealedit'] = 'mealedit';
        $data['mealdelete'] = 'mealdelete';
        $data['query'] = $dataquery->getResultArray();
        $data['session'] = $session;
        $data['page_heading'] = 'Meal List';
        $data['request'] = $this->request;
        $data['menuslinks'] = $this
            ->request
            ->uri
            ->getSegment(1);
        $data["link"] = "meal_plan/" . $id;
        $data["view"] = "user/meal_list";
        return view('templates/default', $data);
    }

    public function delete_user($id = '')
    {
        if ($id == null) {
            return redirect()->to("Admindashboard");
        }
        $this->session = session();
        $session = session();
        $pot = json_decode(json_encode($session->get('userdata')), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $delete = $this->users_model->where('user_id', $id)->delete();
        $deletemeal = $this->mealinfo_model->where('user_id', $id)->delete();
        (empty($delete) && empty($deletemeal)) ? $session->setFlashdata('error', 'Failed due to unknown ID') : $session->setFlashdata('success', 'User has been deleted successfully');
        $deletemodel = $this->days_model->where('user_id', $id)->delete();
        return redirect()->to('userlink');

    }

    public function mealdelete($id = '')
    {
        if ($id == null) {
            return redirect()->to("Admindashboard");
        }
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $deletemeal = $this->mealinfo_model->where('day_id', $id)->delete();
        $deletedate = $this->days_model->where('day_id', $id)->delete();
        (empty($deletedate) && empty($deletemeal)) ? $session->setFlashdata('error', 'Failed due to unknown ID') : $session->setFlashdata('success', 'Deleted successfully');
        return redirect()->to('userlink');
    }

    public function getToken($length_of_string)
    {
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($str_result),
            0, $length_of_string);
    }

    public function generateRandomPassword($length = 7)
    {
        // Define the character set to use
        $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';

        // Get the length of the character set
        $charsetLength = strlen($charset);

        // Initialize an empty password string
        $password = '';

        // Generate a random character for each position in the password
        for ($i = 0; $i < $length; $i++) {
            $randomIndex = rand(0, $charsetLength - 1);
            $password .= $charset[$randomIndex];
        }

        return $password;
    }

    public function savemealinfo()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if ($this->request->getMethod() == 'post') {
            extract($this->request->getPost());
        }
       
        $input = $this->validate(['date' => 'required', 'user_id' => 'required']);
        if (!empty($input)) {
            // $combined_array = array_combine(
            //     array_map(function ($item) {
            //         return $item;
            //     }, $meal_name),
            //     $meal_description
            // );
            $result = array_map(null, $meal_name, $meal_description, $time);
            $data = ['date' => $date, 'user_id' => $user_id, 'created_at' => date('Y-m-d h:m:s')];
            $save = $this
                ->days_model
                ->insert($data);
            $day_id = $this->days_model->getInsertID();
            if (!empty($day_id)) {
                foreach ($result as $key => $sid) {
                    if (empty($result[$key])) {
                        unset($result[$key]);
                    }
                }
               
                foreach ($result as $key => $sid) {
                    $data = [
                        'day_id' => $day_id,
                        'user_id' => $user_id,
                        'meal_name' => $sid[0],
                        'meal_description' => $sid[1],
                        'meal_time' => $sid[2],
                        'created_date' => date('Y-m-d'),
                    ];
                    $saved = $this->mealinfo_model->save($data);
                }
            }
            (empty($saved)) ? $session->setFlashdata('error', 'Failed To Save') : $session->setFlashdata('success', 'Saved Successfully');
        } else {
            $session->setFlashdata('error', 'Fill All Fields');
        }
        return redirect()->to('userlink');
    }

    public function editmealinfo()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if ($this->request->getMethod() == 'post') {
            extract($this->request->getPost());
        }
        $input = $this->validate(['date' => 'required', 'meal_id_hidd' => 'required']);
        if (!empty($input)) {
            $deletemeal = $this->mealinfo_model->where('day_id', $meal_id_hidd)->delete();
            $deletedate = $this->days_model->where('day_id', $meal_id_hidd)->delete();
            if (!empty($deletemeal) && !empty($deletedate)) {
                $combined_array = array_combine(
                    array_map(function ($item) {
                        return $item;
                    }, $meal_name),
                    $meal_description
                );
                $data = ['date' => $date, 'user_id' => $user_id, 'created_at' => date('Y-m-d h:m:s')];
                $save = $this
                    ->days_model
                    ->insert($data);
                $day_id = $this->days_model->getInsertID();
                if (!empty($day_id)) {
                    $result = array_map(null, $meal_name, $meal_description, $time);
                    foreach ($result as $key => $sid) {
                        if (empty($result[$key])) {
                            unset($result[$key]);
                        }
                    }
                   
                    foreach ($result as $key => $sid) {
                        $data = [
                            'day_id' => $day_id,
                            'user_id' => $user_id,
                            'meal_name' => $sid[0],
                            'meal_description' => $sid[1],
                            'meal_time' => $sid[2],
                            'created_date' => date('Y-m-d'),
                        ];
                        $saved = $this->mealinfo_model->save($data);
                    }
                }
                (empty($saved)) ? $session->setFlashdata('error', 'Failed To Save') : $session->setFlashdata('success', 'Saved Successfully');

            } else {
                $session->setFlashdata('error', 'Failed to update');
            }
        }
        return redirect()->to('userlink');
    }
}
