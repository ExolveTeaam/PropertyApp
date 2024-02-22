<?php
namespace App\Utils\Dtos;

class BaseResponse {
    public bool $Status;
    public string $Message;
    public  $Data;

    public function __construct(bool $status = true, string $message = '',  $data = null) {
        $this->Status = $status;
        $this->Message = $message;
        $this->Data = $data;
    }
}

class BaseFlutterWaveResponse{
    public bool $Status;
    public string $Message;
    public  $Data;

    public function __construct(bool $status = true, string $message = '',  $data = null) {
        $this->Status = $status;
        $this->Message = $message;
        $this->Data = $data;
    }
}

class FlutterwaveRequestDto {
    public string $TransactionId;

    public function __construct(string $transactionId) {
        $this->TransactionId = $transactionId;
    }
}
class FlutterwaveTransactionData {
    public string $id;
    public string $tx_ref;
    public string $flw_ref;
    public string $device_fingerprint;
    public float $amount;
    public string $currency;
    public float $charged_amount;
    public float $app_fee;
    public float $merchant_fee;
    public string $processor_response;
    public string $auth_model;
    public string $ip;
    public string $narration;
    public string $status;
    public string $payment_type;
    public string $fraud_status;
    public string $charge_type;
    public string $created_at;
    public string $account_id;
    public float $amount_settled;
    public object $card;
    public object $customer;

    public function __construct(array $data) {
        $this->id = $data['id'];
        $this->tx_ref = $data['tx_ref'];
        $this->flw_ref = $data['flw_ref'];
        $this->device_fingerprint = $data['device_fingerprint'];
        $this->amount = $data['amount'];
        $this->currency = $data['currency'];
        $this->charged_amount = $data['charged_amount'];
        $this->app_fee = $data['app_fee'];
        $this->merchant_fee = $data['merchant_fee'];
        $this->processor_response = $data['processor_response'];
        $this->auth_model = $data['auth_model'];
        $this->ip = $data['ip'];
        $this->narration = $data['narration'];
        $this->status = $data['status'];
        $this->payment_type = $data['payment_type'];
        $this->fraud_status = $data['fraud_status'];
        $this->charge_type = $data['charge_type'];
        $this->created_at = $data['created_at'];
        $this->account_id = $data['account_id'];
        $this->amount_settled = $data['amount_settled'];
        $this->card = $data['card'];
        $this->customer = $data['customer'];
    }
}
?>
