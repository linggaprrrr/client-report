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
        $query = $this->db->query("SELECT assign_report_box.id, cat.category, assign_report_box.box_name,  assign_report_box.confirmed, assign_report_box.box_value, assign_report_box.description, assign_report_box.status, assign_report_box.va_id, box_sum.client_id, box_sum.order_date, box_sum.investment_id, box_sum.cost_left, users.id as userid, users.fullname, users.company, users.brand_approval, investments.date as investdate, investments.cost-SUM(reports.cost) as current_cost FROM assign_report_box LEFT JOIN box_sum ON box_sum.box_name = assign_report_box.box_name LEFT JOIN investments ON investments.id = box_sum.investment_id LEFT JOIN reports ON reports.investment_id = box_sum.investment_id LEFT JOIN (SELECT assign_report_box.box_name, category  FROM assign_report_box LEFT JOIN assign_report_details ON assign_report_details.box_name = assign_report_box.box_name GROUP BY assign_report_box.box_name) as cat ON cat.box_name=assign_report_box.box_name LEFT JOIN users ON users.id = box_sum.client_id GROUP BY assign_report_box.box_name  ORDER BY assign_report_box.id ASC");
        return $query;
    }



    public function getAllAssignReportProcess($id = null, $role = null)
    {
        if ($role == 'superadmin' || $role == 'admin') {
            $query = $this->db->query("SELECT assign_report_box.id, cat.category, assign_report_box.box_name, assign_report_box.confirmed, assign_report_box.box_value, assign_report_box.description, assign_report_box.fba_number, assign_report_box.shipment_number, assign_report_box.status, assign_report_box.va_id, box_sum.client_id, box_sum.order_date, box_sum.investment_id, box_sum.cost_left, users.id as userid, users.fullname, users.company, users.brand_approval, investments.date as investdate, investments.cost-SUM(reports.cost) as current_cost FROM assign_report_box LEFT JOIN box_sum ON box_sum.box_name = assign_report_box.box_name LEFT JOIN investments ON investments.id = box_sum.investment_id LEFT JOIN reports ON reports.investment_id = box_sum.investment_id LEFT JOIN (SELECT assign_report_box.box_name, category  FROM assign_report_box LEFT JOIN assign_report_details ON assign_report_details.box_name = assign_report_box.box_name GROUP BY assign_report_box.box_name) as cat ON cat.box_name=assign_report_box.box_name LEFT JOIN users ON users.id = box_sum.client_id WHERE confirmed='1' AND assign_report_box.status='waiting' GROUP BY assign_report_box.box_name ORDER BY box_sum.order_date DESC");
        } else {
            $query = $this->db->query("SELECT assign_report_box.id, cat.category, assign_report_box.box_name, assign_report_box.confirmed, assign_report_box.box_value, assign_report_box.description, assign_report_box.fba_number, assign_report_box.shipment_number, assign_report_box.status, assign_report_box.va_id, box_sum.client_id, box_sum.order_date, box_sum.investment_id, box_sum.cost_left, users.id as userid, users.fullname, users.company, users.brand_approval, investments.date as investdate, investments.cost-SUM(reports.cost) as current_cost FROM assign_report_box LEFT JOIN box_sum ON box_sum.box_name = assign_report_box.box_name LEFT JOIN investments ON investments.id = box_sum.investment_id LEFT JOIN reports ON reports.investment_id = box_sum.investment_id LEFT JOIN (SELECT assign_report_box.box_name, category  FROM assign_report_box LEFT JOIN assign_report_details ON assign_report_details.box_name = assign_report_box.box_name GROUP BY assign_report_box.box_name) as cat ON cat.box_name=assign_report_box.box_name LEFT JOIN users ON users.id = box_sum.client_id WHERE confirmed='1' AND assign_report_box.status='waiting' AND assign_report_box.va_id='$id' GROUP BY assign_report_box.box_name ORDER BY box_sum.order_date DESC");
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
        $query = $this->db->query("SELECT  assign_report_box.*, assign_report_details.category, ROUND(SUM(assign_report_details.cost), 2) as new_box_value, box_sum.cost_left, users.id as userid, users.fullname, users.brand_approval, users.company, investments.date as investdate, box_sum.order_date, v.fullname as va FROM assign_report_details JOIN assign_report_box ON assign_report_details.box_name = assign_report_box.box_name JOIN users ON users.id = assign_report_box.client_id JOIN box_sum ON box_sum.box_name = assign_report_box.box_name JOIN investments ON investments.id = box_sum.investment_id JOIN (SELECT id, fullname FROM users WHERE role='va') as v ON v.id = assign_report_box.va_id WHERE item_status=1 AND assign_report_box.status<>'waiting' GROUP BY assign_report_box.box_name ORDER BY assign_report_box.status DESC");
        return $query;
    }

    public function getCategoryPercentage($currentCost, $investment)
    {
        $query = $this->db->query("SELECT assign_report_box.box_name, fullname, '$currentCost' as cost_left, SUM(assign_report_details.cost) as fulfilled, category, count(category) as total_qty, (SUM(assign_report_details.cost)/'$currentCost')*100 as percentage FROM assign_report_details JOIN assign_report_box ON assign_report_box.box_name = assign_report_details.box_name JOIN box_sum ON box_sum.box_name = assign_report_box.box_name JOIN users ON users.id = box_sum.client_id JOIN investments ON investments.id = box_sum.investment_id WHERE investments.id = '$investment' GROUP BY assign_report_details.category");
        return $query;
    }

    public function getTotalBox()
    {
        $query = $this->db->query("SELECT weeks.week, assign_reports.id, assign_reports.date, COUNT(assign_report_box.box_name) as total_box, SUM(box_value) as client_cost  FROM assign_report_box JOIN assign_reports ON assign_reports.id = assign_report_box.report_id LEFT JOIN weeks ON assign_reports.date >= weeks.date1 AND assign_reports.date <= weeks.date2 GROUP BY weeks.week ORDER BY assign_reports.id DESC LIMIT 1")->getRow();
        // $query = $this->db->query("SELECT weeks.week, assign_reports.id, assign_reports.date, COUNT(assign_report_box.box_name) as total_box, SUM(box_value) as client_cost  FROM assign_report_box JOIN assign_reports ON assign_reports.id = assign_report_box.report_id LEFT JOIN weeks ON assign_reports.date >= weeks.date1 AND assign_reports.date <= weeks.date2 WHERE YEARWEEK(assign_reports.date, 1) = YEARWEEK(CURDATE(), 1) GROUP BY weeks.week ORDER BY assign_reports.id DESC LIMIT 1")->getRow();
        return $query;
    }

    public function getBoxStatus($stat = 'waiting')
    {
        $query = $this->db->query("SELECT COUNT(assign_report_box.status) as status FROM assign_report_box JOIN assign_reports ON assign_reports.id = assign_report_box.report_id LEFT JOIN weeks ON assign_reports.date >= weeks.date1 AND assign_reports.date <= weeks.date2 WHERE assign_report_box.status = '$stat' GROUP BY weeks.week  ORDER BY assign_reports.id DESC LIMIT 1 ")->getRow();
        return $query;
    }

    public function getTotalUnit()
    {
        // $query = $this->db->query("SELECT SUM(assign_report_details.qty) as unit FROM assign_report_details JOIN assign_report_box ON assign_report_details.box_name=assign_report_box.box_name JOIN assign_reports ON assign_reports.id = assign_report_box.report_id LEFT JOIN weeks ON assign_reports.date >= weeks.date1 AND assign_reports.date <= weeks.date2 GROUP BY weeks.week ORDER BY assign_reports.id DESC LIMIT 1")->getRow();
        $query = $this->db->query("SELECT SUM(assign_report_details.qty) as unit FROM assign_report_details JOIN assign_report_box ON assign_report_details.box_name=assign_report_box.box_name JOIN assign_reports ON assign_reports.id = assign_report_box.report_id LEFT JOIN weeks ON assign_reports.date >= weeks.date1 AND assign_reports.date <= weeks.date2 GROUP BY weeks.week ORDER BY assign_reports.id DESC LIMIT 1")->getRow();
        return $query;
    }

    public function getTotalItemByCat()
    {
        $query = $this->db->query("SELECT COUNT(category) as value, UPPER(category) as name FROM `assign_report_details` GROUP BY category");
        return $query;
    }

    public function getWeeks()
    {
        $query = $this->db->query("SELECT * FROM weeks");
        return $query->getResultArray();
    }

    public function getCostBox()
    {
        $query = $this->db->query("SELECT order_date, cost, status FROM (SELECT order_date, SUM(box_value) as cost, 'shipped' as status FROM assign_report_box JOIN box_sum ON assign_report_box.box_name = box_sum.box_name WHERE assign_report_box.status = 'approved' AND order_date > DATE_ADD(NOW(), INTERVAL -14 DAY) GROUP BY box_sum.order_date) as s UNION SELECT order_date, cost, status FROM (SELECT order_date, SUM(box_value) as cost, 'remanifested' as status FROM assign_report_box JOIN box_sum ON assign_report_box.box_name = box_sum.box_name WHERE assign_report_box.status = 'remanifested' AND order_date > DATE_ADD(NOW(), INTERVAL -14 DAY) GROUP BY box_sum.order_date) as m UNION SELECT order_date, cost, status FROM (SELECT order_date, SUM(box_value) as cost, 'reassigned' as status FROM assign_report_box JOIN box_sum ON assign_report_box.box_name = box_sum.box_name WHERE assign_report_box.status = 'reassigned' AND order_date > DATE_ADD(NOW(), INTERVAL -14 DAY) GROUP BY box_sum.order_date) as r ORDER BY order_date DESC");
        return $query;
    }
}
