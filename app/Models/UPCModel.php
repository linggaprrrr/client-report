<?php

namespace App\Models;

use CodeIgniter\Model;

class UPCModel extends Model
{
    protected $table = 'upc';
    protected $allowedFields = ['upc', 'asin', 'item_description', 'retail_value', 'vendor_name'];
    protected $db = "";
    
    public function __construct() {
        $this->db = \Config\Database::connect();
    }   

    public function insertBoxItem($arr) {
        $this->db->table('assign_report_details')
            ->insert($arr);
    }

    public function insertBox($arr) {
        $this->db->table('assign_report_box')
            ->insert($arr);
    }

    public function historyBox() {
        $query = $this->db->table('assign_report_box as b')
            ->select('b.*, us.fullname, total_item, unknown_upc')
            ->join('(SELECT box_name, COUNT(assign_report_details.sku) as total_item FROM assign_report_details WHERE item_description <> "ITEM NOT FOUND" GROUP BY box_name) as t', 't.box_name = b.box_name', 'left')
            ->join('(SELECT box_name, COUNT(assign_report_details.sku) as unknown_upc FROM assign_report_details WHERE item_description = "ITEM NOT FOUND" GROUP BY box_name) as u', 'u.box_name = b.box_name', 'left')            
            ->join('users as us', 'b.user_id = us.id')
            ->groupBy('b.id')
            ->orderBy('b.date', 'DESC')
            ->get();
        return $query;
    }

    public function findBox($id) {
        $query = $this->db->table('assign_report_box')
            ->select('assign_report_box.*, sku, item_description, cond, SUM(qty) as qty, retail, SUM(original) as original, SUM(cost) cost, vendor')
            ->join('assign_report_details', 'assign_report_details.box_name = assign_report_box.box_name')            
            ->where('assign_report_box.id', $id)
            ->groupBy('sku')
            ->get();
        return $query;
    }

    public function getUnkownUPC() {
        $query = $this->db->table('assign_report_details')
            ->select('sku, assign_report_box.category')
            ->join('assign_report_box', 'assign_report_box.box_name = assign_report_details.box_name')
            ->where('item_description', 'ITEM NOT FOUND')
            ->get();
        return $query;
    }

    public function updateUPCDesc($upc, $desc, $retail, $cost, $vendor) {
        $this->db->table('assign_report_details')
            ->set('item_description', $desc)
            ->set('cond', 'NEW')
            ->set('qty', '1')
            ->set('retail', $retail) 
            ->set('original', $retail)
            ->set('cost', $cost)
            ->set('vendor', $vendor)
            ->where('sku', $upc)
            ->update();            
    }
    

}