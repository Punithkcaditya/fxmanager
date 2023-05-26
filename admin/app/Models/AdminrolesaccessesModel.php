<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class AdminrolesaccessesModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'admin_roles_accesses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['role_id', 'menuitem_id', 'add_permission', 'edit_permission', 'delete_permission'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
        $this->primary_key = array();
        $this->date = array();

        // OR $this->db = db_connect();
    }

    public function get_role_access($role_id, $parent_menuitem_id = null)
    {
        if (empty($parent_menuitem_id)) {
            $sql = 'SELECT *   FROM admin_menuitems as am  LEFT JOIN ' . $this->table . ' as ua ON am.menuitem_id = ua.menuitem_id where am.parent_menuitem_id IS NULL    AND ua.role_id =  ' . $role_id . '  AND am.status_ind =  1  ORDER BY `display_order` asc';

        } else {

            // personal try

            $sql = 'SELECT *   FROM admin_menuitems as am  LEFT JOIN ' . $this->table . ' as ua ON am.menuitem_id = ua.menuitem_id where am.parent_menuitem_id =' . $parent_menuitem_id . '   AND ua.role_id =  ' . $role_id . '  AND am.status_ind =  1  ORDER BY `display_order` asc';

        }

        $query = $this->db->query($sql);
        if ($query->getNumRows() > 0) {
            $result = $query->getResultObject();
            $tmpresult = $result;

            for ($i = 0; $i < count($tmpresult); $i++) {
                $tmpresult[$i]->submenus = $this->get_role_access($role_id, $tmpresult[$i]->menuitem_id);
            }
            return $tmpresult;
        } else {
            return;
        }
    }

    public function view($role_id = 1)
    {
        $sqle = 'SELECT *   FROM ' . $this->table . '  where role_id = ' . $role_id . ' ';
        // echo '<pre>';
        // print_r($sql);
        // exit;
        $query = $this->db->query($sqle);
        $result = $query->getResultObject();
        return $result;

    }

    public function view_access($role_id)
    {
        $sql = 'SELECT a.* , am.parent_menuitem_id, am.menuitem_link, am.menuitem_text FROM admin_roles_accesses as a  LEFT JOIN admin_menuitems as am ON a.menuitem_id = am.menuitem_id where a.role_id = ' . $role_id . '  ';
        //  echo '<pre>';
        //     print_r($sql);
        //     exit;
        $query = $this->db->query($sql);
        $result = $query->getResultObject();
        return $result;
    }

    public function get_permisions($role_id, $menu_id) {
        $sql3 = 'SELECT add_permission,edit_permission,delete_permission  FROM ' . $this->table . '  where role_id = ' . $role_id . ' AND menuitem_id = '.$menu_id.' ';
        $query = $this->db->query($sql3);
        $result = $query->getRow();
        return $result;
    }
}
