<?php

namespace App\Database\Seeds;

use App\Models\UserModel;
use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();
        $data = [
            'fullname' => 'Administratror 2',
            'company' => 'Smart Wholesale LLC',
            'email' => 'admin2g@buysmartwholesale.com',
            'username' => 'admin2',
            'password' => password_hash('superadmin', PASSWORD_BCRYPT),
            'role' => 'superadmin'
        ];
        $userModel->insert($data);
    }
}
