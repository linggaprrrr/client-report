<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel = "";

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $getAllUsers = $this->userModel->getAllUser();
        $data = [
            'tittle' => 'User Managmeent | Report Management System',
            'menu'  => 'User Mangement',
            'user'  => $user,
            'users' => $getAllUsers,
        ];
        return view('administrator/user_management', $data);
    }

    public function addClient()
    {
        $post = $this->request->getVar();
        // $photo = $this->request->getFile('photo');

        $this->userModel->save(array(
            "fullname" => $post['fullname'],
            "company" => $post['company'],
            "address" => $post['address'],
            "username" => $post['username'],
            "role" => $post['role'],
            "password" => password_hash($post['new_password'], PASSWORD_BCRYPT),
            'role' => 'client'
        ));
        return redirect()->back()->with('success', 'User Successfully Created!');
    }

    public function editClient($id)
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return redirect()->to(base_url('/login'));
        }
        $user = $this->userModel->find($userId);
        $profile = $this->userModel->find($id);
        $data = [
            'tittle' => "Account Setting | Report Management System",
            'menu' => $user['fullname'] . "'s Setting",
            'user' => $user,
            'profile' => $profile
        ];
        return view('administrator/client_setting', $data);
    }

    public function updateClient()
    {
        $post = $this->request->getVar();
        $user = $this->userModel->find($post['id']);
        if (!empty($post['new_password'])) {
            if (password_verify($post['old_password'], $user['password'])) {
                $this->userModel->save(array(
                    "id" => $post['id'],
                    "fullname" => $post['fullname'],
                    "company" => $post['company'],
                    "address" => $post['address'],
                    "password" => password_hash($post['new_password'], PASSWORD_BCRYPT),

                ));
            } else {
                return redirect()->back()->with('failed', 'User Successfully Updated!');
            }
        } else {
            $this->userModel->save(array(
                "id" => $post['id'],
                "fullname" => $post['fullname'],
                "company" => $post['company'],
                "address" => $post['address'],
            ));
        }
        return redirect()->back()->with('success', 'User Successfully Updated!');
    }

    public function deleteClient($id)
    {
        $this->userModel->delete($id);
        return redirect()->back()->with('delete', 'User Successfully Deleted!');
    }

    public function accountSetting()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return redirect()->to(base_url('/login'));
        }
        $user = $this->userModel->find($userId);
        $data = [
            'tittle' => "Account Setting | Report Management System",
            'menu' => $user['fullname'] . "'s Setting",
            'user' => $user
        ];

        return view('administrator/account_setting', $data);
    }
}
