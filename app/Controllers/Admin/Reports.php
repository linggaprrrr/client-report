<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
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
    protected $spreadsheetReader;
    protected $db;


    public function __construct()
    {
        $this->reportModel = new ReportModel();
        $this->investmentModel = new InvestmentModel();
        $this->categoryModel = new CategoryModel();
        $this->userModel = new UserModel();
        $this->newsModel = new NewsModel();
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
        $date = $this->request->getVar('date');
        $date = date('Y-m-d', strtotime($date));
        $report = $this->request->getFile('file');
        $reportName = $report->getTempName();
        $csv_data = array_map('str_getcsv', file($reportName));

        $category = array();
        $reportData = array();
        $investment = array();
        $logFiles = array();
        $investmentLastId = "";
        if (count($csv_data) > 0) {
            $idx = 0;
            foreach ($csv_data as $data) {
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
                    if (!empty($data[1])) {
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
                $idx++;
            }
        }
        $fileName = time() . $report->getName();
        $report->move('files', $fileName);
        $this->db->query("INSERT into log_files(date, file, client_id, investment_id) VALUES(NOW()," . $this->db->escape($fileName) . " , $client, $investmentLastId) ");
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
                if (!empty($row[2]) || !empty($row[3]) || !empty($row[4])) {
                    $month = array();
                    if (strpos($row[2], '%') !== false) {
                        for ($i = 2; $i < 14; $i++) {
                            $temp = str_replace('%', '', $row[$i]);
                            $temp = str_replace(',', '', $temp);
                            array_push($month, $temp);
                        }

                        array_push($type, 'percentage');
                    } elseif (strpos($row[2], '$') !== false) {
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


                    if (strpos($row[20], '%') !== false) {
                        for ($i = 20; $i < 32; $i++) {
                            $temp = str_replace('%', '', $row[$i]);
                            $temp = str_replace(',', '', $temp);
                            array_push($month, $temp);
                        }

                        array_push($type, 'percentage');
                    } elseif (strpos($row[20], '$') !== false) {
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
