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
    protected $allowedFields    = ['exposurereInfo', 'currency', 'dateofInvoice', 'counterParty',  'counterPartycountry', 'exposureType', 'spot_rate', 'forward_rate', 'exposureidentificationdate' , 'bank_id', 'dueDate', 'amountinFC', 'inr_target_value', 'targetRate', 'created_at'];

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
	
    public function opendetails()
    {
        return $this->hasMany(OpenDetailsModel::class, 'transactionforeing_id', 'transaction_id');
    }


	   public function getdependantData($transaction_id )
    {
        $sql = 'SELECT a.* , bnk.bank_name, am.deal_no, am.forward_coverdetails_id, GROUP_CONCAT(deal_no) as differentdealno, GROUP_CONCAT(forward_coverdetails_id) as differentcoverdetails  FROM transactiondetails as a  LEFT JOIN forward_coverdetails as am ON a.transaction_id  = am.underlying_exposure_ref 
        LEFT JOIN bank_master as bnk ON a.bank_id  = bnk.bank_id    where a.transaction_id  = ' . $transaction_id . '  ';
        $query = $this->db->query($sql);
        $result = $query->getRow();
        return $result;
    }

       public function mtmoperatingrisk()
    {
        $sql = 'SELECT a.*, 
        cr.Currency, cp.counterPartyName,
        ex.exposure_type,
        op.open_amount, op.isSettled,
        frcw.expiry_date,
        SUM(frcw.ToatalforwardAmount) as ToatalforwardAmount,
        SUM(frcw.Avgrate) as Avgrate,
        SUM(p.Toatalallpayment) as Toatalallpayment,
        SUM(p.AvgspotamountRate) as AvgspotamountRate
        FROM transactiondetails as a
        LEFT  JOIN (
        SELECT underlying_exposure_ref, expiry_date , SUM(amount_FC) as ToatalforwardAmount, AVG(contracted_Rate) as Avgrate
        FROM   forward_coverdetails
        GROUP  BY 1
        ) frcw ON a.transaction_id  = frcw.underlying_exposure_ref
        LEFT JOIN currency as cr ON a.currency   = cr.currency_id
        LEFT JOIN counter_party as cp ON a.counterParty = cp.counterParty_id
        LEFT JOIN exposure_type as ex ON a.exposureType = ex.exposure_type_id
        LEFT JOIN open_details as op ON a.transaction_id  = op.transactionforeing_id
        LEFT  JOIN (
        SELECT  underlying_exposure_ref, SUM(forward_Amount*forward_Rate)  as Toatalallpayment, (spot_Amount*spotamount_Rate) as AvgspotamountRate
        FROM   paymentreceiptdetails
        GROUP  BY 1
        ) p ON a.transaction_id  = p.underlying_exposure_ref GROUP  BY a.transaction_id';
          $query = $this->db->query($sql);
          if (is_object($query)) {
              $result = $query->getResult();
              return $result;
          }else{
              return [];
          }
    }
	

    public function tabsarrangement($curid='')
    {
        $sql = 'SELECT
                    months.month AS month,
                    COALESCE(SUM(CASE WHEN a.exposureType = 1 THEN 0 ELSE amountinFC END), 0) AS UnderlyingExposures,
                    COALESCE(SUM(CASE WHEN a.exposureType = 2 THEN amountinFC ELSE 0 END), 0) AS SumImportsType,
                    COALESCE(SUM(CASE WHEN a.exposureType = 3 THEN amountinFC ELSE 0 END), 0) AS SumBuyersCreditType,
                    COALESCE(SUM(CASE WHEN a.exposureType = 4 THEN amountinFC ELSE 0 END), 0) AS CapitalPaymentsType,
                    COALESCE(SUM(CASE WHEN a.exposureType = 5 THEN amountinFC ELSE 0 END), 0) AS OtherPaymentsType,
                    COALESCE(SUM(frcw.ToatalforwardAmount), 0) AS ToatalforwardAmount,
                    COALESCE(amount_FC, 0) AS amount_FC
                FROM
                    (
                        SELECT DATE_FORMAT(DATE_ADD("2023-04-01", INTERVAL n MONTH), "%Y-%m") AS month
                        FROM (
                            SELECT 0 AS n
                            UNION SELECT 1 UNION SELECT 2 UNION SELECT 3
                            UNION SELECT 4 UNION SELECT 5 UNION SELECT 6
                            UNION SELECT 7 UNION SELECT 8 UNION SELECT 9
                            UNION SELECT 10 UNION SELECT 11 
                        ) AS numbers
                    ) AS months
                LEFT JOIN transactiondetails AS a ON DATE_FORMAT(a.dueDate, "%Y-%m") = months.month
                    AND a.currency = '.$curid.'
                    AND a.exposureType != 1
                LEFT JOIN currency AS c ON c.currency_id = a.currency
                LEFT JOIN (
                    SELECT underlying_exposure_ref,
                        amount_FC,
                        SUM(amount_FC) AS ToatalforwardAmount,
                        AVG(contracted_Rate) AS Avgrate
                    FROM forward_coverdetails
                    GROUP BY 1
                ) AS frcw ON a.transaction_id = frcw.underlying_exposure_ref
                WHERE months.month >= DATE_FORMAT("2023-04-01", "%Y-%m")
                GROUP BY months.month
                ORDER BY months.month';
        $query = $this->db->query($sql);
    
        if (is_object($query)) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return [];
        }
    }
    


	// public function tabsarrangement($curid='')
    // {
    //     $sql = 'SELECT DATE_FORMAT(dueDate, "%Y-%m") AS month,
    //     SUM(CASE WHEN a.exposureType = 1 THEN 0 ELSE amountinFC END) AS UnderlyingExposures,
    //     SUM(CASE WHEN a.exposureType = 2 THEN amountinFC ELSE 0 END) AS SumImportsType,
    //     SUM(CASE WHEN a.exposureType = 3 THEN amountinFC ELSE 0 END) AS SumBuyersCreditType,
    //     SUM(CASE WHEN a.exposureType = 4 THEN amountinFC ELSE 0 END) AS CapitalPaymentsType,
    //     SUM(CASE WHEN a.exposureType = 5 THEN amountinFC ELSE 0 END) AS OtherPaymentsType,
    //     SUM(frcw.ToatalforwardAmount) AS ToatalforwardAmount,
    //     amount_FC
    //     FROM transactiondetails AS a
    //     LEFT JOIN (
    //         SELECT currency_id
    //         FROM currency
    //     ) c ON c.currency_id = a.currency
    //     LEFT JOIN (
    //         SELECT underlying_exposure_ref,
    //             amount_FC,
    //             SUM(amount_FC) AS ToatalforwardAmount,
    //             AVG(contracted_Rate) AS Avgrate
    //         FROM forward_coverdetails
    //         GROUP BY 1
    //     ) frcw ON a.transaction_id = frcw.underlying_exposure_ref
    //     WHERE a.currency = '.$curid.'
    //     AND a.exposureType != 1
    //     GROUP BY 1';
    //     $query = $this->db->query($sql);
    //     if (is_object($query)) {
    //        $result = $query->getResultArray();
    //        return $result;
    //    }else{
    //        return [];
    //    }
	// }
	

    public function tabsarrangementforexport($curid='')
    {
        $sql = 'SELECT 
                    DATE_FORMAT(months.month, "%Y-%m") AS month,
                    COALESCE(SUM(a.amountinFC), 0) AS UnderlyingExposures,
                    c.currency_id,
                    COALESCE(a.amountinFC, 0) AS amountinFC,
                    COALESCE(SUM(frcw.ToatalforwardAmount), 0) AS ToatalforwardAmount		
                FROM
                    (SELECT DATE_ADD("2023-04-01", INTERVAL (n-1) MONTH) AS month
                    FROM (SELECT 1 AS n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 
                          UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 
                          UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) AS numbers) AS months
                LEFT JOIN transactiondetails AS a ON DATE_FORMAT(months.month, "%Y-%m") = DATE_FORMAT(a.dueDate, "%Y-%m")
                                                AND a.currency = '.$curid.'
                                                AND a.exposureType = 1
                LEFT JOIN currency AS c ON c.currency_id = a.currency
                LEFT JOIN (SELECT underlying_exposure_ref, SUM(amount_FC) AS ToatalforwardAmount
                            FROM forward_coverdetails
                            GROUP BY 1) AS frcw ON a.transaction_id = frcw.underlying_exposure_ref
                GROUP BY months.month
                ORDER BY months.month';
        
        $query = $this->db->query($sql);
        
        if (is_object($query)) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return [];
        }
    }
    

	
	// public function tabsarrangementforexport($curid='')
    // {
	// 	$sql = ' SELECT DATE_FORMAT(dueDate, "%Y-%m") AS month, SUM(amountinFC) as UnderlyingExposures, currency, amountinFC,
	// 	SUM(frcw.ToatalforwardAmount) as ToatalforwardAmount		
	// 	 FROM   transactiondetails as a
	// 	 LEFT  JOIN (
	// 	 SELECT currency_id
	// 	 FROM   currency
	// 	 ) c ON c.currency_id = a.currency
	// 	 LEFT  JOIN (
    //     SELECT underlying_exposure_ref , SUM(amount_FC) as ToatalforwardAmount
    //     FROM   forward_coverdetails
    //     GROUP  BY 1
    //     ) frcw ON a.transaction_id  = frcw.underlying_exposure_ref  WHERE `a`.`currency` = '.$curid.' AND `a`.`exposureType` = 1 GROUP BY 1';
    //      $query = $this->db->query($sql);
    //      if (is_object($query)) {
    //        $result = $query->getResultArray();
    //        return $result;
    //    }else{
    //        return [];
    //    }
	// }
	

    public function helicopterviewimportold($curid = '')
    {
        
        $sql = "SELECT YEAR(td.dueDate) AS `Year`, 
		IF(QUARTER(td.dueDate) = 2, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.amount_FC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.spot_rate END) ), NULL) AS `Q1`, 
		IF(QUARTER(td.dueDate) = 3, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.amount_FC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.spot_rate END) ), NULL) AS `Q2`, 
		IF(QUARTER(td.dueDate) = 4, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.contracted_Rate END),  ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.amount_FC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.spot_rate END) ), NULL) AS `Q3`, 
		IF(QUARTER(td.dueDate) = 1, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.contracted_Rate END),  ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.amount_FC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.spot_rate END) ), NULL) AS `Q4`
		FROM transactiondetails AS td INNER JOIN forward_coverdetails AS frcw ON td.transaction_id = frcw.underlying_exposure_ref WHERE `td`.`exposureType` = 2 AND `td`.`currency` = ".$curid." GROUP BY YEAR(td.dueDate), QUARTER(td.dueDate);";	
          $query = $this->db->query($sql);
          if (is_object($query)) {
            $result = $query->getResultArray();
            return $result;
        }else{
            return [];
        }
    }


