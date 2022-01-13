<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AssignReportModel;
use App\Models\CategoryModel;
use App\Models\InvestmentModel;
use App\Models\NewsModel;
use App\Models\ReportModel;
use App\Models\UserModel;
use CodeIgniter\Database\BaseBuilder;


class Reports extends BaseController
{
    protected $reportModel = "";
    protected $investmentModel = "";
    protected $categoryModel = "";
    protected $userModel = "";
    protected $newsModel = "";
    protected $assignReportModel = "";
    protected $spreadsheetReader;
    protected $db;


    public function __construct()
    {
        $this->reportModel = new ReportModel();
        $this->investmentModel = new InvestmentModel();
        $this->categoryModel = new CategoryModel();
        $this->userModel = new UserModel();
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

        $totalInvest = $this->investmentModel->totalClientInvestment();
        $totalUnit = $this->reportModel->totalUnit();
        $totalRetail = $this->reportModel->totalRetail();
        $totalCostLeft = $this->reportModel->totalCostLeft();
        $totalFulfilled = $this->reportModel->totalFulfilled();
        $getAllReports = $this->reportModel->getAllReports();
        $news = $this->newsModel->getLastNews();

        $data = [
            'tittle' => 'Dashboard | Report Management System',
            'menu' => 'Dashboard',
            'user' => $user,
            'totalInvest' => $totalInvest,
            'totalUnit' => $totalUnit,
            'totalRetail' => $totalRetail,
            'totalCostLeft' => $totalCostLeft,
            'totalFulfilled' => $totalFulfilled,
            'getAllReports' => $getAllReports,
            'news' => $news,
        ];
        return view('administrator/dashboard', $data);
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
        // dd($getAllFiles->getResultArray());

        $data = [
            'tittle' => 'Client Activities | Report Management System',
            'menu' => 'Client Activities',
            'totalClientUploaded' => $totalClientUploaded,
            'totalReport' => $totalReport,
            'getAllFiles' => $getAllFiles,
            'getAllClient' => $getAllClient,
            'user' => $user
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
        $ext = $report->getClientExtension();
        if ($ext == 'xls') {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $spreadsheet = $render->load($report);
        $data = $spreadsheet->getActiveSheet()->toArray();
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
                    "client_id" => $client,
                    "created_at" => date("Y-m-d H:i:s"),
                    "updated_at" => date("Y-m-d H:i:s")
                );

                $this->investmentModel->save($investment);
                $investmentLastId = $this->investmentModel->getLastId();
                $category = array(
                    "category_name" => $data[1],
                    "investment_id" => $investmentLastId,
                    "created_at" => date("Y-m-d H:i:s"),
                    "updated_at" => date("Y-m-d H:i:s")
                );
                $this->categoryModel->save($category);
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


    public function deleteReport($id)
    {
        $this->reportModel->deleteReport($id);
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

        $data = [
            'tittle' => 'P&L Report | Report Management System',
            'menu' => 'P&L Report',
            'totalClientUploaded' => $totalClientUploaded,
            'totalReport' => $totalReport,
            'getAllFiles' => $getAllFiles,
            'getAllClient' => $getAllClient,
            'user' => $user
        ];

        return view('administrator/pl_reports', $data);
    }

    public function uploadPLReport()
    {
        $client = $this->request->getVar('client');
        $report = $this->request->getFile('file');
        $chart = $this->request->getFile('chart');
        $ext = $chart->getClientExtension();
        if ($ext == 'xls') {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $spreadsheet = $render->load($chart);
        $data = $spreadsheet->getActiveSheet()->toArray();
        $chartTitle = array();
        $monthData = array();
        $type = array();
        foreach ($data as $idx => $row) {
            if (!empty($row[0])) {
                array_push($chartTitle, $row[0]);
                array_push($chartTitle, $row[18]);
            } else {
                if (!empty($row[2]) || !empty($row[3]) || !empty($row[4]) || !empty($row[5]) || !empty($row[6]) || !empty($row[7]) || !empty($row[8] || !empty($row[9]) || !empty($row[10]) || !empty($row[11]) || !empty($row[12]) || !empty($row[13]))) {
                    $month = array();
                    if (strpos($row[2], '%') !== false || strpos($row[3], '%') !== false || strpos($row[4], '%') !== false || strpos($row[5], '%') !== false || strpos($row[6], '%') !== false || strpos($row[7], '%') !== false || strpos($row[8], '%') !== false || strpos($row[9], '%') !== false || strpos($row[10], '%') !== false || strpos($row[11], '%') !== false || strpos($row[12], '%') !== false || strpos($row[13], '%') !== false) {
                        for ($i = 2; $i < 14; $i++) {
                            $temp = str_replace('%', '', $row[$i]);
                            $temp = str_replace(',', '', $temp);
                            array_push($month, $temp);
                        }

                        array_push($type, 'percentage');
                    } elseif (strpos($row[2], '$') !== false || strpos($row[3], '$') !== false || strpos($row[4], '$') !== false || strpos($row[5], '$') !== false || strpos($row[6], '$') !== false || strpos($row[7], '$') !== false || strpos($row[8], '$') !== false || strpos($row[9], '$') !== false || strpos($row[10], '$') !== false || strpos($row[11], '$') !== false || strpos($row[12], '$') !== false || strpos($row[13], '$') !== false) {
                        for ($i = 2; $i < 14; $i++) {
                            $temp = str_replace('$', '', $row[$i]);
                            $temp = str_replace(',', '', $temp);
                            array_push($month, $temp);
                        }

                        array_push($type, 'currency');
                    } else {
                        for ($i = 2; $i < 14; $i++) {
                            array_push($month, $row[$i]);
                        }
                        array_push($type, 'num');
                    }
                    array_push($monthData, $month);
                    $month = array();


                    if (strpos($row[20], '%') !== false || strpos($row[21], '%') !== false || strpos($row[22], '%') !== false || strpos($row[23], '%') !== false || strpos($row[24], '%') !== false || strpos($row[25], '%') !== false || strpos($row[26], '%') !== false || strpos($row[27], '%') !== false || strpos($row[28], '%') !== false || strpos($row[29], '%') !== false || strpos($row[30], '%') !== false || strpos($row[31], '%') !== false) {
                        for ($i = 20; $i < 32; $i++) {
                            $temp = str_replace('%', '', $row[$i]);
                            $temp = str_replace(',', '', $temp);
                            array_push($month, $temp);
                        }

                        array_push($type, 'percentage');
                    } elseif (strpos($row[20], '$') !== false || strpos($row[21], '$') !== false || strpos($row[22], '$') !== false || strpos($row[23], '$') !== false || strpos($row[24], '$') !== false || strpos($row[25], '$') !== false || strpos($row[26], '$') !== false || strpos($row[27], '$') !== false || strpos($row[28], '$') !== false || strpos($row[29], '$') !== false || strpos($row[30], '$') !== false || strpos($row[31], '$') !== false || strpos($row[32], '$') !== false) {
                        for ($i = 20; $i < 32; $i++) {
                            $temp = str_replace('$', '', $row[$i]);
                            $temp = str_replace(',', '', $temp);
                            array_push($month, $temp);
                        }
                        array_push($type, 'currency');
                    } else {
                        for ($i = 20; $i < 32; $i++) {
                            array_push($month, $row[$i]);
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
        $fileName = time() . $report->getName();
        $report->move('files', $fileName);
        $this->db->query("INSERT into log_files(date, file, client_id) VALUES(NOW()," . $this->db->escape($fileName) . " , $client) ");
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
        $getAllClient = $this->assignReportModel->getAllClient();
        $getAllAssignReport = $this->assignReportModel->getAllAssignReport();
        $data = [
            'tittle' => 'Assignment Reports | Report Management System',
            'menu' => 'BOX ASSIGNMENT FOR CLIENT FULFILLMENT',
            'user' => $user,
            'getAllClient' => $getAllClient,
            'getAllAssignReport' => $getAllAssignReport
        ];
        return view('administrator/assignment_report', $data);
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
        $assignReport = array();
        $boxValue = 0;
        $insertId = 1;

        foreach ($data as $idx => $row) {
            if ($idx == 1) {
                $retail = str_replace('$', '', $row[4]);
                $retail = str_replace(',', '', $retail);
                $original = str_replace('$', '', $row[5]);
                $original = str_replace(',', '', $original);
                $cost = str_replace('$', '', $row[6]);
                $cost = str_replace(',', '', $cost);
                $this->db->query("INSERT INTO assign_reports(file, units, retails, originals, costs) VALUES(" . $this->db->escape($fileName) . ", $row[3], $retail, $original, $cost) ");
                $insertId = $this->assignReportModel->getLastId();
            }
            if ($idx > 2) {
                if (!empty($row[9]) || (strcasecmp($row[9], "BOX") == 0 || strcasecmp($row[9], "SHIP") == 0)) {
                    if (!empty($row[4]) || !empty($row[5]) || !empty($row[6])) {
                        $retail = str_replace('$', '', $row[4]);
                        $retail = str_replace(',', '', $retail);
                        $original = str_replace('$', '', $row[5]);
                        $original = str_replace(',', '', $original);
                        $cost = str_replace('$', '', $row[6]);
                        $cost = str_replace(',', '', $cost);
                        $assignReport = array(
                            'sku' => $row[0],
                            'item_description' => $row[1],
                            'cond' => $row[2],
                            'qty' => $row[3],
                            'retail' => $retail,
                            'original' => $original,
                            'cost' => $cost,
                            'vendor' => $row[7],
                            'box_name' => $row[9]
                        );
                        $boxValue += $cost;
                        $this->assignReportModel->save($assignReport);
                    } elseif (strcmp($row[9], "BOX") == 0) {
                        $this->db->query("INSERT INTO assign_report_box(box_name, box_value, description, date, messenger, report_id) VALUES('$row[2]', $boxValue ," . $this->db->escape($row[1]) . ", '$row[7]', '$row[0]', $insertId)");
                        $boxValue = 0;
                    }
                } else {
                    continue;
                }
            }
        }

        $report->move('files', $fileName);
        return redirect()->back()->with('success', 'Report Successfully Uploaded!');
    }

    public function getCompany($id)
    {
        $company = $this->investmentModel->getCompany($id);
        echo json_encode($company);
    }

    public function checklistReport()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $getAllInvestment = $this->investmentModel->getAllInvestment();
        // dd($getAllInvestment->getResultArray());
        $data = [
            'tittle' => 'Assignment Reports: Checklist Report | Report Management System',
            'menu' => 'Checklist Report',
            'user' => $user,
            'getAllInvestment' => $getAllInvestment
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

    public function updateLink()
    {
        $post = $this->request->getVar();
        $id = $post['file_id'];
        $this->db->query("UPDATE log_files SET link = " . $this->db->escape($post['link']) . " WHERE id='$id' ");
        return redirect()->back()->with('link', 'Link Successfully updated!');
    }

    public function test()
    {
        $client = $this->request->getVar('client');
        $date = $this->request->getVar('date');
        $date = date('Y-m-d', strtotime($date));
        $report = $this->request->getFile('file');
        $reportName = $report->getTempName();
        $csv_data = array_map('str_getcsv', file($reportName));
    }
}
