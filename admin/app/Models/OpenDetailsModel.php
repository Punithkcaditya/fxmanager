<?php

namespace App\Models;

use Config\Database;
use CodeIgniter\Model;

class OpenDetailsModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'open_details';
    protected $primaryKey       = 'open_detailsid';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['open_amount' , 'transactionforeing_id', 'isSettled' , 'created_at'];

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


    public function transaction()
    {
        return $this->belongsTo(TransactionModel::class, 'transactionforeing_id', 'transaction_id');
    }
 
    
}
