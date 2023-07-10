<?php

namespace App\Models;

use CodeIgniter\Model;

class ForwardCoverdetails extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'forward_coverdetails';
    protected $primaryKey       = 'forward_coverdetails_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['bankname', 'deal_no', 'deal_date', 'underlying_exposure_ref', 'fordward_option', 'currencybought', 'currencysold', 'amount_FC', 'contracted_Rate', 'bank_id', 'expiry_date' , 'created_at'];

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
}
