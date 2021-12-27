<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\InvestmentModel;
use App\Models\ReportModel;
use App\Models\UserModel;

class Clients extends BaseController
{
    protected $reportModel = "";
    protected $userModel = "";
    protected $investmentModel = "";
    protected $categoryModel = "";

    public function __construct()
    {
        $this->reportModel = new ReportModel();
        $this->userModel = new UserModel();
        $this->investmentModel = new InvestmentModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return redirect()->to(base_url('/login'));
        }
        $user = $this->userModel->find($userId);
        $investId = $this->investmentModel->getWhere(['client_id' => $user['id']])->getRow();
        $dateId = $this->request->getVar('investdate');

        if ($dateId == null) {
            $lastInvestment = $this->investmentModel->getWhere(['client_id' => $user['id']])->getLastRow();
            $category = $this->categoryModel->getCategory($investId->id);
            $totalInvest = $this->investmentModel->totalClientInvestment($investId->id);
            $totalUnit = $this->reportModel->totalUnit($investId->id);
            $totalRetail = $this->reportModel->totalRetail($investId->id);
            $totalCostLeft = $this->reportModel->totalCostLeft($investId->id);
            $totalFulfilled = $this->reportModel->totalFulfilled($investId->id);
            $getAllReportClient = $this->reportModel->getAllReportClient($investId->id);
            $investmentDate = $this->investmentModel->investmentDate($user['id']);
            $getVendorName = $this->reportModel->getVendorName($investId->id);
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
            'title' => 'RMS Dashboard | Smart Wholesale',
            'menu' => 'Dashboard',
            'user' => $user,
            'totalInvest' => $totalInvest,
            'totalUnit' => $totalUnit,
            'totalRetail' => $totalRetail,
            'totalCostLeft' => $totalCostLeft,
            'totalFulfilled' => $totalFulfilled,
            'getAllReports' => $getAllReportClient,
            'category' => $category->category_name,
            'investDate' => $investmentDate,
            'lastInvestment' => $lastInvestment,
            'getVendorName' => $getVendorName
        ];
        return view('client/dashboard', $data);
    }

    public function accountSetting()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return redirect()->to(base_url('/login'));
        }
        $user = $this->userModel->find($userId);
        $data = [
            'title' => "Account Setting | Smart Wholesale",
            'menu' => $user['fullname'] . "'s Setting",
            'user' => $user
        ];

        return view('client/account_setting', $data);
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


    public function tickets()
    {
        $data = [
            "title" => "Tickets | Smart Wholesale"
        ];

        return view('client/tickets', $data);
    }
}
