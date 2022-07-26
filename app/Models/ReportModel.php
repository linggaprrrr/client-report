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
            $query = $this->db->query("SELECT SUM(original_value) as total_retail FROM reports JOIN users ON users.id = reports.client_id")->getRow();
        } else {
            $query = $this->db->query("SELECT SUM(original_value) as total_retail FROM reports WHERE investment_id ='$id' ")->getRow();
        }
        return $query;
    }

    public function totalCostLeft($id = null)
    {
        if ($id == null) {
            $totalCost = $this->db->query("SELECT SUM(cost) as total_cost FROM reports JOIN users ON users.id = reports.client_id")->getRow();
            $totalInvest = $this->db->query("SELECT SUM(cost) as total_invest FROM (SELECT fullname, cost FROM `investments` JOIN users ON users.id = investments.client_id ORDER BY fullname ASC ) as t")->getRow();
        } else {
            $totalCost = $this->db->query("SELECT SUM(cost) as total_cost FROM reports WHERE investment_id = '$id'")->getRow();
            $totalInvest = $this->db->query("SELECT SUM(cost) as total_invest FROM (SELECT fullname, cost FROM `investments` JOIN users ON users.id = investments.client_id WHERE investments.id = '$id' ORDER BY fullname ASC ) as t  ")->getRow();
        }
        $totalCostLeft = $totalInvest->total_invest - $totalCost->total_cost;
        return $totalCostLeft;
    }

    public function totalFulfilled($id = null)
    {
        if ($id == null) {
            $query =  $this->db->query("SELECT SUM(cost) as total_fulfilled FROM reports JOIN users ON users.id = reports.client_id")->getRow();
        } else {
            $query =  $this->db->query("SELECT SUM(cost) as total_fulfilled FROM reports WHERE investment_id='$id' ")->getRow();
        }
        return $query;
    }


    public function getAllReports()
    {
        $query = $this->db->query("SELECT investments.client_id, users.fullname, investments.date as investment_date, investments.status, users.company, investments.cost as client_cost, total_retail, total_unit, total_fulfilled, investments.cost - IFNULL(cost_, 0) as cost_left FROM investments LEFT JOIN (SELECT SUM(reports.qty) as total_unit, SUM(reports.original_value) as total_retail, SUM(reports.cost) as total_fulfilled, SUM(IFNULL(reports.cost, 0)) as cost_, investment_id FROM reports GROUP BY reports.investment_id ) as rep  ON investments.id = rep.investment_id JOIN users ON users.id = investments.client_id ORDER BY investments.date DESC");
        return $query;
    }

    public function getAllReportClient($id = null)
    {
        $query = $this->db->query("SELECT reports.*, log_files.link from reports JOIN log_files ON reports.client_id = log_files.client_id WHERE reports.investment_id = '$id' GROUP BY reports.id ORDER by ID ASC");
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
        $query = $this->db->query("SELECT log_files.id as log_id, investments.date as invest_date, investments.cost as amount, fullname, company, file, log_files.date, link, investments.id as investment_id  FROM `log_files` RIGHT JOIN investments ON investments.id = log_files.investment_id JOIN users ON users.id = log_files.client_id ORDER BY investments.date DESC");
        return $query;
    }

    public function getAllClient()
    {
        $query = $this->db->query("SELECT * FROM users WHERE role='client' ORDER BY fullname ASC");
        return $query;
    }

    public function deleteReport($id)
    {
        $this->db->query("DELETE FROM reports WHERE investment_id='$id' ");
        $this->db->query("DELETE FROM investments WHERE id='$id' ");
        $this->db->query("DELETE FROM log_files WHERE investment_id='$id' ");
    }

    public function getVendorName($id)
    {
        $query = $this->db->query("SELECT SUM(qty) as qty, vendor FROM reports WHERE investment_id = '$id' AND vendor IS NOT NULL GROUP BY vendor ORDER BY qty DESC LIMIT 15");
        return $query;
    }

    public function getPLReport()
    {
        $query = $this->db->query("SELECT log_files.client_id, link, log_files.id as log_id, fullname, company, file, date from users join log_files on users.id=log_files.client_id where role <> 'superadmin' AND investment_id IS NULL AND link NOT LIKE 'BULK' ORDER BY date DESC");
        return $query;
    }

    public function savePLReport($chartTitle, $monthData, $type, $client)
    {
        $query = $this->db->query("INSERT INTO `chart_pl`(`chart`, `last_year`, `jan`, `feb`, `mar`, `apr`, `may`, `jun`, `jul`, `aug`, `sep`, `oct`, `nov`, `dec`, `type`, `client_id`) VALUES('$chartTitle', '$monthData[0]', '$monthData[1]', '$monthData[2]', '$monthData[3]', '$monthData[4]', '$monthData[5]', '$monthData[6]', '$monthData[7]', '$monthData[8]', '$monthData[9]', '$monthData[10]', '$monthData[11]', '$monthData[12]', '$type', '$client' ) ");
        return $query;
    }

    public function savePLReportExclude($chartTitle, $monthData, $type, $client)
    {
        $query = $this->db->query("INSERT INTO `chart_pl`(`chart`, `jan`, `feb`, `mar`, `apr`, `may`, `jun`, `jul`, `aug`, `sep`, `oct`, `nov`, `dec`, `type`, `client_id`) VALUES('$chartTitle', '$monthData[0]', '$monthData[1]', '$monthData[2]', '$monthData[3]', '$monthData[4]', '$monthData[5]', '$monthData[6]', '$monthData[7]', '$monthData[8]', '$monthData[9]', '$monthData[10]', '$monthData[11]', '$type', '$client' ) ");
        return $query;
    }

    public function showPLReport($id)
    {
        $query = $this->db->query("SELECT * FROM chart_pl WHERE client_id = $id");
        return $query;
    }

    public function getBulkUploaded() {
        $query = $this->db->query("SELECT * FROM log_files WHERE link LIKE 'BULK' ");
        return $query;
    }

    public function downloadPLReport($id)
    {
        $query = $this->db->query("SELECT * FROM log_files WHERE client_id = '$id' AND investment_id IS NULL ORDER BY id DESC LIMIT 1")->getRow();
        return $query;
    }

    public function deletePLReport($id)
    {
        $this->db->query("DELETE FROM chart_pl  WHERE client_id = '$id'");
        $this->db->query("DELETE FROM log_files WHERE client_id = '$id' AND (investment_id='' OR investment_id IS NULL) ");
    }

    public function getFileManifest($id)
    {
        $query = $this->db->query("SELECT file FROM log_files WHERE investment_id='$id' ")->getRow();
        return $query;
    }

    public function getPLReportClient($id)
    {
        $query = $this->db->query("SELECT file FROM log_files WHERE client_id = '$id' AND investment_id IS NULL")->getRow();
        return $query;
    }

    public function getClientCostLeft($id)
    {
        $totalCost = $this->db->query("SELECT SUM(reports.cost) as total_cost FROM reports JOIN investments ON investments.id = reports.investment_id WHERE investments.client_id = '$id' GROUP BY investments.id ORDER BY investments.id DESC LIMIT 1 ")->getRow();
        $totalInvest = $this->db->query("SELECT SUM(cost) as total_invest FROM (SELECT investments.id, fullname, cost FROM `investments` JOIN users ON users.id = investments.client_id WHERE investments.client_id = '$id' GROUP BY investments.id ORDER BY investments.id DESC LIMIT 1 ) as t ")->getRow();
        $totalCostLeft = $totalInvest->total_invest - $totalCost->total_cost;
        return $totalCostLeft;
    }

    public function getPLClient($id)
    {
        $query = $this->db->query("SELECT users.id as user_id, users.fullname, users.company, log_files.id as log_id, file, link FROM log_files JOIN users ON users.id = log_files.client_id WHERE log_files.id ='$id'")->getRow();
        return $query;
    }

    public function finSummary($type = null)
    {
        if ($type == 'spend') {
            $query = $this->db->query("SELECT date_format(date, '%b %Y') as month, sum(investments.cost) as spend FROM investments JOIN users ON users.id = investments.client_id group by year(date),month(date) ORDER BY date DESC LIMIT 12");
        } else {
            $query = $this->db->query("SELECT date_format(date, '%b %Y') as month, SUM(reports.cost) as fulfill FROM reports JOIN investments ON investments.id = reports.investment_id GROUP BY year(date),month(date) ORDER BY date DESC LIMIT 15");
        }
        return $query;
    }
}
