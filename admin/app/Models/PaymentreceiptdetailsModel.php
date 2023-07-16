<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentreceiptdetailsModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'paymentreceiptdetails';
    protected $primaryKey       = 'paymentreceiptdetails_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['amount_FC', 'value_INR', 'target_Value', 'underlying_Exposure_ref', 'dateof_Settlement', 'spot_Amount', 'spotamount_Rate', 'deal_Referenceno', 'forward_Amount', 'forward_Rate', 'wash_Rate', 'exposure_Currency', 'bank_Name', 'created_at'];

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
