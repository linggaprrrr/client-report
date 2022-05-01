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
        $companysetting = $this->db->query("SELECT * FROM company")->getRow();
        $data = [
            'tittle' => "News Announcement | Report Management System",
            'menu' => "News Announcement",
            'user' => $user,
            'news' => $news,
            'allNews' => $allNews,
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
}
