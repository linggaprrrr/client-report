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
            'fullname' => 'test',
            'company' => 'Smart Wholesale LLC',
            'email' => 'test@buysmartwholesale.com',
            'username' => 'test',
            'password' => password_hash('test', PASSWORD_BCRYPT),
            'role' => 'client'
        ];
        $userModel->insert($data);
    }
}
