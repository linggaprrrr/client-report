<?php

namespace App\Models;

use CodeIgniter\Model;

class AssignReportModel extends Model
{

    protected $table = 'assign_report_details';
    protected $allowedFields = ['sku', 'item_description', 'cond', 'qty', 'retail', 'original', 'cost', 'vendor', 'box_name'];
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
        $query = $this->db->query("SELECT assign_report_box.*, box_sum.order_date, box_sum.investment_id, users.id as userid, users.fullname, users.company, investments.date as investdate, investments.cost as current_cost, box_sum.cost_left FROM assign_report_box LEFT JOIN box_sum ON assign_report_box.box_name = box_sum.box_name LEFT JOIN users ON box_sum.client_id = users.id LEFT JOIN investments ON box_sum.investment_id = investments.id");
        return $query;
    }

    public function getAllAssignReportProcess() {
        $query = $this->db->query("SELECT assign_report_box.*, box_sum.order_date, box_sum.investment_id, users.id as userid, users.fullname, users.company, investments.date as investdate, investments.cost as current_cost, box_sum.cost_left FROM assign_report_box JOIN box_sum ON assign_report_box.box_name = box_sum.box_name JOIN users ON box_sum.client_id = users.id JOIN investments ON box_sum.investment_id = investments.id");
        return $query;
    }

    public function getAllClient()
    {
        $query = $this->db->query("SELECT investments.cost, users.id, users.fullname, users.company FROM users JOIN investments ON investments.client_id = users.id WHERE investments.status = 'assign' GROUP BY client_id ");
        return $query;
    }

    public function checkBoxClient($box_name) {
        $query = $this->db->query("SELECT box_name FROM box_sum WHERE box_name='$box_name' ")->getRow();
        return $query;
    }

    public function getBoxSummary($box_name) {
        $query = $this->db->query("SELECT * FROM box_sum JOIN assign_report_details ON assign_report_details.box_name = box_sum.box_name JOIN assign_report_box ON assign_report_details.box_name = assign_report_box.box_name WHERE box_sum.box_name ='$box_name' ");
        return $query;
    }

}
