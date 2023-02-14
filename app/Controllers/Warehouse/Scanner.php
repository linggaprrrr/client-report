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



class Scanner extends BaseController
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

    public function UPCScanner() {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $data = [
            'tittle' => 'UPC Scanner | Report Management System',
            'menu' => 'UPC Scanner',
            'user' => $user,
        ];
        return view('scanner/upc_scanner', $data);
    }
}