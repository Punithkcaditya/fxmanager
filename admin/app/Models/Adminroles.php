<?php

namespace App\Models;
use Config\Database;
use CodeIgniter\Model;

class Adminroles extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'admin_roles';
    protected $primaryKey       = 'role_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = ['role_name', 'status_ind', 'created_date' , 'created_by', 'last_modified_by'];

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


    function __construct() {
        parent::__construct();
        $this->db = Database::connect();
        $this->table = 'admin_roles';
        $this->data = array();
       
    }


    public function get_row($id) {

      
        $sql2 = 'SELECT *   from admin_roles  where role_id =  '.$id.' ';
        // echo '<pre>';
        // print_r( $sql2);
        // exit;
        $query = $this->db->query($sql2);
        $result = $query->getResultObject();
        return $result;
       }


       public function getsellerid($sellername = "") {
        $sql6 = 'SELECT role_id  from admin_roles  where role_name =  "'.$sellername.'" ';
        $query = $this->db->query($sql6);
        $result = $query->getResultArray();
        return $result;
    }
}
