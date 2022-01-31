<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $allowedFields = ['fullname', 'company', 'email', 'address', 'username', 'password', 'photo', 'role'];
    protected $db = "";

    public function getAllUser()
    {
        $this->db = \Config\Database::connect();
        $query = $this->db->query("SELECT * FROM users WHERE role <> 'superadmin' ORDER BY fullname ASC ");
        return $query;
    }
}
