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
        // $totalUnit = $this->reportModel->totalUnit();
        // $totalRetail = $this->reportModel->totalRetail();
        // $totalCostLeft = $this->reportModel->totalCostLeft();
        $totalFulfilled = $this->reportModel->totalFulfilled();
        $getAllReports = $this->reportModel->getAllReports();
        $news = $this->newsModel->getLastNews();

        $data = [
            'tittle' => 'Dashboard | Report Management System',
            'menu' => 'Dashboard',
            'user' => $user,
            'getAllReports' => $getAllReports,
            'totalInvest' => $totalInvest,
            'totalFulfilled' => $totalFulfilled,
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
                    "client_id" => $client
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
        $link = $this->request->getVar('link');
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
                            if (strpos($temp, '(') !== false) {
                                $temp = str_replace('(', '', $temp);
                                $temp = str_replace(')', '', $temp);
                                $temp = -1 * abs($temp);
                            }
                            array_push($month, $temp);
                        }

                        array_push($type, 'percentage');
                    } elseif (strpos($row[2], '$') !== false || strpos($row[3], '$') !== false || strpos($row[4], '$') !== false || strpos($row[5], '$') !== false || strpos($row[6], '$') !== false || strpos($row[7], '$') !== false || strpos($row[8], '$') !== false || strpos($row[9], '$') !== false || strpos($row[10], '$') !== false || strpos($row[11], '$') !== false || strpos($row[12], '$') !== false || strpos($row[13], '$') !== false) {
                        for ($i = 2; $i < 14; $i++) {
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
                        for ($i = 2; $i < 14; $i++) {
                            $temp = $row[$i];
                            if (strpos($temp, '(') !== false) {
                                $temp = str_replace('(', '', $temp);
                                $temp = str_replace(')', '', $temp);
                                $temp = -1 * abs($temp);
                            }
                            array_push($month, $temp);
                        }
                        array_push($type, 'num');
                    }
                    array_push($monthData, $month);
                    $month = array();


                    if (strpos($row[20], '%') !== false || strpos($row[21], '%') !== false || strpos($row[22], '%') !== false || strpos($row[23], '%') !== false || strpos($row[24], '%') !== false || strpos($row[25], '%') !== false || strpos($row[26], '%') !== false || strpos($row[27], '%') !== false || strpos($row[28], '%') !== false || strpos($row[29], '%') !== false || strpos($row[30], '%') !== false || strpos($row[31], '%') !== false) {
                        for ($i = 20; $i < 32; $i++) {
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
                    } elseif (strpos($row[20], '$') !== false || strpos($row[21], '$') !== false || strpos($row[22], '$') !== false || strpos($row[23], '$') !== false || strpos($row[24], '$') !== false || strpos($row[25], '$') !== false || strpos($row[26], '$') !== false || strpos($row[27], '$') !== false || strpos($row[28], '$') !== false || strpos($row[29], '$') !== false || strpos($row[30], '$') !== false || strpos($row[31], '$') !== false || strpos($row[32], '$') !== false) {
                        for ($i = 20; $i < 32; $i++) {
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
                        for ($i = 20; $i < 32; $i++) {
                            $temp = $row[$i];
                            if (strpos($temp, '(') !== false) {
                                $temp = str_replace('(', '', $temp);
                                $temp = str_replace(')', '', $temp);
                                $temp = -1 * abs($temp);
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
            $this->reportModel->savePLReport($chartTitle[$i], $monthData[$i], $type[$i], $client);
        }
        $fileName = time() . $chart->getName();
        $chart->move('files', $fileName);
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
        $getAllClient = $this->assignReportModel->getAllClient();
        $getAllVA = $this->assignReportModel->getAllVA();

        $getAllAssignReport = $this->assignReportModel->getAllAssignReport();
        $getAllAssignReportPending = $this->assignReportModel->getAllAssignReportProcess($userId, $user['role']);
        $getAllAssignReportCompleted = $this->assignReportModel->getAllAssignReportCompleted();
        $data = [
            'tittle' => 'Assignment Reports | Report Management System',
            'menu' => 'BOX ASSIGNMENT FOR CLIENT FULFILLMENT',
            'user' => $user,
            'getAllClient' => $getAllClient,
            'getAllVA' => $getAllVA,
            'getAllAssignReport' => $getAllAssignReport,
            'getAllAssignReportPending' => $getAllAssignReportPending,
            'getAllAssignReportCompleted' => $getAllAssignReportCompleted,
        ];
        return view('administrator/assignment_report', $data);
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
        $temp = array();
        $boxValue = 0;
        $insertId = 1;
        $affected_rows = 0;
        $rejected = 0;
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
                            'box_name' => trim($row[9]),
                            'category' => trim(strtolower($row[13]))
                        );
                        $boxValue += $cost;
                        array_push($temp, $assignReport);
                    } elseif (strcmp($row[9], "BOX") == 0) {

                        $this->db->query("INSERT IGNORE INTO assign_report_box(box_name, box_value, description, date, messenger, report_id) VALUES('$row[2]', $boxValue ," . $this->db->escape($row[1]) . ", '$row[7]', '$row[0]', $insertId)");
                        if ($this->db->affectedRows() == 0) {
                            $rejected++;
                        } else {
                            for ($i = 0; $i < count($temp); $i++) {
                                $this->assignReportModel->save($temp[$i]);
                            }
                            $temp = array();
                        }
                        $boxValue = 0;
                        $affected_rows =  $affected_rows + $this->db->affectedRows();
                    }
                } else {
                    continue;
                }
            }
        }
        $report->move('files', $fileName);
        return redirect()->back()->with('success', $affected_rows . ' box(es) successfully added, and ' . $rejected . ' box(es) was rejected');
    }

    public function getCompany($id)
    {
        $company = $this->investmentModel->getCompany($id);
        echo json_encode($company);
    }

    public function assignClient()
    {
        $post = $this->request->getVar();
        $boxId = substr($post['box_id'], 4);
        $clientId = $post['client_id'];
        $boxValue = $post['box_value'];
        $total = 0;
        $totalBox = $this->db->query("SELECT SUM(box_value) as total_box, MIN(client_cost_left) as cost_left FROM assign_report_box WHERE client_id='$clientId' ")->getRow();
        dd($totalBox);
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

    public function saveAssignmentReport()
    {
        $post = $this->request->getVar();

        foreach ($post['client'] as $idx => $data) {
            if ($data != '0') {
                $clientId = $post['client'][$idx];
                $date = $post['date'][$idx];
                $boxId = $post['box_id'][$idx];
                $vaId = $post['va'][$idx];
                $this->db->query("UPDATE assign_report_box SET confirmed='1', client_id='$clientId', date='$date', va_id='$vaId' WHERE id='$boxId' ");
            }
        }
        return redirect()->back()->with('success', 'Report Successfully saved!');
    }

    public function saveAssignmentProcess()
    {
        $post = $this->request->getVar();
        if (empty($post['status'])) {
            return redirect()->back()->with('reset', 'Assignment Successfully reseted!');
        }
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
                        $this->db->query("INSERT INTO reports(sku, item_description, cond, qty, retail_value, original_value, cost, vendor, client_id, investment_id) SELECT sku, item_description, cond, qty, retail, original, cost, vendor, '$client', '$investment_id' FROM assign_report_details JOIN assign_report_box ON assign_report_box.box_name = assign_report_details.box_name WHERE assign_report_box.id ='$box_id' AND assign_report_details.item_status='1' ");
                    }
                    $this->db->query("UPDATE assign_report_box SET confirmed='1', fba_number='$fba_number', shipment_number='$shipment_number', status='$status' WHERE id='$box_id' ");
                }
            }
        }
        return redirect()->back()->with('reset', 'Assignment Successfully reseted!');
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

    public function getCategory()
    {
        $post = $this->request->getVar();
        $current = $post['current_cost'];
        $investmentId = $post['investment_id'];
        $getCategory = $this->assignReportModel->getCategoryPercentage($current, $investmentId);
        $category = array();
        if ($getCategory->getNumRows() > 0) {
            foreach ($getCategory->getResultArray() as $cat) {
                $temp = array(
                    'category' => ucfirst($cat['category']),
                    'fulfilled' => $cat['fulfilled'],
                    'total_qty' => $cat['total_qty'],
                    'percent' => number_format($cat['percentage'], 2)
                );
                array_push($category, $temp);
            }
        }


        echo json_encode($category);
    }

    public function assignBox()
    {
        $post = $this->request->getVar();
        $boxId = trim(substr($post['box_id'], 4));
        $boxName = $post['box_name'];
        $orderDate = $post['order_date'];
        $newDate = date('Y-m-d', strtotime($orderDate));

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
                $this->db->query("UPDATE box_sum SET client_id='$clientId', cost_left='$costLeft', investment_id='$investmentId', order_date='$newDate' WHERE box_name='$boxName' ");
            }
        } else {
            if ($costLeft <= -250) {
                $status = 0;
            } else {
                $this->db->query("INSERT INTO box_sum(box_name, cost_left, client_id, investment_id, order_date) VALUES('$boxName', '$costLeft', '$clientId', '$investmentId', '$newDate') ");
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
        $data = [
            'tittle' => 'Assignment Reports | Report Management System',
            'menu' => 'APPROVAL BOX ASSIGNMENT',
            'user' => $user,
            'getAllClient' => $getAllClient,
            'getAllAssignReportProcess' => $getAllAssignReportProcess
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

    public function saveBoxDetails()
    {
        $post = $this->request->getVar();
        for ($i = 0; $i < count($post['item']); $i++) {
            $retail = str_replace('$', '', trim($post['retail'][$i]));
            $original = str_replace('$', '', trim($post['original'][$i]));
            $cost = str_replace('$', '', trim($post['cost'][$i]));
            $stat = $post['item_status'][$i];
            $check = $post['item_check'][$i];
            $note = $post['note'][$i];
            $id = $post['item'][$i];
            $this->db->query("UPDATE assign_report_details SET retail='$retail', original='$original', cost='$cost', item_status='$stat', item_check='$check', item_note=" . $this->db->escape($note) . " WHERE id='$id' ");
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
        $data = [
            'tittle' => 'Assignment Reports | Report Management System',
            'menu' => 'APPROVAL BOX ASSIGNMENT',
            'user' => $user,
            'assignCompleted' => $assignCompleted
        ];
        return view('administrator/assignment_completed', $data);
    }

    public function resetAssignment()
    {

        $this->db->query("DELETE FROM box_sum WHERE box_name IN (SELECT box_sum.box_name FROM assign_report_box JOIN box_sum ON box_sum.box_name = assign_report_box.box_name WHERE confirmed = 0)");
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
        $data = [
            'tittle' => 'Assignment Reports | Report Management System',
            'menu' => 'APPROVAL BOX ASSIGNMENT',
            'user' => $user,
            'getAllVA' => $getAllVA,
            'assignCompleted' => $assignCompleted
        ];
        return view('administrator/assignment_history', $data);
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
        $data = [
            'tittle' => 'Completed Assignments | Report Management System',
            'menu' => 'COMPLETED ASSIGNMENTS',
            'user' => $user,
            'completedInvestments' => $completedInvestments
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
                                if (strpos($temp, '(') !== false) {
                                    $temp = str_replace('(', '', $temp);
                                    $temp = str_replace(')', '', $temp);
                                    $temp = -1 * abs($temp);
                                }
                                array_push($month, $temp);
                            }

                            array_push($type, 'percentage');
                        } elseif (strpos($row[2], '$') !== false || strpos($row[3], '$') !== false || strpos($row[4], '$') !== false || strpos($row[5], '$') !== false || strpos($row[6], '$') !== false || strpos($row[7], '$') !== false || strpos($row[8], '$') !== false || strpos($row[9], '$') !== false || strpos($row[10], '$') !== false || strpos($row[11], '$') !== false || strpos($row[12], '$') !== false || strpos($row[13], '$') !== false) {
                            for ($i = 2; $i < 14; $i++) {
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
                            for ($i = 2; $i < 14; $i++) {
                                $temp = $row[$i];
                                if (strpos($temp, '(') !== false) {
                                    $temp = str_replace('(', '', $temp);
                                    $temp = str_replace(')', '', $temp);
                                    $temp = -1 * abs($temp);
                                }
                                array_push($month, $temp);
                            }
                            array_push($type, 'num');
                        }
                        array_push($monthData, $month);
                        $month = array();


                        if (strpos($row[20], '%') !== false || strpos($row[21], '%') !== false || strpos($row[22], '%') !== false || strpos($row[23], '%') !== false || strpos($row[24], '%') !== false || strpos($row[25], '%') !== false || strpos($row[26], '%') !== false || strpos($row[27], '%') !== false || strpos($row[28], '%') !== false || strpos($row[29], '%') !== false || strpos($row[30], '%') !== false || strpos($row[31], '%') !== false) {
                            for ($i = 20; $i < 32; $i++) {
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
                        } elseif (strpos($row[20], '$') !== false || strpos($row[21], '$') !== false || strpos($row[22], '$') !== false || strpos($row[23], '$') !== false || strpos($row[24], '$') !== false || strpos($row[25], '$') !== false || strpos($row[26], '$') !== false || strpos($row[27], '$') !== false || strpos($row[28], '$') !== false || strpos($row[29], '$') !== false || strpos($row[30], '$') !== false || strpos($row[31], '$') !== false || strpos($row[32], '$') !== false) {
                            for ($i = 20; $i < 32; $i++) {
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
                            for ($i = 20; $i < 32; $i++) {
                                $temp = $row[$i];
                                if (strpos($temp, '(') !== false) {
                                    $temp = str_replace('(', '', $temp);
                                    $temp = str_replace(')', '', $temp);
                                    $temp = -1 * abs($temp);
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
                $this->reportModel->savePLReport($chartTitle[$i], $monthData[$i], $type[$i], $client);
            }
            $fileName = time() . $chart->getName();
            $chart->move('files', $fileName);
            $this->db->query("UPDATE log_files SET file=" . $this->db->escape($fileName) . " ,link=" . $this->db->escape($fileName) . ", client_id ='$client' WHERE id='$log_id'");
        }

        return redirect()->back()->with('success', 'Report Successfully Uploaded!');
    }
    public function test()
    {
    }
}
