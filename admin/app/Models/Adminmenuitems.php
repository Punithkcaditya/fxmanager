<?php

namespace App\Models;
use Config\Database;
use CodeIgniter\Model;

class Adminmenuitems extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'admin_menuitems';
    protected $primaryKey       = 'menuitem_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['menu_id', 'parent_menuitem_id', 'menuitem_target', 'menuitem_link', 'menuitem_text', 'display_order', 'status_ind', 'created_date'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];




    public function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
        $this->date = array();

        // OR $this->db = db_connect();
    }


    public function view($parent_menuitem_id = NULL)
    {

        if (empty($parent_menuitem_id)) {


            $sql = 'SELECT *   FROM ' . $this->table . '  where parent_menuitem_id IS NULL AND `status_ind` = 1';
        } else {


            $sql = 'SELECT *   FROM ' . $this->table . '  where parent_menuitem_id = ' . $parent_menuitem_id . ' AND `status_ind` = 1';
            // echo '<pre>';
            // print_r($sql);
            // exit;


        }

        $query = $this->db->query($sql);
    

        if (count($query->getResultArray()) > 0) {
            $result = $query->getResultObject();
       
            for ($i = 0; $i < count($result); $i++) {

                $result[$i]->submenus = $this->view($result[$i]->menuitem_id);
            }

            return $result;
        }
        else {

            return;

        }
    }
}