//     public function helicopterviewcommon($curid = '', $type = '')
// {
//     $sql = "SELECT
//         td.transaction_id,
//         t.Year,
//         t.Quarter,
//         t.amountinFC,
//         AVG(frcw.contracted_Rate) AS contracted_Rate,
//         SUM(frcw.amount_FC) AS amount_FC,
//         AVG(td.spot_rate) AS spot_rate,
//         AVG(td.targetRate) AS targetRate,
//         CASE t.Quarter
//             WHEN 1 THEN 'Q4'
//             WHEN 2 THEN 'Q1'
//             WHEN 3 THEN 'Q2'
//             WHEN 4 THEN 'Q3'
//         END AS quarter_name,
//         CASE
//             WHEN td.inr_target_value > 0 THEN AVG(td.targetRate * td.inr_target_value)
//             WHEN td.inr_target_value <= 0 THEN AVG(td.targetRate)
//         END AS calculated_targetRate
//     FROM
//         transactiondetails AS td
//         LEFT JOIN forward_coverdetails AS frcw ON td.transaction_id = frcw.underlying_exposure_ref
//         INNER JOIN (
//             SELECT
//                 transaction_id,
//                 YEAR(dueDate) AS Year,
//                 QUARTER(dueDate) AS Quarter,
//                 SUM(amountinFC) AS amountinFC
//             FROM
//                 transactiondetails
//             WHERE
//                 currency = '$curid'
//                 AND exposureType = '$type'
//             GROUP BY
//                 transaction_id, Year, Quarter
//         ) AS t ON td.transaction_id = t.transaction_id AND YEAR(td.dueDate) = t.Year AND QUARTER(td.dueDate) = t.Quarter
//     GROUP BY
//         td.transaction_id, t.Year, t.Quarter, t.amountinFC;";
//     $query = $this->db->query($sql);
//     if ($query && $query->getNumRows() > 0) {
//         $result = $query->getResultArray();
//         return $result;
//     } else {
//         return [];
//     }
// }



        public function helicopterviewcommon($curid = '', $type = '')
        {
        $sql = "SELECT
        td.transaction_id,
        t.Year,
        t.Quarter,
        t.amountinFC,
        AVG(frcw.contracted_Rate) AS contracted_Rate,
        SUM(frcw.amount_FC) AS amount_FC,
        AVG(td.spot_rate) AS spot_rate,
        AVG(td.targetRate) AS targetRate,
        CASE t.Quarter
            WHEN 1 THEN 'Q4'
            WHEN 2 THEN 'Q1'
            WHEN 3 THEN 'Q2'
            WHEN 4 THEN 'Q3'
        END AS quarter_name,
        CASE
            WHEN td.inr_target_value > 0 THEN AVG(td.targetRate * td.inr_target_value)
            WHEN td.inr_target_value <= 0 THEN AVG(td.targetRate)
        END AS calculated_targetRate
        FROM
        transactiondetails AS td
        INNER JOIN
        forward_coverdetails AS frcw ON td.transaction_id = frcw.underlying_exposure_ref
        INNER JOIN (
        SELECT
            transaction_id,
            YEAR(dueDate) AS Year,
            QUARTER(dueDate) AS Quarter,
            SUM(amountinFC) AS amountinFC
        FROM
            transactiondetails
        WHERE
            currency = '$curid'
            AND exposureType = '$type'
        GROUP BY
            transaction_id, Year, Quarter
        ) AS t ON td.transaction_id = t.transaction_id AND YEAR(td.dueDate) = t.Year AND QUARTER(td.dueDate) = t.Quarter
        GROUP BY
        td.transaction_id, t.Year, t.Quarter, t.amountinFC;";
        $query = $this->db->query($sql);
        if ($query && $query->getNumRows() > 0) {
        $result = $query->getResultArray();
        return $result;
        } else {
        return [];
        }
        }



    // public function helicopterviewcommon($curid = '', $type = '')
    // {
    //     $curYear = date('Y');
    //     $sql = "SELECT
    //         td.transaction_id,
    //         t.Year,
    //         t.Quarter,
    //         t.amountinFC,
    //         AVG(frcw.contracted_Rate) AS contracted_Rate,
    //         SUM(frcw.amount_FC) AS amount_FC,
    //         AVG(td.spot_rate) AS spot_rate,
    //         AVG(td.targetRate) AS targetRate,
    //         CASE t.Quarter
    //             WHEN 1 THEN 'Q4'
    //             WHEN 2 THEN 'Q1'
    //             WHEN 3 THEN 'Q2'
    //             WHEN 4 THEN 'Q3'
    //         END AS quarter_name,
    //         CASE
    //             WHEN td.inr_target_value > 0 THEN AVG(td.targetRate * td.inr_target_value)
    //             WHEN td.inr_target_value <= 0 THEN AVG(td.targetRate)
    //         END AS calculated_targetRate
    //     FROM
    //         transactiondetails AS td
    //     INNER JOIN
    //         forward_coverdetails AS frcw ON td.transaction_id = frcw.underlying_exposure_ref
    //     INNER JOIN (
    //         SELECT
    //             transaction_id,
    //             YEAR(dueDate) AS Year,
    //             QUARTER(dueDate) AS Quarter,
    //             SUM(amountinFC) AS amountinFC
    //         FROM
    //             transactiondetails
    //         WHERE
    //             YEAR(dueDate) = '$curYear'
    //             AND currency = '$curid'
    //             AND exposureType = '$type'
    //         GROUP BY
    //             transaction_id, Year, Quarter
    //     ) AS t ON td.transaction_id = t.transaction_id AND YEAR(td.dueDate) = t.Year AND QUARTER(td.dueDate) = t.Quarter
    //     GROUP BY
    //         td.transaction_id, t.Year, t.Quarter, t.amountinFC;";        
    //     $query = $this->db->query($sql);
    //     if ($query && $query->getNumRows() > 0) {
    //         $result = $query->getResultArray();
    //         return $result;
    //     } else {
    //         return [];
    //     }
    // }
    

    // public function helicopterviewcommon($curid = '', $type = '')
    // {
    //     $curYear = date('Y');
    //     $sql = "SELECT
    //         td.transaction_id,
    //         t.Year,
    //         t.Quarter,
    //         t.amountinFC,
    //         AVG(frcw.contracted_Rate) AS contracted_Rate,
    //         SUM(frcw.amount_FC) AS amount_FC,
    //         AVG(td.spot_rate) AS spot_rate,
    //         CASE
    //             WHEN td.inr_target_value > 0 THEN SUM(td.targetRate * td.inr_target_value)
    //             WHEN td.inr_target_value <= 0 THEN SUM(td.targetRate)
    //         END AS calculated_targetRate
    //     FROM
    //         transactiondetails AS td
    //     INNER JOIN
    //         forward_coverdetails AS frcw ON td.transaction_id = frcw.underlying_exposure_ref
    //     INNER JOIN (
    //         SELECT
    //             transaction_id,
    //             YEAR(dueDate) AS Year,
    //             QUARTER(dueDate) AS Quarter,
    //             SUM(amountinFC) AS amountinFC
    //         FROM
    //             transactiondetails
    //         WHERE
    //             YEAR(dueDate) = '$curYear'
    //             AND currency = '$curid'
    //             AND exposureType = '$type'
    //         GROUP BY
    //             transaction_id, Year, Quarter
    //     ) AS t ON td.transaction_id = t.transaction_id AND YEAR(td.dueDate) = t.Year AND QUARTER(td.dueDate) = t.Quarter
    //     GROUP BY
    //         td.transaction_id, t.Year, t.Quarter, t.amountinFC;";        
    //     $query = $this->db->query($sql);
    //     if ($query && $query->getNumRows() > 0) {
    //         $result = $query->getResultArray();
    //         return $result;
    //     } else {
    //         return [];
    //     }
    // }
    
    
    


    
    public function helicopterviewcommonoldversiontwo($curid='', $type='')
    {
        $curYear = date('Y');
        $sql = "SELECT
            YEAR(td.dueDate) AS `Year`,
            IF(QUARTER(td.dueDate) = 1, CONCAT(SUM( CASE WHEN QUARTER(td.dueDate) = 1 AND td.exposureType = '$type' THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 1  AND td.exposureType = '$type' THEN frcw.contracted_Rate END), ',', SUM( CASE WHEN QUARTER(td.dueDate) = 1 AND td.exposureType = '$type' AND td.inr_target_value > 0 THEN td.targetRate * td.inr_target_value WHEN QUARTER(td.dueDate) = 1 AND td.exposureType = '$type' AND td.inr_target_value <= 0 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 AND td.exposureType = '$type' THEN frcw.amount_FC END), ',', AVG( CASE WHEN QUARTER(td.dueDate) = 1 AND td.exposureType = '$type' THEN td.spot_rate END), ',', AVG( CASE WHEN QUARTER(td.dueDate) = 1 AND td.exposureType = '$type' THEN td.targetRate END)), NULL) AS `Q4`,
            IF(QUARTER(td.dueDate) = 2, CONCAT(SUM( CASE WHEN QUARTER(td.dueDate) = 2 AND td.exposureType = '$type' THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 2  AND td.exposureType = '$type' THEN frcw.contracted_Rate END), ',', SUM( CASE WHEN QUARTER(td.dueDate) = 2  AND td.exposureType = '$type' AND td.inr_target_value > 0 THEN td.targetRate * td.inr_target_value WHEN QUARTER(td.dueDate) = 2  AND td.exposureType = '$type' AND td.inr_target_value <= 0 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2  AND td.exposureType = '$type' THEN frcw.amount_FC END), ',', AVG( CASE WHEN QUARTER(td.dueDate) = 2  AND td.exposureType = '$type' THEN td.spot_rate END), ',', AVG( CASE WHEN QUARTER(td.dueDate) = 2 AND td.exposureType = '$type' THEN td.targetRate END)), NULL) AS `Q1`,
            IF(QUARTER(td.dueDate) = 3, CONCAT(SUM( CASE WHEN QUARTER(td.dueDate) = 3 AND td.exposureType = '$type' THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 3  AND td.exposureType = '$type' THEN frcw.contracted_Rate END), ',', SUM( CASE WHEN QUARTER(td.dueDate) = 3  AND td.exposureType = '$type' AND td.inr_target_value > 0 THEN td.targetRate * td.inr_target_value WHEN QUARTER(td.dueDate) = 3  AND td.exposureType = '$type' AND td.inr_target_value <= 0 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3  AND td.exposureType = '$type' THEN frcw.amount_FC END), ',', AVG( CASE WHEN QUARTER(td.dueDate) = 3  AND td.exposureType = '$type' THEN td.spot_rate END), ',', AVG( CASE WHEN QUARTER(td.dueDate) = 3 AND td.exposureType = '$type' THEN td.targetRate END)), NULL) AS `Q2`,
            IF(QUARTER(td.dueDate) = 4, CONCAT(SUM( CASE WHEN QUARTER(td.dueDate) = 4 AND td.exposureType = '$type' THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 4  AND td.exposureType = '$type' THEN frcw.contracted_Rate END), ',', SUM( CASE WHEN QUARTER(td.dueDate) = 4  AND td.exposureType = '$type' AND td.inr_target_value > 0 THEN td.targetRate * td.inr_target_value WHEN QUARTER(td.dueDate) = 4  AND td.exposureType = '$type' AND td.inr_target_value <= 0 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4  AND td.exposureType = '$type' THEN frcw.amount_FC END), ',', AVG( CASE WHEN QUARTER(td.dueDate) = 4  AND td.exposureType = '$type' THEN td.spot_rate END), ',', AVG( CASE WHEN QUARTER(td.dueDate) = 4 AND td.exposureType = '$type' THEN td.targetRate END)), NULL) AS `Q3`
        FROM
            (SELECT DISTINCT td.transaction_id, td.dueDate, td.exposureType, td.amountinFC, td.inr_target_value, td.targetRate, td.currency, td.spot_rate FROM transactiondetails AS td) AS td
            INNER JOIN forward_coverdetails AS frcw ON td.transaction_id = frcw.underlying_exposure_ref
        WHERE
            YEAR(td.dueDate) = '$curYear'
            AND td.currency = '$curid'
        GROUP BY
        YEAR(td.dueDate), QUARTER(td.dueDate);";
        $query = $this->db->query($sql);
        if (is_object($query)) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return [];
        }
    }


    public function helicopterviewcommon1($curid = '', $type = '')
    {
        $curYear = date('Y');
        $sql = "SELECT 
                    YEAR(td.dueDate) AS `Year`, 
                    IF(QUARTER(td.dueDate) = 1, CONCAT(SUM(DISTINCT CASE WHEN QUARTER(td.dueDate) = 1 AND td.exposureType = '$type' THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 1  AND td.exposureType = '$type' THEN frcw.contracted_Rate END), ',', SUM(DISTINCT CASE  WHEN QUARTER(td.dueDate) = 1 AND td.exposureType = '$type' AND td.inr_target_value > 0 THEN td.targetRate * td.inr_target_value WHEN QUARTER(td.dueDate) = 1 AND td.exposureType = '$type' AND td.inr_target_value <= 0 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 AND td.exposureType = '$type' THEN frcw.amount_FC END), ',', AVG( CASE WHEN QUARTER(td.dueDate) = 1 AND td.exposureType = '$type' THEN td.spot_rate END), ',', AVG( CASE WHEN QUARTER(td.dueDate) = 1 AND td.exposureType = '$type' THEN td.targetRate END)), NULL) AS `Q4`, 
                    IF(QUARTER(td.dueDate) = 2, CONCAT(SUM(DISTINCT CASE WHEN QUARTER(td.dueDate) = 2 AND td.exposureType = '$type' THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 2  AND td.exposureType = '$type' THEN frcw.contracted_Rate END), ',', SUM(DISTINCT CASE WHEN QUARTER(td.dueDate) = 2  AND td.exposureType = '$type' AND td.inr_target_value > 0 THEN td.targetRate * td.inr_target_value WHEN QUARTER(td.dueDate) = 2  AND td.exposureType = '$type' AND td.inr_target_value <= 0 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2  AND td.exposureType = '$type' THEN frcw.amount_FC END), ',', AVG( CASE WHEN QUARTER(td.dueDate) = 2  AND td.exposureType = '$type' THEN td.spot_rate END), ',', AVG( CASE WHEN QUARTER(td.dueDate) = 2 AND td.exposureType = '$type' THEN td.targetRate END)), NULL) AS `Q1`, 
                    IF(QUARTER(td.dueDate) = 3, CONCAT(SUM(DISTINCT CASE WHEN QUARTER(td.dueDate) = 3 AND td.exposureType = '$type' THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 3  AND td.exposureType = '$type' THEN frcw.contracted_Rate END), ',', SUM(DISTINCT CASE WHEN QUARTER(td.dueDate) = 3  AND td.exposureType = '$type' AND td.inr_target_value > 0 THEN td.targetRate * td.inr_target_value WHEN QUARTER(td.dueDate) = 3  AND td.exposureType = '$type' AND td.inr_target_value <= 0 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3  AND td.exposureType = '$type' THEN frcw.amount_FC END), ',', AVG( CASE WHEN QUARTER(td.dueDate) = 3  AND td.exposureType = '$type' THEN td.spot_rate END), ',', AVG( CASE WHEN QUARTER(td.dueDate) = 3 AND td.exposureType = '$type' THEN td.targetRate END)), NULL) AS `Q2`, 
                    IF(QUARTER(td.dueDate) = 4, CONCAT(SUM(DISTINCT CASE WHEN QUARTER(td.dueDate) = 4 AND td.exposureType = '$type' THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 4  AND td.exposureType = '$type' THEN frcw.contracted_Rate END), ',', SUM(DISTINCT CASE WHEN QUARTER(td.dueDate) = 4  AND td.exposureType = '$type' AND td.inr_target_value > 0 THEN td.targetRate * td.inr_target_value WHEN QUARTER(td.dueDate) = 4  AND td.exposureType = '$type' AND td.inr_target_value <= 0 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4  AND td.exposureType = '$type' THEN frcw.amount_FC END), ',', AVG( CASE WHEN QUARTER(td.dueDate) = 4  AND td.exposureType = '$type' THEN td.spot_rate END), ',', AVG( CASE WHEN QUARTER(td.dueDate) = 4 AND td.exposureType = '$type' THEN td.targetRate END)), NULL) AS `Q3` 
                FROM 
                    transactiondetails AS td 
                    LEFT JOIN forward_coverdetails AS frcw ON td.transaction_id = frcw.underlying_exposure_ref 
                WHERE 
                    YEAR(td.dueDate) = '$curYear' 
                    AND td.currency = '$curid' 
                GROUP BY 
                    td.transaction_id , YEAR(td.dueDate), QUARTER(td.dueDate);";
        
        $query = $this->db->query($sql);
        
        if (is_object($query)) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return [];
        }
    }


	public function helicopterviewimport($curid='')
    {
		$sql = "SELECT YEAR(td.dueDate) AS `Year`, 
		IF(QUARTER(td.dueDate) = 1, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.amount_FC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.spot_rate END) ), NULL) AS `Q4`, 
		IF(QUARTER(td.dueDate) = 2, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.amount_FC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.spot_rate END) ), NULL) AS `Q1`, 
		IF(QUARTER(td.dueDate) = 3, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.contracted_Rate END),  ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.amount_FC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.spot_rate END) ), NULL) AS `Q2`, 
		IF(QUARTER(td.dueDate) = 4, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.contracted_Rate END),  ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.amount_FC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.spot_rate END) ), NULL) AS `Q3`
		FROM transactiondetails AS td INNER JOIN forward_coverdetails AS frcw ON td.transaction_id = frcw.underlying_exposure_ref WHERE `td`.`exposureType` = 2 AND `td`.`currency` = ".$curid." GROUP BY YEAR(td.dueDate), QUARTER(td.dueDate);";	
          $query = $this->db->query($sql);
          if (is_object($query)) {
            $result = $query->getResultArray();
            return $result;
        }else{
            return [];
        }

	}
	
	public function helicopterviewexport($curid='')
    {
		$sql = "SELECT YEAR(td.dueDate) AS `Year`, 
		IF(QUARTER(td.dueDate) = 1, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.amount_FC END),',', AVG(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.spot_rate END)), NULL) AS `Q4`, 
		IF(QUARTER(td.dueDate) = 2, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.amount_FC END),',', AVG(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.spot_rate END)), NULL) AS `Q1`, 
		IF(QUARTER(td.dueDate) = 3, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.amount_FC END) ,',', AVG(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.spot_rate END)), NULL) AS `Q2`, 
		IF(QUARTER(td.dueDate) = 4, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.amount_FC END) ,',', AVG(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.spot_rate END)), NULL) AS `Q3`
		FROM transactiondetails AS td INNER JOIN forward_coverdetails AS frcw ON td.transaction_id = frcw.underlying_exposure_ref WHERE `td`.`exposureType` = 1 AND `td`.`currency` = ".$curid." GROUP BY YEAR(td.dueDate), QUARTER(td.dueDate);";	
			$query = $this->db->query($sql);
            if (is_object($query)) {
                $result = $query->getResultArray();
                return $result;
            }else{
                return [];
            }
	}
	
	public function helicopterviewbuyersCredit($curid='')
    {
		$sql = "SELECT YEAR(td.dueDate) AS `Year`, 
		IF(QUARTER(td.dueDate) = 1, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.amount_FC END),',', AVG(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.spot_rate END)), NULL) AS `Q4`, 
		IF(QUARTER(td.dueDate) = 2, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.amount_FC END),',', AVG(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.spot_rate END)), NULL) AS `Q1`, 
		IF(QUARTER(td.dueDate) = 3, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.amount_FC END) ,',', AVG(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.spot_rate END)), NULL) AS `Q2`, 
		IF(QUARTER(td.dueDate) = 4, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.amount_FC END) ,',', AVG(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.spot_rate END)), NULL) AS `Q3`
		FROM transactiondetails AS td INNER JOIN forward_coverdetails AS frcw ON td.transaction_id = frcw.underlying_exposure_ref WHERE `td`.`exposureType` = 3 AND `td`.`currency` = ".$curid." GROUP BY YEAR(td.dueDate), QUARTER(td.dueDate);";	
		$query = $this->db->query($sql);
        if (is_object($query)) {
            $result = $query->getResultArray();
            return $result;
        }else{
            return [];
        }

	}
	
	public function helicopterviewbuyersmisc($curid='')
    {
		$sql = "SELECT YEAR(td.dueDate) AS `Year`, 
		IF(QUARTER(td.dueDate) = 1, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.amount_FC END),',', AVG(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.spot_rate END) ), NULL) AS `Q4`, 
		IF(QUARTER(td.dueDate) = 2, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.targetRate END) , ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.amount_FC END),',', AVG(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.spot_rate END) ), NULL) AS `Q1`, 
		IF(QUARTER(td.dueDate) = 3, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.amount_FC END) ,',', AVG(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.spot_rate END)), NULL) AS `Q2`, 
		IF(QUARTER(td.dueDate) = 4, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.amount_FC END) ,',', AVG(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.spot_rate END)), NULL) AS `Q3`
		FROM transactiondetails AS td INNER JOIN forward_coverdetails AS frcw ON td.transaction_id = frcw.underlying_exposure_ref WHERE `td`.`exposureType` = 5 AND `td`.`currency` = ".$curid." GROUP BY YEAR(td.dueDate), QUARTER(td.dueDate);";	
		$query = $this->db->query($sql);
        if (is_object($query)) {
            $result = $query->getResultArray();
            return $result;
        }else{
            return [];
        }

	}
	
	public function helicoptertabscapitalpaymnts($curid='')
    {
		$sql = "SELECT YEAR(td.dueDate) AS `Year`, 
		IF(QUARTER(td.dueDate) = 1, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 1 THEN frcw.amount_FC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 1 THEN td.spot_rate END)  ), NULL) AS `Q4`, 
		IF(QUARTER(td.dueDate) = 2, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 2 THEN frcw.amount_FC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 2 THEN td.spot_rate END)  ), NULL) AS `Q1`, 
		IF(QUARTER(td.dueDate) = 3, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 3 THEN frcw.amount_FC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 3 THEN td.spot_rate END) ), NULL) AS `Q2`, 
		IF(QUARTER(td.dueDate) = 4, CONCAT(SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.amountinFC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.contracted_Rate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.targetRate END), ',', SUM(CASE WHEN QUARTER(td.dueDate) = 4 THEN frcw.amount_FC END), ',', AVG(CASE WHEN QUARTER(td.dueDate) = 4 THEN td.spot_rate END) ), NULL) AS `Q3`
		FROM transactiondetails AS td INNER JOIN forward_coverdetails AS frcw ON td.transaction_id = frcw.underlying_exposure_ref WHERE `td`.`exposureType` = 4 AND `td`.`currency` = ".$curid." GROUP BY YEAR(td.dueDate), QUARTER(td.dueDate);";	
		$query = $this->db->query($sql);
        if (is_object($query)) {
            $result = $query->getResultArray();
            return $result;
        }else{
            return [];
        }

	}


    public function totaloutwrds($curid='')
    {
        $sql = "SELECT YEAR(td.dueDate) AS `Year`,
        IF(MONTH(td.dueDate) = MONTH(CURRENT_DATE()), 
        SUM(CASE WHEN MONTH(td.dueDate) = MONTH(CURRENT_DATE()) AND td.exposureType = 1 THEN 1 ELSE 0 END),
        NULL
        ) AS `CurrentMonthCount`
        FROM transactiondetails AS td
        LEFT JOIN currency AS currencynew ON td.currency = currencynew.currency_id
        WHERE td.exposureType = 1 AND currencynew.Currency = '".$curid."'
        GROUP BY YEAR(td.dueDate), MONTH(td.dueDate)
        LIMIT 1;";	
        $query = $this->db->query($sql);
        if (is_object($query)) {
            $result = $query->getResultArray();
            return $result;
        }else{
            return [];
        }
    }
    

    public function totalinwrds($curid=''){
        $sql = "SELECT YEAR(td.dueDate) AS `Year`,
        IF(MONTH(td.dueDate) = MONTH(CURRENT_DATE()), 
        SUM(CASE WHEN MONTH(td.dueDate) = MONTH(CURRENT_DATE()) AND td.exposureType != 1 THEN 1 ELSE 0 END),
        NULL
        ) AS `CurrentMonthCount`
        FROM transactiondetails AS td
        LEFT JOIN currency AS currencynew ON td.currency = currencynew.currency_id
        WHERE td.exposureType != 1 AND currencynew.Currency = '".$curid."'
        GROUP BY YEAR(td.dueDate), MONTH(td.dueDate)
        LIMIT 1;";	
        $query = $this->db->query($sql);
        if (is_object($query)) {
            $result = $query->getResultArray();
            return $result;
        }else{
            return [];
        }
    }

    public function totalinwrdscurrentquarter($curid=''){
        $sql = "SELECT YEAR(td.dueDate) AS `Year`,
        IF(QUARTER(td.dueDate) = QUARTER(CURRENT_DATE()), 
        SUM(CASE WHEN QUARTER(td.dueDate) = QUARTER(CURRENT_DATE()) AND td.exposureType != 1 THEN 1 ELSE 0 END),
        NULL
        ) AS `CurrentQuarterCount`
        FROM transactiondetails AS td
        LEFT JOIN currency AS currencynew ON td.currency = currencynew.currency_id
        WHERE td.exposureType != 1 AND currencynew.Currency = '".$curid."'
        GROUP BY YEAR(td.dueDate), QUARTER(td.dueDate)
        LIMIT 1;";
        $query = $this->db->query($sql);
        if (is_object($query)) {
            $result = $query->getResultArray();
            return $result;
        }else{
            return [];
        }

    }

    public function totaloutwrdscurrentquarter($curid=''){
        $sql = "SELECT YEAR(td.dueDate) AS `Year`,
        IF(QUARTER(td.dueDate) = QUARTER(CURRENT_DATE()), 
        SUM(CASE WHEN QUARTER(td.dueDate) = QUARTER(CURRENT_DATE()) AND td.exposureType != 1 THEN 1 ELSE 0 END),
        NULL
        ) AS `CurrentQuarterCount`
        FROM transactiondetails AS td
        LEFT JOIN currency AS currencynew ON td.currency = currencynew.currency_id
        WHERE td.exposureType = 1 AND currencynew.Currency = '".$curid."'
        GROUP BY YEAR(td.dueDate), QUARTER(td.dueDate)
        LIMIT 1;";
        $query = $this->db->query($sql);
        if (is_object($query)) {
            $result = $query->getResultArray();
            return $result;
        }else{
            return [];
        }
    }

    public function currentportfoliovalueimp($curid='')
    {
        $sql = 'SELECT a.currency, a.transaction_id, a.targetRate, a.amountinFC, a.inr_target_value, a.dueDate,
        cr.Currency,
        op.open_amount, op.isSettled,
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
        LEFT JOIN open_details as op ON a.transaction_id  = op.transactionforeing_id
        LEFT  JOIN (
        SELECT  underlying_exposure_ref, SUM(forward_Amount*forward_Rate)  as Toatalallpayment, (spot_Amount*spotamount_Rate) as AvgspotamountRate
        FROM   paymentreceiptdetails
        GROUP  BY 1
        ) p ON a.transaction_id  = p.underlying_exposure_ref
        WHERE a.exposureType != 1 AND cr.Currency = "'.$curid.'"
        GROUP  BY a.transaction_id';
        $query = $this->db->query($sql);
        if (is_object($query)) {
            $result = $query->getResultArray();
            return $result;
        }else{
            return [];
        }
    }

    public function currentportfoliovalueexp($curid='')
    {
        $sql = 'SELECT a.currency, a.transaction_id, a.targetRate, a.amountinFC, a.inr_target_value, a.dueDate,
        cr.Currency,
        op.open_amount, op.isSettled,
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
        LEFT JOIN open_details as op ON a.transaction_id  = op.transactionforeing_id
        LEFT  JOIN (
        SELECT  underlying_exposure_ref, SUM(forward_Amount*forward_Rate)  as Toatalallpayment, (spot_Amount*spotamount_Rate) as AvgspotamountRate
        FROM   paymentreceiptdetails
        GROUP  BY 1
        ) p ON a.transaction_id  = p.underlying_exposure_ref
        WHERE a.exposureType = 1 AND cr.Currency = "'.$curid.'"
        GROUP  BY a.transaction_id';
        $query = $this->db->query($sql);
        if (is_object($query)) {
            $result = $query->getResultArray();
            return $result;
        }else{
            return [];
        }
    }

    public function currentquarterportfoliovalueexp($curid='')
{
    $sql = 'SELECT a.currency, a.transaction_id, a.targetRate, a.amountinFC, a.inr_target_value,
    cr.Currency,
    op.open_amount, op.isSettled,
    SUM(frcw.ToatalforwardAmount) as ToatalforwardAmount,
    SUM(frcw.Avgrate) as Avgrate,
    SUM(p.Toatalallpayment) as Toatalallpayment,
    SUM(p.AvgspotamountRate) as AvgspotamountRate
    FROM transactiondetails as a
    LEFT JOIN (
        SELECT underlying_exposure_ref, SUM(amount_FC) as ToatalforwardAmount, AVG(contracted_Rate) as Avgrate
        FROM forward_coverdetails
        GROUP BY 1
    ) frcw ON a.transaction_id = frcw.underlying_exposure_ref
    LEFT JOIN currency as cr ON a.currency = cr.currency_id
    LEFT JOIN open_details as op ON a.transaction_id = op.transactionforeing_id
    LEFT JOIN (
        SELECT underlying_exposure_ref, SUM(forward_Amount*forward_Rate) as Toatalallpayment, (spot_Amount*spotamount_Rate) as AvgspotamountRate
        FROM paymentreceiptdetails
        GROUP BY 1
    ) p ON a.transaction_id = p.underlying_exposure_ref
    WHERE a.exposureType = 1 AND cr.Currency = "'.$curid.'"
        AND QUARTER(a.dueDate) = QUARTER(CURDATE())
    GROUP BY a.transaction_id';
    
    $query = $this->db->query($sql);
    if (is_object($query)) {
        $result = $query->getResultArray();
        return $result;
    }else{
        return [];
    }
}

