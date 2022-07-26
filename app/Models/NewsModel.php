<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{
    protected $table = 'news';
    protected $allowedFields = ['type', 'title', 'message', 'date'];
    protected $db = "";

    public function getLastNews()
    {
        $this->db = \Config\Database::connect();
        $query = $this->db->query("SELECT * FROM news ORDER BY id DESC LIMIT 1")->getRow();
        return $query;
    }

    public function getNews()
    {
        $this->db = \Config\Database::connect();
        $query = $this->db->query("SELECT * FROM news ORDER BY id DESC");
        return $query;
    }
}
