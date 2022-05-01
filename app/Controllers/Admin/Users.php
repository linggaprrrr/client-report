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
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();
        $data = [
            'tittle' => 'User Managment | Report Management System',
            'menu'  => 'User Management',
            'user'  => $user,
            'users' => $getAllUsers,
            'companySetting' => $companysetting
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
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();
        $data = [
            'tittle' => "Account Setting | Report Management System",
            'menu' => $user['fullname'] . "'s Setting",
            'user' => $user,
            'profile' => $profile,
            'companySetting' => $companysetting
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
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();
        $data = [
            'tittle' => "Account Setting | Report Management System",
            'menu' => $user['fullname'] . "'s Setting",
            'user' => $user,
            'companySetting' => $companysetting
        ];

        return view('administrator/account_setting', $data);
    }

    public function companySetting() {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return redirect()->to(base_url('/login'));
        }
        $company = $this->db->query("SELECT * FROM company")->getRowArray();
        // dd($company);
        $user = $this->userModel->find($userId);
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();
        $data = [
            'tittle' => "Company Setting | Report Management System",
            'menu' => "Company Setting",
            'user' => $user,
            'company' => $company,
            'companySetting' => $companysetting
        ];

        return view('administrator/company_setting', $data);
    }

    public function updateCompanySetting() {
        $post = $this->request->getVar();
        $name = $post['company'];
        $address = $post['address'];
        $email = $post['email'];
        $phone = $post['phone'];
        $photo = $this->request->getFile('logo');
        $fileName = "";

        if (!empty($photo->getTempName())) {
            $fileName = time() . $photo->getName();
            $photo->move('assets/images', $fileName);
        }
        
        if (empty($fileName)) {
           $this->db->query("UPDATE company SET name=".$this->db->escape($name).", address=".$this->db->escape($address).", email='$email', phone='$phone' ");
        } else {
            $this->db->query("UPDATE company SET name=".$this->db->escape($name).", address=".$this->db->escape($address).", email='$email', phone='$phone', logo='$fileName' ");
        }
        return redirect()->back()->with('success', 'Company Successfully Updated');
    }

    public function resetPassword()
    {
        $post = $this->request->getVar();
        $cid = $post['id'];
        $password = password_hash($post['new_password'], PASSWORD_BCRYPT);
        $this->db->query("UPDATE users SET password = $password WHERE id='$cid'");
        return redirect()->back()->with('success', 'User Successfully Updated!');
    }
}
