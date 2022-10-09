<?php 

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class PromoCode extends BaseController
{
    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function index() {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $promo = $this->db->query("SELECT * FROM promocode ORDER BY date DESC");
        $data = [
            'tittle' => 'Promo Code | Report Management System',
            'menu' => 'Promo Code',
            'user' => $user,
            'promocode' => $promo
        ];
        return view('administrator/promocode', $data);
    }

    public function addPromo() {
        $post = $this->request->getVar();
        $promocode = $post['promocode'];
        $description = $post['promo-description'];
        $clothes = $post['clothes'];
        $shoes = $post['shoes'];
        $this->db->query("INSERT INTO promocode(promo, description, clothes, shoes) VALUES('$promocode', ". $this->db->escape($description) .", '$clothes', '$shoes' ) ");
        return redirect()->back()->with('success', 'Promo Successfully Uploaded!');
    }
}

?>