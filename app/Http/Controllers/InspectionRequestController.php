<?php

namespace App\Http\Controllers;

use App\Core\Enums\InspectionRequestStatusEnum;
use Ramsey\Uuid\Guid\Guid;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use App\Models\InspectionRequests;
use App\Core\Enums\TransactionStatusEnum;
use App\Core\Services\Interfaces\ICloudinaryService;
use App\Core\Services\Interfaces\IFlutterWaveService;

class InspectionRequestController extends Controller
{
    use HttpResponses;
    private ICloudinaryService $cloudinaryService;
    private IFlutterWaveService $flutterWaveService;

    public function __construct(ICloudinaryService $cloudinaryService, IFlutterWaveService $flutterWaveService)
    {
        $this->cloudinaryService = $cloudinaryService;
        $this->flutterWaveService = $flutterWaveService;
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
        $transaction->amount = 25_000;
        $transaction->transaction_reference = Guid::uuid4()->toString();
        $transaction->status = TransactionStatusEnum::PENDING->value;
        $transaction->save();

        $inspectionRequest = new InspectionRequests();
        $inspectionRequest->unit_name = $request->input()->unit_name;
        $inspectionRequest->location = $request->input()->location;
        $inspectionRequest->property_type = $request->input()->property_type;
        $inspectionRequest->is_occupied = $request->input()->is_occupied;
        $inspectionRequest->occupants_name = $request->input()->occupants_name;
        $inspectionRequest->occupants_contact = $request->input()->occupants_contact;
        $inspectionRequest->first_date = $request->input()->first_date;
        $inspectionRequest->second_date = $request->input()->second_date;
        $inspectionRequest->transaction_reference = $transaction->transaction_reference;
        $images = $request->images == null ?  $this->cloudinaryService->uploadImage($request) : null;
        $inspectionRequest->images = $images;
        $inspectionRequest->status = InspectionRequestStatusEnum::PENDING->value;
        $inspectionRequest->save();
        return $this->success('Inspection request created successfully',[
            'transaction_reference' => $transaction->transaction_reference,
            'inspection_request_id' => $inspectionRequest->id,
        ]);
    }

    public function VerifyInspectionRequest(Request $request): JsonResponse{
        $request->validate([
            'transaction_reference' => 'required|string',
            'flutterwave_transaction_id' => 'required|string'

        ]);
        $inspectionRequest = InspectionRequests::where('transaction_reference', $request->input()->transaction_reference)->first();
        $transaction = Transactions::where('transaction_reference', $request->input()->transaction_reference)->first();
        if($inspectionRequest == null){
            return $this->error('Inspection request not found');
        }
        if($transaction == null){
            return $this->error('Transaction not found');
        }
        $verifytransaction = $this->flutterWaveService->verifyTransaction($request->input()->flutterwave_transaction_id);
        if(!$verifytransaction->Status){
            return $this->error('Transaction verification failed');
        }
        $transaction->status = TransactionStatusEnum::SUCCESSFUL->value;
        $transaction->external_transaction_id = $request->input()->flutterwave_transaction_id;
        $transaction->payment_method = 'flutterwave';
        $transaction->save();
        return $this->success('Inspection Payment Successfull');
    }
}
