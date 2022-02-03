<?php

namespace App\Models;

use CodeIgniter\Model;

class AssignReportModel extends Model
{

    protected $table = 'assign_report_details';
    protected $allowedFields = ['sku', 'item_description', 'category', 'cond', 'qty', 'retail', 'original', 'cost', 'vendor', 'box_name'];
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
        $query = $this->db->query("SELECT assign_report_box.id, assign_report_details.category, assign_report_box.box_name, assign_report_box.confirmed, assign_report_box.box_value, assign_report_box.description, assign_report_box.status, assign_report_box.va_id, box_sum.client_id, box_sum.order_date, box_sum.investment_id, box_sum.cost_left, users.id as userid, users.fullname, users.company, users.brand_approval, investments.date as investdate, investments.cost-SUM(reports.cost) as current_cost FROM assign_report_box LEFT JOIN box_sum ON assign_report_box.box_name = box_sum.box_name LEFT JOIN users ON users.id = box_sum.client_id LEFT JOIN investments ON investments.id = box_sum.investment_id LEFT JOIN reports ON reports.investment_id = box_sum.investment_id LEFT JOIN assign_report_details ON assign_report_details.box_name = assign_report_box.box_name GROUP BY assign_report_box.box_name ORDER BY assign_report_box.id ASC");
        return $query;
    }



    public function getAllAssignReportProcess($id = null, $role = null)
    {
        if ($role == 'superadmin' || $role == 'admin') {
            $query = $this->db->query("SELECT assign_report_box.id, assign_report_box.va_id, assign_report_box.box_name, assign_report_details.category, assign_report_box.confirmed, assign_report_box.box_value, assign_report_box.description, assign_report_box.status, assign_report_box.fba_number, assign_report_box.shipment_number, assign_report_box.box_note, box_sum.client_id, box_sum.order_date, box_sum.investment_id, box_sum.cost_left, users.id as userid, users.fullname, users.company, users.brand_approval, investments.date as investdate, investments.cost-SUM(reports.cost) as current_cost FROM assign_report_box LEFT JOIN box_sum ON assign_report_box.box_name = box_sum.box_name LEFT JOIN users ON users.id = box_sum.client_id LEFT JOIN investments ON investments.id = box_sum.investment_id LEFT JOIN reports ON reports.investment_id = box_sum.investment_id LEFT JOIN assign_report_details ON assign_report_details.box_name=assign_report_box.box_name WHERE confirmed='1' AND assign_report_box.status='waiting' GROUP BY assign_report_box.box_name ORDER BY box_sum.order_date DESC;");
        } else {
            $query = $this->db->query("SELECT assign_report_box.id, assign_report_box.va_id, assign_report_box.box_name, assign_report_details.category, assign_report_box.confirmed, assign_report_box.box_value, assign_report_box.description, assign_report_box.status, assign_report_box.fba_number, assign_report_box.shipment_number, assign_report_box.box_note, box_sum.client_id, box_sum.order_date, box_sum.investment_id, box_sum.cost_left, users.id as userid, users.fullname, users.company, users.brand_approval, investments.date as investdate, investments.cost-SUM(reports.cost) as current_cost FROM assign_report_box LEFT JOIN box_sum ON assign_report_box.box_name = box_sum.box_name LEFT JOIN users ON users.id = box_sum.client_id LEFT JOIN investments ON investments.id = box_sum.investment_id LEFT JOIN reports ON reports.investment_id = box_sum.investment_id LEFT JOIN assign_report_details ON assign_report_details.box_name=assign_report_box.box_name WHERE confirmed='1' AND assign_report_box.status='waiting' AND assign_report_box.va_id='$id' GROUP BY assign_report_box.box_name ORDER BY box_sum.order_date DESC");
        }
        return $query;
    }

    public function getAllClient()
    {
        $query = $this->db->query("SELECT investments.cost, users.id, users.fullname, users.company FROM `investments` JOIN users ON users.id = investments.client_id JOIN reports ON reports.investment_id = investments.id WHERE investments.status='assign' GROUP BY investments.id ");
        return $query;
    }

    public function getAllVA()
    {
        $query = $this->db->query("SELECT * FROM users WHERE role='va' ");
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
        $query = $this->db->query("SELECT assign_report_details.id, assign_report_details.item_check, assign_report_box.box_note, assign_report_box.description, assign_report_box.fba_number, assign_report_box.shipment_number, assign_report_box.status, sku, item_description, cond, qty, retail, original, cost, vendor, item_note, item_status FROM box_sum RIGHT JOIN assign_report_details ON assign_report_details.box_name = box_sum.box_name JOIN assign_report_box ON assign_report_details.box_name = assign_report_box.box_name WHERE assign_report_box.box_name ='$box_name' ");
        return $query;
    }

    public function getAllAssignReportCompleted()
    {
        $query = $this->db->query("SELECT  assign_report_box.*, assign_report_details.category, ROUND(SUM(assign_report_details.cost), 2) as new_box_value, users.id as userid, users.fullname, users.brand_approval, users.company, investments.date as investdate, box_sum.order_date FROM assign_report_details JOIN assign_report_box ON assign_report_details.box_name = assign_report_box.box_name JOIN users ON users.id = assign_report_box.client_id JOIN box_sum ON box_sum.box_name = assign_report_box.box_name JOIN investments ON investments.id = box_sum.investment_id WHERE item_status=1 AND assign_report_box.status<>'waiting' GROUP BY assign_report_box.box_name ORDER BY order_date DESC");
        return $query;
    }

    public function getCategoryPercentage($currentCost, $investment)
    {
        $query = $this->db->query("SELECT assign_report_box.box_name, fullname, '$currentCost' as cost_left, SUM(assign_report_details.cost) as fulfilled, category, count(category) as total_qty, (SUM(assign_report_details.cost)/'$currentCost')*100 as percentage FROM assign_report_details JOIN assign_report_box ON assign_report_box.box_name = assign_report_details.box_name JOIN box_sum ON box_sum.box_name = assign_report_box.box_name JOIN users ON users.id = box_sum.client_id JOIN investments ON investments.id = box_sum.investment_id WHERE investments.id = '$investment' GROUP BY assign_report_details.category");
        return $query;
    }
}
