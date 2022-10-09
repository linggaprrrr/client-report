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

    public function getAllAssignReport($date1 = null, $date2 = null)
    {
        if (is_null($date1)) {
            $query = $this->db->query("SELECT b.*, u.fullname, u.company, s.client_id, s.investment_id, i.date as investdate, i.cost-IFNULL(r.cost, 0) as current_cost, s.client_id as userid, s.order_date, s.cost_left FROM assign_report_box as b LEFT JOIN box_sum as s ON s.box_name = b.box_name LEFT JOIN users as u ON u.id = s.client_id LEFT JOIN investments as i ON i.id = s.investment_id LEFT JOIN (SELECT reports.investment_id, SUM(reports.cost) as cost FROM reports GROUP BY investment_id) as r ON r.investment_id = s.investment_id WHERE b.report_id IS NOT NULL AND confirmed = 0 GROUP BY b.id ORDER BY b.date DESC");
        } else {
            $query = $this->db->query("SELECT b.*, u.fullname, u.company, s.client_id, s.investment_id, i.date as investdate, i.cost-IFNULL(r.cost, 0) as current_cost, s.client_id as userid, s.order_date, s.cost_left FROM assign_report_box as b LEFT JOIN box_sum as s ON s.box_name = b.box_name LEFT JOIN users as u ON u.id = s.client_id LEFT JOIN investments as i ON i.id = s.investment_id LEFT JOIN (SELECT reports.investment_id, SUM(reports.cost) as cost FROM reports GROUP BY investment_id) as r ON r.investment_id = s.investment_id WHERE b.report_id IS NOT NULL AND confirmed = 0 AND b.date BETWEEN '$date1' AND '$date2' GROUP BY b.id ORDER BY b.date DESC");
        }
        return $query;
    }

    public function getAllAssignReportProcess($id = null, $role = null)
    {
        if ($role == 'superadmin' || $role == 'admin') {
            $query = $this->db->query("SELECT b.*, u.fullname, u.company, s.client_id, s.investment_id, i.date as investdate, i.cost-IFNULL(r.cost, 0) as current_cost, s.order_date, s.cost_left FROM assign_report_box as b LEFT JOIN box_sum as s ON s.box_name = b.box_name LEFT JOIN users as u ON u.id = s.client_id LEFT JOIN investments as i ON i.id = s.investment_id LEFT JOIN (SELECT reports.investment_id, SUM(reports.cost) as cost FROM reports GROUP BY investment_id) as r ON r.investment_id = s.investment_id WHERE b.report_id IS NOT NULL AND (b.confirmed = 1 AND b.status = 'waiting') GROUP BY b.id ORDER BY s.order_date DESC");
        } elseif ($role == 'va') {
            $query = $this->db->query("SELECT b.*, u.fullname, u.company, s.client_id, s.investment_id, i.date as investdate, i.cost-IFNULL(r.cost, 0) as current_cost, s.order_date, s.cost_left FROM assign_report_box as b LEFT JOIN box_sum as s ON s.box_name = b.box_name LEFT JOIN users as u ON u.id = s.client_id LEFT JOIN investments as i ON i.id = s.investment_id LEFT JOIN (SELECT reports.investment_id, SUM(reports.cost) as cost FROM reports GROUP BY investment_id) as r ON r.investment_id = s.investment_id WHERE b.report_id IS NOT NULL AND (b.confirmed = 1 AND b.status = 'waiting') AND b.va_id = '$id' GROUP BY b.id ORDER BY s.order_date DESC;");
        }
        return $query;
    }

    public function getAllClient()
    {
        $query = $this->db->query("SELECT investments.cost, users.id, users.fullname, users.company FROM `investments` JOIN users ON users.id = investments.client_id LEFT JOIN reports ON reports.investment_id = investments.id WHERE investments.status='assign' GROUP BY users.id ");
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
        $query = $this->db->query("SELECT assign_report_details.id, assign_report_details.fnsku, assign_report_details.item_check, assign_report_box.box_note, assign_report_box.category, assign_report_box.description, assign_report_box.fba_number, assign_report_box.shipment_number, assign_report_box.status, sku, item_description, cond, qty, retail, original, cost, vendor, item_note, item_status FROM box_sum RIGHT JOIN assign_report_details ON assign_report_details.box_name = box_sum.box_name JOIN assign_report_box ON assign_report_details.box_name = assign_report_box.box_name WHERE assign_report_box.box_name ='$box_name' ");
        return $query;
    }

    public function getAllAssignReportCompleted($vaID = null)
    {
        if (is_null($vaID)) {
            $query = $this->db->query("SELECT  assign_report_box.*, ROUND(SUM(assign_report_details.cost), 2) as new_box_value, box_sum.cost_left, users.id as userid, users.fullname, users.brand_approval, users.company, investments.date as investdate, box_sum.order_date, v.fullname as va FROM assign_report_details JOIN assign_report_box ON assign_report_details.box_name = assign_report_box.box_name JOIN users ON users.id = assign_report_box.client_id JOIN box_sum ON box_sum.box_name = assign_report_box.box_name JOIN investments ON investments.id = box_sum.investment_id JOIN (SELECT id, fullname FROM users WHERE role='va') as v ON v.id = assign_report_box.va_id WHERE item_status=1 AND assign_report_box.status<>'waiting' GROUP BY assign_report_box.box_name ORDER BY assign_report_box.status DESC");
        } else {
            $query = $this->db->query("SELECT  assign_report_box.*, ROUND(SUM(assign_report_details.cost), 2) as new_box_value, box_sum.cost_left, users.id as userid, users.fullname, users.brand_approval, users.company, investments.date as investdate, box_sum.order_date, v.fullname as va FROM assign_report_details JOIN assign_report_box ON assign_report_details.box_name = assign_report_box.box_name JOIN users ON users.id = assign_report_box.client_id JOIN box_sum ON box_sum.box_name = assign_report_box.box_name JOIN investments ON investments.id = box_sum.investment_id JOIN (SELECT id, fullname FROM users WHERE role='va') as v ON v.id = assign_report_box.va_id WHERE item_status=1 AND assign_report_box.status<>'waiting' AND va_id='$vaID' GROUP BY assign_report_box.box_name ORDER BY assign_report_box.status DESC");
        }
        return $query;
    }

    public function getCategoryPercentage($id)
    {
        $query = $this->db->query("SELECT SUM(reports.qty) as qty, assign_report_box.category FROM reports JOIN assign_report_details ON assign_report_details.sku = reports.sku JOIN assign_report_box ON assign_report_box.box_name = assign_report_details.box_name WHERE reports.client_id = '$id' GROUP BY assign_report_box.box_name;");
        return $query;
    }
    
    public function getCategoryPercentage2($currentCost, $investment)
    {
        $query = $this->db->query("SELECT assign_report_box.box_name, fullname, '$currentCost' as cost_left, SUM(assign_report_details.cost) as fulfilled, category, count(category) as total_qty, (SUM(assign_report_details.cost)/'$currentCost')*100 as percentage FROM assign_report_details JOIN assign_report_box ON assign_report_box.box_name = assign_report_details.box_name JOIN box_sum ON box_sum.box_name = assign_report_box.box_name JOIN users ON users.id = box_sum.client_id JOIN investments ON investments.id = box_sum.investment_id WHERE investments.id = '$investment' GROUP BY assign_report_details.category");
        return $query;
    }

    public function getTotalBox($week_start, $week_end)
    {
        $query = $this->db->query("SELECT (SELECT COUNT(assign_report_box.box_name) FROM assign_report_box WHERE date BETWEEN '$week_start' AND '$week_end') as total_box, SUM(assign_report_details.retail) as retail, SUM(assign_report_details.cost) as client_cost FROM assign_report_box JOIN assign_report_details ON assign_report_details.box_name = assign_report_box.box_name WHERE assign_report_box.date BETWEEN '$week_start' AND '$week_end' ")->getRow();
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
