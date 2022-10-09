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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
            'tittle' => 'Scan Log | Report Management System',
            'menu' => 'Scan Log',
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

    public function inputItem() {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $datefilter = $this->request->getVar('datefilter');
        $getAllClient = $this->reportModel->getAllClient();
        if (is_null($datefilter)) {
            $date1 = date('Y-m-d 00:00:00');             
            $date2 = date('Y-m-d 00:00:00', strtotime("+1 day"));   
            $items = $this->db->query("SELECT reports.*, users.fullname FROM reports JOIN users ON users.id = reports.user_id WHERE user_id IS NOT NULL AND input_date BETWEEN '$date1' AND '$date2' ORDER BY input_date DESC");
            $totalQty = $this->db->query("SELECT SUM(qty) as qty FROM reports WHERE user_id IS NOT NULL ")->getRow();
            $totalRetail = $this->db->query("SELECT SUM(retail_value) as retail FROM reports WHERE user_id IS NOT NULL ")->getRow();
            $totalOriginal = $this->db->query("SELECT SUM(original_value) as original FROM reports WHERE user_id IS NOT NULL ")->getRow();
            $totalClientCost = $this->db->query("SELECT SUM(cost) as client_cost FROM reports WHERE user_id IS NOT NULL ")->getRow();
                    
        } else {
            $datefilter = explode('-', $datefilter);
            $date1 = date('Y-m-d 00:00:00', strtotime(trim($datefilter[0])));
            $date2 = date('Y-m-d 00:00:00', strtotime(trim($datefilter[1])));         
            $items = $this->db->query("SELECT reports.*, users.fullname FROM reports JOIN users ON users.id = reports.user_id WHERE user_id IS NOT NULL AND input_date BETWEEN '$date1' AND '$date2' ORDER BY input_date DESC");
            $totalQty = $this->db->query("SELECT SUM(qty) as qty FROM reports WHERE user_id IS NOT NULL AND input_date BETWEEN '$date1' AND '$date2' ")->getRow();
            $totalRetail = $this->db->query("SELECT SUM(retail_value) as retail FROM reports WHERE user_id IS NOT NULL AND input_date BETWEEN '$date1' AND '$date2' ")->getRow();
            $totalOriginal = $this->db->query("SELECT SUM(original_value) as original FROM reports WHERE user_id IS NOT NULL AND input_date BETWEEN '$date1' AND '$date2'")->getRow();
            $totalClientCost = $this->db->query("SELECT SUM(cost) as client_cost FROM reports WHERE user_id IS NOT NULL AND input_date BETWEEN '$date1' AND '$date2'")->getRow();
            

        }
        
        $user = $this->userModel->find($userId);
        $data = [
            'tittle' => 'Input Item | Report Management System',
            'menu' => 'Input Item',
            'user' => $user,
            'getAllClient' => $getAllClient,
            'items' => $items,
            'totalQty' => $totalQty,
            'totalRetail' => $totalRetail,
            'totalOriginal' => $totalOriginal,
            'totalClientCost' => $totalClientCost,
            'date1' => date('Y-m-d', strtotime($date1)),
            'date2' => date('Y-m-d', strtotime($date2)),
        ];
        return view('warehouse/input_item', $data);
    }

    public function saveItem() {
        $post = $this->request->getVar();
        $client = $post['client'];        
        $getInvestId = $post['investment'];        
        for ($i = 0; $i < count($post['upc']); $i++) {
            if (!empty($post['upc'][$i]) || $post['upc'][$i] != "") {
                $upc = $post['upc'][$i];
                $desc = $post['desc'][$i];
                $condition = $post['condition'][$i];
                $qty = $post['qty'][$i];
                $retail = $post['original-retail'][$i];
                $original = $qty * $retail;
                $cost = $post['client-cost'][$i];
                $vendor = $post['vendor-name'][$i];
                
                $userId = session()->get('user_id');
                $this->db->query("INSERT INTO reports(sku, item_description, cond, qty, retail_value, original_value, cost, vendor, client_id, investment_id, user_id) VALUES('$upc', '$desc', '$condition', '$qty', '$retail', '$original', '$cost', '$vendor', '$client', '$getInvestId', '$userId')");

            }
        }
        return redirect()->back()->with('success', 'UPC Successfully Uploaded!');
    }

    public function exportDataInput($date1, $date2) {        
        $date = date('m-d-Y');
        $fileName = "Manual Input Data - {$date}.xlsx";  
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'UPC/SKU');
		$sheet->setCellValue('B1', 'ITEM DESCRIPTION');
		$sheet->setCellValue('C1', 'CONDITION');
		$sheet->setCellValue('D1', 'ORIGINAL QTY');
        $sheet->setCellValue('E1', 'RETAIL VALUE');
        $sheet->setCellValue('F1', 'ORIGINAL RETAIL');
        $sheet->setCellValue('G1', 'CLIENT COST');
        $sheet->setCellValue('H1', 'VENDOR');
        $no = 2;
        $items = $this->db->query("SELECT reports.*, users.fullname FROM reports JOIN users ON users.id = reports.user_id WHERE user_id IS NOT NULL AND input_date BETWEEN '$date1' AND '$date2' ORDER BY input_date DESC");
        foreach($items->getResultObject() as $row) {                
            $sheet->setCellValue('A' . $no, $row->sku);
            $sheet->setCellValue('B' . $no, $row->item_description);                
            $sheet->setCellValue('C' . $no, $row->cond);
            $sheet->setCellValue('D' . $no, $row->qty);
            $sheet->setCellValue('E' . $no, $row->retail_value);
            $sheet->setCellValue('F' . $no, $row->original_value);
            $sheet->setCellValue('G' . $no, $row->cost);
            $sheet->setCellValue('G' . $no, $row->vendor);
            $no++;
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save("files/". $fileName);
      
        header("Content-Type: application/vnd.ms-excel");

		header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length:' . filesize("files/". $fileName));
		flush();
		readfile("files/". $fileName);
		exit;
    }

    public function test()
    {
        
    }

    

   
}