public function currentquarterportfoliovaluimp($curid='')
{
    $sql = 'SELECT a.currency, a.transaction_id, a.targetRate, a.amountinFC, a.inr_target_value,
    cr.Currency,
    op.open_amount, op.isSettled,
    SUM(frcw.ToatalforwardAmount) as ToatalforwardAmount,
    SUM(frcw.Avgrate) as Avgrate,
    SUM(p.Toatalallpayment) as Toatalallpayment,
    SUM(p.AvgspotamountRate) as AvgspotamountRate
    FROM transactiondetails as a
    LEFT JOIN (
        SELECT underlying_exposure_ref, SUM(amount_FC) as ToatalforwardAmount, AVG(contracted_Rate) as Avgrate
        FROM forward_coverdetails
        GROUP BY 1
    ) frcw ON a.transaction_id = frcw.underlying_exposure_ref
    LEFT JOIN currency as cr ON a.currency = cr.currency_id
    LEFT JOIN open_details as op ON a.transaction_id = op.transactionforeing_id
    LEFT JOIN (
        SELECT underlying_exposure_ref, SUM(forward_Amount*forward_Rate) as Toatalallpayment, (spot_Amount*spotamount_Rate) as AvgspotamountRate
        FROM paymentreceiptdetails
        GROUP BY 1
    ) p ON a.transaction_id = p.underlying_exposure_ref
    WHERE a.exposureType != 1 AND cr.Currency = "'.$curid.'"
        AND QUARTER(a.dueDate) = QUARTER(CURDATE())
    GROUP BY a.transaction_id';
    
    $query = $this->db->query($sql);
    $query = $this->db->query($sql);
    if (is_object($query)) {
        $result = $query->getResultArray();
        return $result;
    }else{
        return [];
    }
}

