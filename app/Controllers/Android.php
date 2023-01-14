<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AssignReportModel;
use App\Models\CategoryModel;
use App\Models\InvestmentModel;
use App\Models\NewsModel;
use App\Models\ReportModel;
use App\Models\UserModel;


class Android extends BaseController
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
        helper('cookie');   
    }

    public function index() {
      $userId = session()->get('user_id');
      if (is_null($userId)) {
          return view('mobile_copy/login');
      } else {           
          return redirect()->to(base_url('/android/get-started/'. $userId));
      }
    }

    public function loginProses()
    {
        $post = $this->request->getVar();
        $user = $this->userModel->getWhere(['username' => $post['username']])->getRow();
        $username = $post['username'];
        $password = $post['password'];
        
        if (isset($post['rememberme'])) {            
            setcookie("sw-username", $username, time()+ (10 * 365 * 24 * 60 * 60));            
            setcookie("sw-pw", $password, time()+ (10 * 365 * 24 * 60 * 60));            
        }
        
        if ($user->under_comp == '2') {
            return redirect()->back()->with('error', 'Username Not Found!');
        }
        $currentPage = $post['current'];
        if ($user) {
            if (password_verify($post['password'], $user->password)) {
                $params = [
                    'user_id' => $user->id,
                    'role' => $user->role
                ];
                session()->set($params);
                            
                if ($user->role == "master" && $user->under_comp == '1') {
                    return redirect()->to(base_url('mobile_c/master/manifest'))->with('message', 'Login Successful!');
                }
              
                if ($user->role == "client") {
                    $ip = getenv('HTTP_CLIENT_IP')?: getenv('HTTP_X_FORWARDED_FOR')?: getenv('HTTP_X_FORWARDED')?: getenv('HTTP_FORWARDED_FOR')?: getenv('HTTP_FORWARDED')?: getenv('REMOTE_ADDR');
                    $page = 'get-started';
                    $this->userModel->logActivityAndroid($user->id, $page, $ip);
                    if ($currentPage == base_url() || $currentPage == base_url() . '/android') {
                        return redirect()->to(base_url('mobile_copy/get-started'))->with('message', 'Login Successful!');
                    } else {
                        return redirect()->to($currentPage)->with('message', 'Login Successful!');
                    }
                }
            } else {
                return redirect()->back()->with('error', 'Incorrect Password!');
            }
        } else {

            return redirect()->back()->with('error', 'Username Not Found!');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/mobile'));
    }

    public function accountSetting()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return redirect()->to(base_url('/mobile'));
        }
        $user = $this->userModel->find($userId);
        $data = [
            'tittle' => "Account Setting | Report Management System",
            'menu' => $user['fullname'] . "'s Setting",
            'user' => $user
        ];

        return view('mobile_copy/account_setting', $data);
    }

    public function updateSetting()
    {
        $post = $this->request->getVar();
        $photo = $this->request->getFile('photo');
        $fileName = "";

        if (!empty($photo->getTempName())) {
            $fileName = time() . $photo->getName();
            $photo->move('img', $fileName);
        }
        $user = $this->userModel->find($post['id']);
        if (!empty($post['new_password'])) {
            if (password_verify($post['old_password'], $user['password'])) {
                if ($fileName != "") {
                    $this->userModel->save(array(
                        "id" => $post['id'],
                        "fullname" => $post['fullname'],
                        "company" => $post['company'],
                        "address" => $post['address'],
                        "photo" => $fileName,
                        "password" => password_hash($post['new_password'], PASSWORD_BCRYPT),
                    ));
                } else {
                    $this->userModel->save(array(
                        "id" => $post['id'],
                        "fullname" => $post['fullname'],
                        "company" => $post['company'],
                        "address" => $post['address'],
                        "password" => password_hash($post['new_password'], PASSWORD_BCRYPT),
                    ));
                }
            } else {
                return redirect()->back()->with('failed', 'User Successfully Updated!');
            }
        } else {
            if ($fileName != "") {
                $this->userModel->save(array(
                    "id" => $post['id'],
                    "fullname" => $post['fullname'],
                    "company" => $post['company'],
                    "address" => $post['address'],
                    "photo" => $fileName,
                ));
            } else {
                $this->userModel->save(array(
                    "id" => $post['id'],
                    "fullname" => $post['fullname'],
                    "company" => $post['company'],
                    "address" => $post['address'],
                ));
            }
        }
        return redirect()->back()->with('success', 'User Successfully Updated!');
    }

    public function dashboard() {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return redirect()->to(base_url('/mobile_copy'));
        }
        $user = $this->userModel->find($userId);
        $investId = $this->investmentModel->getInvestmentId($userId);
        // dd($investId);
        $dateId = $this->request->getVar('investdate');
        $underComp = 1;
        if (str_contains(base_url(uri_string()), 'eliteapp')) {
            $underComp = 2;
        }
        $news = $this->newsModel->getLastNews($underComp);
        if ($dateId == null) {
            if ($user['role'] == 'client' and $investId == null) {
                $data = [
                    'tittle' => 'Dashboard | Report Management System',
                    'menu' => 'Dashboard',
                    'user' => $user,
                    'news' => $news
                ];

                return view('/mobile_copy/dashboard2', $data);
            }

            $lastInvestment = $this->investmentModel->getLastDateOfInvestment($userId);
            
            $totalInvest = $this->investmentModel->totalClientInvestment($investId);
            $totalUnit = $this->reportModel->totalUnit($investId);
            $totalRetail = $this->reportModel->totalRetail($investId);
            $totalCostLeft = $this->reportModel->totalCostLeft($investId);
            $totalFulfilled = $this->reportModel->totalFulfilled($investId);
            $getAllReportClient = $this->reportModel->getAllReportClient($investId);
            $investmentDate = $this->investmentModel->investmentDate($user['id']);
            $getVendorName = $this->reportModel->getVendorName($investId);
            $file = $this->exportReceipt($investId);
            $getStatusManifest = $this->assignReportModel->getStatusManifest($investId);
        } else {
            $lastInvestment = $this->investmentModel->getWhere(['id' => $dateId])->getLastRow();
            
            $totalInvest = $this->investmentModel->totalClientInvestment($dateId);
            $totalUnit = $this->reportModel->totalUnit($dateId);
            $totalRetail = $this->reportModel->totalRetail($dateId);
            $totalCostLeft = $this->reportModel->totalCostLeft($dateId);
            $totalFulfilled = $this->reportModel->totalFulfilled($dateId);
            $getAllReportClient = $this->reportModel->getAllReportClient($dateId);
            $investmentDate = $this->investmentModel->investmentDate($user['id']);
            $getVendorName = $this->reportModel->getVendorName($dateId);
            $file = $this->exportReceipt($dateId);
            $getStatusManifest = $this->assignReportModel->getStatusManifest($dateId);
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
            'statusManifest' => $getStatusManifest,
            'investDate' => $investmentDate,
            'lastInvestment' => $lastInvestment,
            'getVendorName' => $getVendorName,
            'news' => $news,
            'file' => $file
        ];
        return view('/mobile_copy/dashboard', $data);
    }

    public function getStarted()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return redirect()->to(base_url('/mobile'));
        }
        $user = $this->userModel->find($userId);
        $getClientCostLeft = $this->reportModel->getClientCostLeft($userId);
        $monthdiff = $this->investmentModel->monthDiff($userId);

        $data = [
            'tittle' => "Get Started | Report Management System",
            'menu' => "Get Started",
            'user' => $user,
            'costLeft' => $getClientCostLeft,
            'monthDiff' => $monthdiff
        ];
        return view('mobile_copy/getstarted', $data);
    }

    public function brandApprovals()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return redirect()->to(base_url('/mobile'));
        }
        $brands = $this->categoryModel->getBrands();
        $user = $this->userModel->find($userId);
        $selectedBrand = $this->categoryModel->selectedBrand($userId);
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
        $data = [
            'tittle' => "Brand Approvals | Report Management System",
            'menu' => "Brand Approvals",
            'user' => $user,
            'brands' => $temp_brand
        ];
        return view('mobile_copy/brand_approvals', $data);
    }
    public function purchaseInventory()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return redirect()->to(base_url('/android'));
        }
        $user = $this->userModel->find($userId);
        $data = [
            'tittle' => "Purchase Inventory | Report Management System",
            'menu' => "Purchase Inventory",
            'user' => $user
        ];

        return view('mobile_copy/purchase_inventory', $data);
    }

    public function plReport()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return redirect()->to(base_url('/android'));
        }
        $user = $this->userModel->find($userId);
        $plReport = $this->reportModel->showPLReport($userId);
        $downloadPLReport = $this->reportModel->downloadPLReport($userId);
        $data = [
            'tittle' => "P&L Report | Report Management System",
            'menu' => "P&L Report",
            'user' => $user,
            'plReport' => $plReport,
            'file' => $downloadPLReport
        ];
        return view('mobile_copy/pl_report', $data);
    }

    public function news() {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return redirect()->to(base_url('/android'));
        }
        $user = $this->userModel->find($userId);
        $underComp = 1;
        if (str_contains(base_url(uri_string()), 'eliteapp')) {
            $underComp = 2;
        }
        $news = $this->newsModel->getLastNews($underComp);
        $allNews = $this->newsModel->getNews($underComp);
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();

        $data = [
            'tittle' => "News Announcement | Report Management System",
            'menu' => "News Announcement",
            'user' => $user,
            'news' => $news,
            'allNews' => $allNews,
            'companySetting' => $companysetting
        ];
        return view('mobile_copy/news', $data);
    }

    public function getClientCostLeft($clientId)
    {   
        $getClientCostLeft = $this->reportModel->getClientCostLeft($clientId);
        $data = array (
            'cost_left' => $getClientCostLeft-400
        );
        echo json_encode($data);
    }

    public function master($id = null) {
        $dateId = $this->request->getVar('investdate');
        $client = $this->request->getVar('client');
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return redirect()->to(base_url('/android'));
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

                return view('mobile_copy/master/dashboard2', $data);
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
            $file = $this->exportReceipt($investId);
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
            $file = $this->exportReceipt($dateId);
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
            'clientSelect' => $userId,
            'file' => $file
    
        ];
        $page = 'manifest';
        return view('mobile_copy/master/dashboard', $data);
    }

    public function masterPLReport($id)
    {
        $userId = $id;
        if (is_null($userId)) {
            return redirect()->to(base_url('/android'));
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
        return view('mobile_copy/master/pl_report', $data);
    }
    public function exportReceipt($dateId) {
        $investment = $this->investmentModel->getReceiptClient($dateId);
        $receiptData = $this->investmentModel->getReceiptData($dateId);
        $purchaseTotal = $this->investmentModel->totalClientInvestment($dateId);
        $totalUnit = $this->reportModel->totalUnit($dateId);
        $totalRetail = $this->reportModel->totalRetail($dateId);
        $totalCostLeft = $this->reportModel->totalCostLeft($dateId);
        $totalClientCost = $this->reportModel->totalFulfilled($dateId);
        $avgUnitRetail = $totalRetail->total_retail / ($totalUnit->total_unit == 0 ? 1 : 1);
        $avgUnitClientCost = $totalClientCost->total_fulfilled / ($totalUnit->total_unit == 0 ? 1 : 1);
        $link = $this->reportModel->getLinkManifest($dateId);
        $path = FCPATH."/assets/images/fba-logo.png";
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $data = [
            'manifestDesc' => $investment->getResultObject(),
            'manifestData' => $receiptData->getResultObject(),
            'totalUnit' => $totalUnit->total_unit,
            'totalRetail' => $totalRetail->total_retail,
            'totalCostLeft' => $totalCostLeft,
            'totalClientCost' => $totalClientCost->total_fulfilled,
            'avgUnitRetail' => $avgUnitRetail,
            'avgUnitClientCost' => $avgUnitClientCost,
            'img' => $base64,
            'link' => $link->getResultObject()
        ];
        $client = $investment->getResultObject();
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml(view('ex-pdf', $data));
        $dompdf->setPaper('legal');
        $dompdf->render();        
        // $dompdf->stream("Receipt Smart FBA - ". $client[0]->fullname ." - ". $client[0]->company ." .pdf");
        $fileName = "Receipt Smart FBA - ". $client[0]->fullname ." - ". $client[0]->company ." ".time().".pdf";
        $output = $dompdf->output();
        file_put_contents('receipts/'.$fileName , $output);
        return $fileName;
    }

    public function AmazonPayment() {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return redirect()->to(base_url('/android'));
        }        
        $user = $this->userModel->find($userId);
        
        $data = [
            'tittle' => "How Amazon Payments Work | Report Management System",
            'menu' => "How Amazon Payments Work",
            'user' => $user,
        ];
        $page = 'how-amazon-payments-work';        
        return view('mobile_copy/amazon_payments', $data);
    }
}