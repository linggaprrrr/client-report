<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{
    protected $table = 'news';
    protected $allowedFields = ['type', 'title', 'message', 'date', 'under_comp'];
    protected $db = "";

    public function getLastNews($underComp)
    {
        $this->db = \Config\Database::connect();
        $query = $this->db->query("SELECT * FROM news WHERE under_comp='$underComp' ORDER BY id DESC LIMIT 1")->getRow();
        return $query;
    }

    public function getNews($underComp = null)
    {
        $this->db = \Config\Database::connect();
        if ($underComp == null) {
            $query = $this->db->query("SELECT * FROM news ORDER BY id DESC");
        } else {
            $query = $this->db->query("SELECT * FROM news WHERE under_comp='$underComp' ORDER BY id DESC");
        }
        return $query;
    }
}
