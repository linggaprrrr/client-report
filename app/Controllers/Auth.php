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
        $company = $this->db->query("SELECT logo FROM company LIMIT 1")->getRow();
       
        if (is_null($userId)) {
            $data = array(
                'logo' => $company->logo
            );
            return view('login', $data);
        } else {
            if (session()->get('role') == 'superadmin') {
                return redirect()->route('admin/dashboard');
            } else if (session()->get('role') == 'master') {
                return redirect()->route('master/manifest');
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
            if ($user->under_comp == '2') {
                return redirect()->back()->with('error', 'Username Not Found!');
            }
            if (password_verify($post['password'], $user->password)) {
                $params = [
                    'user_id' => $user->id,
                    'role' => $user->role
                ];
                session()->set($params);
                            
                if ($user->role == "master" && $user->under_comp == '1') {
                    return redirect()->to(base_url('master/manifest'))->with('message', 'Login Successful!');
                }
              
                if ($user->role == "superadmin") {
                    if ($currentPage == base_url()) {
                        return redirect()->to(base_url('admin/dashboard'))->with('message', 'Login Successful!');
                    } else {
                        return redirect()->to($currentPage)->with('message', 'Login Successful!');
                    }
                } elseif ($user->role == "va") {
                    return redirect()->to(base_url('/va/assignment-process'))->with('message', 'Login Successful!');
                }  elseif ($user->role == "admin") {
                    return redirect()->to(base_url('/warehouse/scan-log'))->with('message', 'Login Successful!');
                } else {
                    $ip = getenv('HTTP_CLIENT_IP')?: getenv('HTTP_X_FORWARDED_FOR')?: getenv('HTTP_X_FORWARDED')?: getenv('HTTP_FORWARDED_FOR')?: getenv('HTTP_FORWARDED')?: getenv('REMOTE_ADDR');
                    $page = 'get-started';
                    $this->userModel->logActivity($user->id, $page, $ip);
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

    public function findUsername() {
        $post = $this->request->getVar();
        $user = $this->userModel->getWhere(['username' => $post['username'], 'under_comp !=' => '2'])->getRow();
        if ($user) {
            $data = [
                'username' => $post['username']
            ];
            return view('reset-password', $data);
        } else {
            return redirect()->back()->with('error', 'Username Not Found!');
        }
        
    }

    public function forgotPassword() {
        return view('forgot-password');
    }

    public function forgotPasswordProcess() {
        $password = $this->request->getVar('password');
        $re_password = $this->request->getVar('confirm-password');
        $username = $this->request->getVar('username');
        if ($password == $re_password) {
            $newPassword = password_hash($password, PASSWORD_BCRYPT);
            $this->db->query("UPDATE users SET password='$newPassword' WHERE username = '$username' ");
            return redirect()->route('login');
        } else {
            return redirect()->back()->with('error', 'Password doesn\'t Match!');
        }
        
    }

}
