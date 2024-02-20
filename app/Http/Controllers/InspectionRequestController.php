<?php

namespace App\Http\Controllers;

use App\Core\Services\Interfaces\ICloudinaryService;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use App\Models\InspectionRequests;

class InspectionRequestController extends Controller
{
    use HttpResponses;
    private ICloudinaryService $cloudinaryService;

    public function __construct(ICloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    public function CreateInspectionRequest(Request $request): JsonResponse{

        $request->validate([
            'unit_name' => 'required|string',
            'location' => 'required|string',
            'property_type' => 'required|string',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_occupied' => 'required|boolean',
            'occupants_name' => 'required|string',
            'occupants_contact' => 'required|string',
            'first_date' => 'required|date',
            'second_date' => 'required|date'
        ]);

        $inspectionRequest = new InspectionRequests();
        $inspectionRequest->unit_name = $request->input()->unit_name;
        $inspectionRequest->location = $request->input()->location;
        $inspectionRequest->property_type = $request->input()->property_type;
        $inspectionRequest->is_occupied = $request->input()->is_occupied;
        $inspectionRequest->occupants_name = $request->input()->occupants_name;
        $inspectionRequest->occupants_contact = $request->input()->occupants_contact;
        $inspectionRequest->first_date = $request->input()->first_date;
        $inspectionRequest->second_date = $request->input()->second_date;
        // TODO - Add transaction reference from generating new transaction
        $images = $this->cloudinaryService->uploadImage($request);
        $inspectionRequest->images = $images;
        $inspectionRequest->save();
        return $this->success('Inspection request created successfully');
    }
}
