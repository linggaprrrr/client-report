<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $allowedFields = ['category_name', 'investment_id'];
    protected $db = "";

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getCategory($id)
    {
        $query = $this->db->query("SELECT * FROM categories WHERE investment_id = '$id'")->getRow();
        return $query;
    }
}
