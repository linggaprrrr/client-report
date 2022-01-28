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
        $query = $this->db->query("SELECT assign_report_box.*, box_sum.order_date, box_sum.investment_id, users.id as userid, users.fullname, users.company, t.investdate, t.current_cost, box_sum.cost_left FROM assign_report_box LEFT JOIN box_sum ON box_sum.client_id = assign_report_box.client_id LEFT JOIN users ON users.id = box_sum.client_id LEFT JOIN (SELECT investments.id,  investments.date as investdate, investments.cost-SUM(reports.cost) as current_cost FROM investments JOIN reports ON investments.id=reports.investment_id GROUP BY investments.id) as t ON t.id = box_sum.investment_id");
        return $query;
    }



    public function getAllAssignReportProcess()
    {
        $query = $this->db->query("SELECT assign_report_box.id as box_id, assign_report_box.status, assign_report_box.confirmed, assign_report_box.box_value, assign_report_box.fba_number, assign_report_box.shipment_number, assign_report_box.box_note, box_sum.id, box_sum.box_name, box_sum.order_date, box_sum.investment_id, users.id as userid, users.fullname, users.company, t.investdate, t.current_cost, box_sum.cost_left FROM assign_report_box LEFT JOIN box_sum ON box_sum.client_id = assign_report_box.client_id LEFT JOIN users ON users.id = box_sum.client_id LEFT JOIN (SELECT investments.id,  investments.date as investdate, investments.cost-SUM(reports.cost) as current_cost FROM investments JOIN reports ON investments.id=reports.investment_id GROUP BY investments.id) as t ON t.id = box_sum.investment_id WHERE confirmed='1' AND assign_report_box.status='waiting' GROUP BY box_sum.id ORDER BY box_sum.id DESC");
        return $query;
    }

    public function getAllClient()
    {
        $query = $this->db->query("SELECT investments.cost, users.id, users.fullname, users.company FROM users JOIN investments ON investments.client_id = users.id WHERE investments.status = 'assign' GROUP BY client_id ");
        return $query;
    }

    public function checkBoxClient($box_name)
    {
        $query = $this->db->query("SELECT box_name FROM box_sum WHERE box_name='$box_name' ")->getRow();
        return $query;
    }

    public function checkBoxDiffClient($box_name, $clientId)
    {
        $query = $this->db->query("SELECT box_name FROM box_sum WHERE box_name='$box_name' AND client_id='$clientId' ")->getRow();
        return $query;
    }

    public function getBoxSummary($box_name)
    {
        $query = $this->db->query("SELECT assign_report_details.id, assign_report_box.box_note, assign_report_box.fba_number, assign_report_box.shipment_number, assign_report_box.status, sku, item_description, cond, qty, retail, original, cost, vendor, item_note, item_status FROM box_sum RIGHT JOIN assign_report_details ON assign_report_details.box_name = box_sum.box_name JOIN assign_report_box ON assign_report_details.box_name = assign_report_box.box_name WHERE assign_report_box.box_name ='$box_name' ");
        return $query;
    }

    public function getAllAssignReportCompleted()
    {
        $query = $this->db->query("SELECT assign_report_box.status, assign_report_box.confirmed, assign_report_box.box_value, assign_report_box.fba_number, assign_report_box.shipment_number, assign_report_box.box_note, ROUND(SUM(assign_report_details.cost), 2) as new_box_value, box_sum.id, box_sum.box_name, box_sum.order_date, box_sum.investment_id, users.id as userid, users.fullname, users.company, t.investdate, t.current_cost, box_sum.cost_left FROM assign_report_box LEFT JOIN box_sum ON box_sum.client_id = assign_report_box.client_id LEFT JOIN users ON users.id = box_sum.client_id LEFT JOIN (SELECT investments.id,  investments.date as investdate, investments.cost-SUM(reports.cost) as current_cost FROM investments JOIN reports ON investments.id=reports.investment_id GROUP BY investments.id) as t ON t.id = box_sum.investment_id JOIN assign_report_details ON assign_report_details.box_name=box_sum.box_name WHERE confirmed='1' AND assign_report_box.status <> 'waiting' AND assign_report_details.item_status=1 GROUP BY box_sum.id ORDER BY box_sum.id DESC ");
        return $query;
    }
}
