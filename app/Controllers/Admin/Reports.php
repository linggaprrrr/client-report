<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AssignReportModel;
use App\Models\CategoryModel;
use App\Models\InvestmentModel;
use App\Models\NewsModel;
use App\Models\ReportModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use CodeIgniter\Database\BaseBuilder;



class Reports extends BaseController
{
    protected $reportModel = "";
    protected $investmentModel = "";
    protected $categoryModel = "";
    protected $userModel = "";
    protected $newsModel = "";
    protected $assignReportModel = "";
    protected $transactionModel = "";
    protected $spreadsheetReader;
    protected $db;


    public function __construct()
    {
        $this->reportModel = new ReportModel();
        $this->investmentModel = new InvestmentModel();
        $this->categoryModel = new CategoryModel();
        $this->userModel = new UserModel();
        $this->newsModel = new NewsModel();
        $this->transactionModel = new TransactionModel();
        $this->assignReportModel = new AssignReportModel();
        $this->db = \Config\Database::connect();
        // $this->db->query("DELETE FROM myTable WHERE dateEntered < DATE_SUB(NOW(), INTERVAL 1 MONTH)");
    }

    public function index()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }

        $user = $this->userModel->find($userId);

        $totalInvest = $this->investmentModel->totalClientInvestment();
        $totalFulfilled = $this->reportModel->totalFulfilled();
        $getAllReports = $this->reportModel->getAllReports();
        $finSummary = $this->reportModel->finSummary("spend");
        $finSummaryFulfill = $this->reportModel->finSummary();
        $costUnderOnek = $this->db->query("SELECT investments.client_id, users.fullname, investments.date as investment_date, investments.status, users.company, investments.cost as client_cost, total_retail, total_unit, total_fulfilled, investments.cost - cost_ as cost_left FROM investments LEFT JOIN (SELECT SUM(reports.qty) as total_unit, SUM(reports.original_value) as total_retail, SUM(reports.cost) as total_fulfilled, SUM(IFNULL(reports.cost, 0)) as cost_, investment_id FROM reports GROUP BY reports.investment_id ) as rep  ON investments.id = rep.investment_id JOIN users ON users.id = investments.client_id WHERE (investments.cost - cost_) BETWEEN 1 AND 1000 ORDER BY (investments.cost - cost_) ASC");
        $news = $this->newsModel->getLastNews(1);
        $getBoxCost = $this->assignReportModel->getCostBox();
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();
        $tempBoxSummary = array();
        $temp_date = "";
        $counter = 1;
        $shipped = 0;
        $remanifested = 0;
        $reassigned = 0;
        foreach ($getBoxCost->getResultArray() as $box) {
            $temp_shipped = 0;
            $temp_remanifested = 0;
            $temp_reassigned = 0;

            if ($box['status'] == "shipped") {
                $temp_shipped = $box['cost'];
            } elseif ($box['status'] == "remanifested") {
                $temp_remanifested = $box['cost'];
            } else {
                $temp_reassigned = $box['cost'];
            }
        

            if ($counter == 1) {
                $temp_date = $box['order_date'];
                $shipped = $shipped + $temp_shipped;
                $remanifested = $remanifested + $temp_remanifested;
                $reassigned = $reassigned + $temp_reassigned;
                $counter++;
            } else {
                if ($box['order_date'] == $temp_date) {
                    $temp_date = $box['order_date'];
                    $shipped = $shipped + $temp_shipped;
                    $remanifested = $remanifested + $temp_remanifested;
                    $reassigned = $reassigned + $temp_reassigned;
                } else {
                    $temp_box = array(
                        'date' => $temp_date,
                        'shipped' => number_format($shipped, 2), 
                        'remanifested' => number_format($remanifested, 2),
                        'reassigned' => number_format($reassigned, 2),
                    );  
                    array_push($tempBoxSummary, $temp_box); 
                    $shipped = 0;
                    $remanifested = 0;
                    $reassigned = 0;
                    $temp_date = $box['order_date'];
                    $shipped = $shipped + $temp_shipped;
                    $remanifested = $remanifested + $temp_remanifested;
                    $reassigned = $reassigned + $temp_reassigned;
                }
                
            }
        }
        $temp_box = array(
            'date' => $temp_date,
            'shipped' => number_format($shipped, 2), 
            'remanifested' => number_format($remanifested, 2),
            'reassigned' => number_format($reassigned, 2),
        );  
        array_push($tempBoxSummary, $temp_box); 
        $summ = array();
        $check = 0;
        foreach ($finSummary->getResultArray() as $row) {
            foreach ($finSummaryFulfill->getResultArray() as $fill) {
                if ($row['month'] == $fill['month']) {
                    $temp = array(
                        'month' => $row['month'],
                        'spend' => $row['spend'],
                        'fulfill' => $fill['fulfill']
                    );
                    $check = 1;
                    array_push($summ, $temp);
                }
            }
            if ($check == 0) {
                $temp = array(
                    'month' => $row['month'],
                    'spend' => $row['spend'],
                    'fulfill' => 0
                );
                array_push($summ, $temp);
            }
            $check = 0;
        }

        $data = [
            'tittle' => 'Dashboard | Report Management System',
            'menu' => 'Dashboard',
            'user' => $user,
            'getAllReports' => $getAllReports,
            'totalInvest' => $totalInvest,
            'totalFulfilled' => $totalFulfilled,
            'costUnderOnek' => $costUnderOnek,
            'finSummary' => $summ,
            'boxStatSummary' => $tempBoxSummary,
            'news' => $news,
            'companySetting' => $companysetting
        ];
        return view('administrator/dashboard', $data);
    }

    public function exportAllReport() {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
		$sheet->setCellValue('B1', 'Name');
		$sheet->setCellValue('C1', 'Company');
		$sheet->setCellValue('D1', 'Link');
		$sheet->setCellValue('E1', 'Status');
        $sheet->setCellValue('F1', 'Investment Date');
        $sheet->setCellValue('G1', 'Total Client Cost');
        $sheet->setCellValue('H1', 'Total Retail');
        $sheet->setCellValue('I1', 'Qty');
        $sheet->setCellValue('J1', 'Total Fulfilled');
        $sheet->setCellValue('K1', 'Total Cost Left');
        $no = 2;
        $i = 1;
        $reports = $this->reportModel->exportAllReport();
        foreach($reports->getResultObject() as $row) {             
            $sheet->setCellValue('A' . $no, $i++);               
            $sheet->setCellValue('B' . $no, $row->fullname);
            $sheet->setCellValue('C' . $no, $row->company);                
            $sheet->setCellValue('D' . $no, $row->link);
            if ($row->status == 'complete') {
                $sheet->setCellValue('E' . $no, 'COMPLETE');
            } else {
                $sheet->setCellValue('E' . $no, 'WORKING');
            }
            
            $sheet->setCellValue('F' . $no, $row->investment_date);
            $sheet->setCellValue('G' . $no, $row->client_cost);
            $sheet->setCellValue('H' . $no, $row->total_retail);
            $sheet->setCellValue('I' . $no, $row->total_unit);
            $sheet->setCellValue('J' . $no, $row->total_fulfilled);
            $sheet->setCellValue('K' . $no, $row->cost_left);
            $no++;
        }
        $fileName = "Reports Summary.xlsx";  
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

    public function date_sort($a, $b) {
        $t1 = strtotime($a["date"]);
        $t2 = strtotime($b["date"]);
        return ($t2 - $t1);
    }

    public function clientActivities()
    {

        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $totalClientUploaded = $this->reportModel->totalClientUploaded();
        $totalReport = $this->reportModel->totalReport();
        $getAllFiles = $this->reportModel->getAllFiles();
        $getAllClient = $this->reportModel->getAllClient();
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();

        $data = [
            'tittle' => 'Client Activities | Report Management System',
            'menu' => 'Client Activities',
            'totalClientUploaded' => $totalClientUploaded,
            'totalReport' => $totalReport,
            'getAllFiles' => $getAllFiles,
            'getAllClient' => $getAllClient,
            'user' => $user,
            'companySetting' => $companysetting
        ];

        return view('administrator/client_activities', $data);
    }

    public function uploadReport()
    {
        $client = $this->request->getVar('client');
        $link = $this->request->getVar('link');
        $report = $this->request->getFile('file');

        $date = $this->request->getVar('date');
        $date = date('Y-m-d', strtotime($date));

        $investmentId = "";
        $check = $this->db->query("SELECT * FROM investments WHERE date = '$date' AND client_id='$client' ")->getRow();
        if (!empty($check)) {
            $investmentId = $check->id;
            $this->db->query("DELETE FROM investments WHERE id = '$investmentId' ");
            $this->db->query("DELETE FROM reports WHERE investment_id = '$investmentId' ");
            $this->db->query("DELETE FROM log_files WHERE investment_id = '$investmentId' ");
        } 
        
        $ext = $report->getClientExtension();
        if ($ext == 'xls') {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $spreadsheet = $render->load($report);
        $data = $spreadsheet->getActiveSheet()->toArray(null, true,false,false);
        $category = array();
        $reportData = array();
        $investment = array();
        $logFiles = array();
        $investmentLastId = "";
        foreach ($data as $idx => $data) {
            if ($idx == 1) {
                $tempInvest = str_replace('$', '', $data[5]);
                $tempInvest = str_replace(',', '', $tempInvest);

                $investment = array(
                    "cost" => $tempInvest,
                    "date" => $date,
                    "client_id" => $client
                );

                $this->investmentModel->save($investment);
                $investmentLastId = $this->investmentModel->getLastId();
            } elseif ($idx > 2) {
                if (!empty($data[1] || strcasecmp($data[1], "tracking") != 0)) {
                    if (!empty($data[0])) {
                        $retail = str_replace('$', '', $data[4]);
                        $retail = str_replace(',', '', $retail);
                        $original = str_replace('$', '', $data[5]);
                        $original = str_replace(',', '', $original);
                        $cost = str_replace('$', '', $data[6]);
                        $cost = str_replace(',', '', $cost);
                        $reportData = array(
                            "sku" => $data[0],
                            "item_description" => trim($data[1]),
                            "cond" => $data[2],
                            "qty" => $data[3],
                            "retail_value" => $retail,
                            "original_value" => $original,
                            "cost" => $cost,
                            "vendor" => $data[7],
                            "client_id" => $client,
                            "investment_id" => $investmentLastId,
                            "created_at" => date("Y-m-d H:i:s"),
                            "updated_at" => date("Y-m-d H:i:s")
                        );
                        $this->reportModel->save($reportData);
                    }
                } else {
                    continue;
                }
            }
        }
        $fileName = time() . $report->getName();
        $report->move('files', $fileName);
        $this->db->query("INSERT into log_files(date, file, link, client_id, investment_id) VALUES(NOW()," . $this->db->escape($fileName) . ", " . $this->db->escape($link) . " ,$client, $investmentLastId) ");



        return redirect()->back()->with('success', 'Report Successfully Uploaded!');
    }

    public function uploadReportBulk() {
        $reports = $this->request->getFileMultiple('file');
        $files = array();
        foreach ($reports as $report) {
            array_push($files, $report->getName());
            $fileName = $report->getName();
            if (file_exists(FCPATH. "/files/". $fileName)) {
                $fileName = "NEW ". time() . $report->getName(); 
            }
            $report->move('files', $fileName);
        }
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $totalClientUploaded = $this->reportModel->totalClientUploaded();
        $totalReport = $this->reportModel->totalReport();
        $getAllFiles = $this->reportModel->getAllFiles();
        $getAllClient = $this->reportModel->getAllClient();
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();

        $data = [
            'tittle' => 'Client Activities | Report Management System',
            'menu' => 'Client Activities',
            'totalClientUploaded' => $totalClientUploaded,
            'totalReport' => $totalReport,
            'getAllFiles' => $getAllFiles,
            'getAllClient' => $getAllClient,
            'files' => $files,
            'user' => $user,
            'companySetting' => $companysetting
        ];
        
        return view('administrator/client_activities_bulk_upload', $data);
      
    }

    public function assignReportBulk() {
        $client = $this->request->getVar('client[]');
        $date = $this->request->getVar('date[]');
        $file = $this->request->getVar('file[]');
        $link = $this->request->getVar('link[]');

        $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        for ($i = 0; $i < count($file); $i++) {
            $investmentId = "";
            $newDate = date('Y-m-d', strtotime($date[$i]));
            $check = $this->db->query("SELECT * FROM investments WHERE date = '$newDate' AND client_id='$client[$i]' ")->getRow();
           
            if (!empty($check)) {
                $investmentId = $check->id;
                $this->db->query("DELETE FROM investments WHERE id = '$investmentId' ");
                $this->db->query("DELETE FROM reports WHERE investment_id = '$investmentId' ");
                $this->db->query("DELETE FROM log_files WHERE investment_id = '$investmentId' ");
            } 

            $spreadsheet = $render->load(FCPATH. "/files/". $file[$i]);
            $data = $spreadsheet->getActiveSheet()->toArray(null, true,false,false);
            $category = array();
            $reportData = array();
            $investment = array();
            $investmentLastId = "";
           
            foreach ($data as $idx => $data) {
                if ($idx == 1) {
                    $tempInvest = str_replace('$', '', $data[5]);
                    $tempInvest = str_replace(',', '', $tempInvest);
                    $investment = array(
                        "cost" => $tempInvest,
                        "date" => $newDate,
                        "client_id" => $client[$i]
                    );
                  
                   
                    $this->investmentModel->save($investment);
                    $investmentLastId = $this->investmentModel->getLastId();
            
                } elseif ($idx > 2) {
                    if (!empty($data[1] || strcasecmp($data[1], "tracking") != 0)) {
                        if (!empty($data[0])) {
                            $retail = str_replace('$', '', $data[4]);
                            $retail = str_replace(',', '', $retail);
                            $original = str_replace('$', '', $data[5]);
                            $original = str_replace(',', '', $original);
                            $cost = str_replace('$', '', $data[6]);
                            $cost = str_replace(',', '', $cost);
                            $reportData = array(
                                "sku" => $data[0],
                                "item_description" => trim($data[1]),
                                "cond" => $data[2],
                                "qty" => $data[3],
                                "retail_value" => $retail,
                                "original_value" => $original,
                                "cost" => $cost,
                                "vendor" => $data[7],
                                "client_id" => $client[$i],
                                "investment_id" => $investmentLastId,
                                "created_at" => date("Y-m-d H:i:s"),
                                "updated_at" => date("Y-m-d H:i:s")
                            );
                            $this->reportModel->save($reportData);
                        }
                    } else {
                        continue;
                    }
                }
            }
            $fileName = $file[$i];
            $this->db->query("INSERT into log_files(date, file, link, client_id, investment_id) VALUES(NOW()," . $this->db->escape($fileName) . ", " . $this->db->escape($link[$i]) . " ,$client[$i], $investmentLastId) ");
        }
        return redirect()->to('/admin/client-activities')->with('success', 'Report Successfully Uploaded!');
    }

    public function deleteReport($id)
    {
        $manifest = $this->reportModel->getFileManifest($id);
        $this->reportModel->deleteReport($id);
        if (!is_null($manifest)) {
            unlink('files/' . $manifest->file);
        }
        return redirect()->back()->with('delete', 'Report Successfully Deleted!');
    }

    public function plReport()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $totalClientUploaded = $this->reportModel->totalClientUploaded();
        $totalReport = $this->reportModel->totalReport();
        $getAllFiles = $this->reportModel->getPLReport();
        $getAllClient = $this->reportModel->getAllClient();
        $getBulk = $this->reportModel->getBulkUploaded();
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();

        $data = [
            'tittle' => 'P&L Report | Report Management System',
            'menu' => 'P&L Report',
            'totalClientUploaded' => $totalClientUploaded,
            'totalReport' => $totalReport,
            'getAllFiles' => $getAllFiles,
            'getAllClient' => $getAllClient,
            'getBulk' => $getBulk,
            'user' => $user,
            'companySetting' => $companysetting
        ];

        return view('administrator/pl_reports', $data);
    }

    public function uploadPLReport() {
        $client = $this->request->getVar('client');
        $link = $this->request->getVar('link');
        $chart = $this->request->getFile('chart');
        $types = $this->request->getVar('type');
        
        $ext = $chart->getClientExtension();
        if ($ext == 'xls') {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $spreadsheet = $render->load($chart);
        $data = $spreadsheet->getActiveSheet()->toArray();
        
        if ($types == 'yes') {    
            $chartTitle = array();
            $monthData = array();
            $type = array();
            
            foreach ($data as $idx => $row) {
                if (!empty($row[0])) {
                    array_push($chartTitle, $row[0]);                    
                } else {
                    if (!empty($row[2]) || !empty($row[3]) || !empty($row[4]) || !empty($row[5]) || !empty($row[6]) || !empty($row[7]) || !empty($row[8] || !empty($row[9]) || !empty($row[10]) || !empty($row[11]) || !empty($row[12]) || !empty($row[13]) || !empty($row[14]) || !empty($row[15]) )) {
                        $month = array();
                        if (strpos($row[2], '%') !== false || strpos($row[3], '%') !== false || strpos($row[4], '%') !== false || strpos($row[5], '%') !== false || strpos($row[6], '%') !== false || strpos($row[7], '%') !== false || strpos($row[8], '%') !== false || strpos($row[9], '%') !== false || strpos($row[10], '%') !== false || strpos($row[11], '%') !== false || strpos($row[12], '%') !== false || strpos($row[13], '%') !== false || strpos($row[14], '%') !== false || strpos($row[15], '%') !== false || strpos($row[16], '%') !== false || strpos($row[17], '%') !== false || strpos($row[18], '%') !== false || strpos($row[19], '%') !== false) {
                            for ($i = 2; $i < 19; $i++) {
                                if ($i != 3) {
                                    $temp = str_replace('%', '', trim($row[$i]));
                                    $temp = str_replace(',', '', $temp);
                                    if (strpos($temp, '(') !== false) {
                                        $temp = str_replace('(', '', $temp);
                                        $temp = str_replace(')', '', $temp);
                                        $temp = -1 * abs(trim($temp));
                                    }
                                    array_push($month, trim($temp));
                                }
                            }

                            array_push($type, 'percentage');
                        } elseif (strpos($row[2], '$') !== false || strpos($row[3], '$') !== false || strpos($row[4], '$') !== false || strpos($row[5], '$') !== false || strpos($row[6], '$') !== false || strpos($row[7], '$') !== false || strpos($row[8], '$') !== false || strpos($row[9], '$') !== false || strpos($row[10], '$') !== false || strpos($row[11], '$') !== false || strpos($row[12], '$') !== false || strpos($row[13], '$') !== false || strpos($row[14], '$') !== false || strpos($row[15], '$') !== false || strpos($row[16], '$') !== false || strpos($row[17], '$') !== false || strpos($row[18], '$') !== false || strpos($row[19], '$') !== false) {
                            for ($i = 2; $i < 19; $i++) {
                                if ($i != 3) {
                                    $temp = str_replace('$', '', trim($row[$i]));
                                    $temp = str_replace(',', '', $temp);
                                    if (strpos($temp, '(') !== false) {
                                        $temp = str_replace('(', '', $temp);
                                        $temp = str_replace(')', '', $temp);
                                        $temp = -1 * abs(trim($temp));
                                    }
                                    array_push($month, trim($temp));
                                }
                            }

                            array_push($type, 'currency');
                        } else {
                            for ($i = 2; $i < 19; $i++) {
                                if ($i != 3) {
                                    $temp = trim($row[$i]);
                                    if (strpos($temp, '(') !== false) {
                                        $temp = str_replace('(', '', $temp);
                                        $temp = str_replace(')', '', $temp);
                                        $temp = -1 * abs(trim($temp));
                                    }

                                    if (strpos($temp, ',') !== false) {
                                        $temp = str_replace(',', '', $temp);                                      
                                    }
                                    array_push($month, trim($temp));
                                }
                            }
                            array_push($type, 'num');
                        }
                        array_push($monthData, $month);   
                              
                    }
                    
                }
               
        
            }             
            
            for ($i = 0; $i < count($chartTitle); $i++) {
                $this->reportModel->savePLReport($chartTitle[$i], $monthData[$i], $type[$i], $client);
            }    
        } else {

            $chartTitle = array();
            $monthData = array();
            $type = array();
            foreach ($data as $idx => $row) {
                if (!empty($row[0])) {
                    array_push($chartTitle, $row[0]);
                } else {
                    if (!empty($row[2]) || !empty($row[3]) || !empty($row[4]) || !empty($row[5]) || !empty($row[6]) || !empty($row[7]) || !empty($row[8] || !empty($row[9]) || !empty($row[10]) || !empty($row[11]) || !empty($row[12]) || !empty($row[13]))) {
                        $month = array();
                        if (strpos($row[2], '%') !== false || strpos($row[3], '%') !== false || strpos($row[4], '%') !== false || strpos($row[5], '%') !== false || strpos($row[6], '%') !== false || strpos($row[7], '%') !== false || strpos($row[8], '%') !== false || strpos($row[9], '%') !== false || strpos($row[10], '%') !== false || strpos($row[11], '%') !== false || strpos($row[12], '%') !== false || strpos($row[13], '%') !== false || strpos($row[14], '%') !== false || strpos($row[15], '%') !== false || strpos($row[16], '%') !== false || strpos($row[17], '%') !== false || strpos($row[18], '%') !== false || strpos($row[19], '%') !== false) {
                            for ($i = 4; $i < 19; $i++) {
                                $temp = str_replace('%', '', trim($row[$i]));
                                $temp = str_replace(',', '', $temp);
                                if (strpos($temp, '(') !== false) {
                                    $temp = str_replace('(', '', $temp);
                                    $temp = str_replace(')', '', $temp);
                                    $temp = -1 * abs(trim($temp));
                                }
                                array_push($month, trim($temp));
                            }

                            array_push($type, 'percentage');
                        } elseif (strpos($row[2], '$') !== false || strpos($row[3], '$') !== false || strpos($row[4], '$') !== false || strpos($row[5], '$') !== false || strpos($row[6], '$') !== false || strpos($row[7], '$') !== false || strpos($row[8], '$') !== false || strpos($row[9], '$') !== false || strpos($row[10], '$') !== false || strpos($row[11], '$') !== false || strpos($row[12], '$') !== false || strpos($row[13], '$') !== false || strpos($row[14], '$') !== false || strpos($row[15], '$') !== false || strpos($row[16], '$') !== false || strpos($row[17], '$') !== false || strpos($row[18], '$') !== false || strpos($row[19], '$') !== false) {
                            for ($i = 4; $i < 19; $i++) {
                                $temp = str_replace('$', '', trim($row[$i]));
                                $temp = str_replace(',', '', $temp);
                                if (strpos($temp, '(') !== false) {
                                    $temp = str_replace('(', '', $temp);
                                    $temp = str_replace(')', '', $temp);
                                    $temp = -1 * abs(trim($temp));
                                }
                                array_push($month, trim($temp));
                            }

                            array_push($type, 'currency');
                        } else {
                            for ($i = 4; $i < 19; $i++) {
                                $temp = trim($row[$i]);
                                if (strpos($temp, '(') !== false) {
                                    $temp = str_replace('(', '', $temp);
                                    $temp = str_replace(')', '', $temp);
                                    $temp = -1 * abs(trim($temp));
                                }
                                if (strpos($temp, ',') !== false) {
                                    $temp = str_replace(',', '', $temp);                                      
                                }
                                array_push($month, trim($temp));
                            }
                            array_push($type, 'num');
                        }
                        array_push($monthData, $month);    
                                
                    }
                }
            }
            
            for ($i = 0; $i < count($chartTitle); $i++) {
                $this->reportModel->savePLReportExclude($chartTitle[$i], $monthData[$i], $type[$i], $client);
            }
        }
        
        $fileName = $chart->getName();
        $this->db->query("INSERT into log_files(date, file, link, client_id) VALUES(NOW(), " . $this->db->escape($fileName) . "," . $this->db->escape($link) . " , $client) ");
        return redirect()->back()->with('success', 'Report Successfully Uploaded!');
    }

    
    public function deletePLReport($id)
    {
        $this->reportModel->deletePLReport($id);

        return redirect()->back()->with('delete', 'Report Successfully deleted!');
    }

    public function assignmentReport()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $datefilter = $this->request->getVar('datefilter');
        $date1 = null;
        $date2 = null;  
        if (empty($datefilter)) {
            $day = date('w');
            $week_start = date('Y-m-d 00:00:00', strtotime('-'.$day.' days'));
            $week_end = date('Y-m-d 00:00:00', strtotime('+'.(6-$day).' days'));
            $totalBox = $this->assignReportModel->getTotalBox($week_start, $week_end);
            $onprocess = $this->assignReportModel->getBoxStatus("waiting");
            $complete = $this->assignReportModel->getBoxStatus("approved");
            $getAllAssignReport = $this->assignReportModel->getAllAssignReport();
            $getAllAssignReportPending = $this->assignReportModel->getAllAssignReportProcess($userId, $user['role']);
            $getAllAssignReportCompleted = $this->assignReportModel->getAllAssignReportCompleted();
        } else {
            $daterange = explode('-', $datefilter);
            $date1 = date('Y-m-d', strtotime(trim($daterange[0])));
            $date2 = date('Y-m-d', strtotime(trim($daterange[1])));
            $week_start = date('Y-m-d 00:00:00', strtotime($date1));
            $week_end = date('Y-m-d 00:00:00', strtotime($date2));
            $totalBox = $this->assignReportModel->getTotalBox($week_start, $week_end);
            $onprocess = $this->assignReportModel->getBoxStatus("waiting");
            $complete = $this->assignReportModel->getBoxStatus("approved");
            $getAllAssignReport = $this->assignReportModel->getAllAssignReport($date1, $date2);
            $getAllAssignReportPending = $this->assignReportModel->getAllAssignReportProcess($userId, $user['role']);
            $getAllAssignReportCompleted = $this->assignReportModel->getAllAssignReportCompleted();
        }
        
        $getAllClient = $this->assignReportModel->getAllClient();
        $getAllVA = $this->assignReportModel->getAllVA();

        
        $getWeeks = $this->assignReportModel->getWeeks();
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();
        $getUsers = $this->userModel->where('role', 'client')->orderBy('fullname', 'ASC')->get();
        $getBrands = $this->categoryModel->getBrands();
        
        $getAllInvestment = $this->investmentModel->getAllInvestmentSearch();
        
        $data = [
            'tittle' => 'Assignment Reports | Report Management System',
            'menu' => 'BOX ASSIGNMENT FOR CLIENT FULFILLMENT',
            'user' => $user,
            'getAllClient' => $getAllClient,
            'getAllVA' => $getAllVA,
            'getAllAssignReport' => $getAllAssignReport,
            'getAllAssignReportPending' => $getAllAssignReportPending,
            'getAllAssignReportCompleted' => $getAllAssignReportCompleted,
            'weeks' => $getWeeks,
            'brands' => $getBrands,
            'users' => $getUsers,
            'companySetting' => $companysetting,
            'total_box' => $totalBox->total_box,
            'total_retail' =>number_format($totalBox->retail, 2),
            'client_cost' => number_format($totalBox->client_cost, 2),
            'onprocess' => (!is_null($onprocess) ? $onprocess->status : 0),
            'complete' => (!is_null($complete) ? $complete->status : 0),  
            'date1' => $date1,
            'date2' => $date2,  
            'getAllInvestment' => $getAllInvestment,
            
        ];
        return view('administrator/assignment_report', $data);
    }

    public function searchBrandPage() {        
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        
        $data = [
            'tittle' => 'Assignment Reports: Checklist Report | Report Management System',
            'menu' => 'Brand History',
            'user' => $user,
            
        ];
        return view('administrator/search_brand', $data);
    }

    public function searchBrand() {
        $params['draw'] = $_REQUEST['draw'];
        $start = $_REQUEST['start'];
        $length = $_REQUEST['length'];
        $search_value = $_REQUEST['search']['value'];    
        ini_set('memory_limit', '-1');    
        if(!empty($search_value)){
            $total_count = $this->db->query("SELECT users.fullname, users.company, DATE_FORMAT(investments.date, '%m/%d/%Y') as date, log_files.link, reports.item_description FROM `reports` JOIN investments ON reports.investment_id = investments.id JOIN users ON users.id = investments.client_id JOIN log_files ON log_files.investment_id = investments.id WHERE reports.item_description LIKE '%".$search_value."%' OR reports.vendor LIKE '%".$search_value."%' OR users.fullname LIKE '%".$search_value."%'")->getResult(); 
            $data = $this->db->query("SELECT users.fullname, users.company, DATE_FORMAT(investments.date, '%m/%d/%Y') as date, log_files.link, reports.item_description FROM `reports` JOIN investments ON reports.investment_id = investments.id JOIN users ON users.id = investments.client_id JOIN log_files ON log_files.investment_id = investments.id WHERE reports.item_description like '%".$search_value."%' OR reports.vendor LIKE '%".$search_value."%' OR users.fullname LIKE '%".$search_value."%' limit $start, $length")->getResult();
        }else{
            $total_count = $this->db->query("SELECT users.fullname, users.company, DATE_FORMAT(investments.date, '%m/%d/%Y') as date, log_files.link, reports.item_description FROM `reports` JOIN investments ON reports.investment_id = investments.id JOIN users ON users.id = investments.client_id JOIN log_files ON log_files.investment_id = investments.id")->getResult();
            $data = $this->db->query("SELECT users.fullname, users.company, DATE_FORMAT(investments.date, '%m/%d/%Y') as date, log_files.link, reports.item_description FROM `reports` JOIN investments ON reports.investment_id = investments.id JOIN users ON users.id = investments.client_id JOIN log_files ON log_files.investment_id = investments.id limit $start, $length")->getResult();
        }
        $json_data = array(
            "draw" => intval($params['draw']),
            "recordsTotal" => count($total_count),
            "recordsFiltered" => count($total_count),
            "data" => $data   // total data array
        );

        echo json_encode($json_data);
    }

    public function brandHistory() {
        $post = $this->request->getVar();
        $query = $this->db->query("SELECT users.fullname, users.company, DATE_FORMAT(investments.date, '%m/%d/%Y') as date, log_files.link, reports.item_description, IF((investments.cost-IFNULL(cost_left, 0)) > 0, '1', '0') as available_order FROM `reports` JOIN investments ON reports.investment_id = investments.id LEFT JOIN (SELECT investment_id, SUM(reports.cost) as cost_left FROM reports GROUP BY investment_id) as r ON r.investment_id = investments.id JOIN users ON users.id = investments.client_id JOIN log_files ON log_files.investment_id = investments.id  WHERE item_description LIKE '%".$post['brand']."%'");
        $data = array();
        $count = 0;
        foreach ($query->getResultObject() as $item) {
            array_push($data, $item);
            if ($item->available_order == '1') {
                $count++;
            }
        }

        echo json_encode($data);
    }

    public function searchBrand2() {
        $params['draw'] = $_REQUEST['draw'];
        $start = $_REQUEST['start'];
        $length = $_REQUEST['length'];
        $search_value = $_REQUEST['search']['value'];    
        ini_set('memory_limit', '-1');    
        if(!empty($search_value)){
            $total_count = $this->db->query("SELECT users.fullname, users.company, log_files.link, reports.item_description FROM `reports` JOIN users ON users.id = reports.client_id JOIN log_files ON log_files.investment_id = reports.investment_id WHERE reports.item_description LIKE '%".$search_value."%' OR reports.vendor LIKE '%".$search_value."%' OR users.fullname LIKE '%".$search_value."%'")->getResult(); 
            $data = $this->db->query("SELECT users.fullname, users.company, log_files.link, reports.item_description FROM `reports` JOIN users ON users.id = reports.client_id JOIN log_files ON log_files.investment_id = reports.investment_id WHERE reports.item_description like '%".$search_value."%' OR reports.vendor LIKE '%".$search_value."%' OR users.fullname LIKE '%".$search_value."%' limit $start, $length")->getResult();
        }else{
            $total_count = $this->db->query("SELECT users.fullname, users.company, log_files.link, reports.item_description FROM `reports` JOIN users ON users.id = reports.client_id JOIN log_files ON log_files.investment_id = reports.investment_id")->getResult();
            $data = $this->db->query("SELECT users.fullname, users.company, log_files.link, reports.item_description FROM `reports`JOIN users ON users.id = reports.client_id JOIN log_files ON log_files.investment_id = reports.investment_id limit $start, $length")->getResult();
        }
        $json_data = array(
            "draw" => intval($params['draw']),
            "recordsTotal" => count($total_count),
            "recordsFiltered" => count($total_count),
            "data" => $data   // total data array
        );

        echo json_encode($json_data);
    }

    public function getSummaryBox()
    {

        $day = date('w');
        $week_start = date('Y-m-d 00:00:00', strtotime('-'.$day.' days'));
        $week_end = date('Y-m-d 00:00:00', strtotime('+'.(6-$day).' days'));
        $totalBox = $this->assignReportModel->getTotalBox($week_start, $week_end);
        $onprocess = $this->assignReportModel->getBoxStatus("waiting");
        $complete = $this->assignReportModel->getBoxStatus("approved");
        
        
        $summary = array(        
            'total_box' => $totalBox->total_box,
            'total_retail' =>number_format($totalBox->retail, 2),
            'client_cost' => number_format($totalBox->client_cost, 2),
            'onprocess' => (!is_null($onprocess) ? $onprocess->status : 0),
            'complete' => (!is_null($complete) ? $complete->status : 0),            
        );

        echo json_encode($summary);
    }

    public function updatePriceBox()
    {
        $post = $this->request->getVar();
        for ($i = 0; $i < count($post['item']); $i++) {
            $retail = str_replace('$', '', trim($post['retail'][$i]));
            $original = str_replace('$', '', trim($post['original'][$i]));
            $cost = str_replace('$', '', trim($post['cost'][$i]));
            $id = $post['item'][$i];
            $this->db->query("UPDATE assign_report_details SET retail='$retail', original='$original', cost='$cost' WHERE id='$id' ");
        }
        return redirect()->back()->with('success', 'Report Successfully saved!');
    }

    public function reassignBox()
    {
        $post = $this->request->getVar();
        $box = $post['box_name'];
        $client = $post['client'];
        for ($i = 0; $i < count($post['item']); $i++) {
            $retail = str_replace('$', '', trim($post['retail'][$i]));
            $original = str_replace('$', '', trim($post['original'][$i]));
            $cost = str_replace('$', '', trim($post['cost'][$i]));
            $id = $post['item'][$i];
            $this->db->query("UPDATE assign_report_details SET retail='$retail', original='$original', cost='$cost' WHERE id='$id' ");
        }
        $this->db->query("UPDATE assign_report_box SET status='waiting', confirmed='1', client_id='$client' WHERE box_name='$box' ");
        return redirect()->back()->with('success', 'Report Successfully saved!');
    }

    public function assignmentReportSubmit()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4048M');
        $report = $this->request->getFile('file');
        $ext = $report->getClientExtension();

        if ($ext == 'xls') {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $fileName = time() . $report->getName();

        $spreadsheet = $render->load($report);
        $data = $spreadsheet->getActiveSheet()->toArray();
        $insertId = "";
        $affected_rows = 0;
        $boxName = "";
        $totalCost = 0;
        $count = 1;        
        foreach ($data as $idx => $row) {
            if ($idx == 1) {
                $this->db->query("INSERT INTO assign_reports(file) VALUES(" . $this->db->escape($fileName) . ") ");
                $insertId = $this->assignReportModel->getLastId();
            }
            if ($idx > 2) {
                if (is_null($row[1]) && !is_null($row[2])) {
                    $boxName = $row[3];                    
                    
                } elseif (is_null($row[1]) && is_null($row[2])) {                    
                    $this->db->query("UPDATE assign_report_box SET box_value = '$totalCost', date=now(), report_id='$insertId' WHERE box_name='$boxName' ");                    
                    $totalCost = 0; 
                    $boxName = "";                
                } else {
                    $cost = str_replace('$', '', $row[7]);
                    $cost = str_replace(',', '', trim($cost));
                    
                    $totalCost = $totalCost + ((float) $cost);

                   
                }              
            } 
        }
        $report->move('files', $fileName);
        return redirect()->back()->with('success', 'Need to upload successfully uploaded');
    }

    public function getCompany($id)
    {
        $company = $this->investmentModel->getCompany($id);
        $temp = array();
        $tempCompany = "";
        foreach ($company->getResultArray() as $brand) {
            array_push($temp, $brand['brand_name']);
            $tempCompany = $brand['company'];
        }
        $str = implode(", ", $temp);
        $brands = array(
            'company' => $tempCompany,
            'brands' => $str
        );
        echo json_encode($brands);
    }

    public function assignClient()
    {
        $post = $this->request->getVar();
        $boxId = substr($post['box_id'], 4);
        $clientId = $post['client_id'];
        $boxValue = $post['box_value'];
        $total = 0;
        $totalBox = $this->db->query("SELECT SUM(box_value) as total_box, MIN(client_cost_left) as cost_left FROM assign_report_box WHERE client_id='$clientId' ")->getRow();
        
        if (!is_null($totalBox->total_box)) {
            $currentCost = $totalBox->total_box;
        } else {
            $getCostInvest = $this->db->query("SELECT cost FROM investments WHERE client_id='$clientId' ")->getRow();
            $total = $getCostInvest->cost - $boxValue;
        }


        if ($total > -500) {
            $this->db->query("UPDATE assign_report_box SET client_id='$clientId', client_cost_left='$total' WHERE id='$boxId' ");
        }
        echo $total;
    }

    public function checklistReport()
    {
        $status = $this->request->getVar('status');
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $getAllInvestment = $this->investmentModel->getAllInvestment($status);
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();
        $data = [
            'tittle' => 'Assignment Reports: Checklist Report | Report Management System',
            'menu' => 'Checklist Report',
            'user' => $user,
            'getAllInvestment' => $getAllInvestment,
        ];
        return view('administrator/checklist_report', $data);
    }

    public function checklistReportSave()
    {
        $post = $this->request->getVar();
        for ($i = 0; $i < count($post['investment_id']); $i++) {
            $this->investmentModel->save(array(
                "id" => $post['investment_id'][$i],
                "status" => $post['status'][$i],
            ));
        }
        return redirect()->back()->with('success', 'Report Successfully saved!');
    }

    public function saveAssignmentReport()
    {
        $post = $this->request->getVar();
        $check = 0;
        $clients = [];
        foreach ($post['client'] as $idx => $data) {
            if ($data != '0') {                
                $clientId = $post['client'][$idx];
                array_push($clients, $clientId);
                $boxId = $post['box_id'][$idx];
                $vaId = $post['va'][$idx];
                if ($clientId == 0 || $vaId == 0) {
                    $check = 1;        
                } else {
                    $this->db->query("UPDATE assign_report_box SET confirmed='1', client_id='$clientId', va_id='$vaId', date_assigned=NOW() WHERE id='$boxId' ");
                }
            } 
        }
        
        if ($check == 1) {
            return redirect()->back()->with('error', 'VA or Client cant be empty!');
        }
        $clients = array_unique($clients);
        for ($i = 0; $i < count($clients); $i++) {
            // $this->sendMailPhase1($clients[$i]);
        }
        return redirect()->back()->with('save', 'Phase 1 Successfully saved!');
    }

    function sendMailPhase1($client = null) {        
        date_default_timezone_set('America/Los_Angeles');
        $user = $this->userModel->find($client);    
        $date = date("m/d/y");
        $message  = "<p>Hi Mr ".$user['fullname'].",</p>";
        $message .= "<p style='text-align: justify;'>We’ve started on your order placed on ". $date .". This is also a reminder of the importance of consistency in your orders. Please place another order for the same amount or more in a month from now. <br>Thank you for your business</p>";                    
        $mail = new PHPMailer;
        $mail->isSMTP();        
        $mail->IsHTML(true);
        $mail->Host = 'smtp.titan.email';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        
        if ($user['under_comp'] == '2') {
            $mail->Username = 'noreply.info@eliteapp.site';
            $mail->Password = 'eliteappinfo1';
            $mail->setFrom('noreply.info@eliteapp.site', 'Elite Automation');
        } else {
            $mail->Username = 'noreply.info@swclient.site';
            $mail->Password = 'swclientinfo1';
            $mail->setFrom('noreply.info@swclient.site', 'Smart FBA Inc');
        }
        $mail->addAddress($user['email'], $user['fullname'] .' - '.$user['company'].'');
        $mail->Subject = 'Yout Manifest Order';
        $mail->Body = $message;
        if (!empty($user['email']) || !is_null(empty($user['email']))) {
            $mail->send();
        }
    }

    public function saveAssignmentProcess()
    {
        $post = $this->request->getVar();
        if (empty($post['status'])) {
            return redirect()->back()->with('reset', 'Assignment Successfully reseted!');
        }
        $boxArr = array();
        for ($i = 0; $i < count($post['status']); $i++) {
            $status = $post['status'][$i];
            if ($status != "0") {
                $fba_number = trim($post['fba_number'][$i]);
                $shipment_number = trim($post['shipment_number'][$i]);
                $box_id = $post['box_id'][$i];
                $client = $post['client'][$i];
                $investment_id = $post['investment_id'][$i];
                if (empty($fba_number) || empty($shipment_number)) {
                    return redirect()->back()->with('required', 'FBA/Shipment Number Required!');
                } else {
                    if ($status == 'approved') {
                        
                        $boxArr = array_merge_recursive($boxArr, array($client.'-' => $box_id));                                                
                        $this->db->query("INSERT INTO reports(fnsku, sku, item_description, cond, qty, retail_value, original_value, cost, vendor, client_id, investment_id) SELECT fnsku, sku, item_description, cond, qty, retail, original, cost, vendor, '$client', '$investment_id' FROM assign_report_details JOIN assign_report_box ON assign_report_box.box_name = assign_report_details.box_name WHERE assign_report_box.id ='$box_id' AND assign_report_details.item_status='1' ");
                    }
                    $this->db->query("UPDATE assign_report_box SET confirmed='1', fba_number='$fba_number', shipment_number='$shipment_number', status='$status' WHERE id='$box_id' ");
                }
            }
        }

        // send email
        
        $keys = array_keys($boxArr);        
        // for ($i = 0; $i < count($keys); $i++) {
        //     $client = trim($keys[$i], "-");            
        //     $this->sendMail($boxArr[$keys[$i]], $client);                        
        // }
        return redirect()->back()->with('reset', 'Assignment Successfully reseted!');
    }
    
    function sendMail($box, $client) {
        $user = $this->userModel->find($client);         
        if (is_array($box) == 1) {
            // body
            $message  = "<p>Hi ".$user['fullname'].",</p>";
            $message .= "<p>We want to inform you that we have finished packing your manifest, which will be sent to your Amazon Store immediately. Please find below the box details.</p>";
            $message .= "<html><body>";
            $message .= '<div style="margin: 0 100px 0 100px">';
            $message .= '<table style="font-family:arial,sans-serif;border-collapse:collapse;width:100%">';
            $message .= '<thead><tr><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">SKU/UPC</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">ITEM DESCRIPTION</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">CONDITION</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">ORIGINAL QTY</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">RETAIL VALUE</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">TOTAL ORIGINAL RETAIL</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">TOTAL CLIENT COST</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">VENDOR NAME</th></tr></thead>';
            $message .= '<tbody>';
            for ($i = 0; $i < count($box); $i++) {
                $details = $this->assignReportModel->getDetailBox($box);                                    
                foreach($details->getResultObject() as $det) {
                    $message .= '<tr><td style="border:1px solid #000;text-align:center;padding:3px">'.$det->sku.'</td><td style="border:1px solid #000;text-align:center;padding:3px">'.$det->item_description.'</td><td style="border:1px solid #000;text-align:center;padding:3px">NEW</td><td style="border:1px solid #000;text-align:center;padding:3px">'.$det->qty.'</td><td style="border:1px solid #000;text-align:center;padding:3px">$'.number_format($det->retail, 2).'</td><td style="border:1px solid #000;text-align:center;padding:3px">$'.number_format($det->original, 2).'</td><td style="border:1px solid #000;text-align:center;padding:3px">$'.number_format($det->cost, 2).'</td><td style="border:1px solid #000;text-align:center;padding:3px">'.$det->vendor.'</td></tr>';
                }
                $message .= '<tr><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">'.$det->fba_number.'/'.$det->shipment_number.'</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">'.$det->box_name.'</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">'.date('m/d/Y', strtotime($det->date)).'</th></tr>';                
                
            }
            $message .= '</tbody>';
            $message .= '</table>';
            $message .= '</div>';
            $message .= "</body></html>";
            // end body            
        } else {
            $details = $this->assignReportModel->getDetailBox($box);              
            // body
            if($user['under_comp'] == '2') {
                $message  = "<p>Hi ".$user['fullname'].",</p>";
                $message .= "<p>We want to inform you that we have finished packing your manifest, which will be sent to your Amazon Store immediately. Please find below the box details.</p>";
                $message .= "<html><body>";
                $message .= '<div style="margin: 0 100px 0 100px">';
                $message .= '<table style="font-family:arial,sans-serif;border-collapse:collapse;width:100%">';
                $message .= '<thead><tr><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">SKU/UPC</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">ITEM DESCRIPTION</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">CONDITION</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">ORIGINAL QTY</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">RETAIL VALUE</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">TOTAL ORIGINAL RETAIL</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">TOTAL CLIENT COST</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">VENDOR NAME</th></tr></thead>';
                $message .= '<tbody>';
                foreach($details->getResultObject() as $det) {
                    $message .= '<tr><td style="border:1px solid #000;text-align:center;padding:3px">'.$det->sku.'</td><td style="border:1px solid #000;text-align:center;padding:3px">'.$det->item_description.'</td><td style="border:1px solid #000;text-align:center;padding:3px">NEW</td><td style="border:1px solid #000;text-align:center;padding:3px">'.$det->qty.'</td><td style="border:1px solid #000;text-align:center;padding:3px">$'.number_format($det->retail, 2).'</td><td style="border:1px solid #000;text-align:center;padding:3px">$'.number_format($det->original, 2).'</td><td style="border:1px solid #000;text-align:center;padding:3px">$'.number_format($det->cost, 2).'</td><td style="border:1px solid #000;text-align:center;padding:3px">'.$det->vendor.'</td></tr>';
                }
                $message .= '<tr><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">'.$det->fba_number.'/'.$det->shipment_number.'</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">'.$det->box_name.'</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">'.date('m/d/Y', strtotime($det->date)).'</th></tr>';
                $message .= '</tbody>';
                $message .= '</table>';
                $message .= '</div>';
                $message .= '<div style="text-align: -webkit-center; margin-top: 10px">';
                $message .= '<table style="style="text-align: center; margin-top:20px">';
                $message .= '<tr>';
                $message .= '<th>';
                $message .= '<div style="text-align: center; ">';
                $message .= '<img src="https://swclient.site/assets/images/elite-banner.jpeg" style="max-width: 600px;" />';
                $message .= '</div>';
                $message .= '</th>';
                $message .= '<th style="padding-left: 50px;">';
                $message .= '<div style="text-align: center; padding-top: 10px;padding-left: 5px;"> <h1>Access Your manifest Online</h1><a href="https://apps.apple.com/id/app/smart-fba-client-portal/id1618568127" target="_blink"><img src="https://swclient.site/assets/images/appstore.png" style="max-width: 160px;"></a> <a href="https://play.google.com/store/apps/details?id=smartfba.app.smartfbaclientportal" target="_blink"><img src="https://swclient.site/assets/images/available-google-play.png" style="max-width: 172px; max-height: 53px"></a> </div>';
                $message .= '</th>';
                $message .= '</tr>';        
                $message .= '</table>';
                $message .= '</div>';
                $message .= "</body></html>";
            } else {
                $message  = "<p>Hi ".$user['fullname'].",</p>";
                $message .= "<p>We want to inform you that we have finished packing your manifest, which will be sent to your Amazon Store immediately. Please find below the box details.</p>";
                $message .= "<html><body>";
                $message .= '<div style="margin: 0 100px 0 100px">';
                $message .= '<table style="font-family:arial,sans-serif;border-collapse:collapse;width:100%">';
                $message .= '<thead><tr><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">SKU/UPC</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">ITEM DESCRIPTION</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">CONDITION</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">ORIGINAL QTY</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">RETAIL VALUE</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">TOTAL ORIGINAL RETAIL</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">TOTAL CLIENT COST</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">VENDOR NAME</th></tr></thead>';
                $message .= '<tbody>';
                foreach($details->getResultObject() as $det) {
                    $message .= '<tr><td style="border:1px solid #000;text-align:center;padding:3px">'.$det->sku.'</td><td style="border:1px solid #000;text-align:center;padding:3px">'.$det->item_description.'</td><td style="border:1px solid #000;text-align:center;padding:3px">NEW</td><td style="border:1px solid #000;text-align:center;padding:3px">'.$det->qty.'</td><td style="border:1px solid #000;text-align:center;padding:3px">$'.number_format($det->retail, 2).'</td><td style="border:1px solid #000;text-align:center;padding:3px">$'.number_format($det->original, 2).'</td><td style="border:1px solid #000;text-align:center;padding:3px">$'.number_format($det->cost, 2).'</td><td style="border:1px solid #000;text-align:center;padding:3px">'.$det->vendor.'</td></tr>';
                }
                $message .= '<tr><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">'.$det->fba_number.'/'.$det->shipment_number.'</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">'.$det->box_name.'</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">'.date('m/d/Y', strtotime($det->date)).'</th></tr>';
                $message .= '</tbody>';
                $message .= '</table>';
                $message .= '</div>';
                $message .= '<div style="text-align: -webkit-center; margin-top: 10px">';
                $message .= '<table style="style="text-align: center; margin-top:20px">';
                $message .= '<tr>';
                $message .= '<th>';
                $message .= '<div style="text-align: center; ">';
                $message .= '<img src="https://swclient.site/assets/images/banner.jpeg" style="max-width: 600px;" />';
                $message .= '</div>';
                $message .= '</th>';
                $message .= '<th style="padding-left: 50px;">';
                $message .= '<div style="text-align: center; padding-top: 10px;padding-left: 5px;"> <h1>Access Your manifest Online</h1><a href="https://apps.apple.com/id/app/smart-fba-client-portal/id1618568127" target="_blink"><img src="https://swclient.site/assets/images/appstore.png" style="max-width: 160px;"></a> <a href="https://play.google.com/store/apps/details?id=smartfba.app.smartfbaclientportal" target="_blink"><img src="https://swclient.site/assets/images/available-google-play.png" style="max-width: 172px; max-height: 53px"></a> </div>';
                $message .= '</th>';
                $message .= '</tr>';        
                $message .= '</table>';
                $message .= '</div>';
                $message .= "</body></html>";
            }
            // end body            
        }
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->IsHTML(true);
        $mail->Host = 'smtp.titan.email';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        
        if ($user['under_comp'] == '2') {
            $mail->Username = 'noreply.info@eliteapp.site';
            $mail->Password = 'eliteappinfo1';
            $mail->setFrom('noreply.info@eliteapp.site', 'Elite Automation');
        } else {
            $mail->Username = 'noreply.info@swclient.site';
            $mail->Password = 'swclientinfo1';
            $mail->setFrom('noreply.info@swclient.site', 'Smart FBA Inc');
        }
        $mail->addAddress($user['email'], $user['fullname'] .' - '.$user['company'].'');
        $mail->Subject = 'Box Details';
        $mail->Body = $message;
        if (empty($user['email']) || is_null(empty($user['email']))) {
            $mail->send();
        }
    }

    // status ready to assign
    public function getInvestmentClient()
    {
        $id = $this->request->getVar('id');
        $investments = $this->investmentModel->getInvestcmentClient($id);
        $option = array();
        if ($investments->getNumRows() > 0) {
            foreach ($investments->getResultArray() as $idx => $data) {
                $newDate = date("M-d-Y", strtotime($data['date']));
                if ($idx == 0) {
                    array_push($option, "<option selected value=" . $data['id'] . "  data-foo=" . $data['cost'] . "><b>" . strtoupper($newDate) . "<b/></option>");
                } else {
                    array_push($option, "<option value=" . $data['id'] . "  data-foo=" . number_format($data['cost'], 2) . "><b>" . strtoupper($newDate) . "<b/></option>");
                }
            }
        }
        echo json_encode($option);
    }

    public function getInvestmentClientAll()
    {
        $id = $this->request->getVar('id');
        $investments = $this->investmentModel->getInvestcmentClientAll($id);
        $option = array();
        if ($investments->getNumRows() > 0) {
            foreach ($investments->getResultArray() as $idx => $data) {
                $newDate = date("M-d-Y", strtotime($data['date']));
                if ($idx == 0) {
                    array_push($option, "<option selected value=" . $data['id'] . "  data-foo=" . $data['cost'] . ">" . strtoupper($newDate) . "<small> (Cost Left: $". number_format($data['cost'], 2) .")</small> </option>");
                } else {
                    array_push($option, "<option value=" . $data['id'] . "  data-foo=" . number_format($data['cost'], 2) . ">" . strtoupper($newDate) . "<small> (Cost Left: $". number_format($data['cost'], 2) .")</small></option>");
                }
            }
        }
        echo json_encode($option);
    }

    public function getCategory()
    {
        $id = $this->request->getVar('id');
        $getCategory = $this->assignReportModel->getCategoryPercentage($id);
        $category = array();
        $totalQty = 0;
        if ($getCategory->getNumRows() > 0) {
            foreach ($getCategory->getResultArray() as $qty) {
                $totalQty = $totalQty + $qty['qty'];
            }
            foreach ($getCategory->getResultArray() as $cat) {
                $percent = ($cat['qty'] / $totalQty) * 100;
                $temp = array(
                    'category' => ucfirst($cat['category']),                             
                    'percent' => number_format($percent, 1)
                );
                array_push($category, $temp);        
            }
        }

        echo json_encode($category);
    }

    public function assignBox()
    {
        $post = $this->request->getVar();
        // print_r($post);
        
        $boxId = trim(substr($post['box_id'], 4));
        $boxName = $post['box_name'];
        $vaId = $post['va_id'];
        $clientId = $post['client_id'];
        $valueBox = $post['value_box'];
        $valueBox = str_replace('$', '', trim($valueBox));
        $valueBox = str_replace(',', '', $valueBox);

        $currentCost = $post['current_cost'];
        $investmentId = $post['investment_id'];
        if ($clientId == "0") {
            return $this->db->query("DELETE FROM box_sum WHERE box_name='$boxName' ");
        }
        $checkBoxClient = $this->assignReportModel->checkBoxClient($boxName);
        $checkBoxDiffClient = $this->assignReportModel->checkBoxDiffClient($boxName, $clientId);
        $previousCost = $this->investmentModel->getPreviousCost($clientId, $investmentId);

        $status = 1;
        $costLeft = 0;
        // if (empty($checkBoxDiffClient)) {
        //     $this->db->query("DELETE FROM box_sum WHERE box_name='$boxName' ");
        // }
        if (!is_null($previousCost)) {
            $costLeft = $previousCost->cost_left - $valueBox;
        } else {
            $costLeft = $currentCost - $valueBox;
        }
        if (!empty($checkBoxClient)) {
            if ($costLeft <= -250) {
                $status = 0;
            } else {
                $this->db->query("UPDATE box_sum SET client_id='$clientId', cost_left='$costLeft', investment_id='$investmentId' WHERE box_name='$boxName' ");
            }
        } else {
            if ($costLeft <= -250) {
                $status = 0;
            } else {
                $this->db->query("INSERT INTO box_sum(box_name, cost_left, client_id, investment_id) VALUES('$boxName', '$costLeft', '$clientId', '$investmentId') ");
                $this->db->query("UPDATE assign_report_box SET va_id = $vaId WHERE box_name='$boxName' ");
            }
        }

        $feedback = array(
            'status' => $status,
            'cost_left' => $costLeft
        );
        echo json_encode($feedback);
    }

    public function assignmentReportProcess()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $getAllClient = $this->assignReportModel->getAllClient();
        $getAllAssignReportProcess = $this->assignReportModel->getAllAssignReportProcess($userId, $user['role']);
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();
        $data = [
            'tittle' => 'Assignment Reports | Report Management System',
            'menu' => 'APPROVAL BOX ASSIGNMENT',
            'user' => $user,
            'getAllClient' => $getAllClient,
            'getAllAssignReportProcess' => $getAllAssignReportProcess,
            'companySetting' => $companysetting
        ];
        return view('administrator/assignment_process', $data);
    }

    public function getBoxSummary()
    {
        $boxName = $this->request->getVar('box_name');
        $getBoxSum = $this->assignReportModel->getBoxSummary($boxName);
        $item = array();
        if ($getBoxSum->getNumRows() > 0) {
            foreach ($getBoxSum->getResultArray() as $row) {
                array_push($item, $row);
            }
        }
        echo json_encode($item);
    }

    public function getBoxSummaryHistory()
    {
        $boxName = $this->request->getVar('box_name');
        $getBoxSum = $this->assignReportModel->getBoxSummaryHistory($boxName);
        $item = array();
        if ($getBoxSum->getNumRows() > 0) {
            foreach ($getBoxSum->getResultArray() as $row) {
                array_push($item, $row);
            }
        }
        echo json_encode($item);
    }

    public function saveBoxDetails()
    {
        $post = $this->request->getVar();
        for ($i = 0; $i < count($post['item']); $i++) {
            $description = $post['item_description'][$i];
            $vendor = $post['vendor'][$i];
            $retail = str_replace('$', '', trim($post['retail'][$i]));
            $original = str_replace('$', '', trim($post['original'][$i]));
            $cost = str_replace('$', '', trim($post['cost'][$i]));
            $stat = $post['item_status'][$i];
            $check = $post['item_check'][$i];
            $note = $post['note'][$i];
            $id = $post['item'][$i];
            $this->db->query("UPDATE assign_report_details SET item_description=" . $this->db->escape($description) . ", vendor=" . $this->db->escape($vendor) . ", retail='$retail', original='$original', cost='$cost', item_status='$stat', item_check='$check', item_note=" . $this->db->escape($note) . " WHERE id='$id' ");
        }
        $box_note = $post['box_note'];
        $box_name = $post['box_name'];
        $this->db->query("UPDATE assign_report_box SET box_note=" . $this->db->escape($box_note) . " WHERE box_name='$box_name' ");
    }

    public function assignmentCompleted()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $assignCompleted = $this->assignReportModel->getAllAssignReportCompleted();
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();
        $data = [
            'tittle' => 'Assignment Reports | Report Management System',
            'menu' => 'APPROVAL BOX ASSIGNMENT',
            'user' => $user,
            'assignCompleted' => $assignCompleted,
            'companySetting' => $companysetting
        ];
        return view('administrator/assignment_completed', $data);
    }

    public function resetAssignment()
    {

        $this->db->query("DELETE FROM box_sum WHERE box_name IN (SELECT box_sum.box_name FROM assign_report_box WHERE confirmed = 0)");
        return redirect()->back()->with('reset', 'Assignment Successfully reseted!');
    }

    public function assignmentHistory()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $assignCompleted = $this->assignReportModel->getAllAssignReportCompleted();
        $getAllVA = $this->assignReportModel->getAllVA();
        $getPromolist = $this->db->query("SELECT * FROM promocode ORDER BY id DESC");
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();
        $data = [
            'tittle' => 'Assignment Reports | Report Management System',
            'menu' => 'APPROVAL BOX ASSIGNMENT',
            'user' => $user,
            'getAllVA' => $getAllVA,
            'assignCompleted' => $assignCompleted,
            'companySetting' => $companysetting,
            'promocode' => $getPromolist
        ];
        return view('administrator/assignment_history', $data);
    }

    public function boxHistorySave() {
        $post = $this->request->getVar();
        
        $box = $post['box_name'];
        $promocode = $post['promocode'];
        $promo = 1;
        $getBox = $this->db->query("SELECT * FROM assign_report_box WHERE box_name='$box' ")->getRow();        
        if (empty($promocode)) {
            if ($getBox->category == 'CLOTHES') {
                $promo = 4;
            } else {
                $promo = 3;
            }       
            $this->db->query("UPDATE assign_report_box SET promocode_id=null WHERE box_name='$box' ");             
        } else {
            $getPromo = $this->db->query("SELECT * FROM promocode WHERE id = '$promocode'")->getRow();            
            if ($getBox->category == 'CLOTHES') {
                $promo = $getPromo->clothes;
            } else {
                $promo = $getPromo->shoes;
            }
            $this->db->query("UPDATE assign_report_box SET promocode_id='$getPromo->id' WHERE box_name='$box' ");        
        }                
        
        if (count($post['item']) > 0) {
            foreach($post['item'] as $item) {
                $getItem = $this->db->query("SELECT * FROM reports WHERE sku = '$item' ")->getRow();
                $costLeft = $getItem->retail_value / $promo;                
                $this->db->query("UPDATE reports SET cost = '$costLeft' WHERE sku='$item' ");                                        
                $getItem = $this->db->query("SELECT * FROM assign_report_details WHERE sku = '$item' ")->getRow();                
                $costLeft = $getItem->retail / $promo;     
                $this->db->query("UPDATE assign_report_details SET cost = '$costLeft' WHERE sku='$item' ");
            }
        }
        return redirect()->back()->with('link', 'Link Successfully updated!'); 
    }

    public function exportBox($boxName) {
        $date = date('m-d-Y');
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getActiveSheet()->getStyle('A:A')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('B:B')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('C:C')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('D:D')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('E:E')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('F:F')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('G:G')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('H:H')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('I:I')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $spreadsheet->getActiveSheet()->getStyle('A1:I1')
            ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A1:I1')
            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A1:I1')
            ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A1:I1')
            ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
        $spreadsheet->getActiveSheet()->getStyle('A1:I1')
            ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle('A1:I1')
            ->getFill()->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);
        $spreadsheet->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('B:B')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
        $spreadsheet->getActiveSheet()->getStyle('F:F')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
        $spreadsheet->getActiveSheet()->getStyle('G:G')->getNumberFormat()
        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
        $spreadsheet->getActiveSheet()->getStyle('H:H')->getNumberFormat()
        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);


		$sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'FNSKU');
		$sheet->setCellValue('B1', 'UPC/SKU');
		$sheet->setCellValue('C1', 'ITEM DESCRIPTION');
		$sheet->setCellValue('D1', 'CONDITION');
		$sheet->setCellValue('E1', 'ORIGINAL QTY');
        $sheet->setCellValue('F1', 'RETAIL VALUE');
        $sheet->setCellValue('G1', 'ORIGINAL RETAIL');
        $sheet->setCellValue('H1', 'CLIENT COST');
        $sheet->setCellValue('I1', 'VENDOR');
        $no = 2;
		
        $items = $this->db->query("SELECT assign_report_box.date, assign_report_box.description, assign_report_box.box_name, assign_report_details.fnsku, assign_report_details.sku, assign_report_details.cond, assign_report_details.retail, assign_report_details.original, assign_report_details.item_status, assign_report_details.cost, assign_report_details.item_description, assign_report_details.vendor, assign_report_details.qty FROM assign_report_details JOIN assign_report_box ON assign_report_box.box_name = assign_report_details.box_name WHERE assign_report_box.box_name = '$boxName'");
        foreach($items->getResultObject() as $row) { 
            if ($row->item_description == 'ITEM NOT FOUND' || $row->item_status == '0') {
                continue;
            }
            if ($row->fnsku == null || $row->fnsku == 'null') {
                $row->fnsku = "";
            }
            $sheet->setCellValue('A' . $no, $row->fnsku);               
            $sheet->setCellValue('B' . $no, $row->sku);
            $sheet->setCellValue('C' . $no, $row->item_description);                
            $sheet->setCellValue('D' . $no, $row->cond);
            $sheet->setCellValue('E' . $no, $row->qty);
            $sheet->setCellValue('F' . $no, $row->retail);
            $sheet->setCellValue('G' . $no, $row->original);
            $sheet->setCellValue('H' . $no, $row->cost);
            $sheet->setCellValue('I' . $no, $row->vendor);
            

            $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
            ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
                ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
                ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
                ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                
            $no++;
        }
        $sheet->setCellValue('B' . $no, $row->description);                
        $sheet->setCellValue('C' . $no, $row->box_name);
        $sheet->setCellValue('I' . $no, date('m/d/Y', strtotime($row->date)));                            
        // styling
        $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
        ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
            ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
            ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
            ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
        ->getFill()->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);
        $no++;

        $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
        ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
            ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
            ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
            ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
        ->getFill()->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);
        $no++;

        $col = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
        for ($j = 0; $j < count($col); $j++) {
            $spreadsheet->getActiveSheet()->getStyle($col[$j].'3:'.$col[$j].''.$no)
                ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle($col[$j].'3:'.$col[$j].''.$no)
                ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle($col[$j].'3:'.$col[$j].''.$no)
                ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle($col[$j].'3:'.$col[$j].''.$no)
                ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }
        $fileName = "Box {$row->description} - {$date}.xlsx";  
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

    public function updateLink()
    {
        $post = $this->request->getVar();
        $id = $post['file_id'];
        $this->db->query("UPDATE log_files SET link = " . $this->db->escape($post['link']) . " WHERE id='$id' ");
        return redirect()->back()->with('link', 'Link Successfully updated!');
    }

    public function completedInvestments()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $completedInvestments = $this->investmentModel->completedInvestments();
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();
        $data = [
            'tittle' => 'Completed Assignments | Report Management System',
            'menu' => 'COMPLETED ASSIGNMENTS',
            'user' => $user,
            'completedInvestments' => $completedInvestments,
            'companySetting' => $companysetting
        ];
        return view('administrator/completed_investments', $data);
    }

    public function refreshDashboard()
    {
        $totalInvest = $this->investmentModel->totalClientInvestment();
        $totalUnit = $this->reportModel->totalUnit();
        $unit = ($totalUnit->total_unit > 0) ? $totalUnit->total_unit : "0";
        $totalRetail = $this->reportModel->totalRetail();
        $totalCostLeft = $this->reportModel->totalCostLeft();
        $totalFulfilled = $this->reportModel->totalFulfilled();
        $avgRetail = ($totalUnit->total_unit != 0) ? number_format(($totalFulfilled->total_fulfilled / $totalUnit->total_unit), 2) : "0";
        $avgClientCost = ($totalUnit->total_unit != 0) ? number_format(($totalRetail->total_retail / $totalUnit->total_unit), 2) : "0";
        $summary = array(
            'total_client_cost' => number_format($totalInvest->total_client_cost, 2),
            'total_cost_left' => number_format($totalCostLeft, 2),
            'total_unit' => $unit,
            'total_original' => number_format($totalRetail->total_retail, 2),
            'total_fulfilled' => number_format($totalFulfilled->total_fulfilled, 2),
            'avg_retail' => $avgRetail,
            'avg_client_cost' => $avgClientCost
        );
        echo json_encode($summary);
    }

    public function getPiechart()
    {
        $totalInvest = $this->investmentModel->totalClientInvestment();
        $totalFulfilled = $this->reportModel->totalFulfilled();
        $summary = array(
            'total_client_cost' => $totalInvest->total_client_cost, 2,
            'total_fulfilled' => $totalFulfilled->total_fulfilled
        );
        echo json_encode($summary);
    }


    public function getPLClient()
    {
        $id = $this->request->getVar('log_id');
        $file = $this->reportModel->getPLClient($id);
        echo json_encode($file);
    }


    public function reuploadPL()
    {
        
        $client = $this->request->getVar('client');
        $link = $this->request->getVar('link');
        $log_id = $this->request->getVar('log_id');
        $chart = $this->request->getFile('chart');
        $types = $this->request->getVar('type');
        $ext = $chart->getClientExtension();
        if (empty($ext)) {
            $this->db->query("UPDATE log_files SET link='$link', client_id ='$client' WHERE id='$log_id'");
        } else {
            $this->db->query("DELETE FROM chart_pl WHERE client_id='$client'");
            if ($ext == 'xls') {
                $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $render->load($chart);
            $data = $spreadsheet->getActiveSheet()->toArray();
            if ($types == 'yes') {    
                $chartTitle = array();
                $monthData = array();
                $type = array();
                foreach ($data as $idx => $row) {
                    if (!empty($row[0])) {
                        array_push($chartTitle, $row[0]);
                    } else {
                        if (!empty($row[2]) || !empty($row[3]) || !empty($row[4]) || !empty($row[5]) || !empty($row[6]) || !empty($row[7]) || !empty($row[8] || !empty($row[9]) || !empty($row[10]) || !empty($row[11]) || !empty($row[12]) || !empty($row[13]) || !empty($row[14]) || !empty($row[15]) )) {
                            $month = array();
                            if (strpos($row[2], '%') !== false || strpos($row[3], '%') !== false || strpos($row[4], '%') !== false || strpos($row[5], '%') !== false || strpos($row[6], '%') !== false || strpos($row[7], '%') !== false || strpos($row[8], '%') !== false || strpos($row[9], '%') !== false || strpos($row[10], '%') !== false || strpos($row[11], '%') !== false || strpos($row[12], '%') !== false || strpos($row[13], '%') !== false) {
                                for ($i = 2; $i < 17; $i++) {
                                    if ($i != 3) {
                                        $temp = str_replace('%', '', $row[$i]);
                                        $temp = str_replace(',', '', $temp);
                                        if (strpos($temp, '(') !== false) {
                                            $temp = str_replace('(', '', $temp);
                                            $temp = str_replace(')', '', $temp);
                                            $temp = -1 * abs($temp);
                                        }
                                        array_push($month, $temp);
                                    }
                                }

                                array_push($type, 'percentage');
                            } elseif (strpos($row[2], '$') !== false || strpos($row[3], '$') !== false || strpos($row[4], '$') !== false || strpos($row[5], '$') !== false || strpos($row[6], '$') !== false || strpos($row[7], '$') !== false || strpos($row[8], '$') !== false || strpos($row[9], '$') !== false || strpos($row[10], '$') !== false || strpos($row[11], '$') !== false || strpos($row[12], '$') !== false || strpos($row[13], '$') !== false) {
                                for ($i = 2; $i < 17; $i++) {
                                    if ($i != 3) {
                                        $temp = str_replace('$', '', $row[$i]);
                                        $temp = str_replace(',', '', $temp);
                                        if (strpos($temp, '(') !== false) {
                                            $temp = str_replace('(', '', $temp);
                                            $temp = str_replace(')', '', $temp);
                                            $temp = -1 * abs($temp);
                                        }
                                        array_push($month, $temp);
                                    }
                                }

                                array_push($type, 'currency');
                            } else {
                                for ($i = 2; $i < 17; $i++) {
                                    if ($i != 3) {
                                        $temp = $row[$i];
                                        if (strpos($temp, '(') !== false) {
                                            $temp = str_replace('(', '', $temp);
                                            $temp = str_replace(')', '', $temp);
                                            $temp = -1 * abs($temp);
                                        }

                                        if (strpos($temp, ',') !== false) {
                                            $temp = str_replace(',', '', $temp);                                      
                                        }
                                        array_push($month, $temp);
                                    }
                                }
                                array_push($type, 'num');
                            }
                            array_push($monthData, $month);                  
                        }
                    }
                }

                
                for ($i = 0; $i < count($chartTitle); $i++) {
                    $this->reportModel->savePLReport($chartTitle[$i], $monthData[$i], $type[$i], $client);
                } 
            } else {
                $chartTitle = array();
                $monthData = array();
                $type = array();
                foreach ($data as $idx => $row) {
                    if (!empty($row[0])) {
                        array_push($chartTitle, $row[0]);
                    } else {
                        if (!empty($row[2]) || !empty($row[3]) || !empty($row[4]) || !empty($row[5]) || !empty($row[6]) || !empty($row[7]) || !empty($row[8] || !empty($row[9]) || !empty($row[10]) || !empty($row[11]) || !empty($row[12]) || !empty($row[13]))) {
                            $month = array();
                            if (strpos($row[2], '%') !== false || strpos($row[3], '%') !== false || strpos($row[4], '%') !== false || strpos($row[5], '%') !== false || strpos($row[6], '%') !== false || strpos($row[7], '%') !== false || strpos($row[8], '%') !== false || strpos($row[9], '%') !== false || strpos($row[10], '%') !== false || strpos($row[11], '%') !== false || strpos($row[12], '%') !== false || strpos($row[13], '%') !== false) {
                                for ($i = 4; $i < 17; $i++) {
                                    $temp = str_replace('%', '', $row[$i]);
                                    $temp = str_replace(',', '', $temp);
                                    if (strpos($temp, '(') !== false) {
                                        $temp = str_replace('(', '', $temp);
                                        $temp = str_replace(')', '', $temp);
                                        $temp = -1 * abs($temp);
                                    }
                                    array_push($month, $temp);
                                }

                                array_push($type, 'percentage');
                            } elseif (strpos($row[2], '$') !== false || strpos($row[3], '$') !== false || strpos($row[4], '$') !== false || strpos($row[5], '$') !== false || strpos($row[6], '$') !== false || strpos($row[7], '$') !== false || strpos($row[8], '$') !== false || strpos($row[9], '$') !== false || strpos($row[10], '$') !== false || strpos($row[11], '$') !== false || strpos($row[12], '$') !== false || strpos($row[13], '$') !== false) {
                                for ($i = 4; $i < 17; $i++) {
                                    $temp = str_replace('$', '', $row[$i]);
                                    $temp = str_replace(',', '', $temp);
                                    if (strpos($temp, '(') !== false) {
                                        $temp = str_replace('(', '', $temp);
                                        $temp = str_replace(')', '', $temp);
                                        $temp = -1 * abs($temp);
                                    }
                                    array_push($month, $temp);
                                }

                                array_push($type, 'currency');
                            } else {
                                for ($i = 4; $i < 17; $i++) {
                                    $temp = $row[$i];
                                    if (strpos($temp, '(') !== false) {
                                        $temp = str_replace('(', '', $temp);
                                        $temp = str_replace(')', '', $temp);
                                        $temp = -1 * abs($temp);
                                    }
                                    if (strpos($temp, ',') !== false) {
                                        $temp = str_replace(',', '', $temp);                                      
                                    }
                                    array_push($month, $temp);
                                }
                                array_push($type, 'num');
                            }
                            array_push($monthData, $month);    
                                    
                        }
                    }
                }
                for ($i = 0; $i < count($chartTitle); $i++) {
                    $this->reportModel->savePLReportExclude($chartTitle[$i], $monthData[$i], $type[$i], $client);
                }
            }
            $fileName = $chart->getName();
            $this->db->query("UPDATE log_files SET file=" . $this->db->escape($fileName) . " ,link='$link', client_id ='$client' WHERE id='$log_id'");
        }

        return redirect()->back()->with('success', 'Report Successfully Uploaded!');
    }

    public function bulkUpload() {
        $bulkedFile = $this->request->getFile('bulk_file');
        $ext = $bulkedFile->getClientExtension();
        if ($ext == 'xls') {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $spreadsheet = $render->load($bulkedFile);
        $data = $spreadsheet->getActiveSheet()->toArray(null, true,false,false);    
        $client = null;
        $clientName = "";
        $type = 'num';
        foreach ($data as $idx => $row) {
            if ($idx != 0) {
                $chartTitle = $row[4];
                $getClient = $this->db->query("SELECT id, fullname FROM users WHERE fullname LIKE ". $this->db->escape($row[2]) ." OR company LIKE ". $this->db->escape($row[2]) ." LIMIT 1");
                if ($getClient->getNumRows() > 0) {
                    $client = $getClient->getRow();
                    $isActive = $this->db->query("SELECT * FROM chart_pl WHERE client_id='$client->id' AND chart='$row[4]' ");
                    if ($isActive->getNumRows() > 0) {
                        $isActive = $isActive->getRow();
                        if ($isActive->client_id == $client->id) {
                            $this->db->query("DELETE FROM chart_pl WHERE client_id='$client->id' ");
                        }
                    }

                    if (strcasecmp($row[4], "Gross Profit Margin") == 0 || strcasecmp($row[4], "Fees and Subtractions Rate") == 0 || strcasecmp($row[4], "Net Profit Margin") == 0) {                                
                        $type = 'percentage';
                    } elseif (strcasecmp($row[4], "Net Sales") == 0 || strcasecmp($row[4], "COGS") == 0 || strcasecmp($row[4], "Gross Profit") == 0 || strcasecmp($row[4], "Fees and Subtractions") == 0 || strcasecmp($row[4], "Net Profit") == 0) {                                
                        $type = 'currency';
                    } else {
                        $type = 'num';
                        $row[5] = str_replace(',', '', $row[5]);  
                        $row[6] = str_replace(',', '', $row[6]);  
                        $row[7] = str_replace(',', '', $row[7]);  
                        $row[8] = str_replace(',', '', $row[8]);  
                        $row[9] = str_replace(',', '', $row[9]);  
                        $row[10] = str_replace(',', '', $row[10]);  
                        $row[11] = str_replace(',', '', $row[11]);  
                        $row[12] = str_replace(',', '', $row[12]);  
                        $row[13] = str_replace(',', '', $row[13]);  
                        $row[14] = str_replace(',', '', $row[14]);  
                        $row[15] = str_replace(',', '', $row[15]);  
                        $row[16] = str_replace(',', '', $row[16]);  
                        $row[17] = str_replace(',', '', $row[17]);  
                        $row[18] = str_replace(',', '', $row[18]);  
                    } 
                    $this->db->query("INSERT INTO chart_pl(`chart`, `last_year`, `jan`, `feb`, `mar`, `apr`, `may`, `jun`, `jul`, `aug`, `sep`, `oct`, `nov`, `dec`, `avg`, `type`, `client_id`) 
                    VALUES('$chartTitle', '$row[5]', '$row[6]', '$row[7]', '$row[8]', '$row[9]', '$row[10]', '$row[11]', '$row[12]', '$row[13]', '$row[14]', '$row[15]', '$row[16]', '$row[17]', '$row[18]', '$type', '$client->id') ");                                    
                    if ($clientName != $row[2]) {
                        $this->db->query("INSERT INTO log_files(date, file, link, client_id) VALUES(NOW(), " . $this->db->escape($row[0]) . "," . $this->db->escape($row[1]) . " , '$client->id') ");
                        $clientName = $row[2];
                    }       
                }   
            }
        }
        $fileName = $bulkedFile->getName();
        $this->db->query("INSERT into log_files(date, file, link, client_id) VALUES(NOW(), " . $this->db->escape($fileName) . ", 'BULK', '') ");
        return redirect()->back()->with('success', 'Report Successfully Uploaded!');
    }
        
    public function getTopInvestment()
    {
        $topInvestment = $this->investmentModel->getTopInvestment();
        echo json_encode($topInvestment->getResultArray());
    }

    public function getContinuityInvestment()
    {
        $getUser = $this->investmentModel->continuityInvestment();
        echo json_encode($getUser->getResultArray());
    }

    public function getTopReadyToAssign()
    {
        $getUser = $this->investmentModel->getTopInvestmentAssign();
        echo json_encode($getUser->getResultArray());
    }

    public function getTotalItemByCat()
    {
        $getCat = $this->assignReportModel->getTotalItemByCat();
        echo json_encode($getCat->getResultArray());
    }

    public function savePeriodSetting()
    {
        $post = $this->request->getVar();
        if (!empty($post['week1-start'])) {
            $start = $post['week1-start'];
            $end = $post['week1-end'];
            $this->db->query("UPDATE weeks SET date1='$start', date2='$end' WHERE week='1' ");
        }
        if (!empty($post['week2-start'])) {
            $start = $post['week2-start'];
            $end = $post['week2-end'];
            $this->db->query("UPDATE weeks SET date1='$start', date2='$end' WHERE week='2' ");
        }
        if (!empty($post['week3-start'])) {
            $start = $post['week3-start'];
            $end = $post['week3-end'];
            $this->db->query("UPDATE weeks SET date1='$start', date2='$end' WHERE week='3' ");
        }
        if (!empty($post['week4-start'])) {
            $start = $post['week4-start'];
            $end = $post['week4-end'];
            $this->db->query("UPDATE weeks SET date1='$start', date2='$end' WHERE week='4' ");
        }
        if (!empty($post['week5-start'])) {
            $start = $post['week5-start'];
            $end = $post['week5-end'];
            $this->db->query("UPDATE weeks SET date1='$start', date2='$end' WHERE week='5' ");
        }
        return redirect()->back()->with('success', 'Report Successfully Uploaded!');
    }

    public function brandApproval()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $getUsers = $this->userModel->where('role', 'client')->orderBy('fullname', 'ASC')->get();
        $getBrands = $this->categoryModel->getBrands();
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();
        $data = [
            'tittle' => 'Brand Approval | Report Management System',
            'menu' => 'Brand Approval',
            'user' => $user,
            'brands' => $getBrands,
            'users' => $getUsers,
            'companySetting' => $companysetting
        ];
        return view('administrator/brand_approval', $data);
    }

    public function getBrandClient()
    {
        $id = $this->request->getVar('userid');
        $brands = $this->categoryModel->getBrands();
        $selectedBrand = $this->categoryModel->selectedBrand($id);
        $temp_brand = array();
        $check = 0;
        foreach ($brands->getResultArray() as $brand) {
            foreach ($selectedBrand->getResultArray() as $selected) {
                if ($brand['id'] == $selected['id']) {
                    $temp = array(
                        'id' => $brand['id'],
                        'brand_name' => $brand['brand_name'],
                        'checked' => 1
                    );
                    $check = 1;
                    array_push($temp_brand, $temp);
                }
            }
            if ($check == 0) {
                $temp = array(
                    'id' => $brand['id'],
                    'brand_name' => $brand['brand_name'],
                    'checked' => 0
                );
                array_push($temp_brand, $temp);
            }
            $check = 0;
        }

        echo json_encode($temp_brand);
    }

    public function getClientBrand()
    {
        $id = $this->request->getVar('brandid');
        $users = $this->userModel->getAllUser();
        $selectedBrand = $this->categoryModel->selectedClient($id);
        $temp_brand = array();
        $check = 0;
        foreach ($users->getResultArray() as $user) {
            foreach ($selectedBrand->getResultArray() as $selected) {
                if ($user['id'] == $selected['id']) {
                    $temp = array(
                        'id' => $user['id'],
                        'fullname' => $user['fullname'],
                        'company' => $user['company'],
                        'checked' => 1
                    );
                    $check = 1;
                    array_push($temp_brand, $temp);
                }
            }

            $check = 0;
        }

        echo json_encode($temp_brand);
    }

    public function getClientByDescBrand()
    {
        $desc = $this->request->getVar('description');
        if (strcasecmp($desc, "Unrestricted") == 0) {
            $getClient = $this->db->query("SELECT users.id, users.fullname, users.company FROM users JOIN investments ON users.id = investments.client_id WHERE role = 'client' AND status='assign' ");
        } else {
            $getBrandId = $this->db->query('SELECT id FROM brands WHERE brand_name LIKE ' . $this->db->escape($desc) . ' ')->getRow();
            $getClient = $this->categoryModel->selectedClient($getBrandId->id);
        }
        
        $temp_client = array();
        foreach ($getClient->getResultArray() as $selected) {
            $temp = array(
                'id' => $selected['id'],
                'fullname' => $selected['fullname'],
                'company' => $selected['company'],
            );
            array_push($temp_client, $temp);
        }

        echo json_encode($temp_client);
    }

    public function saveClientBrand()
    {
        $post = $this->request->getVar();
        $userid = $post['user'];
        if ($userid == 0) {
            return "0";
        }
        $brands = array();
        for ($i = 0; $i < count($post['brand']); $i++) {
            array_push($brands, $post['brand'][$i]);
        }
        $brands = implode(", ", $brands);
        $brands = str_replace(' ', '', $brands);
        $this->db->query("UPDATE users SET brand_approval='" . trim($brands) . "' WHERE id='$userid' ");
    }

    public function addBrand()
    {
        $brand = $this->request->getVar('brand');
        $this->db->query("INSERT INTO brands(brand_name) VALUES (" . $this->db->escape($brand) . ") ");
    }

    public function rollbackAssignment()
    {
        $boxName = $this->request->getVar('box_name');
        $this->db->query("UPDATE assign_report_box SET status='waiting', confirmed='0', fba_number=NULL, shipment_number=NULL, client_id=NULL, report_id=NULL, va_id=NULL WHERE box_name='$boxName' ");
        $this->db->query("DELETE FROM box_sum WHERE box_name='$boxName'");
    }

    public function uploadBrand()
    {
        $brand = $this->request->getFile('file');
        $ext = $brand->getClientExtension();
        if (!empty($ext)) {
            if ($ext == 'xls') {
                $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $render->load($brand);
            $data = $spreadsheet->getActiveSheet()->toArray(null, true,false,false);
            $idx = 1;
            foreach ($data as $row) {
                if ($idx != 1) {
                    if (!empty($row[1])) {
                        $brandName = trim($row[1]);
                        $this->db->query("INSERT IGNORE INTO brands(brand_name) VALUES (" . $this->db->escape($brandName) . ") ");
                    }
                }
                $idx++;
            }
        }
        return redirect()->back()->with('success', 'Report Successfully Uploaded!');
    }

    public function uploadBrandPerStore()
    {
        $brand = $this->request->getFile('store');
        $data = "";
        $ext = $brand->getClientExtension();
        if (!empty($ext)) {
            if ($ext == 'xls') {
                $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $render->load($brand);
            $tempBrandId = array();
            $tempName = "";
            $data = $spreadsheet->getActiveSheet()->toArray(null, true,false,false);
            foreach ($data as $idx => $row) {
                if ($idx > 1) {
                    if (!empty($row[1])) {
                        if ($tempName != $row[1]) {
                            if ($tempName != "") {
                                $brands = implode(", ", $tempBrandId);
                                $brands = str_replace(' ', '', $brands);
                                $this->db->query("UPDATE users SET brand_approval='" . trim($brands) . "' WHERE fullname=" . $this->db->escape($tempName) . "");
                                $tempBrandId = array();
                            }
                            $tempName = trim($row[1]);
                        }
                        $brandName = trim($row[2]);
                        $getBrandId = $this->db->query('SELECT id FROM brands WHERE brand_name LIKE ' . $this->db->escape($brandName) . ' ')->getRow();

                        if (!empty($getBrandId)) {
                            array_push($tempBrandId, $getBrandId->id);
                        }
                    }
                }
            }
        }
        return redirect()->back()->with('success', 'Report Successfully Uploaded!');
    }

    public function getPLGraph() {
        $id = $this->request->getVar('log_id');
        echo json_encode($id);
    }

    public function master($id = null) {
        $dateId = $this->request->getVar('investdate');
        $client = $this->request->getVar('client');
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return redirect()->to(base_url('/login'));
        }
        $userId = 9;
        
        if (!is_null($id)) {
            $userId = $id;
        }

        if (!is_null($client) || !empty($client)) {
            $userId = $client;
        }
        $user = $this->userModel->find($userId);
        $investId = $this->investmentModel->getInvestmentId($userId);
        $getAllClient = $this->db->query("SELECT * FROM users WHERE role = 'client' ");
       
        
        if ($dateId == null) {
            if ($user['role'] == 'client' and $investId == null) {
                $data = [
                    'tittle' => 'Dashboard | Report Management System',
                    'menu' => 'Dashboard',
                    'user' => $user,
                    'clients' => $getAllClient,
                    'clientSelect' => $userId
                ];

                return view('administrator/master/dashboard2', $data);
            }

            $lastInvestment = $this->investmentModel->getLastDateOfInvestment($userId);
            $category = $this->categoryModel->getCategory($investId);
            $totalInvest = $this->investmentModel->totalClientInvestment($investId);
            $totalUnit = $this->reportModel->totalUnit($investId);
            $totalRetail = $this->reportModel->totalRetail($investId);
            $totalCostLeft = $this->reportModel->totalCostLeft($investId);
            $totalFulfilled = $this->reportModel->totalFulfilled($investId);
            $getAllReportClient = $this->reportModel->getAllReportClient($investId);
            $investmentDate = $this->investmentModel->investmentDate($user['id']);
            $getVendorName = $this->reportModel->getVendorName($investId);
        } else {
            $lastInvestment = $this->investmentModel->getWhere(['id' => $dateId])->getLastRow();
            $category = $this->categoryModel->getCategory($dateId);
            $totalInvest = $this->investmentModel->totalClientInvestment($dateId);
            $totalUnit = $this->reportModel->totalUnit($dateId);
            $totalRetail = $this->reportModel->totalRetail($dateId);
            $totalCostLeft = $this->reportModel->totalCostLeft($dateId);
            $totalFulfilled = $this->reportModel->totalFulfilled($dateId);
            $getAllReportClient = $this->reportModel->getAllReportClient($dateId);
            $investmentDate = $this->investmentModel->investmentDate($user['id']);
            $getVendorName = $this->reportModel->getVendorName($dateId);
        }   

        
        
        $data = [
            'tittle' => 'Dashboard | Report Management System',
            'menu' => 'Dashboard',
            'user' => $user,
            'totalInvest' => $totalInvest,
            'totalUnit' => $totalUnit,
            'totalRetail' => $totalRetail,
            'totalCostLeft' => $totalCostLeft,
            'totalFulfilled' => $totalFulfilled,
            'getAllReports' => $getAllReportClient,
            'investDate' => $investmentDate,
            'lastInvestment' => $lastInvestment,
            'getVendorName' => $getVendorName,
            'clients' => $getAllClient,
            'clientSelect' => $userId
    
        ];
        $page = 'manifest';
        return view('administrator/master/dashboard', $data);
    }

    public function masterPLReport($id)
    {
        $userId = $id;
        if (is_null($userId)) {
            return redirect()->to(base_url('/login'));
        }
        $user = $this->userModel->find($userId);
        $plReport = $this->reportModel->showPLReport($userId);
        $downloadPLReport = $this->reportModel->downloadPLReport($userId);
        $getAllClient = $this->db->query("SELECT * FROM users WHERE role = 'client' ");
        $data = [
            'tittle' => "P&L Report | Report Management System",
            'menu' => "P&L Report",
            'user' => $user,
            'plReport' => $plReport,
            'file' => $downloadPLReport,
            'clients' => $getAllClient,
            'clientSelect' => $userId
        ];
        $page = 'p&l';
        $this->userModel->logActivity($userId, $page);
        return view('administrator/master/pl_report', $data);
    }
    
    public function getGeneratePL() {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $totalClientUploaded = $this->reportModel->totalClientUploaded();
        $totalReport = $this->reportModel->totalReport();
        $getAllFiles = $this->reportModel->getPLReport();
        $getAllClient = $this->reportModel->getAllClient();
        $getBulk = $this->reportModel->getBulkUploaded();
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();

        $data = [
            'tittle' => 'Generate P&L Report | Report Management System',
            'menu' => 'Generate P&L Report',
            'totalClientUploaded' => $totalClientUploaded,
            'totalReport' => $totalReport,
            'getAllFiles' => $getAllFiles,
            'getAllClient' => $getAllClient,
            'getBulk' => $getBulk,
            'user' => $user,
            'companySetting' => $companysetting
        ];
        return view('administrator/generate-pl', $data);
    }

    public function uploadGeneratePL() {
        $client = $this->request->getVar('client');
        $chart = $this->request->getFileMultiple('chart');
        $daterange = $this->request->getVar('date');
        $daterange = explode('-', $daterange);
        $date1 = date('Y-m-d', strtotime(trim($daterange[0])));
        $date2 = date('Y-m-d', strtotime(trim($daterange[1])));
        
        $this->db->query("INSERT INTO transactions_master(user_id) VALUES ('$client') ");
        $id = $this->db->insertID();


        for ($k=0; $k < count($chart); $k++) {
            $ext = $chart[$k]->getClientExtension();
            if ($ext == 'xls') {
                $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            ini_set('max_execution_time', '300');
        
            $spreadsheet = $render->load($chart[$k]);
            $data = $spreadsheet->getActiveSheet()->toArray();
            $orderData = array();           
            foreach ($data as $idx => $row) {
                if ($idx > 0) {
                    $newDate = date('Y-m-d', strtotime($row[16]));
                    if (($newDate >= $date1) && ($newDate <= $date2)) {
                        $temp = array(
                            'settlement-id' => $row[0],
                            'settlement-start-date' => $row[1],
                            'settlement-end-date' => $row[2],
                            'deposit-date' => $row[3],
                            'total-amount' => $row[4],
                            'currency' => $row[5],
                            'transaction-type' => $row[6],
                            'order-id' => $row[7],
                            'merchant-order-id' => $row[8],
                            'adjustment-id' => $row[9],
                            'shipment-id' => $row[10],
                            'marketplace-name' => $row[11],
                            'amount-type' => $row[12],
                            'amount-description' => $row[13],
                            'amount' => $row[14],
                            'fulfillment-id' => $row[15],
                            'posted-date' => $newDate,
                            'posted-date-time' => $row[17],
                            'order-item-code' => $row[18],
                            'merchant-order-item-id' => $row[19],
                            'merchant-adjustment-item-id' => $row[20],
                            'sku' => $row[21],
                            'quantity-purchased' => $row[22],
                            'promotion-id' => $row[23],
                            'transaction-master-id' => $id
                        );
                        array_push($orderData, $temp);    
                    }            
                }        
            } 

            if (count($orderData)  > 0) {
                $this->transactionModel->insertBatch($orderData);
            }
            
        }
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);

        $transaction = $this->transactionModel->getTransactionUploaded($id, $date1, $date2, $client);
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();

        $skuNotFound = $this->db->query("SELECT transactions.sku, repo FROM transactions LEFT JOIN (SELECT reports.sku as repo FROM reports WHERE client_id = '$client') as r ON transactions.sku = r.repo WHERE `transaction-master-id` = '$id' AND transactions.sku IS NOT NULL AND repo IS NULL GROUP BY transactions.sku");
    
        $getClient = $this->userModel->find($client);
        $getMonth = date('m', strtotime($date1));
        switch($getMonth) {
            case '01' :
                $month = 'jan';
                break;
            case '02' :
                $month = 'feb';
                break;
            case '03' :
                $month = 'mar';
                break;
            case '04' :
                $month = 'apr';
                break;
            case '05' :
                $month = 'may';
                break;
            case '06' :
                $month = 'jun';
                break;
            case '07' :
                $month = 'jul';
                break;
            case '08' :
                $month = 'aug';
                break;
            case '09' :
                $month = 'sep';
                break;
            case '10' :
                $month = 'oct';
                break;
            case '11' :
                $month = 'nov';
                break;
            case '12' :
                $month = 'dec';
                break;
        }
        
        //sales by brand
        $getInvestment = $this->db->query("SELECT investments.*, MONTH(investments.date) FROM `investments` WHERE client_id = '$client' AND MONTH(investments.date) = '$getMonth' ");
        $getInvestment = $getInvestment->getFirstRow();
        $investment = $getInvestment->id;
        $salesByBrand = $this->db->query("SELECT ord.brand, ordered, IFNULL(refund, '-') as refund, (ordered - IFNULL(refund, 0)) as sales  FROM transactions_master JOIN (SELECT `transaction-master-id`, TRIM(rep.vendor) as brand, COUNT(rep.vendor) as ordered FROM `transactions` JOIN transactions_master ON transactions_master.id = `transaction-master-id` JOIN (SELECT reports.sku, TRIM(reports.vendor) as vendor FROM reports GROUP BY reports.sku) as rep ON rep.sku = transactions.sku WHERE `transaction-master-id` = '$id' AND `transaction-type`='Order' GROUP BY rep.vendor) ord ON `ord`.`transaction-master-id` = transactions_master.id LEFT JOIN (SELECT `transaction-master-id`, TRIM(rep.vendor) as brand, COUNT(rep.vendor) as refund FROM `transactions` JOIN transactions_master ON transactions_master.id = `transaction-master-id` JOIN (SELECT reports.sku, TRIM(reports.vendor) as vendor FROM reports GROUP BY reports.sku) as rep ON rep.sku = transactions.sku WHERE `transaction-master-id` = '$id' AND `transaction-type`='Refund' GROUP BY rep.vendor) ref ON `ord`.`brand` = `ref`.`brand` GROUP BY ord.brand ORDER BY `sales`  DESC");
        $profitByBrand = $this->db->query("SELECT ord.brand, ordered, IFNULL(refund, '-') as refund, (ordered - IFNULL(refund, 0)) as sales, totalamount, cost, (((ordered - IFNULL(refund, 0)) * totalamount) - cost) as profit FROM transactions_master JOIN (SELECT `transaction-master-id`, TRIM(rep.vendor) as brand, COUNT(rep.vendor) as ordered, SUM(transactions.amount) as totalamount, SUM(cost) as cost FROM `transactions` JOIN transactions_master ON transactions_master.id = `transaction-master-id` JOIN (SELECT reports.sku, TRIM(reports.vendor) as vendor, cost FROM reports GROUP BY reports.sku) as rep ON rep.sku = transactions.sku WHERE `transaction-master-id` = '$id' AND `transaction-type`='Order' GROUP BY rep.vendor) ord ON `ord`.`transaction-master-id` = transactions_master.id LEFT JOIN (SELECT `transaction-master-id`, TRIM(rep.vendor) as brand, COUNT(rep.vendor) as refund FROM `transactions` JOIN transactions_master ON transactions_master.id = `transaction-master-id` JOIN (SELECT reports.sku, TRIM(reports.vendor) as vendor FROM reports GROUP BY reports.sku) as rep ON rep.sku = transactions.sku WHERE `transaction-master-id` = '$id' AND `transaction-type`='Refund' GROUP BY rep.vendor) ref ON `ord`.`brand` = `ref`.`brand` GROUP BY ord.brand ORDER BY `sales` DESC");
        
        $data = [
            'tittle' => 'Generate P&L Report | Report Management System',
            'menu' => 'Generate P&L Report',         
            'user' => $user,
            'transactions' => $transaction,
            'companySetting' => $companysetting,
            'client' => $getClient,
            'daterange' => $this->request->getVar('date'),
            'month' => $month,
            'date1' => $date1,
            'date2' => $date2,
            'skuNotFound' => $skuNotFound,
            'id' => $id,
            'salesByBrand' => $salesByBrand,
            'profitByBrand' => $profitByBrand,
            'link' => $id."/".$client
        ];
        return view('administrator/transaction_detail', $data);
    }

    public function getSummaryPL() {
        $id = $this->request->getVar('id');
        $client = $this->request->getVar('client');
        $qtySold = $this->db->query("SELECT SUM(`quantity-purchased`) as net_sold FROM `transactions` WHERE `transaction-type` = 'Order' AND `amount-description` = 'Principal' AND `transaction-master-id` = '$id'  ");
        $qtySold = $qtySold->getResultObject();
        
        $qtyReturned = $this->db->query("SELECT COUNT(`order-id`) as rate_returned FROM `transactions` WHERE `transaction-type` = 'Refund' AND `amount-description` = 'Principal' AND `transaction-master-id` = '$id' ");
        $qtyReturned = $qtyReturned->getResultObject();

        $sold = $this->db->query("SELECT SUM(`amount`) as sold FROM `transactions` WHERE `transaction-type` = 'Order' AND `amount-description` = 'Principal' AND `transaction-master-id` = '$id' ");
        $sold = $sold->getResultObject();

        $returned = $this->db->query("SELECT SUM(`amount`) as returned FROM `transactions` WHERE `transaction-type` = 'Refund' AND `amount-description` = 'Principal' AND `transaction-master-id` = '$id' ");
        $returned = $returned->getResultObject();

        // Net Sales
        $netSales = $this->db->query("SELECT SUM(amount) as net_sale FROM transactions WHERE `transaction-master-id` = '$id' AND (`amount-description` = 'Principal' OR `amount-description` = 'Tax') ");
        $netSales = $netSales->getResultObject();
        // COGS
        $getSkuOrder = $this->db->query("SELECT sku FROM `transactions` WHERE `transaction-master-id` = '$id' AND `transaction-type` = 'Order' AND sku IS NOT NULL GROUP BY transactions.sku ");
        $getSkuRefund = $this->db->query("SELECT sku FROM `transactions` WHERE `transaction-master-id` = '$id' AND `transaction-type` = 'Refund' AND sku IS NOT NULL GROUP BY transactions.sku ");
        $orderSkus = array();
        foreach ($getSkuOrder->getResultObject() as $sku) {
            array_push($orderSkus, $sku->sku);
        }
        
        $orderSkus = "'" . implode("|", $orderSkus) . "'";
        
        $refundSkus = array();
        foreach ($getSkuRefund->getResultObject() as $sku) {
            array_push($refundSkus, $sku->sku);
        }

        $refundSkus = "'" . implode("|", $refundSkus) . "'";
        
        $getClientCostOrder = $this->db->query("SELECT SUM(max_cost) cogs FROM (SELECT max(cost/qty) as max_cost FROM `reports` WHERE client_id='$client' AND sku REGEXP (".$orderSkus.") GROUP BY sku) as m ");
        $getClientCostOrder = $getClientCostOrder->getResultObject();

        $getClientCostRefund = $this->db->query("SELECT SUM(max_cost) cogs FROM (SELECT max(cost/qty) as max_cost FROM `reports` WHERE client_id='$client' AND sku REGEXP (".$refundSkus.") GROUP BY sku) as m ");
        $getClientCostRefund = $getClientCostRefund->getResultObject();
        
        $cogs =  $getClientCostRefund[0]->cogs - $getClientCostOrder[0]->cogs; 
        // Gross Profit
        $grossOrder = $this->db->query("SELECT SUM(net_sales) as gross_order FROM (SELECT (amount - max(cost)) as net_sales FROM `reports` JOIN transactions ON reports.sku = transactions.sku WHERE client_id='$client' AND `transaction-master-id` = '$id' AND (reports.qty < 2 OR reports.qty IS NULL) GROUP BY `order-id`) as n");
        $grossOrder = $grossOrder->getResultObject();
        // $grossRefund = $this->db->query("SELECT SUM(net_sales) as gross_refund FROM (SELECT (amount - max(cost)) as net_sales FROM `reports` JOIN transactions ON reports.sku = transactions.sku WHERE `transaction-master-id` = '$id' AND `posted-date` BETWEEN '$date1' AND '$date2' AND `transaction-type`='Refund' AND (reports.qty = 1 OR reports.qty IS NULL) GROUP BY transactions.sku) as n");
        // $grossRefund = $grossRefund->getResultObject();
        $tax = $this->db->query("SELECT SUM(amount) as total_tax FROM transactions WHERE `amount-description` ='Tax' AND `transaction-master-id` = '$id' ");
        $tax = $tax->getResultObject();
        $grossProfit = $grossOrder[0]->gross_order + $tax[0]->total_tax;
        $grossProfitMargin = ($grossProfit/$netSales[0]->net_sale) * 100;
        $fees = $this->db->query("SELECT SUM(amount) as fees FROM transactions WHERE `amount-description` NOT IN ('Principal', 'Tax', 'Shipping') AND `transaction-master-id` = '$id'  ");
        $fees = $fees->getResultObject();
        $netProfit = $fees[0]->fees + $grossProfit;
        $netProfitMargin = ($netProfit / $grossProfit) * 100;
        $skuNotFound = $this->db->query("SELECT transactions.sku, repo FROM transactions LEFT JOIN (SELECT reports.sku as repo FROM reports WHERE client_id = '$client') as r ON transactions.sku = r.repo WHERE `transaction-master-id` = '$id' AND transactions.sku IS NOT NULL AND repo IS NULL GROUP BY transactions.sku"); 
        $numOfSku = $skuNotFound->getNumRows();
        $missingSku = array();
        if ($numOfSku > 0) {
            foreach ($skuNotFound->getResultObject() as $sku) {
                array_push($missingSku, $sku->sku);
            }
        }

        // storage fee
        $storageFee = $this->db->query("SELECT SUM(amount) as storage FROM `transactions` WHERE `transaction-master-id` = '$id' AND (`amount-description` = 'Storage Fee' OR `amount-description` = 'StorageRenewalBilling') ");
        $storageFee = $storageFee->getResultObject();
        
        // inbound transport fee
        $transportFee = $this->db->query("SELECT SUM(amount) as transport FROM `transactions` WHERE `transaction-master-id` = '$id' AND `amount-description` = 'FBAInboundTransportationFee'");
        $transportFee = $transportFee->getResultObject();

        $data = [
            'qtySold' => $qtySold[0]->net_sold,
            'qtyReturned' => $qtyReturned[0]->rate_returned,
            'sold' => $sold[0]->sold,
            'returned' => $returned[0]->returned,
            'cogs' => $cogs,
            'grossProfit' => $grossProfit,
            'grossProfitMargin' => $grossProfitMargin,
            'netSales' => $netSales[0]->net_sale,
            'fees' => $fees[0]->fees,
            'netProfit' => $netProfit,
            'netProfitMargin' => $netProfitMargin,
            'numOfSku' => $numOfSku,
            'missingSku' => $missingSku,
            'storageFee' => $storageFee[0]->storage,
            'transportFee' => $transportFee[0]->transport
            
        ];

        echo json_encode($data);
    }

    public function getSalesByBrand($id) {
        
    }


    public function saveChart() {
        $post = $this->request->getVar();
        $client = $post['client'];
        $month = $post['month'];
        $qtySold = $post['qty_sold'];
        $qtyReturned = $post['qty_returned'];
        $sold = $post['sold'];
        $returned = $post['returned'];
        $cogs = $post['cogs'];
        $grossProfit = $post['gross_profit'];
        $grossProfitMargin = $post['gross_profit_margin'];
        $fees = $post['fees'];
        $netProfit = $post['net_profit'];
        $netProfitMargin = $post['net_profit_margin'];
        $activeSku = $post['active-sku'];
        
        $clientExist = $this->db->query("SELECT * FROM chart_pl WHERE client_id = '$client' GROUP BY client_id ");
        if ($clientExist->getNumRows() > 0) {
            $this->db->query("UPDATE chart_pl SET $month='$activeSku' WHERE chart='Active SKUs' AND client_id='$client' ");
            $this->db->query("UPDATE chart_pl SET $month='$qtySold' WHERE chart='Sold' AND client_id='$client' ");
            $this->db->query("UPDATE chart_pl SET $month='$qtyReturned' WHERE chart='Return' AND client_id='$client' ");
            $this->db->query("UPDATE chart_pl SET $month='$sold' WHERE  chart='Net Sales' AND client_id='$client' ");
            $this->db->query("UPDATE chart_pl SET $month='$cogs' WHERE  chart='COGS' AND client_id='$client' ");
            $this->db->query("UPDATE chart_pl SET $month='$grossProfit' WHERE  chart='Gross Profit' AND client_id='$client' ");
            $this->db->query("UPDATE chart_pl SET $month='$grossProfitMargin' WHERE  chart='Gross Profit Margin' AND client_id='$client' ");
            $this->db->query("UPDATE chart_pl SET $month='$fees' WHERE  chart='Fees and Subtractions' AND client_id='$client' ");
            $this->db->query("UPDATE chart_pl SET $month=".($netProfit/$grossProfit)." WHERE  chart='Fees and Subtractions Rate' AND client_id='$client' ");
            $this->db->query("UPDATE chart_pl SET $month='$netProfit' WHERE  chart='Net Profit' AND client_id='$client' ");
            $this->db->query("UPDATE chart_pl SET $month='$netProfitMargin' WHERE  chart='Net Profit Margin' AND client_id='$client' ");
        } else {
            $this->db->query("INSERT INTO chart_pl(chart,$month,client_id, type) VALUES('Active SKUs', '$activeSku', '$client', 'num' ");
            $this->db->query("INSERT INTO chart_pl(chart,$month,client_id, type) VALUES('Sold', '$qtySold', '$client', 'num' ");
            $this->db->query("INSERT INTO chart_pl(chart,$month,client_id, type) VALUES('Return', '$qtyReturned', '$client', 'num' ");
            $this->db->query("INSERT INTO chart_pl(chart,$month,client_id, type) VALUES('Net Sales', '$sold', '$client', 'currency' ");
            $this->db->query("INSERT INTO chart_pl(chart,$month,client_id, type) VALUES('COGS', '$cogs', '$client', 'currency' ");
            $this->db->query("INSERT INTO chart_pl(chart,$month,client_id, type) VALUES('Gross Profit', '$grossProfit', '$client', 'currency' ");
            $this->db->query("INSERT INTO chart_pl(chart,$month,client_id, type) VALUES('Gross Profit Margin', '$grossProfitMargin', '$client', 'percentage' ");
            $this->db->query("INSERT INTO chart_pl(chart,$month,client_id, type) VALUES('Fees and Subtractions', '$fees', '$client', 'currency' ");
            $this->db->query("INSERT INTO chart_pl(chart,$month,client_id, type) VALUES('Fees and Subtractions Rate', '".($netProfit/$grossProfit)."', '$client', 'percentage' ");
            $this->db->query("INSERT INTO chart_pl(chart,$month,client_id, type) VALUES('Net Profit', '$netProfit', '$client', 'currency' ");
            $this->db->query("INSERT INTO chart_pl(chart,$month,client_id, type) VALUES('Net Profit Margin', '$netProfitMargin', '$client', 'percentage' ");
        } 
        return redirect()->to('/admin/generate-p-l'); 
    }

    public function pushReminder() {
        $getReminder = $this->db->query("SELECT reminder.*, users.email FROM reminder JOIN users ON users.id = reminder.client_id WHERE status = 0 GROUP BY client_id");
        $now = date('Y-m-d');
        
        if ($getReminder->getNumRows() > 0) {
            foreach($getReminder->getResultObject() as $reminder) {
                if ($now == $reminder->date) {
                    $this->sendReminderMail($reminder->email);
                    if ($reminder->continuity == 'once') {
                        $this->db->query("UPDATE reminder SET status = 1 WHERE id ='$reminder->id' ");
                    }
                }
            }
        }
    }

    public function sendReminderMail($mail) {
        $message  = "<p>Hi,</p>";
        $message .= "<p>We want to inform you .</p>"; 
        $message .= "</body></html>";
        // end body

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->IsHTML(true);
        $mail->SMTPDebug = 3;
        $mail->Host = 'smtp.titan.email';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        
        $mail->Username = 'noreply.info@swclient.site';
        $mail->Password = 'swclientinfo1';
        $mail->setFrom('noreply.info@swclient.site', 'Smart FBA Inc');
        $mail->addAddress('lingga@buysmartwholesale.com',' lingga');
        $mail->Subject = 'Smart FBA Manifest Reminder';
        $mail->Body = $message;
        if ($mail->send()) {
            echo 'The email message was sent.';
        } else {
            echo 'The email message wasnt sent.';
        }
    }

    public function getReminder() {
        $user = $this->request->getVar('id');
        $reminder = $this->db->query("SELECT * FROM reminder WHERE client_id = '$user' ");
        $reminder = $reminder->getFirstRow();
        echo json_encode($reminder);
    }

    public function clearReminder() {
        $id = $this->request->getVar('id');
        $this->db->query("DELETE FROM reminder WHERE id = '$id' ");
    }

    public function test()
    {
    }
}
