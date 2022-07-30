<?php

namespace App\Controllers;

use App\Models\UserModel;

class Logs extends BaseController
{
    protected $userModel = "";
    protected $newsModel = "";
    protected $db = "";

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->db = \Config\Database::connect();
    }
    

    public function index()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return redirect()->to(base_url('/login'));
        }
        $user = $this->userModel->find($userId);
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();
        $data = [
            'tittle' => "Logs | Report Management System",
            'menu' => "Log",
            'user' => $user,
            'companySetting' => $companysetting
        ];

        return view('administrator/logs', $data);
    }
}
