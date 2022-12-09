<?php 

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AssignReportModel;
use PHPMailer\PHPMailer\PHPMailer;

class Email extends BaseController
{
    protected $userModel = "";
    protected $assignReportModel = "";

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->assignReportModel = new AssignReportModel();
    }

    function sendMail() {        
        $message  = "<p>Hi lingga,</p>";
        $message .= "<p>We want to inform you that we have finished packing your manifest, which will be sent to your Amazon Store immediately. Please find below the box details.</p>";
        $message .= "<html><body>";
        $message .= '<div style="margin: 0 100px 0 100px">';
        $message .= '<table style="font-family:arial,sans-serif;border-collapse:collapse;width:100%">';
        $message .= '<thead><tr><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">SKU/UPC</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">ITEM DESCRIPTION</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">CONDITION</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">ORIGINAL QTY</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">RETAIL VALUE</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">TOTAL ORIGINAL RETAIL</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">TOTAL CLIENT COST</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">VENDOR NAME</th></tr></thead>';
        $message .= '<tbody>';
        $details = $this->assignReportModel->getDetailBox(15);                
            foreach($details->getResultObject() as $det) {
                $message .= '<tr><td style="border:1px solid #000;text-align:center;padding:3px">'.$det->sku.'</td><td style="border:1px solid #000;text-align:center;padding:3px">'.$det->item_description.'</td><td style="border:1px solid #000;text-align:center;padding:3px">NEW</td><td style="border:1px solid #000;text-align:center;padding:3px">'.$det->qty.'</td><td style="border:1px solid #000;text-align:center;padding:3px">$'.number_format($det->retail, 2).'</td><td style="border:1px solid #000;text-align:center;padding:3px">$'.number_format($det->original, 2).'</td><td style="border:1px solid #000;text-align:center;padding:3px">$'.number_format($det->cost, 2).'</td><td style="border:1px solid #000;text-align:center;padding:3px">'.$det->vendor.'</td></tr>';
            }        
            $message .= '<tr><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">'.$det->fba_number.'/'.$det->shipment_number.'</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">'.$det->box_name.'</th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0"></th><th style="border:1px solid #000;text-align:center;padding:5px;background-color:#ff0">'.date('m/d/Y', strtotime($det->date)).'</th></tr>';        
        $message .= '</tbody>';
        $message .= '</table>';        
        $message .= '</div>';
        $message .= '<div style="text-align: -webkit-center; margin-top: 10px">';
        $message .= '<table style="style="text-align: center; margin-top:20px">';
        $message .= '<tr>';
        $message .= '<th>';
        $message .= '<div style="text-align: center; ">';
        $message .= '<img src="https://swclient.site/assets/images/banner.jpeg" style="max-width: 600px;" />';
        $message .= '</div>';
        $message .= '</th>';
        $message .= '<th style="padding-left: 50px;">';
        $message .= '<div style="text-align: center; padding-top: 10px;padding-left: 5px;"> <h1>Access Your manifest Online</h1><a href="https://apps.apple.com/id/app/smart-fba-client-portal/id1618568127" target="_blink"><img src="https://swclient.site/assets/images/appstore.png" style="max-width: 160px;"></a> <a href="https://play.google.com/store/apps/details?id=smartfba.app.smartfbaclientportal" target="_blink"><img src="https://swclient.site/assets/images/available-google-play.png" style="max-width: 172px; max-height: 53px"></a> </div>';
        $message .= '</th>';
        $message .= '</tr>';        
        $message .= '</table>';
        $message .= '</div>';
        
        $message .= "</body></html>";
        // end body

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->IsHTML(true);
        $mail->SMTPDebug = 3;
        $mail->Host = 'smtp.titan.email';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        
        $mail->Username = 'noreply.info@swclient.site';
        $mail->Password = 'swclientinfo1';
        $mail->setFrom('noreply.info@swclient.site', 'Smart FBA Inc');
        $mail->addAddress('lingga@buysmartwholesale.com',' lingga');
        $mail->Subject = 'Box Details';
        $mail->Body = $message;
        if ($mail->send()) {
            echo 'The email message was sent.';
        } else {
            echo 'The email message wasnt sent.';
        }
        
    }

}