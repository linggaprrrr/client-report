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

    public function getCategory($id = null)
    {
        $query = $this->db->query("SELECT * FROM categories WHERE investment_id = '$id'")->getRow();
        return $query;
    }

    public function getBrands()
    {
        $query = $this->db->query("SELECT * FROM brands ORDER BY brand_name ASC");
        return $query;
    }

    public function selectedBrand($userid)
    {
        $query = $this->db->query("SELECT brands.id FROM users JOIN brands WHERE users.id = '$userid' AND FIND_IN_SET(brands.id, brand_approval)");
        return $query;
    }

    public function selectedClient($brandid)
    {
        $query = $this->db->query("SELECT users.fullname, users.company, users.id FROM users JOIN brands WHERE brands.id = '$brandid' AND FIND_IN_SET('$brandid', brand_approval)");
        return $query;
    }
}