public function lastquarterportfoliovaluimp($curid='')
{
    $sql = 'SELECT a.currency, a.transaction_id, a.targetRate, a.amountinFC, a.inr_target_value,
    cr.Currency,
    op.open_amount, op.isSettled,
    SUM(frcw.ToatalforwardAmount) as ToatalforwardAmount,
    SUM(frcw.Avgrate) as Avgrate,
    SUM(p.Toatalallpayment) as Toatalallpayment,
    SUM(p.AvgspotamountRate) as AvgspotamountRate
    FROM transactiondetails as a
    LEFT JOIN (
        SELECT underlying_exposure_ref, SUM(amount_FC) as ToatalforwardAmount, AVG(contracted_Rate) as Avgrate
        FROM forward_coverdetails
        GROUP BY 1
    ) frcw ON a.transaction_id = frcw.underlying_exposure_ref
    LEFT JOIN currency as cr ON a.currency = cr.currency_id
    LEFT JOIN open_details as op ON a.transaction_id = op.transactionforeing_id
    LEFT JOIN (
        SELECT underlying_exposure_ref, SUM(forward_Amount*forward_Rate) as Toatalallpayment, (spot_Amount*spotamount_Rate) as AvgspotamountRate
        FROM paymentreceiptdetails
        GROUP BY 1
    ) p ON a.transaction_id = p.underlying_exposure_ref
    WHERE a.exposureType != 1 AND cr.Currency = "'.$curid.'"
        AND QUARTER(a.dueDate) = QUARTER(DATE_SUB(CURDATE(), INTERVAL 1 QUARTER))
    GROUP BY a.transaction_id';
    
    $query = $this->db->query($sql);
    if (is_object($query)) {
        $result = $query->getResultArray();
        return $result;
    }else{
        return [];
    }
}


