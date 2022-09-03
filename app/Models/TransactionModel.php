<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{   
    protected $table = 'transactions';
    protected $allowedFields = ['settlement-id', 'settlement-start-date', 'settlement-end-date', 'deposit-date', 'total-amount', 'currency', 'transaction-type', 'vendor', 'order-id', 'adjustment-id', 'shipment-id', 'marketplace-name', 'amount-type', 'amount-description', 'amount', 'fulfillment-id', 'posted-date', 'posted-date-time', 'order-item-code', 'merchant-order-item-id', 'merchant-adjustment-item-id', 'sku', 'quantity-purchased', 'promotion-id', 'transaction-master-id'];
    protected $db = "";

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getTransactionUploaded($id, $date1, $date2, $user) {
        $query = $this->db->query("SELECT transactions.* FROM transactions JOIN transactions_master ON `transaction-master-id` = transactions_master.id WHERE transactions_master.id = '$id' AND `user_id` = '$user' AND `posted-date` BETWEEN '$date1' AND '$date2' ");
        return $query;
    }
}