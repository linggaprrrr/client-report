<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel = "";
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        echo "berhasil";
    }

    public function login()
    {
        $userId = session()->get('user_id');

        if (is_null($userId)) {

            return view('login');
        } else {
            if (session()->get('role') == 'superadmin') {
                return redirect()->route('admin/dashboard');
            }
            return redirect()->route('dashboard');
        }
    }

    public function loginProses()
    {
        $post = $this->request->getVar();
        $user = $this->userModel->getWhere(['username' => $post['username']])->getRow();
        $currentPage = $post['current'];
        if ($user) {
            if (password_verify($post['password'], $user->password)) {
                $params = [
                    'user_id' => $user->id,
                    'role' => $user->role
                ];
                session()->set($params);
                if ($user->role == "superadmin") {
                    if ($currentPage == base_url()) {
                        return redirect()->to(base_url('admin/dashboard'))->with('message', 'Login Successful!');
                    } else {
                        return redirect()->to($currentPage)->with('message', 'Login Successful!');
                    }
                } elseif ($user->role == "va" || $user->role == "admin") {
                    return redirect()->to(base_url('va/assignment-report'))->with('message', 'Login Successful!');
                } else {
                    if ($currentPage == base_url() || $currentPage == base_url() . '/login') {
                        return redirect()->to(base_url('get-started'))->with('message', 'Login Successful!');
                    } else {
                        return redirect()->to($currentPage)->with('message', 'Login Successful!');
                    }
                }
            } else {
                return redirect()->back()->with('error', 'Incorrect Password!');
            }
        } else {

            return redirect()->back()->with('error', 'Username Not Found!');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/login'));
    }
}