public function lastquarterportfoliovaluexp($curid='')
{
    $sql = 'SELECT a.currency, a.transaction_id, a.targetRate, a.amountinFC, a.inr_target_value,
    cr.Currency,
    op.open_amount, op.isSettled,
    SUM(frcw.ToatalforwardAmount) as ToatalforwardAmount,
    SUM(frcw.Avgrate) as Avgrate,
    SUM(p.Toatalallpayment) as Toatalallpayment,
    SUM(p.AvgspotamountRate) as AvgspotamountRate
    FROM transactiondetails as a
    LEFT JOIN (
        SELECT underlying_exposure_ref, SUM(amount_FC) as ToatalforwardAmount, AVG(contracted_Rate) as Avgrate
        FROM forward_coverdetails
        GROUP BY 1
    ) frcw ON a.transaction_id = frcw.underlying_exposure_ref
    LEFT JOIN currency as cr ON a.currency = cr.currency_id
    LEFT JOIN open_details as op ON a.transaction_id = op.transactionforeing_id
    LEFT JOIN (
        SELECT underlying_exposure_ref, SUM(forward_Amount*forward_Rate) as Toatalallpayment, (spot_Amount*spotamount_Rate) as AvgspotamountRate
        FROM paymentreceiptdetails
        GROUP BY 1
    ) p ON a.transaction_id = p.underlying_exposure_ref
    WHERE a.exposureType = 1 AND cr.Currency = "'.$curid.'"
        AND QUARTER(a.dueDate) = QUARTER(DATE_SUB(CURDATE(), INTERVAL 1 QUARTER))
    GROUP BY a.transaction_id';

    // Details of Settled Invoices
    
    $query = $this->db->query($sql);
    if (is_object($query)) {
        $result = $query->getResultArray();
        return $result;
    }else{
        return [];
    }
}

