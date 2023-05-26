<?php

namespace App\Models;
use Config\Database;

use CodeIgniter\Model;

class Settings extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'settings';
    protected $primaryKey       = 'setting_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['setting_id', 'type', 'setting_key', 'setting_name', 'setting_value',  'status_ind' ];

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
        $this->primary_key = array();
        $this->date = array();
       
        // OR $this->db = db_connect();
    }




    public function view()
    {
        $query = $this->db->query('select * from ' . $this->table);
        return $query->getResult();
    }


    public function is_exist()
    {
       
        foreach($this->primary_key as $value1){
            $id1  = $value1;
                }
        $sql ='SELECT COUNT(*)  FROM ' . $this->table.' where setting_id = '. $id1.'';
        $query = $this->db->query($sql);
        $result = $query->getResultObject();
        return count($result);
    }

    public function update_data()
    {
        $this->db->table($this->table)->update($this->data, $this->primary_key);
       // return $this->db->affectedRows();
       return true;
    }

    public function update_all() {
        $this->db->table($this->table)->update($this->data);
        //$this->reset();
        return true;
    }

    public function updatfe_data()
    {
            $builder = $this->db->table("settings");
      
            $updated_data = $this->data;
              
            $builder->where([
                "setting_id" => $this->primary_key
            ]);
            $builder->set($updated_data);
      
            return $builder->update(); 
    }


}
