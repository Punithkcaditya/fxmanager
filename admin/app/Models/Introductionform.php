<?php

namespace App\Models;

use Config\Database;
use CodeIgniter\Model;

class Introductionform extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = ' introduction_form';
    protected $primaryKey       = 'ssjl_into_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['ssjl_full_name', 'ssjl_mobile', 'ssjl_email', 'ssjl_user_name','ssjl_blood_group', 'ssjl_uan_no', 'ssjl_adhaar_no', 'ssjl_pan_no', 'ssl_profile_pic', 'ssjl_bank_name', 'ssjl_bank_ac_no', 'ssjl_marital_status',  'ssjl_fathers_name', 'ssjl_emergency_contact_person', 'ssjl_emergency_contact_no', 'ssjl_reference_person', 'ssjl_date', 'ssjl_contact_no', 'ssjl_health_issue_any', 'ssjl_employee_id' , 'ssjl_resume', 'ssjl_pan_copy', 'ssjl_adhaar_copy', 'ssjl_date_of_birth', 'ssjl_previous_pay_slips', 'ssjl_releiving_letter', 'ssjl_rent_agreement', 'ssjl_education_documents', 'ssjl_isapproved', 'created_at'];

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
}
