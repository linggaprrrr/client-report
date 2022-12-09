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
            'fullname' => 'Elite App Master',
            'company' => 'Elite Automation',
            'email' => 'eliteapp@buysmartwholesale.com',
            'username' => 'master',
            'password' => password_hash('eliteapp123', PASSWORD_BCRYPT),
            'role' => 'master'
        ];
        $userModel->insert($data);
    }
}
