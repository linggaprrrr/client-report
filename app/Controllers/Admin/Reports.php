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
            'tittle' => 'RMS Dashboard | Smart Wholesale',
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
            'tittle' => 'Client Activities | Smart Wholesale',
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

    public function test()
    {
    }
}
