<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class TransactionModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'transactiondetails';
    protected $primaryKey       = 'transaction_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['exposurereInfo', 'currency', 'dateofInvoice', 'counterParty',  'counterPartycountry', 'exposureType', 'spot_rate', 'forward_rate', 'exposureidentificationdate' , 'bank_id', 'dueDate', 'amountinFC', 'targetRate', 'created_at'];

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
        $this->table = 'transactiondetails';
        $this->primary_key = array();
        $this->data = array();
       
    }
	
	   public function getdependantData($transaction_id )
    {
        $sql = 'SELECT a.* , am.deal_no, am.bankname, am.forward_coverdetails_id, GROUP_CONCAT(deal_no) as differentdealno, GROUP_CONCAT(forward_coverdetails_id) as differentcoverdetails  FROM transactiondetails as a  LEFT JOIN forward_coverdetails as am ON a.transaction_id  = am.underlying_exposure_ref  where a.transaction_id  = ' . $transaction_id . '  ';
		$query = $this->db->query($sql);
        $result = $query->getRow();
        return $result;
    }

       public function mtmoperatingrisk()
    {
        $sql = 'SELECT a.*, 
        cr.Currency, cp.counterPartyName,
        ex.exposure_type,
        SUM(frcw.ToatalforwardAmount) as ToatalforwardAmount,
        SUM(frcw.Avgrate) as Avgrate,
        SUM(p.Toatalallpayment) as Toatalallpayment,
        SUM(p.AvgspotamountRate) as AvgspotamountRate
        FROM transactiondetails as a
        LEFT  JOIN (
        SELECT underlying_exposure_ref , SUM(amount_FC) as ToatalforwardAmount, AVG(contracted_Rate) as Avgrate
        FROM   forward_coverdetails
        GROUP  BY 1
        ) frcw ON a.transaction_id  = frcw.underlying_exposure_ref
        LEFT JOIN currency as cr ON a.currency   = cr.currency_id
        LEFT JOIN counter_party as cp ON a.counterParty = cp.counterParty_id
        LEFT JOIN exposure_type as ex ON a.exposureType = ex.exposure_type_id
        LEFT  JOIN (
        SELECT  underlying_exposure_ref, SUM(forward_Amount*forward_Rate)  as Toatalallpayment, (spot_Amount*spotamount_Rate) as AvgspotamountRate
        FROM   paymentreceiptdetails
        GROUP  BY 1
        ) p ON a.transaction_id  = p.underlying_exposure_ref GROUP  BY a.transaction_id';
        $query = $this->db->query($sql);
        $result = $query->getResult();
        return $result;
    }
	
	public function tabsarrangement($curid='')
    {
		$sql = ' SELECT DATE_FORMAT(dueDate, "%Y-%m") AS month, SUM(amountinFC) as UnderlyingExposures, currency, amountinFC,
		SUM(frcw.ToatalforwardAmount) as ToatalforwardAmount		
		 FROM   transactiondetails as a
		 LEFT  JOIN (
		 SELECT currency_id
		 FROM   currency
		 ) c ON c.currency_id = a.currency
		 LEFT  JOIN (
        SELECT underlying_exposure_ref , SUM(amount_FC) as ToatalforwardAmount, AVG(contracted_Rate) as Avgrate
        FROM   forward_coverdetails
        GROUP  BY 1
        ) frcw ON a.transaction_id  = frcw.underlying_exposure_ref  WHERE `a`.`currency` = '.$curid.' AND `a`.`exposureType` = 2 GROUP BY 1';
		 $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
	}
	
	
	public function tabsarrangementforexport($curid='')
    {
		$sql = ' SELECT DATE_FORMAT(dueDate, "%Y-%m") AS month, SUM(amountinFC) as UnderlyingExposures, currency, amountinFC,
		SUM(frcw.ToatalforwardAmount) as ToatalforwardAmount		
		 FROM   transactiondetails as a
		 LEFT  JOIN (
		 SELECT currency_id
		 FROM   currency
		 ) c ON c.currency_id = a.currency
		 LEFT  JOIN (
        SELECT underlying_exposure_ref , SUM(amount_FC) as ToatalforwardAmount, AVG(contracted_Rate) as Avgrate
        FROM   forward_coverdetails
        GROUP  BY 1
        ) frcw ON a.transaction_id  = frcw.underlying_exposure_ref  WHERE `a`.`currency` = '.$curid.' AND `a`.`exposureType` = 1 GROUP BY 1';
		 $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
	}
	
	public function helicopterviewimport($curid='')
    {
		$sql = "SELECT YEAR(td.dueDate) AS `Year`, 
		IF(QUARTER(td.dueDate) = 1, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.amount_FC END)), NULL) AS `Q1`, 
		IF(QUARTER(td.dueDate) = 2, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.amount_FC END)), NULL) AS `Q2`, 
		IF(QUARTER(td.dueDate) = 3, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.amount_FC END)), NULL) AS `Q3`, 
		IF(QUARTER(td.dueDate) = 4, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.amount_FC END)), NULL) AS `Q4`
		FROM transactiondetails AS td INNER JOIN forward_coverdetails AS frcw ON td.transaction_id = frcw.underlying_exposure_ref WHERE `td`.`exposureType` = 2 AND `td`.`currency` = ".$curid." GROUP BY YEAR(td.dueDate), QUARTER(td.dueDate);";	
        $query = $this->db->query($sql);
		$result = $query->getResultArray();
		return $result;

	}
	
	public function helicopterviewexport($curid='')
    {
		$sql = "SELECT YEAR(td.dueDate) AS `Year`, 
		IF(QUARTER(td.dueDate) = 1, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.amount_FC END)), NULL) AS `Q1`, 
		IF(QUARTER(td.dueDate) = 2, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.amount_FC END)), NULL) AS `Q2`, 
		IF(QUARTER(td.dueDate) = 3, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.amount_FC END)), NULL) AS `Q3`, 
		IF(QUARTER(td.dueDate) = 4, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.amount_FC END)), NULL) AS `Q4`
		FROM transactiondetails AS td INNER JOIN forward_coverdetails AS frcw ON td.transaction_id = frcw.underlying_exposure_ref WHERE `td`.`exposureType` = 1 AND `td`.`currency` = ".$curid." GROUP BY YEAR(td.dueDate), QUARTER(td.dueDate);";	
		$query = $this->db->query($sql);
		$result = $query->getResultArray();
		return $result;
	}
	
	public function helicopterviewbuyersCredit($curid='')
    {
		$sql = "SELECT YEAR(td.dueDate) AS `Year`, 
		IF(QUARTER(td.dueDate) = 1, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.amount_FC END)), NULL) AS `Q1`, 
		IF(QUARTER(td.dueDate) = 2, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.amount_FC END)), NULL) AS `Q2`, 
		IF(QUARTER(td.dueDate) = 3, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.amount_FC END)), NULL) AS `Q3`, 
		IF(QUARTER(td.dueDate) = 4, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.amount_FC END)), NULL) AS `Q4`
		FROM transactiondetails AS td INNER JOIN forward_coverdetails AS frcw ON td.transaction_id = frcw.underlying_exposure_ref WHERE `td`.`exposureType` = 3 AND `td`.`currency` = ".$curid." GROUP BY YEAR(td.dueDate), QUARTER(td.dueDate);";	
		$query = $this->db->query($sql);
		$result = $query->getResultArray();
		return $result;

	}
	
	public function helicopterviewbuyersmisc($curid='')
    {
		$sql = "SELECT YEAR(td.dueDate) AS `Year`, 
		IF(QUARTER(td.dueDate) = 1, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.amount_FC END)), NULL) AS `Q1`, 
		IF(QUARTER(td.dueDate) = 2, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.amount_FC END)), NULL) AS `Q2`, 
		IF(QUARTER(td.dueDate) = 3, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.amount_FC END)), NULL) AS `Q3`, 
		IF(QUARTER(td.dueDate) = 4, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.amount_FC END)), NULL) AS `Q4`
		FROM transactiondetails AS td INNER JOIN forward_coverdetails AS frcw ON td.transaction_id = frcw.underlying_exposure_ref WHERE `td`.`exposureType` = 5 AND `td`.`currency` = ".$curid." GROUP BY YEAR(td.dueDate), QUARTER(td.dueDate);";	
		$query = $this->db->query($sql);
		$result = $query->getResultArray();
		return $result;

	}
	
	public function helicoptertabscapitalpaymnts($curid='')
    {
		$sql = "SELECT YEAR(td.dueDate) AS `Year`, 
		IF(QUARTER(td.dueDate) = 1, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.amount_FC END)), NULL) AS `Q1`, 
		IF(QUARTER(td.dueDate) = 2, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.amount_FC END)), NULL) AS `Q2`, 
		IF(QUARTER(td.dueDate) = 3, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.amount_FC END)), NULL) AS `Q3`, 
		IF(QUARTER(td.dueDate) = 4, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.amount_FC END)), NULL) AS `Q4`
		FROM transactiondetails AS td INNER JOIN forward_coverdetails AS frcw ON td.transaction_id = frcw.underlying_exposure_ref WHERE `td`.`exposureType` = 4 AND `td`.`currency` = ".$curid." GROUP BY YEAR(td.dueDate), QUARTER(td.dueDate);";	
		$query = $this->db->query($sql);
		$result = $query->getResultArray();
		return $result;

	}
}
