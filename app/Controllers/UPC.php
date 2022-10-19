<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UPCModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class UPC extends BaseController
{
    protected $upcModel = "";
    protected $userModel = "";

    public function __construct() {
        $this->upcModel = new UPCModel();
        $this->userModel = new UserModel();
    }   

    public function index() {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $totalUPC = $this->db->query("SELECT COUNT(*) as total FROM upc")->getRow();
        $user = $this->userModel->find($userId);
        $data = [
            'tittle' => 'UPC Databaset | Report Management System',
            'menu' => 'UPC Databaset',            
            'user' => $user,
            'totalUPC' => $totalUPC->total
        ];

        return view('administrator/upc', $data);
    }

    public function uploadUPC() {
        $file = $this->request->getFile('upc');        
        $ext = $file->getClientExtension();        
        if ($ext == 'xls') {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $spreadsheet = $render->load($file);
        $data = $spreadsheet->getActiveSheet()->toArray();
        $UPCList = array();
        ini_set('memory_limit','-1');
        ini_set('max_execution_time', '30000');
        foreach ($data as $idx => $row) {
            if ($idx > 0 && !empty($row[0])) {
                $upc = $row[0];
                $asin = $row[1];
                $itemDesc = $row[2];
                $retaiLValue = str_replace('$', '', trim($row[3]));
                $retaiLValue = str_replace(',', '.', $retaiLValue);
                $vendor = $row[4];

                $temp = [                        
                    'upc' => $upc,
                    'asin' => $asin,
                    'item_description' => $itemDesc,
                    'retail_value' => $retaiLValue,
                    'vendor_name' => $vendor,
                ];
                $this->upcModel->ignore(true)->insert($temp);            
            }
        }
        return redirect()->back()->with('success', 'UPC Successfully Uploaded!');
    }

    public function loadUPC() {
        $params['draw'] = $_REQUEST['draw'];
        $start = $_REQUEST['start'];
        $length = $_REQUEST['length'];
        $search_value = $_REQUEST['search']['value'];
        ini_set('memory_limit', '-1');
        if(!empty($search_value)){
            $total_count = $this->db->query("SELECT upc, asin, item_description, CONCAT('$',retail_value) as retail_value, vendor_name from upc WHERE upc like '%".$search_value."%' OR item_description like '%".$search_value."%' OR vendor_name like '%".$search_value."%' ")->getResult();
 
            $data = $this->db->query("SELECT upc, asin, item_description, CONCAT('$',retail_value) as retail_value, vendor_name from upc WHERE upc like '%".$search_value."%' OR item_description like '%".$search_value."%' OR vendor_name like '%".$search_value."%'  limit $start, $length")->getResult();
        }else{
            $total_count = $this->db->query("SELECT upc, asin, item_description, CONCAT('$',retail_value) as retail_value, vendor_name from upc")->getResult();
            $data = $this->db->query("SELECT upc, asin, item_description, CONCAT('$',retail_value) as retail_value, vendor_name from upc limit $start, $length")->getResult();
        }
        $json_data = array(
            "draw" => intval($params['draw']),
            "recordsTotal" => count($total_count),
            "recordsFiltered" => count($total_count),
            "data" => $data   // total data array
        );

        echo json_encode($json_data);
    }
    
    public function findUPC() {
        $upc = $this->request->getVar('upc');
        $getUPC = $this->upcModel->where('upc', $upc)->get();
        $getUPC = $getUPC->getFirstRow();                

        if (!empty($getUPC)) {
            echo json_encode($getUPC);
        } else {
            echo json_encode(0);
        }
    }

    public function saveBox() {
        $post = $this->request->getVar();

        $this->upcModel->insertBox([
            'box_name' => $post['box'],
            'category' => $post['category'],
            'description' => 'BOX #'.$post['box'].'-'.$post['category'],
            'user_id' => session()->get('user_id')      
        ]);
    }

    public function saveLog() {
        $post = $this->request->getVar();
        $category = "";
        $itemCost = 0;
        
        if (!empty($post['desc'])) {
            if ($post['desc'] != 'ITEM NOT FOUND') {
                if ($post['category'] == '1') {
                    $category = "shoes";
                    $itemCost = $post['retail'] / 3;
                } else {
                    $category = "clothes";
                    $itemCost = $post['retail'] / 4;
                }
                $item = [
                    'sku' => $post['upc'],
                    'item_description' => $post['desc'],
                    'category' => $category,
                    'cond' => 'NEW',
                    'qty' => '1',
                    'retail' => $post['retail'],
                    'original' => $post['retail'],
                    'cost' => $itemCost,
                    'box_name' => $post['box'],
                    'vendor' => $post['vendor'],
                ];
            } else {
                $item = [
                    'sku' => $post['upc'],
                    'item_description' => $post['desc'],
                    'box_name' => $post['box'],             
                ];
            }
        }
            
        $this->upcModel->insertBoxItem($item);
        
    }

    public function needToUpload() {
        $userId = session()->get('user_id');
        if (is_null($userId)) {
            return view('login');
        }
        $user = $this->userModel->find($userId);
        $post = $this->request->getVar();
        
        if (!empty($post)) {
            $date1 = date('Y-m-d 00:00:00', strtotime($post['start']));             
            $date2 = date('Y-m-d 00:00:00', strtotime($post['end'] . "+1 day"));
            $boxes = $this->upcModel->historyBox($date1, $date2);
            $totalBox = $boxes->getNumRows();
            $total = $this->upcModel->totalQty($date1, $date2);
            
            $total = 
            $data = [
                'tittle' => 'Need To Upload | Report Management System',
                'menu' => 'Need To Upload',            
                'user' => $user,
                'date1' => $post['start'],
                'date2' => $post['end'],
                'boxes' => $boxes,
                'totalBox' => $totalBox,
                'totalQty' => $total->qty,
                'totalOriginal' => $total->original,
                'totalCost' => $total->cost
                
            ];
        } else {
            $boxes = $this->upcModel->historyBox();
            $totalBox = $boxes->getNumRows();
            $total = $this->upcModel->totalQty();
            $data = [
                'tittle' => 'Need To Upload | Report Management System',
                'menu' => 'Need To Upload',            
                'user' => $user,
                'boxes' => $boxes,
                'totalBox' => $totalBox,
                'totalQty' => $total->qty,
                'totalOriginal' => $total->original,
                'totalCost' => $total->cost
            ];
        }
        

        return view('administrator/need_to_upload', $data);
    }   
    
    public function createNeedToUpload() {
        $post = $this->request->getVar();
        
        $date = date('m-d-Y');
        $fileName = "Need to Upload - {$date}.xlsx";  
        $spreadsheet = new Spreadsheet();
        
        if (empty($post['box_id'])) {
            return redirect()->back()->with('error', 'There is no box');
        }
        // Styling
        $spreadsheet->getActiveSheet()->getStyle('A:A')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('B:B')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('C:C')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('D:D')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('E:E')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('F:F')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('G:G')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('H:H')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('I:I')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $spreadsheet->getActiveSheet()->getStyle('A3:I3')
            ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A3:I3')
            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A3:I3')
            ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A3:I3')
            ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
        $spreadsheet->getActiveSheet()->getStyle('A3:I3')
            ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle('A3:I3')
            ->getFill()->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);
        $spreadsheet->getActiveSheet()->getStyle('A3:I3')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('B:B')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
        $spreadsheet->getActiveSheet()->getStyle('F:F')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
        $spreadsheet->getActiveSheet()->getStyle('G:G')->getNumberFormat()
        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
        $spreadsheet->getActiveSheet()->getStyle('H:H')->getNumberFormat()
        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);


		$sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A3', 'FNSKU');
		$sheet->setCellValue('B3', 'UPC/SKU');
		$sheet->setCellValue('C3', 'ITEM DESCRIPTION');
		$sheet->setCellValue('D3', 'CONDITION');
		$sheet->setCellValue('E3', 'ORIGINAL QTY');
        $sheet->setCellValue('F3', 'RETAIL VALUE');
        $sheet->setCellValue('G3', 'TOTAL ORIGINAL RETAIL');
        $sheet->setCellValue('H3', 'TOTAL CLIENT COST');
        $sheet->setCellValue('I3', 'VENDOR');
        $no = 4;
        for ($i = 0; $i < count($post['box_id']); $i++) {
            $getBox = $this->upcModel->findBox($post['box_id'][$i]);
            foreach($getBox->getResultObject() as $row) {                
                $sheet->setCellValue('B' . $no, $row->sku);
                $sheet->setCellValue('C' . $no, $row->item_description);                
                $sheet->setCellValue('D' . $no, $row->cond);
                $sheet->setCellValue('E' . $no, $row->qty);
                $sheet->setCellValue('F' . $no, $row->retail);
                $sheet->setCellValue('G' . $no, $row->original);
                $sheet->setCellValue('H' . $no, $row->cost);
                $sheet->setCellValue('I' . $no, $row->vendor);

                // styling
                $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
                    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
                    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
                    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
                    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                
                $no++;
            }
            $sheet->setCellValue('C' . $no, $row->description);                
            $sheet->setCellValue('D' . $no, $row->box_name);
            $sheet->setCellValue('I' . $no, date('m/d/Y', strtotime($row->date)));                            
            
            // styling
            $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
            ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
                ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
                ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
                ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
                ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
            ->getFill()->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);
            $no++;
            $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
            ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
                ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
                ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
                ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
                ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $spreadsheet->getActiveSheet()->getStyle('A'.$no.':I'.$no)
            ->getFill()->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);
            $no++;
        }
        $col = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
        for ($j = 0; $j < count($col); $j++) {
            $spreadsheet->getActiveSheet()->getStyle($col[$j].'3:'.$col[$j].''.$no)
                ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle($col[$j].'3:'.$col[$j].''.$no)
                ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle($col[$j].'3:'.$col[$j].''.$no)
                ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle($col[$j].'3:'.$col[$j].''.$no)
                ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
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

    public function extractUnkownUPC() {
        $time = time();        
        $fileName = "Unlisted UPC {$time} .xlsx";  
        $getUPC = $this->upcModel->getUnkownUPC();
        $spreadsheet = new Spreadsheet();
         // Styling
         $spreadsheet->getActiveSheet()->getStyle('A:A')
         ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

         $spreadsheet->getActiveSheet()->getStyle('A1:E1')
             ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
         $spreadsheet->getActiveSheet()->getStyle('A1:E1')
             ->getFill()->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);
         $spreadsheet->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
         
 
		$sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'UPC/SKU');
		$sheet->setCellValue('B1', 'ASIN');
		$sheet->setCellValue('C1', 'ITEM DESCRIPTION');
		$sheet->setCellValue('D1', 'RETAIL VALUE');
		$sheet->setCellValue('E1', 'VENDOR');
        $no = 2;
        if ($getUPC->getNumRows() > 0) {
            foreach ($getUPC->getResultObject() as $row) {
                $sheet->setCellValue('A' . $no, $row->sku);
                $no++;
            }
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

    public function refreshUnknownUPC() {
        $getAllUPC = $this->upcModel->getUnkownUPC();
        $x = 0;
        if ($getAllUPC->getNumRows() > 0) {
            foreach($getAllUPC->getResultObject() as $row) {   
                $getItem = $this->upcModel->where('upc', $row->sku)->get();                
                $item = $getItem->getFirstRow();
                
                if (!is_null($item)) {
                    // replace desc                                   
                    $cost = 0;                    
                    if ($row->category == 'SHOES') {
                        $cost = $item->retail_value / 3;
                    } else {
                        $cost = $item->retail_value / 4;
                    }
                    $x++;
                    $this->upcModel->updateUPCDesc($item->upc, $item->item_description, $item->retail_value, $cost, $item->vendor_name);
                }
            }
        }
        echo json_encode($x);
    }

    public function changeBoxCategory() {
        $box = $this->request->getVar('box');
        $category = $this->request->getVar('category');
        $desc = "BOX #". $box ."-". $category;
        $divider = 1;
        if ($category == 'CLOTHES') {
            $divider = 4;
        } else {
            $divider = 3;
        }        
        $getItem = $this->db->query("SELECT assign_report_details.* FROM assign_report_box JOIN assign_report_details ON assign_report_box.box_name = assign_report_details.box_name WHERE assign_report_box.box_name='$box'");
        $totalCostLeft = 0;
        if ($getItem->getNumRows() > 0) {
            foreach($getItem->getResultObject() as $item) {
                if ($item->item_description != 'ITEM NOT FOUND') {
                    $costLeft = $item->retail / $divider;     
                    $this->db->query("UPDATE assign_report_details SET cost = '$costLeft' WHERE sku='$item->sku' ");
                    $totalCostLeft = $totalCostLeft + $costLeft;
                }
            }
        }        
        $this->db->query("UPDATE assign_report_box SET category='$category', description='$desc' WHERE box_name='$box' ");
        
    }

}