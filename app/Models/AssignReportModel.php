<?php

namespace App\Models;

use CodeIgniter\Model;

class AssignReportModel extends Model
{

    protected $table = 'assign_report_details';
    protected $allowedFields = ['box_name', 'box_value', 'order_date', 'client_id', 'total', 'report_id'];
    protected $db = "";

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getLastId()
    {
        $query = $this->db->query("SELECT * FROM assign_reports ORDER by id DESC LIMIT 1")->getRow();
        if (!is_null($query)) {
            return $query->id;
        } else {
            return 1;
        }
    }

    public function getAllAssignReport()
    {
        $query = $this->db->query("SELECT assign_report_details.* FROM assign_reports JOIN assign_report_details ON assign_reports.id = assign_report_details.report_id WHERE assign_reports.status = 'incomplete' ");
        return $query;
    }

    public function getAllClient()
    {
        $query = $this->db->query("SELECT investments.cost, users.id, users.fullname, users.company  FROM users JOIN investments ON investments.client_id = users.id WHERE investments.status = 'assign' GROUP BY client_id ");
        return $query;
    }
}
