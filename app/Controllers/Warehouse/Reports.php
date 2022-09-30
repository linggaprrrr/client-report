<?php

namespace App\Controllers\Warehouse;

use App\Controllers\BaseController;
use App\Models\AssignReportModel;
use App\Models\CategoryModel;
use App\Models\InvestmentModel;
use App\Models\NewsModel;
use App\Models\ReportModel;
use App\Models\UserModel;
use App\Models\UPCModel;
use CodeIgniter\Database\BaseBuilder;



class Reports extends BaseController
{
    protected $reportModel = "";
    protected $investmentModel = "";
    protected $categoryModel = "";
    protected $userModel = "";
    protected $newsModel = "";
    protected $assignReportModel = "";
    protected $upcModel = "";
    protected $spreadsheetReader;
    protected $db;


    public function __construct()
    {
        $this->reportModel = new ReportModel();
        $this->investmentModel = new InvestmentModel();
        $this->categoryModel = new CategoryModel();
        $this->userModel = new UserModel();
        $this->upcModel = new UPCModel();
        $this->newsModel = new NewsModel();
        $this->assignReportModel = new AssignReportModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $data = [
            'tittle' => 'Dashboard | Report Management System',
            'menu' => 'Dashboard',
            'user' => $user,
        ];
        return view('warehouse/scan_log', $data);
    }

    public function createNewBox() {
        $box = $this->request->getVar('box');
        $this->db->query("INSERT INTO boxes(box_name) VALUES('$box') ");
        echo json_encode([
            'box_name' => $box,
        ]);
    }
        
    public function historyBox() {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $post = $this->request->getVar();
        
        if (!empty($post)) {
            $date1 = date('Y-m-d 00:00:00', strtotime($post['start']));             
            $date2 = date('Y-m-d 00:00:00', strtotime($post['end'] . "+1 day"));
            $boxes = $this->upcModel->historyBox($date1, $date2);
            $totalBox = $boxes->getNumRows();
            $total = $this->upcModel->totalQty($date1, $date2);
            
            $total = 
            $data = [
                'tittle' => 'History Box | Report Management System',
                'menu' => 'History Box',          
                'user' => $user,
                'date1' => $post['start'],
                'date2' => $post['end'],
                'boxes' => $boxes,
                'totalBox' => $totalBox,
                'totalQty' => $total->qty,
                'totalOriginal' => $total->original,
                'totalCost' => $total->cost
                
            ];
        } else {
            $boxes = $this->upcModel->historyBox();
            $totalBox = $boxes->getNumRows();
            $total = $this->upcModel->totalQty();
            
            $data = [
                'tittle' => 'History Box | Report Management System',
                'menu' => 'History Box',           
                'user' => $user,
                'boxes' => $boxes,
                'totalBox' => $totalBox,
                'totalQty' => $total->qty,
                'totalOriginal' => $total->original,
                'totalCost' => $total->cost
            ];
        }
                
        return view('warehouse/history_box', $data);
    }
    

    public function test()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $test = $this->db->query("SELECT * FROM reports LIMIT 100");
        $test =  
        $data = [
            'tittle' => 'Scan Log | Report Management System',
            'menu' => 'Scan Log',
            'user' => $user,
            'test' => $test
        ];
        ini_set('memory_limit', '-1');
        return view('va/test', $data);
    }

    

   
}
