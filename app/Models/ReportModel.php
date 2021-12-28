<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{
    protected $table = 'reports';
    protected $allowedFields = ['sku', 'item_description', 'cond', 'qty', 'retail_value', 'original_value', 'cost', 'vendor', 'client_id', 'investment_id'];
    protected $db = "";

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function totalUnit($id = null)
    {
        if ($id == null) {
            $query = $this->db->query("SELECT SUM(qty) as total_unit FROM reports")->getRow();
        } else {
            $query = $this->db->query("SELECT SUM(qty) as total_unit FROM reports WHERE investment_id = '$id'")->getRow();
        }
        return $query;
    }

    public function totalRetail($id = null)
    {
        if ($id == null) {
            $query = $this->db->query("SELECT SUM(original_value) as total_retail FROM reports")->getRow();
        } else {
            $query = $this->db->query("SELECT SUM(original_value) as total_retail FROM reports WHERE investment_id ='$id' ")->getRow();
        }
        return $query;
    }

    public function totalCostLeft($id = null)
    {
        if ($id == null) {
            $totalCost = $this->db->query("SELECT SUM(cost) as total_cost FROM reports")->getRow();
            $totalInvest = $this->db->query("SELECT SUM(cost) as total_invest FROM investments")->getRow();
        } else {
            $totalCost = $this->db->query("SELECT SUM(cost) as total_cost FROM reports WHERE investment_id = '$id'")->getRow();
            $totalInvest = $this->db->query("SELECT SUM(cost) as total_invest FROM investments WHERE id = '$id' ")->getRow();
        }
        $totalCostLeft = $totalInvest->total_invest - $totalCost->total_cost;
        return $totalCostLeft;
    }

    public function totalFulfilled($id = null)
    {
        if ($id == null) {
            $query =  $this->db->query("SELECT SUM(cost) as total_fulfilled FROM reports")->getRow();
        } else {
            $query =  $this->db->query("SELECT SUM(cost) as total_fulfilled FROM reports WHERE investment_id='$id' ")->getRow();
        }
        return $query;
    }

    public function getAllReports()
    {
        $query = $this->db->query("SELECT a.client_id, fullname, company, SUM(a.qty) as total_unit, SUM(original_value) as total_retail, SUM(a.bal) as client_cost, SUM(a.total_fulfilled) as total_fulfilled, (SUM(a.bal)-SUM(a.total_fulfilled)) as cost_left, (SUM(a.bal)-SUM(a.total_fulfilled)) as total_client_cost, (SUM(a.bal)/SUM(a.qty)) as avg_client_cost, (SUM(original_value)/SUM(qty)) as avg_unit_retail FROM (SELECT (SUM(investments.cost)/COUNT(*)) as bal, investments.client_id, SUM(qty) as qty, SUM(original_value) as original_value, SUM(reports.cost) as total_fulfilled FROM `investments` LEFT JOIN reports ON investments.id=reports.investment_id GROUP BY investments.id) as a  JOIN users ON users.id = a.client_id GROUP BY client_id");
        return $query;
    }

    public function getAllReportClient($id)
    {
        $query = $this->db->query("SELECT * from reports WHERE investment_id = '$id' ");
        return $query;
    }

    // client activity
    public function totalClientUploaded()
    {
        $query = $this->db->query("SELECT COUNT(DISTINCT client_id) as total FROM log_files")->getRow();
        return $query;
    }

    public function totalReport()
    {
        $query = $this->db->query("SELECT COUNT(*) as total FROM log_files")->getRow();
        return $query;
    }

    public function getAllFiles()
    {
        $query = $this->db->query("SELECT investment_id, log_files.id as log_id, fullname, company, file, date from users join log_files on users.id=log_files.client_id where role <> 'superadmin' ");
        return $query;
    }

    public function getAllClient()
    {
        $query = $this->db->query("SELECT * FROM users WHERE role='client' ORDER BY fullname ASC");
        return $query;
    }

    public function deleteReport($id)
    {
        $query = $this->db->query("DELETE FROM reports WHERE investment_id = $id");
        $query = $this->db->query("DELETE FROM investments WHERE id = $id");
        $query = $this->db->query("DELETE FROM categories WHERE investment_id = $id");
        $query = $this->db->query("DELETE FROM log_files WHERE investment_id = $id");
    }

    public function getVendorName($id)
    {
        $query = $this->db->query("SELECT SUM(qty) as qty, vendor FROM reports WHERE investment_id = '$id'  GROUP BY vendor ORDER BY qty");
        return $query;
    }
}
