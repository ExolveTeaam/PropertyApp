<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Ramsey\Uuid\Guid\Guid;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use App\Models\InspectionRequests;
use App\Core\Enums\TransactionStatusEnum;
use App\Core\Enums\InspectionRequestStatusEnum;
use App\Core\Services\Interfaces\ICloudinaryService;
use App\Core\Services\Interfaces\IFlutterWaveService;

class InspectionRequestController extends Controller
{
    use HttpResponses;
    private ICloudinaryService $cloudinaryService;
    private IFlutterWaveService $flutterWaveService;
    private LoggerInterface $logger;

    public function __construct(ICloudinaryService $cloudinaryService, IFlutterWaveService $flutterWaveService, LoggerInterface $logger)
    {
        $this->cloudinaryService = $cloudinaryService;
        $this->flutterWaveService = $flutterWaveService;
        $this->logger = $logger;
    }

    public function CreateInspectionRequest(Request $request): JsonResponse{

        $request->validate([
            'unit_name' => 'required|string',
            'location' => 'required|string',
            'property_type' => 'required|string',
            'images' => 'array',
            'images.*' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_occupied' => 'required|boolean',
            'occupants_name' => 'required|string',
            'occupants_contact' => 'required|string',
            'first_date' => 'required|date',
            'second_date' => 'required|date'
        ]);
        $transaction = new Transactions();
        $transaction->amount = Settings::pluck('inspection_fee')->first();
        $transaction->transaction_reference = Guid::uuid4()->toString();
        $transaction->status = TransactionStatusEnum::PENDING->value;
        $transaction->save();
        $flutterwave = $this->flutterWaveService->InitializeFLutterWavePayment($request->user(),$transaction->amount, $transaction->transaction_reference);
        $inspectionRequest = new InspectionRequests();
        $inspectionRequest->unit_name = $request->unit_name;
        $inspectionRequest->location = $request->location;
        $inspectionRequest->property_type = $request->property_type;
        $inspectionRequest->is_occupied = $request->is_occupied;
        $inspectionRequest->occupants_name = $request->occupants_name;
        $inspectionRequest->occupants_contact = $request->occupants_contact;
        $inspectionRequest->first_date = $request->first_date;
        $inspectionRequest->second_date = $request->second_date;
        $inspectionRequest->transaction_reference = $transaction->transaction_reference;
        $images = $request->images == null ?  $this->cloudinaryService->uploadImage($request) : null;
        $inspectionRequest->images = $images;
        $inspectionRequest->status = InspectionRequestStatusEnum::PENDING->value;
        $inspectionRequest->save();
        return $this->success('Inspection request created successfully',[
            'transaction_reference' => $transaction->transaction_reference,
            'payment_link' =>  $flutterwave->json()->message,
            'inspection_request_id' => $inspectionRequest->id,
        ]);


    }

    public function VerifyInspectionRequest(Request $request): JsonResponse{
        $request->validate([
            "status" => "required|string",
            "transaction_id" => "required"
        ]);
        $status = $request->query('status');

        // Check if the transaction is successful
        if ($status != 'successful') {
            $this->logger->info('Flutterwave transaction failed', ['transactionID' => $request->query('transaction_id')]);
            return $this->error('Payment failed.');
        }
            $transactionID = $request->query('transaction_id');
            if (!$transactionID) {
                $this->logger->info('Flutterwave transaction failed', ['transactionID' => $request->query('transaction_id')]);
            return $this->error('Payment failed.');
        }

        $verifytransaction = $this->flutterWaveService->verifyTransaction($request->query('flutterwave_transaction_id'));
        if(!$verifytransaction->Status){
            return $this->error('Transaction verification failed');
        }
        $inspectionRequest = InspectionRequests::where('transaction_reference', $verifytransaction->Data->tx_ref)->first();
        $transaction = Transactions::where('transaction_reference', $verifytransaction->Data->tx_ref)->first();
        if($inspectionRequest == null){
            return $this->error('Inspection request not found');
        }
        if($transaction == null){
            return $this->error('Transaction not found');
        }
        $transaction->status = TransactionStatusEnum::SUCCESSFUL->value;
        $transaction->external_transaction_id = $request->transaction_id;
        $transaction->payment_method = 'flutterwave';
        $transaction->save();
        return $this->success('Inspection Payment Successfull');
    }
}
