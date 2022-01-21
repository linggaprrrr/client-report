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
        $query = $this->db->query("SELECT company, cost FROM users JOIN investments ON users.id = investments.client_id WHERE client_id = '$id' ")->getRow();
        return $query;
    }

    public function getLastDateOfInvestment($id)
    {
        $query = $this->db->query("SELECT * FROM investments WHERE client_id = '$id' ORDER BY date DESC ")->getRow();
        return $query;
    }

    public function getAllInvestment()
    {
        $query = $this->db->query("SELECT investments.*, users.fullname, users.company, SUM(reports.cost) as cost_left FROM investments JOIN users on investments.client_id = users.id JOIN reports ON reports.investment_id = investments.id WHERE users.role = 'client' GROUP BY reports.investment_id ORDER by date DESC ");
        return $query;
    }


    public function getInvestcmentClient($id) {
        $query = $this->db->query("SELECT investments.id, investments.cost, investments.date FROM investments JOIN users ON users.id = investments.client_id WHERE client_id='$id' AND status = 'assign' ");
        return $query;
    }

    public function getPreviousCost($id) {
        $query = $this->db->query("SELECT cost_left FROM box_sum JOIN assign_report_box ON box_sum.box_name = assign_report_box.box_name WHERE box_sum.client_id = '$id' ORDER BY box_sum.id DESC LIMIT 1")->getRow();
        return $query;

    }

}
