<?php 

namespace App\Controllers;

require_once('PHPDecryptXLSXWithPassword.php');

use App\Models\ReportModel;
use App\Models\InvestmentModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;
use Google\Client;
use Hybridauth\Provider\Google;
use Google\Service\Drive;
use Google\Service\Sheets;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;


class API extends ResourceController
{
    protected $reportModel = "";
    protected $userModel = "";
    protected $investmentModel = "";
    protected $db;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->reportModel = new ReportModel();
        $this->investmentModel = new InvestmentModel();    
        $this->db = \Config\Database::connect();    
    }

    public function UPCScanner() {
        return view('scanner/upc_scanner');
    }

    public function manifestAPI($start, $limit) {
        $spreadsheetId = '1Qf3zbb2_xbC1Ayd9qmNn4O0dYCYh4IGusTbyPEJMmVk';
        $range = 'FS'; 
        $client = new Client();        
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $path = 'credentials/manifestautomation-672a1a71c7ca.json';        
        $client->setAuthConfig($path);
        $service = new \Google_Service_Sheets($client);
        $result = $service->spreadsheets_values->get($spreadsheetId, $range);
        try{
            $numRows = $result->getValues() != null ? count($result->getValues()) : 0;
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();
            
            foreach ($values as $idx => $row) {
                if (($idx >= $start && $idx <= $limit) && $row['0'] != "Currently No Data...") {                             
                    $getSpreadsheetId = explode("/", $row[3]);
                    $spreadsheetId = $getSpreadsheetId[5];    
                    $range = 'Sheet1';
                    $client = new Client();                    
                    $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
                    $client->setAccessType('offline');
                    $path = 'credentials/manifestautomation-672a1a71c7ca.json';        
                    $client->setAuthConfig($path);
                    $service = new \Google_Service_Sheets($client);
                    $result = $service->spreadsheets_values->get($spreadsheetId, $range);
                    try{
                        $numRows = $result->getValues() != null ? count($result->getValues()) : 0;
                        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
                        $ManifestData = $response->getValues();                       
                      
                        $checkManifest = $this->db->query("SELECT investments.id, investments.date, investments.cost, investments.client_id, rep.total_retail, users.fullname, users.email, users.company, users.under_comp FROM investments JOIN log_files ON log_files.investment_id = investments.id JOIN users ON users.id = investments.client_id LEFT JOIN (SELECT reports.investment_id, SUM(reports.original_value) as total_retail FROM reports JOIN log_files ON log_files.investment_id = reports.investment_id WHERE log_files.link LIKE '%" .$spreadsheetId. "%' GROUP BY reports.investment_id ) as rep  ON investments.id = rep.investment_id WHERE log_files.link LIKE '%" .$spreadsheetId. "%' GROUP BY investments.id;")->getRow();                          
                        if (!is_null($checkManifest) || !empty($checkManifest)) {                     
                            $orderId = $checkManifest->id;
                            $client = $checkManifest->client_id;
                            $orderDate = $checkManifest->date;                            
                            // check total retail
                            if (is_null($checkManifest->total_retail) || empty($checkManifest->total_retail)) {
                                // cek company and send email
                                date_default_timezone_set('America/Los_Angeles');                                
                                
                                $message  = "<p>Hi ".$checkManifest->company.",</p>";
                                $message .= "<p style='text-align: justify;'>Order on ".date('m/d/Y', strtotime($checkManifest->date))." with the amount of $".number_format($checkManifest->cost, 0)." has begun processing. <br><br><br>Thank you.</p>";                    
                                $mail = new PHPMailer;
                                $mail->isSMTP();        
                                $mail->IsHTML(true);
                                $mail->Host = 'smtp.titan.email';
                                $mail->Port = 587;
                                $mail->SMTPAuth = true;
                                if ($checkManifest->under_comp == '2') {
                                    $mail->Username = 'noreply.info@eliteapp.site';
                                    $mail->Password = 'eliteappinfo1';
                                    $mail->setFrom('noreply.info@eliteapp.site', 'Elite Automation');
                                } else {
                                    $mail->Username = 'noreply.info@swclient.site';
                                    $mail->Password = 'swclientinfo1';
                                    $mail->setFrom('noreply.info@swclient.site', 'Smart FBA Inc');
                                }
                                $mail->addAddress($checkManifest->email, $checkManifest->fullname .' - '.$checkManifest->company.'');
                                
                                $mail->Subject = 'Yout Manifest Order';
                                $mail->Body = $message;
                                if (!empty($checkManifest->email) || !is_null(empty($checkManifest->email))) {                                   
                                    $mail->send();
                                }

                            }
                            $this->db->query("DELETE FROM reports WHERE investment_id = '$orderId' ");
                            $this->db->query("DELETE FROM log_files WHERE investment_id = '$orderId' ");
                            
                            $cost = 0;
                            
                            foreach ($ManifestData as $idx => $data) {
                                
                                if ($idx == 1) {
                                    $tempInvest = str_replace('$', '', $data[5]);
                                    $tempInvest = str_replace(',', '', $tempInvest);
                                        
                                    $investment = array(
                                        "id" => $orderId,
                                        "cost" => $tempInvest,
                                        "date" => $orderDate,
                                        "client_id" => $client
                                    );
                                    
                                    $this->investmentModel->save($investment);
                                } elseif ($idx > 2) {
                                     if (!empty($data[1] || strcasecmp($data[1], "tracking") != 0)) {
                                        if (!empty($data[0])) {
                                            $retail = str_replace('$', '', $data[4]);
                                            $retail = str_replace(',', '', $retail);
                                            $original = str_replace('$', '', $data[5]);
                                            $original = str_replace(',', '', $original);
                                            $cost = str_replace('$', '', $data[6]);
                                            $cost = str_replace(',', '', $cost);
                                            $vendor = "";
                                            if(isset($data[7])) {
                                                $vendor = $data[7];
                                            }
                                            $reportData = array(
                                                "sku" => $data[0],
                                                "item_description" => trim($data[1]),
                                                "cond" => $data[2],
                                                "qty" => $data[3],
                                                "retail_value" => $retail,
                                                "original_value" => $original,
                                                "cost" => $cost,
                                                "vendor" => $vendor,
                                                "client_id" => $client,
                                                "investment_id" => $orderId,
                                                "created_at" => date("Y-m-d H:i:s"),
                                                "updated_at" => date("Y-m-d H:i:s")
                                                
                                            );
                                            $this->reportModel->save($reportData); 
                                        }
                    
                                     }
                                }
                            }
                            
                            
                            $this->db->query("INSERT into log_files(date, file, link, client_id, investment_id) VALUES(NOW(), 'Manifest update automatically', " . $this->db->escape($row[3]) . " ,$client, $orderId) ");
                        }
                        
                        echo json_encode([
                            'status' => '201',
                            'manifest' => 'success',
                            'message' => $checkManifest
                        ]);
                        
                        $ManifestData = null;
                        $values = null;
                        
                        
                    } catch(Exception $e) { 
                       
                        echo json_encode([
                            'status' => '201',
                            'manifest' => 'error',
                            'message' => $e->getMessage()
                        ]);
                        
                    }
                } else {
                    $data = [
                        'status' => '200',
                        'manifest' => 'no data',
                        'message' => 'success'
                    ];
                    
                    // return $this->respond($data, 200, 'No Manifest Updated');
                }
                
            }
            
            // return $result;
        } catch(Exception $e) {
            // TODO(developer) - handle error appropriately
            echo 'Message: ' .$e->getMessage();
        }
    }
     public function syncPandL() {
        try {
            $client = new Client();
            $client->setClientId('497024862437-ngj53f20u4t8mdaq7ha93kn46epq5m3e.apps.googleusercontent.com');
            $client->setClientSecret('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');
            $client->setRedirectUri('https://swclient.site/sync-profits-and-loses');
            $client->addScope(Drive::DRIVE);
            $driveService = new Drive($client); 
            $totalFile = array();           
            if (isset($_GET['code']) || (isset($_SESSION['access_token']) && $_SESSION['access_token'])) {
                if (isset($_GET['code'])) {
                    $client->authenticate($_GET['code']);
                    $_SESSION['access_token'] = $client->getAccessToken();
                } else
                    $client->setAccessToken($_SESSION['access_token']);
            
                    // Show and Find Drive
                    $pageToken = null;
                    $drives = array();
                    do {
                        $response = $driveService->files->listFiles(array(                            
                            'q' => "'1Kgs1ymaopK5_Oa_MnhhXE0cUrWFg4Gtb' in parents",
                            'spaces' => 'drive',
                            'pageToken' => $pageToken,
                            'fields' => 'nextPageToken, files(id, name)',
                        ));                        
                        foreach ($response->files as $drive) {
                            $temp = array(
                                'id' => $drive->id,
                                'drive' => $drive->name,
                            );
                            array_push($drives, $temp);
                        }
                        $pageToken = $response->pageToken;
                    } while ($pageToken != null);
                    
                    
                    // Show file
                    $files = array();
                    foreach($drives as $drv) {
                        do {
                            $response = $driveService->files->listFiles(array(                            
                                'q' => "'" .$drv['id']. "' in parents and mimeType='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' and modifiedTime > '".date('Y-m-d', strtotime('-1 days'))."T23:59:59'",
                                'spaces' => 'drive',
                                'pageToken' => $pageToken,
                                'fields' => 'nextPageToken, files(id, name)',
                            ));                        
                            foreach ($response->files as $file) {
                                $temp = array(
                                    'id' => $file->id,
                                    'filename' => $file->name,
                                );
                                array_push($files, $temp);
                            }
                            $pageToken = $response->pageToken;
                        } while ($pageToken != null);
                    }
                    
                    
                    // $files = array();
                    // do {
                    //     $response = $driveService->files->listFiles(array(                            
                    //         'q' => "'1SekHVBnPuxIiba8ugSoFihuPCVPb0EQ8' in parents and mimeType='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' and modifiedTime > '".date('Y-m-d', strtotime('-1 days'))."T23:59:59'",
                    //         'spaces' => 'drive',
                    //         'pageToken' => $pageToken,
                    //         'fields' => 'nextPageToken, files(id, name)',
                    //     ));                        
                    //     foreach ($response->files as $file) {
                    //         $temp = array(
                    //             'id' => $file->id,
                    //             'filename' => $file->name,
                    //         );
                    //         array_push($files, $temp);
                    //     }
                    //     $pageToken = $response->pageToken;
                    // } while ($pageToken != null);
                    
                    // dd($files);
                    if (count($files) > 0) {                        
                        // download file 
                        ini_set('memory_limit', -1);
                        foreach ($files as $downloadedFile) {
                            $data = null;
                            $fileId = $downloadedFile['id'];
                            // $fileId = '1SG9l5N1zJnXrrjlocb_cpnq2h5dyPVDq';
                            $response = $driveService->files->get($fileId, array(
                                'alt' => 'media'));
                            $file = $response->getBody()->getSize();
                            if ($file > 0) {
                                $content = $response->getBody()->read($file);
                            }
                            file_put_contents($downloadedFile['filename'], $content);                            

                            // unlock file                            
                            try {
                                $encryptedFilePath = $downloadedFile['filename'];
                                $password = 'eComm'; // password to "open" the file
                                $decryptedFile = 'unprotected-'.$downloadedFile['filename'];                            
                                decrypt($encryptedFilePath, $password, $decryptedFile);     
                            } catch (Exception $e) {
                                $decryptedFile = $downloadedFile['filename'];
                            }
                            
                            // cek data
                            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();                            
                            $render->setReadEmptyCells(false);
                            // $sheetName = 
                            $render->setLoadSheetsOnly('Charts');
                            $render->setReadDataOnly(true);
                            $spreadsheet = $render->load($decryptedFile);                                 
                            
                            $data = array();
                            for ($i = 1; $i < 300; $i++) {
                                try {
                                    $temp = array(
                                        $spreadsheet->getActiveSheet()->getCell('A'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('B'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('C'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('D'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('E'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('F'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('G'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('H'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('I'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('J'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('K'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('L'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('M'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('N'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('O'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('P'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('Q'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('R'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('S'.$i)->getCalculatedValue(),
                                    );
                                } catch(Exception $e) {
                                    $temp = array(
                                        $spreadsheet->getActiveSheet()->getCell('A'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('B'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('C'.$i)->getCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('D'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('E'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('F'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('G'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('H'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('I'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('J'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('K'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('L'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('M'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('N'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('O'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('P'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('Q'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('R'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('S'.$i)->getOldCalculatedValue(),
                                    );
                                }
                                if (!is_null($temp)) {
                                    array_push($data, $temp);
                                }
                            }                                               
                            
                                           
                            // Search client_id                            
                            $temp = explode("-", $encryptedFilePath);
                            $result = trim(str_replace('(web)', '', $temp[0]));
                            $result = trim(str_replace(",", "", $result));
                            $findClientOnFileLog = $this->db->query("SELECT fullname, company, client_id FROM log_files JOIN users ON users.id = log_files.client_id WHERE file  LIKE '%".$this->db->escapeLikeString($result)."%' ")->getRow();
                            $findClientOnUsers = $this->db->query("SELECT id, fullname, company  FROM users WHERE fullname LIKE '%".$this->db->escapeLikeString($result)."%' OR company LIKE '%".$this->db->escapeLikeString($result)."%' ")->getRow();
                            
                            if (is_null($findClientOnFileLog) && is_null($findClientOnUsers)) {
                                $result = trim(str_replace("'", " ", $result));
                            
                                $findClientOnFileLog = $this->db->query("SELECT fullname, company, client_id FROM log_files JOIN users ON users.id = log_files.client_id WHERE file LIKE CONCAT('%', '". $result ."' ,'%') ")->getRow();
                                $findClientOnUsers = $this->db->query("SELECT id, fullname, company  FROM users WHERE fullname LIKE CONCAT('%', '". $result ."' ,'%') OR company LIKE CONCAT('%', '". $result ."' ,'%') ")->getRow();
                            }
                            
                            
                            $clientId = "";
                            $clientName = "";
                            $companyName = "";
                            $tempId = "";
                            $tempId2 = "";
                            if (!is_null($findClientOnFileLog)) {                                
                                $tempId = $findClientOnFileLog->client_id;
                                $clientName = $findClientOnFileLog->fullname;
                                $companyName = $findClientOnFileLog->company;
                            } elseif (!is_null($findClientOnUsers)) {
                                $tempId2 = $findClientOnUsers->id;
                                $clientName = $findClientOnUsers->fullname;
                                $companyName = $findClientOnUsers->company;
                            } else {
                                $clientId = "not found";
                                $resp = array(
                                    'client_id' => $clientId,
                                    'client_name' => $clientName,
                                    'company' => $companyName,
                                    'file' => $encryptedFilePath,
                                    'status' => 'failed'
                                );
                                array_push($totalFile, $resp);
                                // delete file
                                if (file_exists($decryptedFile) && file_exists("unprotected-".$decryptedFile)) {
                                    unlink($decryptedFile);
                                    unlink("unprotected-".$decryptedFile);
                                }
                                continue;                                
                            }

                            if (($clientId != "not found") && ($tempId == $tempId2)) {
                                $clientId = $tempId;
                                
                            } else if (($clientId != "not found") && ($tempId != "")) {
                                $clientId = $tempId;
                            } else if (($clientId != "not found") && ($tempId2 != "")) {
                                $clientId = $tempId2;
                            }
                            

                            // Sync Data                                                                                                            
                            // check Last Year 
                            $lastYear = false;                            
                            if (!is_null($data[1][2]) || !empty($data[1][2])) {
                                $lastYear = true;
                            }
                            
                            // upload data
                            $this->db->query("DELETE FROM chart_pl WHERE client_id = '$clientId' ");            
                            $this->db->query("DELETE FROM log_files WHERE client_id = '$clientId' AND log_files.investment_id IS NULL ");            
                            
                            if ($lastYear == true) {    
                                $chartTitle = array();
                                $monthData = array();
                                $type = array();
                                $chart = false;
                                foreach ($data as $row) {
                                    if (!empty($row[0])) {
                                        if (strcasecmp($row[0], "Active SKU") == 0) {
                                            $title = "Active SKUs";                                            
                                        } else if (strcasecmp($row[0], "Sold") == 0 || strcasecmp($row[0], "Solds") == 0) {
                                            $title = "Unit Sold";
                                        } else if (strcasecmp($row[0], "Gross Sales") == 0 || strcasecmp($row[0], "Gross Sale") == 0) {
                                            $title = "Gross Revenue";
                                        } else {
                                            $title = $row[0];
                                        }                    
                                        array_push($chartTitle, $title);                                                            
                                        $chart = true;
                                    } else {
                                        if ($chart == true) {
                                            $month = array();
                                            $chart = false;                                            
                                            for ($i = 2; $i < 19; $i++) {
                                                if ($i != 3) {
                                                    $temp = trim($row[$i]);
                                                    if (strpos($temp, '(') !== false) {
                                                        $temp = str_replace('(', '', $temp);
                                                        $temp = str_replace(')', '', $temp);
                                                        $temp = -1 * abs(trim($temp));
                                                    }
                
                                                    if (strpos($temp, ',') !== false) {
                                                        $temp = str_replace(',', '', $temp);                                      
                                                    }
                                                    array_push($month, trim($temp));
                                                }
                                            }  

                                            $chart = false;
                                            array_push($monthData, $month);  
                                        }
                                        
                                        
                                    }
                                   
                            
                                }
                                $type = ['currency', 'num', 'num', 'currency', 'currency','currency', 'num', 'currency', 'percentage', 'currency', 'percentage', 'currency', 'currency', 'currency', 'currency', 'percentage', 'percentage'];
                                for ($i = 0; $i < count($chartTitle); $i++) {
                                    $this->reportModel->savePLReport($chartTitle[$i], $monthData[$i], $type[$i], $clientId);
                                }    
                            } else {
                                $chartTitle = array();
                                $monthData = array();
                                $type = array();
                                $chart = false;
                                foreach ($data as $row) {
                                    if (!empty($row[0])) {
                                        if (strcasecmp($row[0], "Active SKU") == 0) {
                                            $title = "Active SKUs";
                                        } else if (strcasecmp($row[0], "Sold") == 0 || strcasecmp($row[0], "Solds") == 0) {
                                            $title = "Unit Sold";
                                        } else if (strcasecmp($row[0], "Gross Sales") == 0 || strcasecmp($row[0], "Gross Sale") == 0) {
                                            $title = "Gross Revenue";
                                        } else {
                                            $title = $row[0];
                                        }                                        
                                        array_push($chartTitle, $title);   
                                        $chart = true;
                                    } else {
                                        if ($chart == true) {
                                            $month = array();
                                            $chart = false;                                            
                                            for ($i = 4; $i < 19; $i++) {
                                                $temp = trim($row[$i]);
                                                if (strpos($temp, '(') !== false) {
                                                    $temp = str_replace('(', '', $temp);
                                                    $temp = str_replace(')', '', $temp);
                                                    $temp = -1 * abs(trim($temp));
                                                }
                                                if (strpos($temp, ',') !== false) {
                                                    $temp = str_replace(',', '', $temp);                                      
                                                }
                                                array_push($month, trim($temp));
                                            }
                                            array_push($monthData, $month);   
                                            
                                        }
                                    }
                                }
                                                                
                                $type = ['currency', 'num', 'num', 'currency', 'currency','currency', 'num', 'currency', 'percentage', 'currency', 'percentage', 'currency', 'currency', 'currency', 'currency', 'percentage', 'percentage'];
                                for ($i = 0; $i < count($chartTitle); $i++) {
                                    $this->reportModel->savePLReportExclude($chartTitle[$i], $monthData[$i], $type[$i], $clientId);
                                }
                            }
                            

                            $findPL = $this->db->query("SELECT users.fullname, users.company, users.email FROM log_files JOIN users ON users.id = log_files.client_id WHERE link LIKE '%".$downloadedFile['id']."%' ")->getRow();
                            if (!is_null($findPL) || !empty($findPL)) {
                                date_default_timezone_set('America/Los_Angeles');                                
                                
                                
                                $mail = new PHPMailer;
                                $mail->isSMTP();        
                                $mail->IsHTML(true);
                                $mail->Host = 'smtp.titan.email';
                                $mail->Port = 587;
                                $mail->SMTPAuth = true;
                                if ($findPL->under_comp == '2') {
                                    $message  = '<div><table style="background-color:#fff;padding-top:20px;color:#434245;width:100%;border:0;text-align:center;border-collapse:collapse" class="m_-3284274901574111302background_main"><tbody><tr><td style="vertical-align:top;padding:0"><center><table id="m_-3284274901574111302body" style="border:0;border-collapse:collapse;margin:0 auto;background:#fff;border-radius:8px;margin-bottom:16px"><tbody><tr><td style="width:546px;vertical-align:top;padding-top:32px"><div style="max-width:600px;margin:0 auto"><div style="margin-left:50px;margin-right:50px;margin-bottom:72px;margin-bottom:30px" class="m_-3284274901574111302lg_margin_left_right m_-3284274901574111302xl_margin_bottom"><div style="margin-top:18px" class=""></div><h1>Hi, '.$findPL->company.' <img src="https://ci3.googleusercontent.com/proxy/Fs2AhUjffUUKf86AB2mdll9m_5Iv48fOEF3Tqk6YleLCs0SzLxmRnPrQQ2vo8F7AMRJecQz115N63XjDmYwBocgcMVJuRgnaMb24CVnhD1b534kpJPc=s0-d-e1-ft#https://a.slack-edge.com/80588/img/emoji_2017_12_06/apple/1f44b.png" width="30" height="30" alt="wave emoji" class="CToWUd" data-bit="iit"></h1><p style="font-size:20px;line-height:28px;letter-spacing:-.2px;margin-bottom:28px;word-break:break-word" class="m_-3284274901574111302hero_paragraph">Your P&amp;L has been updated, please check through the app</p><table style="width:100%"><tbody><tr style="width:100%"><td style="width:100%"><span style="display:inline-block;border-radius:4px;background-color:#611f69;width:100%;text-align:center" class="m_-3284274901574111302button_link_wrapper m_-3284274901574111302plum"><a class="m_-3284274901574111302button_link m_-3284274901574111302plum m_-3284274901574111302restyle_button" href="https://eliteapp.site/" style="border-top:13px solid;border-bottom:13px solid;border-right:24px solid;border-left:24px solid;border-radius:4px;background-color:#e36743;color:#fff;font-size:16px;line-height:18px;word-break:break-word;font-weight:700;font-size:14px;border-top:20px solid;border-bottom:20px solid;border-color:#e36743;line-height:14px;letter-spacing:.8px;text-transform:uppercase;box-sizing:border-box;width:100%;text-align:center;display:inline-block;text-align:center;font-weight:900;text-decoration:none!important" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://smartwholesal-ndj8403.slack.com/x-p2788623983059-4560040714915-4892727880467/archives/D04S5T1P8MT&amp;source=gmail&amp;ust=1678505058793000&amp;usg=AOvVaw2OuipL20flZUB50JmeTKZj">Open Elite App</a></span></td></tr></tbody></table></div><div style="margin-left:50px;margin-right:50px;margin-bottom:72px;margin-bottom:30px" class="m_-3284274901574111302lg_margin_left_right m_-3284274901574111302xl_margin_bottom"><table style="margin:0 auto;width:100%"><tbody><tr><td style="box-sizing:border-box;padding:0;width:100%;margin:0 auto"><hr></td></tr></tbody></table></div></div></td></tr></tbody></table></center></td></tr><tr><td class="m_-3284274901574111302email_footer" style="font-size:15px;color:#717274;text-align:center;width:100%"><center><table style="background-color:#fff;border:0;text-align:center;border-collapse:collapse"><tbody><tr><td style="width:546px;vertical-align:top;padding:0"><div style="max-width:600px;margin:0 auto"><div style="padding:0 50px"><table><tbody><tr><td style="text-align:left"><img width="" height="24" style="margin-top:0;margin-right:0;margin-bottom:32px;margin-left:0" src="https://elite-automation.com/wp-content/uploads/2021/06/logo1_clr-1.png" alt="slack logo" class="CToWUd" data-bit="iit"></td></tr></tbody></table><div style="font-size:12px;opacity:.5;color:#696969;text-align:left;line-height:15px;margin-bottom:50px;text-align:left"><div>Elite Automation is the premier provider in Amazon FBA Automation. Our team has the experience and expertise to provide unparalled service to our clients.<br></div><br>All rights reserved.</div></div></div></td></tr></tbody></table></center></td></tr></tbody></table></div>';                            
                                    $mail->Username = 'noreply.info@eliteapp.site';
                                    $mail->Password = 'eliteappinfo1';
                                    $mail->setFrom('noreply.info@eliteapp.site', 'Elite Automation');
                                    $mail->Subject = 'Your P&L Updates - Elite Automation';
                                } else {
                                    $message = '<div class=""><div class="aHl"></div><div id=":ms" tabindex="-1"></div><div id=":mh" class="ii gt" jslog="20277; u014N:xr6bB; 1:WyIjdGhyZWFkLWY6MTc1OTM1MjM5Mjk1NzIyMDEyMiIsbnVsbCxudWxsLG51bGwsbnVsbCxudWxsLG51bGwsbnVsbCxudWxsLG51bGwsbnVsbCxudWxsLG51bGwsW11d; 4:WyIjbXNnLWY6MTc1OTM1MjM5Mjk1NzIyMDEyMiIsbnVsbCxbXV0."><div id=":mg" class="a3s aiL msg-3284274901574111302"><u></u><div><table style="background-color:#fff;padding-top:20px;color:#434245;width:100%;border:0;text-align:center;border-collapse:collapse" class="m_-3284274901574111302background_main"><tbody><tr><td style="vertical-align:top;padding:0"><center><table id="m_-3284274901574111302body" style="border:0;border-collapse:collapse;margin:0 auto;background:#fff;border-radius:8px;margin-bottom:16px"><tbody><tr><td style="width:546px;vertical-align:top;padding-top:32px"><div style="max-width:600px;margin:0 auto"><div style="margin-left:50px;margin-right:50px;margin-bottom:72px;margin-bottom:30px" class="m_-3284274901574111302lg_margin_left_right m_-3284274901574111302xl_margin_bottom"><div style="margin-top:18px" class=""></div><h1>Hi, '.$findPL->company.' <img src="https://ci3.googleusercontent.com/proxy/Fs2AhUjffUUKf86AB2mdll9m_5Iv48fOEF3Tqk6YleLCs0SzLxmRnPrQQ2vo8F7AMRJecQz115N63XjDmYwBocgcMVJuRgnaMb24CVnhD1b534kpJPc=s0-d-e1-ft#https://a.slack-edge.com/80588/img/emoji_2017_12_06/apple/1f44b.png" width="30" height="30" alt="wave emoji" class="CToWUd" data-bit="iit"></h1><p style="font-size:20px;line-height:28px;letter-spacing:-.2px;margin-bottom:28px;word-break:break-word" class="m_-3284274901574111302hero_paragraph">Your P&amp;L has been updated, please check through the app</p><table style="width:100%"><tbody><tr style="width:100%"><td style="width:100%"><span style="display:inline-block;border-radius:4px;background-color:#611f69;width:100%;text-align:center" class="m_-3284274901574111302button_link_wrapper m_-3284274901574111302plum"><a class="m_-3284274901574111302button_link m_-3284274901574111302plum m_-3284274901574111302restyle_button" href="https://swclient.site/" style="border-top:13px solid;border-bottom:13px solid;border-right:24px solid;border-left:24px solid;border-radius:4px;background-color:#1d59a9;color:#fff;font-size:16px;line-height:18px;word-break:break-word;font-weight:700;font-size:14px;border-top:20px solid;border-bottom:20px solid;border-color:#1d59a9;line-height:14px;letter-spacing:.8px;text-transform:uppercase;box-sizing:border-box;width:100%;text-align:center;display:inline-block;text-align:center;font-weight:900;text-decoration:none!important" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://smartwholesal-ndj8403.slack.com/x-p2788623983059-4560040714915-4892727880467/archives/D04S5T1P8MT&amp;source=gmail&amp;ust=1678505058793000&amp;usg=AOvVaw2OuipL20flZUB50JmeTKZj">Open SWClient App</a></span></td></tr></tbody></table></div><div style="margin-left:50px;margin-right:50px;margin-bottom:72px;margin-bottom:30px" class="m_-3284274901574111302lg_margin_left_right m_-3284274901574111302xl_margin_bottom"><table style="margin:0 auto;width:100%"><tbody><tr><td style="box-sizing:border-box;padding:0;width:100%;margin:0 auto"><hr></td></tr></tbody></table></div></div></td></tr></tbody></table></center></td></tr><tr><td class="m_-3284274901574111302email_footer" style="font-size:15px;color:#717274;text-align:center;width:100%"><center><table style="background-color:#fff;border:0;text-align:center;border-collapse:collapse"><tbody><tr><td style="width:546px;vertical-align:top;padding:0"><div style="max-width:600px;margin:0 auto"><div style="padding:0 50px"><table><tbody><tr><td style="text-align:left"><img width="" height="56px" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0" src="https://swclient.site/assets/images/fba-logo.png" alt="slack logo" class="CToWUd" data-bit="iit"></td></tr></tbody></table><div style="font-size:12px;opacity:.5;color:#696969;text-align:left;line-height:15px;margin-bottom:50px;text-align:left"><div>Smart FBA is an Amazon Automation provider which utilizes the wholesale model. We purchase items in build, and get clients approved for restricted brands, which allows for as much growth as possible within the FBA model.<br></div><br>All rights reserved.</div></div></div></td></tr></tbody></table></center></td></tr></tbody></table></div></div><div class="yj6qo"></div></div><div id=":mw" class="ii gt" style="display:none"><div id=":mx" class="a3s aiL"></div></div><div class="hi"></div></div>';
                                    $mail->Username = 'noreply.info@swclient.site';
                                    $mail->Password = 'swclientinfo1';
                                    $mail->setFrom('noreply.info@swclient.site', 'Smart FBA Inc');
                                    $mail->Subject = 'Your P&L Updates - Smart FBA Inc.';
                                }
                                $mail->addAddress($findPL->email, $findPL->fullname .' - '.$findPL->company.'');
                                
                                $mail->Body = $message;
                                if (!empty($findPL->email) || !is_null(empty($findPL->email))) {                                   
                                    $mail->send();
                                }
                            }
                            $link = "https://drive.google.com/file/d/" .$downloadedFile['id']. "/view";
                            $this->db->query("INSERT into log_files(date, file, link, client_id) VALUES(NOW(), " . $this->db->escape($decryptedFile) . "," . $this->db->escape($link) . " , $clientId) ");
                            // delete file
                            if (file_exists($decryptedFile) && file_exists("unprotected-".$decryptedFile)) {
                                unlink($decryptedFile);
                                unlink("unprotected-".$decryptedFile);
                            } else if (file_exists($decryptedFile)) {
                                unlink($decryptedFile);
                            }

                            $resp = array(
                                'client_id' => $clientId,
                                'client_name' => $clientName,
                                'company' => $companyName,
                                'file' => $encryptedFilePath,
                                'status' => 'success'
                            );
                            
                            array_push($totalFile, $resp);
                            
                        }
                    } else {                    
                        echo "There is no P&L file to be uploaded this time.";
                    }
                                
            
            } else {
                $authUrl = $client->createAuthUrl();
                header('Location: ' . $authUrl);
                exit();
            }
            
        } catch(Exception $e) {
            echo "Error Message: ".$e;
        } 
        
        for ($i = 0; $i < count($totalFile); $i++) {
            if ($totalFile[$i]['status'] == 'success') {
                echo $totalFile[$i]['file']." Successfully uploaded to <b>".$totalFile[$i]['client_name']." - ".$totalFile[$i]['company']."</b> <br>";
            } else {
                echo $totalFile[$i]['file']." Failed uploading cause user not found. <br>";
            }
        };      
    }
    

    public function unlockFile() {
        $result = "Jahaan";
        $findClientOnFileLog = $this->db->query("SELECT id FROM users WHERE fullname LIKE '%".$this->db->escapeLikeString($result)."%' OR company LIKE '%".$this->db->escapeLikeString($result)."%'")->getRow();
        dd($findClientOnFileLog);
        if (file_exists("test.xlsx")) {
            unlink("test.xlsx");
        }
        dd();
        $encryptedFilePath = 'Family Ecommerce (web) - Dec 2022.xlsx';
        $password = 'eComm'; 
        $decryptedFilePath = 'unprotected-Family Ecommerce (web) - Dec 2022.xlsx';                            
        decrypt($encryptedFilePath, $password, $decryptedFilePath);                        
    }

    public function testgdrive() {
        try {
            $client = new Client();
            $client->setClientId('497024862437-ngj53f20u4t8mdaq7ha93kn46epq5m3e.apps.googleusercontent.com');
            $client->setClientSecret('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');
            $client->setRedirectUri('http://localhost:8080/test-gdrive');
            $client->addScope(Drive::DRIVE);
            $driveService = new Drive($client);
            $totalFile = array();
            if (isset($_GET['code']) || (isset($_SESSION['access_token']) && $_SESSION['access_token'])) {
                if (isset($_GET['code'])) {
                    $client->authenticate($_GET['code']);
                    $_SESSION['access_token'] = $client->getAccessToken();
                } else
                    $client->setAccessToken($_SESSION['access_token']);
            
                    // Show and Find Drive
                    $pageToken = null;
                    $drives = array();
                    do {
                        $response = $driveService->files->listFiles(array(                            
                            'q' => "'1BR2ObBEbfIFQETa1Jmc1HZuX4GULz3nT' in parents",
                            'spaces' => 'drive',
                            'pageToken' => $pageToken,
                            'fields' => 'nextPageToken, files(id, name)',
                        ));                        
                        foreach ($response->files as $drive) {
                            $temp = array(
                                'id' => $drive->id,
                                'drive' => $drive->name,
                            );
                            array_push($drives, $temp);
                        }
                        $pageToken = $response->pageToken;
                    } while ($pageToken != null);
                    d($drives);
                    
                    // Show file
                    $files = array();
                    foreach($drives as $drv) {
                        do {
                            $response = $driveService->files->listFiles(array(                            
                                'q' => "'" .$drv['id']. "' in parents and mimeType='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' and modifiedTime > '".date('Y-m-d', strtotime('-1 days'))."T00:00:00'",
                                'spaces' => 'drive',
                                'pageToken' => $pageToken,
                                'fields' => 'nextPageToken, files(id, name)',
                            ));                        
                            foreach ($response->files as $file) {
                                $temp = array(
                                    'id' => $file->id,
                                    'filename' => $file->name,
                                );
                                array_push($files, $temp);
                            }
                            $pageToken = $response->pageToken;
                        } while ($pageToken != null);
                    }
                    d($files);
                    
                    if (count($files) > 0) {                        
                        // download file 
                        ini_set('memory_limit', -1);
                        foreach ($files as $downloadedFile) {
                            $data = null;
                            // $fileId = $downloadedFile['id'];
                            $fileId = '1IJWOoIvxitK4aFL4I_gWUe5C4x1BpZUf';
                            $response = $driveService->files->get($fileId, array(
                                'alt' => 'media'));
                            $file = $response->getBody()->getSize();
                            if ($file > 0) {
                                $content = $response->getBody()->read($file);
                            }
                            file_put_contents($downloadedFile['filename'], $content);                            

                            // unlock file                            
                            try {
                                $encryptedFilePath = $downloadedFile['filename'];
                                $password = 'eComm'; // password to "open" the file
                                $decryptedFile = 'unprotected-'.$downloadedFile['filename'];                            
                                decrypt($encryptedFilePath, $password, $decryptedFile);     
                            } catch (Exception $e) {
                                $decryptedFile = $downloadedFile['filename'];
                            }                                            
                            // cek data
                            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();                            
                            $render->setReadEmptyCells(false);
                            // $sheetName = 
                            $render->setLoadSheetsOnly('Charts');
                            $render->setReadDataOnly(true);
                            $spreadsheet = $render->load($decryptedFile);                                 
                            
                            $data = array();
                            for ($i = 1; $i < 300; $i++) {
                                try {
                                    $temp = array(
                                        $spreadsheet->getActiveSheet()->getCell('A'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('B'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('C'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('D'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('E'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('F'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('G'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('H'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('I'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('J'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('K'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('L'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('M'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('N'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('O'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('P'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('Q'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('R'.$i)->getValue(),
                                        $spreadsheet->getActiveSheet()->getCell('S'.$i)->getCalculatedValue(),
                                    );
                                } catch(Exception $e) {
                                    $temp = array(
                                        $spreadsheet->getActiveSheet()->getCell('A'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('B'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('C'.$i)->getCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('D'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('E'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('F'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('G'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('H'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('I'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('J'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('K'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('L'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('M'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('N'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('O'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('P'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('Q'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('R'.$i)->getOldCalculatedValue(),
                                        $spreadsheet->getActiveSheet()->getCell('S'.$i)->getOldCalculatedValue(),
                                    );
                                }
                                if (!is_null($temp)) {
                                    array_push($data, $temp);
                                }
                            }                                               
                            
                            d($data);                            
                            // Search client_id                            
                            $temp = explode("-", $encryptedFilePath);
                            $result = trim(str_replace('(web)', '', $temp[0]));
                            d($result);
                            $findClientOnFileLog = $this->db->query("SELECT client_id FROM log_files WHERE file LIKE '%".$this->db->escapeLikeString($result)."%' ")->getRow();
                            $findClientOnUsers = $this->db->query("SELECT id FROM users WHERE fullname LIKE '%".$this->db->escapeLikeString($result)."%' OR company LIKE '%".$this->db->escapeLikeString($result)."%' ")->getRow();
                            $clientId = "";
                            $tempId = "";
                            $tempId2 = "";
                            if (!is_null($findClientOnFileLog)) {                                
                                $tempId = $findClientOnFileLog->client_id;
                            } elseif (!is_null($findClientOnUsers)) {
                                $tempId2 = $findClientOnUsers->id;
                            } else {
                                $clientId = "not found";
                                $resp = array(
                                  'client' => $clientId,
                                  'file' => $encryptedFilePath,
                                  'status' => 'failed'
                                );
                                array_push($totalFile, $resp);
                                // delete file
                                if (file_exists($decryptedFile) && file_exists("unprotected-".$decryptedFile)) {
                                    unlink($decryptedFile);
                                    unlink("unprotected-".$decryptedFile);
                                } else if (file_exists($decryptedFile)) {
                                    unlink($decryptedFile);
                                }
                                continue;                                
                            }

                            if (($clientId != "not found") && ($tempId == $tempId2)) {
                                $clientId = $tempId;
                            } else if (($clientId != "not found") && ($tempId != "")) {
                                $clientId = $tempId;
                            } else if (($clientId != "not found") && ($tempId2 != "")) {
                                $clientId = $tempId2;
                            }

                            d($clientId);

                            // Sync Data                                                                                                            
                            // check Last Year 
                            $lastYear = false;                            
                            if (!is_null($data[1][2]) || !empty($data[1][2])) {
                                $lastYear = true;
                            }
                            d($lastYear);
                            // upload data
                            $this->db->query("DELETE FROM chart_pl WHERE client_id = '$clientId' ");                                                        
                            if ($lastYear == true) {    
                                $chartTitle = array();
                                $monthData = array();
                                $type = array();
                                $chart = false;
                                foreach ($data as $row) {
                                    if (!empty($row[0])) {
                                        if (strcasecmp($row[0], "Active SKU") == 0) {
                                            $title = "Active SKUs";                                            
                                        } else if (strcasecmp($row[0], "Sold") == 0 || strcasecmp($row[0], "Solds") == 0) {
                                            $title = "Unit Sold";
                                        } else if (strcasecmp($row[0], "Gross Sales") == 0 || strcasecmp($row[0], "Gross Sale") == 0) {
                                            $title = "Gross Revenue";
                                        } else {
                                            $title = $row[0];
                                        }                    
                                        array_push($chartTitle, $title);                                                            
                                        $chart = true;
                                    } else {
                                        if ($chart == true) {
                                            $month = array();
                                            $chart = false;                                            
                                            for ($i = 2; $i < 19; $i++) {
                                                if ($i != 3) {
                                                    $temp = trim($row[$i]);
                                                    if (strpos($temp, '(') !== false) {
                                                        $temp = str_replace('(', '', $temp);
                                                        $temp = str_replace(')', '', $temp);
                                                        $temp = -1 * abs(trim($temp));
                                                    }
                
                                                    if (strpos($temp, ',') !== false) {
                                                        $temp = str_replace(',', '', $temp);                                      
                                                    }
                                                    array_push($month, trim($temp));
                                                }
                                            }  

                                            $chart = false;
                                        }
                                        array_push($monthData, $month);  
                                        
                                    }
                                   
                            
                                }
                                 
                                d($monthData);
                                d($chartTitle);
                                $type = ['currency', 'num', 'num', 'currency', 'currency','currency', 'num', 'currency', 'percentage', 'currency', 'percentage', 'currency', 'currency', 'currency', 'currency', 'percentage', 'percentage'];
                                for ($i = 0; $i < count($chartTitle); $i++) {
                                    $this->reportModel->savePLReport($chartTitle[$i], $monthData[$i], $type[$i], $clientId);
                                }    
                            } else {
                                $chartTitle = array();
                                $monthData = array();
                                $type = array();
                                $chart = false;
                                foreach ($data as $row) {
                                    if (!empty($row[0])) {
                                        if (strcasecmp($row[0], "Active SKU") == 0) {
                                            $title = "Active SKUs";
                                        } else if (strcasecmp($row[0], "Sold") == 0 || strcasecmp($row[0], "Solds") == 0) {
                                            $title = "Unit Sold";
                                        } else if (strcasecmp($row[0], "Gross Sales") == 0 || strcasecmp($row[0], "Gross Sale") == 0) {
                                            $title = "Gross Revenue";
                                        } else {
                                            $title = $row[0];
                                        }                                        
                                        array_push($chartTitle, $title);   
                                        $chart = true;
                                    } else {
                                        if ($chart == true) {
                                            $month = array();
                                            $chart = false;                                            
                                            for ($i = 4; $i < 19; $i++) {
                                                $temp = trim($row[$i]);
                                                if (strpos($temp, '(') !== false) {
                                                    $temp = str_replace('(', '', $temp);
                                                    $temp = str_replace(')', '', $temp);
                                                    $temp = -1 * abs(trim($temp));
                                                }
                                                if (strpos($temp, ',') !== false) {
                                                    $temp = str_replace(',', '', $temp);                                      
                                                }
                                                array_push($month, trim($temp));
                                            }
                                            array_push($monthData, $month);   
                                            
                                        }
                                    }
                                }
                                
                                d($monthData);
                                d($chartTitle);
                                $type = ['currency', 'num', 'num', 'currency', 'currency','currency', 'num', 'currency', 'percentage', 'currency', 'percentage', 'currency', 'currency', 'currency', 'currency', 'percentage', 'percentage'];
                                for ($i = 0; $i < count($chartTitle); $i++) {
                                    $this->reportModel->savePLReportExclude($chartTitle[$i], $monthData[$i], $type[$i], $clientId);
                                }
                            }
                            $link = "https://drive.google.com/file/d/" .$downloadedFile['id']. "/view";
                            $this->db->query("INSERT into log_files(date, file, link, client_id) VALUES(NOW(), " . $this->db->escape($decryptedFile) . "," . $this->db->escape($link) . " , $clientId) ");

                            // delete file
                            if (file_exists($decryptedFile) && file_exists("unprotected-".$decryptedFile)) {
                                unlink($decryptedFile);
                                unlink("unprotected-".$decryptedFile);
                            } else if(file_exists($decryptedFile)) {
                                unlink($decryptedFile);
                            }

                            $resp = array(
                                'client' => $clientId,
                                'file' => $encryptedFilePath,
                                'status' => 'success'
                            );
                            array_push($totalFile, $resp);
                            
                        }
                    } else {                    

                    }
                                
            
            } else {
                $authUrl = $client->createAuthUrl();
                header('Location: ' . $authUrl);
                exit();
            }
            
        } catch(Exception $e) {
            echo "Error Message: ".$e;
        } 

        echo json_encode($totalFile);        
    }

    public function test() {
        $spreadsheetId = '1Qf3zbb2_xbC1Ayd9qmNn4O0dYCYh4IGusTbyPEJMmVk';
        $range = 'test'; 
        $start = 1;
        $limit = 21;
        $client = new Client();        
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $path = 'credentials/manifestautomation-672a1a71c7ca.json';        
        $client->setAuthConfig($path);
        $service = new \Google_Service_Sheets($client);
        $result = $service->spreadsheets_values->get($spreadsheetId, $range);
        try{
            $numRows = $result->getValues() != null ? count($result->getValues()) : 0;
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();
            
            foreach ($values as $idx => $row) {
                if (($idx >= $start && $idx <= $limit) && $row['0'] != "Currently No Data...") {    
                    $getSpreadsheetId = explode("/", $row[5]);
                    
                    $spreadsheetId = $getSpreadsheetId[5];    
                    $range = 'Sheet1';
                    $client = new Client();                    
                    $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
                    $client->setAccessType('offline');
                    $path = 'credentials/manifestautomation-672a1a71c7ca.json';        
                    $client->setAuthConfig($path);
                    $service = new \Google_Service_Sheets($client);
                    $result = $service->spreadsheets_values->get($spreadsheetId, $range);
                    try{
                        $numRows = $result->getValues() != null ? count($result->getValues()) : 0;
                        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
                        $ManifestData = $response->getValues();                       
                        
                        $checkManifest = $this->db->query("SELECT investments.id, investments.date, investments.cost, investments.client_id, rep.total_retail, users.fullname, users.email, users.company, users.under_comp FROM investments JOIN log_files ON log_files.investment_id = investments.id JOIN users ON users.id = investments.client_id LEFT JOIN (SELECT reports.investment_id, SUM(reports.original_value) as total_retail FROM reports JOIN log_files ON log_files.investment_id = reports.investment_id WHERE log_files.link LIKE '%" .$spreadsheetId. "%' GROUP BY reports.investment_id ) as rep  ON investments.id = rep.investment_id WHERE log_files.link LIKE '%" .$spreadsheetId. "%' GROUP BY investments.id;")->getRow();                          
                        if (!is_null($checkManifest) || !empty($checkManifest)) {                     
                            $orderId = $checkManifest->id;
                            $client = $checkManifest->client_id;
                            $orderDate = $checkManifest->date;                            
                            // check total retail
                            // if (is_null($checkManifest->total_retail) || empty($checkManifest->total_retail)) {
                            //     // cek company and send email
                            //     date_default_timezone_set('America/Los_Angeles');                                
                                
                            //     $message  = "<p>Hi ".$checkManifest->company.",</p>";
                            //     $message .= "<p style='text-align: justify;'>Order on ".date('m/d/Y', strtotime($checkManifest->date))." with the amount of $".number_format($checkManifest->cost, 0)." has begun processing. <br><br><br>Thank you, Admin.</p>";                    
                            //     $mail = new PHPMailer;
                            //     $mail->isSMTP();        
                            //     $mail->IsHTML(true);
                            //     $mail->Host = 'smtp.titan.email';
                            //     $mail->Port = 587;
                            //     $mail->SMTPAuth = true;
                            //     if ($checkManifest->under_comp == '2') {
                            //         $mail->Username = 'noreply.info@eliteapp.site';
                            //         $mail->Password = 'eliteappinfo1';
                            //         $mail->setFrom('noreply.info@eliteapp.site', 'Elite Automation');
                            //     } else {
                            //         $mail->Username = 'noreply.info@swclient.site';
                            //         $mail->Password = 'swclientinfo1';
                            //         $mail->setFrom('noreply.info@swclient.site', 'Smart FBA Inc');
                            //     }
                            //     $mail->addAddress($checkManifest->email, $checkManifest->fullname .' - '.$checkManifest->company.'');
                            //     $mail->Subject = 'Yout Manifest Order';
                            //     $mail->Body = $message;
                            //     if (!empty($checkManifest->email) || !is_null(empty($checkManifest->email))) {                                   
                            //         $mail->send();
                            //     }

                            // }
                            d($checkManifest);
                            $this->db->query("DELETE FROM reports WHERE investment_id = '$orderId' ");
                            $this->db->query("DELETE FROM log_files WHERE investment_id = '$orderId' ");
                            
                            $cost = 0;
                            
                            foreach ($ManifestData as $idx => $data) {
                                
                                if ($idx == 1) {
                                    $tempInvest = str_replace('$', '', $data[5]);
                                    $tempInvest = str_replace(',', '', $tempInvest);
                                        
                                    $investment = array(
                                        "id" => $orderId,
                                        "cost" => $tempInvest,
                                        "date" => $orderDate,
                                        "client_id" => $client
                                    );
                                    
                                    $this->investmentModel->save($investment);
                                } elseif ($idx > 3) {
                                    if ($data[0] != '') {
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
                                            "investment_id" => $orderId,
                                        );
                                        $this->reportModel->save($reportData);                
                                    }
                                }
                            }
                            
                            
                            $this->db->query("INSERT into log_files(date, file, link, client_id, investment_id) VALUES(NOW(), 'Manifest update automatically', " . $this->db->escape($row[5]) . " ,$client, $orderId) ");
                        }
                        
                        $ManifestData = null;
                        $values = null;
                        
                        // return $this->respond($data, 200, 'Manifest Updated');
                    } catch(Exception $e) {                                          
                        echo json_encode([
                            'status' => '201',
                            'manifest' => 'error',
                            'message' => $e->getMessage()
                        ]);
                    }
                } else {
                    $data = [
                        'status' => '200',
                        'manifest' => 'no data',
                        'message' => 'success'
                    ];
                    
                    // return $this->respond($data, 200, 'No Manifest Updated');
                }
                
            }
            
            // return $result;
        } catch(Exception $e) {
            // TODO(developer) - handle error appropriately
            echo 'Message: ' .$e->getMessage();
        }
          
    }
    
    public function getUser() {
        $users = $this->reportModel->getAllReports2();
        echo json_encode($users->getResultObject());
    }
    

}

?>