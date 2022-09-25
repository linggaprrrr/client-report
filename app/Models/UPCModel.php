<?php

namespace App\Models;

use CodeIgniter\Model;

class UPCModel extends Model
{
    protected $table = 'upcs';
    protected $allowedFields = ['upc', 'asin', 'item_description', 'retail_value', 'condition', 'size', 'vendor_name'];
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
        $query = $this->db->table('assign_report_box')
            ->select('assign_report_box.*, users.fullname')
            ->join('users', 'assign_report_box.user_id = users.id')
            ->orderBy('date', 'DESC')
            ->get();
        return $query;
    }

}