public function quarterportfoliovalueexp($curid='')
{
    $sql = 'SELECT a.currency, a.transaction_id, a.targetRate, a.amountinFC, a.inr_target_value, a.dueDate,
    cr.Currency,
    op.open_amount, op.isSettled,
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
    LEFT JOIN open_details as op ON a.transaction_id  = op.transactionforeing_id
    LEFT  JOIN (
    SELECT  underlying_exposure_ref, SUM(forward_Amount*forward_Rate)  as Toatalallpayment, (spot_Amount*spotamount_Rate) as AvgspotamountRate
    FROM   paymentreceiptdetails
    GROUP  BY 1
    ) p ON a.transaction_id  = p.underlying_exposure_ref
    WHERE a.exposureType = 1 AND cr.Currency = "'.$curid.'"
     AND QUARTER(a.dueDate) = QUARTER(CURDATE())
    GROUP  BY a.transaction_id';
    $query = $this->db->query($sql);
    if (is_object($query)) {
        $result = $query->getResultArray();
        return $result;
    }else{
        return [];
    }
}

public function lastquarterportfoliovalueexp($curid='')
{
    $sql = 'SELECT a.currency, a.transaction_id, a.targetRate, a.amountinFC, a.inr_target_value, a.dueDate,
    cr.Currency,
    op.open_amount, op.isSettled,
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
    LEFT JOIN open_details as op ON a.transaction_id  = op.transactionforeing_id
    LEFT  JOIN (
    SELECT  underlying_exposure_ref, SUM(forward_Amount*forward_Rate)  as Toatalallpayment, (spot_Amount*spotamount_Rate) as AvgspotamountRate
    FROM   paymentreceiptdetails
    GROUP  BY 1
    ) p ON a.transaction_id  = p.underlying_exposure_ref
    WHERE a.exposureType = 1 AND cr.Currency = "'.$curid.'"
    AND QUARTER(a.dueDate) = QUARTER(DATE_SUB(CURDATE(), INTERVAL 1 QUARTER))
    GROUP  BY a.transaction_id';
    $query = $this->db->query($sql);
    if (is_object($query)) {
        $result = $query->getResultArray();
        return $result;
    }else{
        return [];
    }
}

