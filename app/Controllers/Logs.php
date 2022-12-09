<?php

namespace App\Controllers;

use App\Models\UserModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Logs extends BaseController
{
    protected $userModel = "";
    protected $newsModel = "";
    protected $db = "";

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->db = \Config\Database::connect();
    }
    

    public function index()
    {
        $login = $this->request->getVar('login');
        $page = $this->request->getVar('page');
        $social = $this->request->getVar('social');
       
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return redirect()->to(base_url('/login'));
        }
        $user = $this->userModel->find($userId);
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();
        
        if (empty($login)) {
            $userLoginGraphBrowser = $this->db->query("SELECT COUNT(id) as total FROM log_logins WHERE month(date)=month(now()) AND media='BROWSER' ORDER BY date DESC;");
            $userLoginGraphiOS = $this->db->query("SELECT COUNT(id) as total FROM log_logins WHERE month(date)=month(now()) AND media='iOS' ORDER BY date DESC;");
            $userLoginGraphAndroid = $this->db->query("SELECT COUNT(id) as total FROM log_logins WHERE month(date)=month(now()) AND media='ANDROID' ORDER BY date DESC;");
            $userLogin = $this->db->query("SELECT log_logins.*, fullname FROM log_logins JOIN users ON users.id = log_logins.user_id WHERE month(date)=month(now()) ORDER BY date DESC");
        } elseif ($login == date('Y')) {
            $userLoginGraphBrowser = $this->db->query("SELECT COUNT(id) as total FROM log_logins WHERE year(date)='$login' AND media='BROWSER' ORDER BY date DESC;");
            $userLoginGraphiOS = $this->db->query("SELECT COUNT(id) as total FROM log_logins WHERE year(date)='$login' AND media='iOS' ORDER BY date DESC;");
            $userLoginGraphAndroid = $this->db->query("SELECT COUNT(id) as total FROM log_logins WHERE year(date)='$login' AND media='ANDROID' ORDER BY date DESC;");
            $userLogin = $this->db->query("SELECT log_logins.*, fullname FROM log_logins JOIN users ON users.id = log_logins.user_id WHERE year(date)='$login' ORDER BY date DESC");
        } else {
            $userLoginGraphBrowser = $this->db->query("SELECT COUNT(id) as total FROM log_logins WHERE month(date)='$login' AND media='BROWSER' ORDER BY date DESC;");
            $userLoginGraphiOS = $this->db->query("SELECT COUNT(id) as total FROM log_logins WHERE month(date)='$login' AND media='iOS' ORDER BY date DESC;");
            $userLoginGraphAndroid = $this->db->query("SELECT COUNT(id) as total FROM log_logins WHERE month(date)='$login' AND media='ANDROID' ORDER BY date DESC;");
            $userLogin = $this->db->query("SELECT log_logins.*, fullname FROM log_logins JOIN users ON users.id = log_logins.user_id WHERE month(date)='$login' ORDER BY date DESC");
        }
        
        if (empty($page)) {
            $userClick = $this->db->query("SELECT count(id) as total, page FROM `log_pages` WHERE month(date)=month(now()) GROUP BY page");
            $getUserClick = $this->db->query("SELECT log_pages.*, fullname FROM log_pages JOIN users ON users.id = log_pages.user_id WHERE month(date)=month(now()) ORDER BY date DESC");
        } elseif ($page == date('Y')) {
            $userClick = $this->db->query("SELECT count(id) as total, page FROM `log_pages` WHERE year(date)='$page' GROUP BY page");
            $getUserClick = $this->db->query("SELECT log_pages.*, fullname FROM log_pages JOIN users ON users.id = log_pages.user_id WHERE year(date)='$page' ORDER BY date DESC");
        } else {
            $userClick = $this->db->query("SELECT count(id) as total, page FROM `log_pages` WHERE month(date)='$page' GROUP BY page");
            $getUserClick = $this->db->query("SELECT log_pages.*, fullname FROM log_pages JOIN users ON users.id = log_pages.user_id WHERE month(date)='$page' ORDER BY date DESC");
        }

        if (empty($social)) {
            $userClickMedia = $this->db->query("SELECT count(id) as total, social FROM `log_media` WHERE month(date)=month(now()) GROUP BY social");
            $getUserClickMedia = $this->db->query("SELECT log_media.*, fullname FROM log_media JOIN users ON users.id = log_media.user_id WHERE month(date)=month(now()) ORDER BY date DESC");
        } elseif ($social == date('Y')) {
            $userClickMedia = $this->db->query("SELECT count(id) as total, social FROM `log_media` WHERE year(date)='$social'  GROUP BY social");
            $getUserClickMedia = $this->db->query("SELECT log_media.*, fullname FROM log_media JOIN users ON users.id = log_media.user_id WHERE year(date)='$social'  ORDER BY date DESC");
        } else {
            $userClickMedia = $this->db->query("SELECT count(id) as total, social FROM `log_media` WHERE month(date)='$social'  GROUP BY social");
            $getUserClickMedia = $this->db->query("SELECT log_media.*, fullname FROM log_media JOIN users ON users.id = log_media.user_id WHERE month(date)='$social' ORDER BY date DESC");
        }
        $data = [
            'tittle' => "Logs | Report Management System",
            'menu' => "Log Activities",
            'user' => $user,
            'companySetting' => $companysetting,
            'userLogin' => $userLogin,
            'userLoginGraphBrowser' => $userLoginGraphBrowser->getResultObject(),
            'userLoginGraphiOS' => $userLoginGraphiOS->getResultObject(),
            'userLoginGraphAndroid' => $userLoginGraphAndroid->getResultObject(),
            'userClick' => $userClick,
            'getUserClick' => $getUserClick,
            'userClickMedia' => $userClickMedia,
            'getUserClickMedia' => $getUserClickMedia,
            'login' => $login,
            'page2' => $page,
            'social2' => $social,
        ];

        return view('administrator/logs', $data);
    }

    public function fbClick() {
        $userId = session()->get('user_id');
        $this->db->query("INSERT INTO log_media(social, user_id) VALUES('facebook', $userId) ");
    }

    public function igClick() {
        $userId = session()->get('user_id');
        $this->db->query("INSERT INTO log_media(social, user_id) VALUES('instagram', $userId) ");
    }

    public function ytClick() {
        $userId = session()->get('user_id');
        $this->db->query("INSERT INTO log_media(social, user_id) VALUES('youtube', $userId) ");
    }

    public function inClick() {
        $userId = session()->get('user_id');
        $this->db->query("INSERT INTO log_media(social, user_id) VALUES('linkedin', $userId) ");
    }

    public function chatClick() {
        $userId = session()->get('user_id');
        $this->db->query("INSERT INTO log_media(social, user_id) VALUES('facebook-chat', $userId) ");
    }
    public function creditClick() {
        $userId = session()->get('user_id');
        $this->db->query("INSERT INTO log_media(social, user_id) VALUES('credit', $userId) ");
    }


    public function exportLoginAct($date = null) {
        if ($date == null) {
            $loginAct = $this->db->query("SELECT log_logins.*, fullname, company FROM log_logins JOIN users ON log_logins.user_id = users.id WHERE month(date) = month(now()) ORDER BY date DESC");
        } elseif ($date == date('Y')) {
            $loginAct = $this->db->query("SELECT log_logins.*, fullname, company FROM log_logins JOIN users ON log_logins.user_id = users.id WHERE year(date) = '$date' ORDER BY date DESC");
        } else {
            $loginAct = $this->db->query("SELECT log_logins.*, fullname, company FROM log_logins JOIN users ON log_logins.user_id = users.id WHERE month(date) = '$date' ORDER BY date DESC");
        }
        $date = time();
        $fileName = "Login Log {$date}.xlsx";  
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'No');
		$sheet->setCellValue('B1', 'Client');
		$sheet->setCellValue('C1', 'Company');
		$sheet->setCellValue('D1', 'IP Address');
		$sheet->setCellValue('E1', 'Date');
		$sheet->setCellValue('F1', 'Media');
        $i = 2;
        $no = 1;
        foreach($loginAct->getResultObject() as $row) {
            $sheet->setCellValue('A' . $i, $no++);
            $sheet->setCellValue('B' . $i, $row->fullname);
            $sheet->setCellValue('C' . $i, $row->company);
            $sheet->setCellValue('D' . $i, $row->ip_address);
            $sheet->setCellValue('E' . $i, date('F j, Y - g:i:s a', strtotime($row->date)));
            $sheet->setCellValue('F' . $i, $row->media);
            $i++;
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

    
    public function exportPageAct($date = null) {
        if ($date == null) {
            $loginAct = $this->db->query("SELECT log_pages.*, fullname, company FROM log_pages JOIN users ON log_pages.user_id = users.id WHERE month(date) = month(now()) ORDER BY date DESC");
        } elseif ($date == date('Y')) {
            $loginAct = $this->db->query("SELECT log_pages.*, fullname, company FROM log_pages JOIN users ON log_pages.user_id = users.id WHERE year(date) = '$date' ORDER BY date DESC");
        } else {
            $loginAct = $this->db->query("SELECT log_pages.*, fullname, company FROM log_pages JOIN users ON log_pages.user_id = users.id WHERE month(date) = '$date' ORDER BY date DESC");
        }
        
        $date = time();
        $fileName = "User Page Log {$date}.xlsx";  
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'No');
		$sheet->setCellValue('B1', 'Client');
		$sheet->setCellValue('C1', 'Company');
		$sheet->setCellValue('D1', 'Page');
		$sheet->setCellValue('E1', 'Date');
		$sheet->setCellValue('F1', 'Click');
        $i = 2;
        $no = 1;
        foreach($loginAct->getResultObject() as $row) {
            $sheet->setCellValue('A' . $i, $no++);
            $sheet->setCellValue('B' . $i, $row->fullname);
            $sheet->setCellValue('C' . $i, $row->company);
            $sheet->setCellValue('D' . $i, $row->page);
            $sheet->setCellValue('E' . $i, date('F j, Y - g:i:s a', strtotime($row->date)));
            $sheet->setCellValue('F' . $i, $row->click);
            $i++;
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

    public function exportSocialAct($date = null) {
        if ($date == null) {
            $loginAct = $this->db->query("SELECT log_media.*, fullname, company FROM log_media JOIN users ON log_media.user_id = users.id WHERE month(date) = month(now()) ORDER BY date DESC");
        } elseif ($date == date('Y')) {
            $loginAct = $this->db->query("SELECT log_media.*, fullname, company FROM log_media JOIN users ON log_media.user_id = users.id WHERE year(date) = '$date' ORDER BY date DESC");
        } else {
            $loginAct = $this->db->query("SELECT log_media.*, fullname, company FROM log_media JOIN users ON log_media.user_id = users.id WHERE month(date) = '$date' ORDER BY date DESC");
        }
        
        $date = time();
        $fileName = "User Social and Credit Log {$date}.xlsx";  
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'No');
		$sheet->setCellValue('B1', 'Client');
		$sheet->setCellValue('C1', 'Company');
		$sheet->setCellValue('D1', 'Link');
		$sheet->setCellValue('E1', 'Date');
		$sheet->setCellValue('F1', 'Click');
        $i = 2;
        $no = 1;
        foreach($loginAct->getResultObject() as $row) {
            $sheet->setCellValue('A' . $i, $no++);
            $sheet->setCellValue('B' . $i, $row->fullname);
            $sheet->setCellValue('C' . $i, $row->company);
            $sheet->setCellValue('D' . $i, $row->social);
            $sheet->setCellValue('E' . $i, date('F j, Y - g:i:s a', strtotime($row->date)));
            $sheet->setCellValue('F' . $i, $row->click);
            $i++;
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
}
