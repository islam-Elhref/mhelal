<?php

namespace MYMVC\MODELS;

use MYMVC\LIB\filter;
use MYMVC\LIB\Messenger;
use MYMVC\LIB\Language;

class salesBillsModel extends AbstractModel
{
    use filter;

    protected static $tableName = 'sales_bills';
    protected static $primaryKey = 'bill_id';

    protected $bill_id, $client_id, $payment_type, $payment_status, $user_id, $created;

    protected static $table_schema = [
        'client_id' => self::DATA_TYPE_int,
        'payment_type' => self::DATA_TYPE_int,
        'payment_status' => self::DATA_TYPE_int,
        'user_id' => self::DATA_TYPE_int,
        'created' => self::DATA_TYPE_STR,
    ];

    public function __construct($client_id, $payment_type, $user_id)
    {
        $this->client_id = $this->filterInt($client_id);
        $this->payment_type = $this->filterInt($payment_type);
        $this->payment_status = 0;
        $this->user_id = $this->filterInt($user_id);
        $this->created = date('Y-m-d');
    }


    public function setbill_id($bill_id)
    {
        $this->bill_id = $bill_id;
    }

    public function getbill_id()
    {
        return $this->bill_id;
    }

    public function getclient_id()
    {
        return $this->client_id;
    }

    public function getclientname()
    {
        return $this->clientname;
    }

    public function getcountproduct()
    {
        return $this->countproduct;
    }

    public function getfinalorderprice()
    {
        return $this->finalorderprice;
    }

    public function getpayment_type()
    {
        return $this->payment_type;
    }

    public function getpayment_status()
    {
        return $this->payment_status;
    }


    public function setPaymentStatus(int $payment_status): void
    {
        $this->payment_status = $payment_status;
    }

    public function getuser_id()
    {
        return $this->user_id;
    }

    public function getcreated()
    {
        return $this->created;
    }

    public static function getAll_sales($id = false, $statue = false)
    {
        $where = '';
        if ($id != false && is_numeric($id)) {
            $where = 'WHERE bill_id = ' . $id;
        }
        $statue_where = '';
        if ($statue === true) {
            $statue_where = ' WHERE sales_bills.payment_status = 0';
        }

        return parent::getAll('SELECT sales_bills.* , clients.name as clientname , users.username as username , 
        (SELECT COUNT(*) FROM sales_orders WHERE sales_orders.sales_bill_id = sales_bills.bill_id ) as countproduct , (SELECT SUM(sales_orders.order_price) FROM sales_orders WHERE sales_orders.sales_bill_id = sales_bills.bill_id ) as finalorderprice
        ,(SELECT SUM(sales_receipt.receipt_price) FROM sales_receipt WHERE sales_receipt.bill_id = sales_bills.bill_id ) as receiptpayprice  
        FROM `sales_bills` 
        JOIN clients ON clients.client_id = sales_bills.client_id 
        JOIN users ON users.user_id = sales_bills.user_id ' . $where . $statue_where);
    }

    public static function getsales_bill($id)
    {
        if (is_numeric($id)) {
            $where = 'WHERE bill_id = ' . $id;

            return parent::getbySQL('SELECT sales_bills.* , clients.name as clientname , users.username as username , 
        (SELECT COUNT(*) FROM sales_orders WHERE sales_orders.sales_bill_id = sales_bills.bill_id ) as countproduct , (SELECT SUM(sales_orders.order_price) FROM sales_orders WHERE sales_orders.sales_bill_id = sales_bills.bill_id ) as finalorderprice
        ,(SELECT SUM(sales_receipt.receipt_price) FROM sales_receipt WHERE sales_receipt.bill_id = sales_bills.bill_id ) as receiptpayprice  
        FROM `sales_bills` 
        JOIN clients ON clients.client_id = sales_bills.client_id 
        JOIN users ON users.user_id = sales_bills.user_id ' . $where); // TODO: Change the autogenerated stub
        }
    }


    public function deletePurchasesBills($id)
    {
        if ($id != null || $id != '') {
            $PurchasesBillsexist = self::getByPK($id);
            if (!empty($PurchasesBillsexist)) {
                $PurchasesBillsexist->delete();
            }
        }
    }


}