<?php

namespace App\Models;

use CodeIgniter\Model;

class InvestmentModel extends Model
{

    protected $table = 'investments';
    protected $allowedFields = ['cost', 'date', 'client_id', 'status'];
    protected $db = "";

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function totalClientInvestment($id = null)
    {
        if ($id != null) {
            $query = $this->db->query("SELECT SUM(cost) as total_client_cost FROM (SELECT fullname, cost FROM `investments` JOIN users ON users.id = investments.client_id WHERE investments.id = '$id' ORDER BY fullname ASC ) as t  ")->getRow();
        } else {
            $query = $this->db->query("SELECT SUM(cost) as total_client_cost FROM (SELECT fullname, cost FROM `investments` JOIN users ON users.id = investments.client_id ORDER BY fullname ASC ) as t  ")->getRow();
        }
        return $query;
    }

    public function getLastId()
    {
        $query = $this->db->query("SELECT id FROM investments ORDER BY id DESC LIMIT 1");
        if ($query->getNumRows() > 0) {
            $query = $query->getRow();
            return $query->id;
        } else {
            return 0;
        }
    }

    public function isExist($id = null)
    {
        $query = $this->db->query("SELECT * FROM investments WHERE id = $id");
        if ($query->getNumRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function investmentDate($id)
    {
        $query = $this->db->query("SELECT * FROM investments WHERE client_id ='$id' ORDER BY date ASC");
        return $query;
    }

    public function getInvestmentId($id)
    {
        $query = $this->db->query("SELECT * FROM investments WHERE client_id = '$id' ORDER BY date DESC")->getRow();
        if (!is_null($query)) {
            return $query->id;
        } else {
            return $query;
        }
    }

    public function getCompany($id)
    {
        $query = $this->db->query("SELECT company, brand_name FROM users JOIN investments ON users.id = investments.client_id JOIN brands WHERE client_id = '$id' AND FIND_IN_SET(brands.id, brand_approval) GROUP BY brand_name ");
        return $query;
    }

    public function getLastDateOfInvestment($id)
    {
        $query = $this->db->query("SELECT * FROM investments WHERE client_id = '$id' ORDER BY date DESC ")->getRow();
        return $query;
    }

    public function getAllInvestment($status = null)
    {
        if ($status == null || $status == 'incomplete') {
            $query = $this->db->query("SELECT investments.*, users.fullname, users.company, IFNULL(SUM(reports.cost), 0) as total_cost, (investments.cost - IFNULL(SUM(reports.cost), 0)) as cost_left FROM investments JOIN users on investments.client_id = users.id LEFt JOIN reports ON reports.investment_id = investments.id WHERE users.role = 'client' AND investments.status = 'incomplete' GROUP BY investments.id ORDER BY status DESC, cost_left ASC");
        } elseif ($status == 'assign') {
            $query = $this->db->query("SELECT investments.*, users.fullname, users.company, IFNULL(SUM(reports.cost), 0) as total_cost, (investments.cost - IFNULL(SUM(reports.cost), 0)) as cost_left FROM investments JOIN users on investments.client_id = users.id LEFt JOIN reports ON reports.investment_id = investments.id WHERE users.role = 'client' AND investments.status = 'assign' GROUP BY investments.id  ORDER BY `cost_left`  DESC;");
        } else {
            $query = $this->db->query("SELECT investments.*, users.fullname, users.company, SUM(reports.cost) as total_cost, (investments.cost - SUM(reports.cost)) as cost_left FROM investments JOIN users on investments.client_id = users.id JOIN reports ON reports.investment_id = investments.id WHERE users.role = 'client' AND status = 'complete' GROUP BY reports.investment_id ORDER BY status DESC, cost_left ASC");
        }
        return $query;
    }


    public function getInvestcmentClient($id)
    {
        $query = $this->db->query("SELECT investments.id, (investments.cost-IFNULL(SUM(reports.cost), 0)) as cost, investments.date FROM investments JOIN users ON users.id = investments.client_id LEFT JOIN reports ON reports.investment_id = investments.id  WHERE investments.client_id='$id' AND status = 'assign' GROUP BY investments.id; ");
        return $query;
    }

    public function getPreviousCost($id, $investmentId)
    {
        $query = $this->db->query("SELECT cost_left FROM box_sum JOIN assign_report_box ON box_sum.box_name = assign_report_box.box_name WHERE box_sum.client_id = '$id' and investment_id='$investmentId' ORDER BY box_sum.id DESC LIMIT 1")->getRow();
        return $query;
    }

    public function completedInvestments()
    {
        $query = $this->db->query("SELECT investments.*, users.fullname, users.company, SUM(reports.cost) as total_cost, (investments.cost - SUM(reports.cost)) as cost_left FROM investments JOIN users on investments.client_id = users.id JOIN reports ON reports.investment_id = investments.id WHERE users.role = 'client' AND status = 'complete' GROUP BY reports.investment_id ORDER BY date DESC");
        return $query;
    }

    public function monthDiff($userId)
    {
        $query = $this->db->query("SELECT TIMESTAMPDIFF(MONTH, date, NOW()) as monthdiff FROM investments WHERE client_id=9 ORDER BY date DESC LIMIT 1")->getRow();
        return $query;
    }

    public function getTopInvestment()
    {
        $query = $this->db->query("SELECT fullname, amount, currency FROM (SELECT fullname, SUM(cost) as amount, CONCAT('$',FORMAT(SUM(cost),0,'en_US')) as currency FROM investments JOIN users ON users.id = investments.client_id GROUP BY client_id ORDER BY SUM(cost) DESC LIMIT 10) as tp ORDER BY amount ASC");
        return $query;
    }

    public function continuityInvestment()
    {
        $query = $this->db->query("SELECT fullname, total FROM (SELECT fullname, COUNT(investments.id) as total FROM investments JOIN users ON users.id = investments.client_id GROUP BY client_id ORDER BY COUNT(investments.id)  DESC LIMIT 10) as con ORDER BY total ASC");
        return $query;
    }

    public function getTopInvestmentAssign()
    {
        $query = $this->db->query("SELECT fullname, amount, cost_left FROM (SELECT investments.cost as amount, users.fullname, (investments.cost - SUM(reports.cost)) as cost_left FROM investments JOIN users on investments.client_id = users.id JOIN reports ON reports.investment_id = investments.id WHERE users.role = 'client' AND status = 'assign' GROUP BY reports.investment_id ORDER BY amount DESC LIMIT 10) as assign ORDER BY amount ASC");
        return $query;
    }
}
