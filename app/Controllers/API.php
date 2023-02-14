<?php 

namespace App\Controllers;

require_once('PHPDecryptXLSXWithPassword.php');

use App\Models\ReportModel;
use App\Models\InvestmentModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Sheets;

class API extends ResourceController
{
    protected $reportModel = "";
    protected $userModel = "";
    protected $investmentModel = "";

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->reportModel = new ReportModel();
        $this->investmentModel = new InvestmentModel();        
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
                        
                        $checkManifest = $this->db->query("SELECT investments.id, investments.date, investments.client_id FROM investments JOIN log_files ON log_files.investment_id = investments.id WHERE log_files.link LIKE '%" .$spreadsheetId. "%' ")->getRow();                          
                        if (!is_null($checkManifest) || !empty($checkManifest)) {                     
                            $orderId = $checkManifest->id;
                            $client = $checkManifest->client_id;
                            $orderDate = $checkManifest->date;
                            
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
                                    if (!empty($data[0] || !is_null($data[0]))) {
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
                            
                            
                            $this->db->query("INSERT into log_files(date, file, link, client_id, investment_id) VALUES(NOW(), 'Manifest update automatically', " . $this->db->escape($row[3]) . " ,$client, $orderId) ");
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
                    
                    return $this->respond($data, 200, 'No Manifest Updated');
                }
                
            }
            
            // return $result;
        } catch(Exception $e) {
            // TODO(developer) - handle error appropriately
            echo 'Message: ' .$e->getMessage();
        }
    }

    public function unlockFile() {
        
        $encryptedFilePath = 'test.xlsx';
        $password = '123'; // password to "open" the file
        $decryptedFilePath = 'test.xlsx';
        echo "kwkwkw";
        decrypt($encryptedFilePath, $password, $decryptedFilePath);
    }
}

?>