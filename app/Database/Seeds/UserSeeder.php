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
            'fullname' => 'Administratror',
            'company' => 'Smart Wholesale LLC',
            'email' => 'training@buysmartwholesale.com',
            'username' => 'admin',
            'password' => password_hash('training', PASSWORD_BCRYPT),
            'role' => 'superadmin'
        ];
        $userModel->insert($data);
    }
}
