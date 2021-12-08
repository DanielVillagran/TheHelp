<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Invoice
 *
 * Billpocket ANT.
 *
 * @package		Invoice
 * @author		Isaac Torres
 * @version		1.0.0
 */
class Billpocket {

    private $error = array();
    public $pos_order_id;
    public $opId;


    function __construct() {
        $this->ci = & get_instance();
        $this->ci->load->library('session');
        $this->ci->load->database();
    }

    public function txn($data){
        $data_bill = array(
            "cardReq" => array('pan' => $data['card'],'cvv2' => $data['ccv'], 'expDate' => $data['expdate']),
            "amount" => (float) $data['amount'],
            "apiKey" => 'Cwt76ChbO0caqeuXX8zUGgAAF6IxOH28Zm1dBMIKNFlRo5OGAAAK9Q'
        );

        if($data['msi'] > 1){
            $data_bill["paymentPlan"] = $data['msi'];
        }

        $data_string = json_encode($data_bill);

        $ch = curl_init('https://test.bpckt.com/scops/txn');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        $resultBill = curl_exec($ch);
        
        $billpocketJson = json_decode($resultBill);

        $result = array();

        if($billpocketJson == NULL){
            $result = array(
                'status' => -1,
                'message' => 'Fallo en comunicacion. Intente nuevamente'
            );
            if($data['amount'] < 10){
                $result['message'] = 'El importe mínimo es $10 MXN';
            }
        }else{
            foreach ($billpocketJson as $column => $value){
                $column = strtolower($column);
                $result[$column] = $value;
            }
        }

        return (object) $result;
    }

    public function refund($data){
        $data = array(
            "opId" => $data['opId'],
            "apiKey" => 'Cwt76ChbO0caqeuXX8zUGgAAF6IxOH28Zm1dBMIKNFlRo5OGAAAK9Q'
        );
        $data_string = json_encode($data);

        $ch = curl_init('https://test.bpckt.com/scops/txn/refund');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);

        return json_decode($result);
    }

}

/* End of file Billpocket.php */
/* Location: ./application/libraries/Billpocket.php */