public function quarterportfoliovalueimp($curid='')
{
    $sql = 'SELECT a.currency, a.transaction_id, a.targetRate, a.amountinFC, a.inr_target_value, a.dueDate,
    cr.Currency,
    op.open_amount, op.isSettled,
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
    LEFT JOIN open_details as op ON a.transaction_id  = op.transactionforeing_id
    LEFT  JOIN (
    SELECT  underlying_exposure_ref, SUM(forward_Amount*forward_Rate)  as Toatalallpayment, (spot_Amount*spotamount_Rate) as AvgspotamountRate
    FROM   paymentreceiptdetails
    GROUP  BY 1
    ) p ON a.transaction_id  = p.underlying_exposure_ref
    WHERE a.exposureType != 1 AND cr.Currency = "'.$curid.'"
     AND QUARTER(a.dueDate) = QUARTER(CURDATE())
    GROUP  BY a.transaction_id';
    $query = $this->db->query($sql);
    if (is_object($query)) {
        $result = $query->getResultArray();
        return $result;
    }else{
        return [];
    }
}


public function lastquarterportfoliovalueimp($curid='')
{
    $sql = 'SELECT a.currency, a.transaction_id, a.targetRate, a.amountinFC, a.inr_target_value, a.dueDate,
    cr.Currency,
    op.open_amount, op.isSettled,
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
    LEFT JOIN open_details as op ON a.transaction_id  = op.transactionforeing_id
    LEFT  JOIN (
    SELECT  underlying_exposure_ref, SUM(forward_Amount*forward_Rate)  as Toatalallpayment, (spot_Amount*spotamount_Rate) as AvgspotamountRate
    FROM   paymentreceiptdetails
    GROUP  BY 1
    ) p ON a.transaction_id  = p.underlying_exposure_ref
    WHERE a.exposureType != 1 AND cr.Currency = "'.$curid.'"
    AND QUARTER(a.dueDate) = QUARTER(DATE_SUB(CURDATE(), INTERVAL 1 QUARTER))
    GROUP  BY a.transaction_id';
    $query = $this->db->query($sql);
    if (is_object($query)) {
        $result = $query->getResultArray();
        return $result;
    }else{
        return [];
    }
}

}
