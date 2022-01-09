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
            $query = $this->db->query("SELECT SUM(cost) as total_client_cost FROM investments WHERE id = '$id' ")->getRow();
        } else {
            $query = $this->db->query("SELECT SUM(cost) as total_client_cost FROM investments")->getRow();
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
        $query = $this->db->query("SELECT investments.*, users.fullname, users.company FROM investments JOIN users on investments.client_id = users.id WHERE users.role = 'client' ORDER by date DESC ");
        return $query;
    }
}
