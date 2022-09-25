<?php

namespace App\Controllers;

use App\Models\UserModel;;
use App\Models\UPCModel;

class UPC extends BaseController
{
    protected $upcModel = "";
    protected $userModel = "";

    public function __construct() {
        $this->upcModel = new UPCModel();
        $this->userModel = new UserModel();
    }   

    public function index() {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $data = [
            'tittle' => 'UPC Databaset | Report Management System',
            'menu' => 'UPC Databaset',            
            'user' => $user,
        ];

        return view('administrator/upc', $data);
    }

    public function uploadUPC() {
        $file = $this->request->getFile('upc');        
        $ext = $file->getClientExtension();        
        if ($ext == 'xls') {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $spreadsheet = $render->load($file);
        $data = $spreadsheet->getActiveSheet()->toArray();
        $UPCList = array();
        
        foreach ($data as $idx => $row) {
            if ($idx > 0 && !empty($row[0])) {
                $upc = $row[0];
                $asin = $row[1];
                $itemDesc = $row[2];
                $retaiLValue = str_replace('$', '', trim($row[3]));
                $retaiLValue = str_replace(',', '.', $retaiLValue);
                $vendor = $row[4];

                $getUPC = $this->upcModel->where('upc', $row[0])->get();
                $getUPC = $getUPC->getFirstRow();                
                
                $temp = [                        
                    'upc' => $upc,
                    'asin' => $asin,
                    'item_description' => $itemDesc,
                    'retail_value' => $retaiLValue,
                    'vendor_name' => $vendor,
                ];
                $this->upcModel->ignore(true)->insert($temp);            
            }
        }
        return redirect()->back()->with('success', 'UPC Successfully Uploaded!');
    }

    public function loadUPC() {
        $params['draw'] = $_REQUEST['draw'];
        $start = $_REQUEST['start'];
        $length = $_REQUEST['length'];
        $search_value = $_REQUEST['search']['value'];
        ini_set('memory_limit', '-1');
        if(!empty($search_value)){
            $total_count = $this->db->query("SELECT upc, asin, item_description, CONCAT('$',retail_value) as retail_value, vendor_name from upcs WHERE upc like '%".$search_value."%' OR item_description like '%".$search_value."%' OR vendor_name like '%".$search_value."%' ")->getResult();
 
            $data = $this->db->query("SELECT upc, asin, item_description, CONCAT('$',retail_value) as retail_value, vendor_name from upcs WHERE upc like '%".$search_value."%' OR item_description like '%".$search_value."%' OR vendor_name like '%".$search_value."%'  limit $start, $length")->getResult();
        }else{
            $total_count = $this->db->query("SELECT upc, asin, item_description, CONCAT('$',retail_value) as retail_value, vendor_name from upcs")->getResult();
            $data = $this->db->query("SELECT upc, asin, item_description, CONCAT('$',retail_value) as retail_value, vendor_name from upcs limit $start, $length")->getResult();
        }
        $json_data = array(
            "draw" => intval($params['draw']),
            "recordsTotal" => count($total_count),
            "recordsFiltered" => count($total_count),
            "data" => $data   // total data array
        );

        echo json_encode($json_data);
    }
    
    public function findUPC() {
        $upc = $this->request->getVar('upc');
        $getUPC = $this->upcModel->where('upc', $upc)->get();
        $getUPC = $getUPC->getFirstRow();                

        if (!empty($getUPC)) {
            echo json_encode($getUPC);
        } else {
            echo json_encode(0);
        }
    }

    public function saveLog() {
        $post = $this->request->getVar();
        $category = "";
        $itemCost = 0;
        
        $this->upcModel->insertBox([
            'box_name' => $post['box'],
            'category' => $category,
            'description' => 'BOX #'.$post['box'].'-'.$category,
            'user_id' => session()->get('user_id')      
        ]);
        
        if (!empty($post['desc'])) {
            if ($post['desc'] != 'ITEM NOT FOUND') {
                if ($post['category'] == '1') {
                    $category = "shoes";
                    $itemCost = $post['retail'] / 3;
                } else {
                    $category = "clothes";
                    $itemCost = $post['retail'] / 4;
                }
                $item = [
                    'sku' => $post['upc'],
                    'item_description' => $post['desc'],
                    'category' => $category,
                    'cond' => 'NEW',
                    'qty' => '1',
                    'retail' => $post['retail'],
                    'original' => $post['retail'],
                    'cost' => $itemCost,
                    'box_name' => $post['box'],
                    'vendor' => $post['vendor'],
                ];
            } else {
                $item = [
                    'sku' => $post['upc'],
                    'item_description' => $post['desc'],
                    'box_name' => $post['box'],             
                ];
            }
        }
            
        $this->upcModel->insertBoxItem($item);
        
    }

}