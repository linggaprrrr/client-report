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

    public function getPromocode() {
        $id = $this->request->getVar('id');
        $promo = $this->db->query("SELECT * FROM promocode WHERE id='$id' ");
        $res = $promo->getResultObject();
        echo json_encode($res[0]);
    }

    public function updatePromocode() {
        $post = $this->request->getVar();
        $id = $post['id'];
        $promo = $post['promocode'];
        $desc = $post['promo-description'];
        $clothes = $post['clothes'];
        $shoes = $post['shoes'];

        $this->db->query("UPDATE promocode SET promo = '$promo', `description` = '$desc', clothes='$clothes', shoes = '$shoes' WHERE id = '$id' ");
        echo json_encode($post);
    }
}

?>