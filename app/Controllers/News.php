<?php

namespace App\Controllers;

use App\Models\NewsModel;
use App\Models\UserModel;;

class News extends BaseController
{
    protected $userModel = "";
    protected $newsModel = "";
    protected $db = "";

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->newsModel = new NewsModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return redirect()->to(base_url('/login'));
        }
        $user = $this->userModel->find($userId);
        $news = $this->newsModel->getLastNews();
        $allNews = $this->newsModel->getNews();
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();

        $data = [
            'tittle' => "Announcement | Report Management System",
            'menu' => "Announcement",
            'user' => $user,
            'news' => $news,
            'allNews' => $allNews,
            'companySetting' => $companysetting
        ];
        return view('client/news', $data);
    }

    public function news()
    {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return redirect()->to(base_url('/login'));
        }
        $user = $this->userModel->find($userId);
        $news = $this->newsModel->getLastNews();
        $allNews = $this->newsModel->getNews();
        $notifications = $this->db->query("SELECT * FROM push_notifications ORDER BY date DESC");
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();
        $data = [
            'tittle' => "Announcements | Report Management System",
            'menu' => "Announcements",
            'user' => $user,
            'news' => $news,
            'allNews' => $allNews,
            'notifications' => $notifications,
            'companySetting' => $companysetting
        ];
        return view('administrator/news', $data);
    }

    public function createNews()
    {
        $post = $this->request->getVar();
        $this->newsModel->save([
            "title" => $post['title'],
            "message" => $post['message'],
        ]);
        return redirect()->back()->with('success', 'News Successfully Created!');
    }

    public function showNews($id)
    {
        $news = $this->newsModel->find($id);
        echo json_encode($news);
    }

    public function updateNews()
    {
        $post = $this->request->getVar();
        $temp = $post['message'];

        $this->newsModel->save([
            'id' => $post['id'],
            'title' => $post['title'],
            'message' => $post['message']
        ]);
        return redirect()->back()->with('success', 'News Successfully Created!');
    }

    public function deleteNews($id)
    {
        $this->newsModel->delete($id);
        return redirect()->back()->with('delete', 'News Successfully Created!');
    }

    public function getPushNotifications() {
        return 0;
    }

    public function pushNotification() {
        $title = $this->request->getVar('title');
        $body = $this->request->getVar('body');
        $this->db->query("INSERT INTO push_notifications(title, body, status) VALUES(". $this->db->escape($title) .", ". $this->db->escape($body) .", 1) ");
        $getDevicesToken = $this->db->query("SELECT token FROM device_token");
        $regists = array();
        if ($getDevicesToken->getNumRows() > 0) {
            foreach ($getDevicesToken->getResultArray() as $tokenApp) {
                array_push($regists, $tokenApp['token']);
            }
        }
        $curl = curl_init();
        $authKey = "key=AAAAQ5YfKhs:APA91bH4aSGkr65YAi6DWa2hnzSBO_rdyJyNs48Mr0l5T9vs_4VXEdQQ2x4zvitmZtNzBguWJEMHhAIbODzvBX3lMZ-YbxVn5hjKMMBlc3ikOTAyxysdEJZ5g7T_apNzoaZO01NI2R_s";
        $registration_ids = json_encode($regists);
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => '{
                        "registration_ids": ' . $registration_ids . ',
                        "notification": {
                            "title": "'. $title .'",
                            "body": "'. $body .'"
                        }
                    }',
        CURLOPT_HTTPHEADER => array(
            "Authorization: " . $authKey,
            "Content-Type: application/json",
            "cache-control: no-cache"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return redirect()->back()->with('successPush', 'News Successfully Created!');
    }

    public function sendDeviceToken() {
        $token = $this->request->getVar('token');
        $this->db->query("INSERT IGNORE INTO device_token(token) VALUES ('$token') ");
    }

    
}
