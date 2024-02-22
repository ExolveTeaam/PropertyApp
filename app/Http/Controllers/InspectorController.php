<?php

namespace App\Http\Controllers;

use App\Core\Enums\InspectionRequestStatusEnum;
use App\Models\Reports;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\InspectionRequests;
use App\Core\Services\Interfaces\ICloudinaryService;

class InspectorController extends Controller
{
    //
    use HttpResponses;
    private ICloudinaryService $cloudinaryService;
    public function __construct(ICloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }
    public function CreateReport(Request $request){
        $request->validate([
            'summary' => 'required|string|max:255',
            'property_name' => 'required|string|max:255',
            'images' => 'array',
            'images.*' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'property_type' => 'required|string|max:255',
            'inspection_id' => 'required|integer',
            'door_accessing_property' => 'required|integer|in:1,2,3,4',
            'stairway' => 'required|integer|in:1,2,3,4',
            'door_hinges' => 'required|integer|in:1,2,3,4',
            'door_locks' => 'required|integer|in:1,2,3,4',
            'conduit_wiring' => 'required|integer|in:1,2,3,4',
            'plumbing_leakage' => 'required|integer|in:1,2,3,4',
            'flooring' => 'required|integer|in:1,2,3,4',
            'electrical' => 'required|integer|in:1,2,3,4',
            'kitchen_sink' => 'required|integer|in:1,2,3,4',
            'kitchen_slab' => 'required|integer|in:1,2,3,4',
            'paintings' => 'required|integer|in:1,2,3,4',
            'windows_nets' => 'required|integer|in:1,2,3,4',
            'ceiling_pop' => 'required|integer|in:1,2,3,4',
            'bathtubs' => 'required|integer|in:1,2,3,4',
            'rooms_bedrooms_cabinet' => 'required|integer|in:1,2,3,4',
            'overall' => 'required|integer|in:1,2,3,4',
            'input_criteria' => 'required|string'
        ]);
        $inspection = InspectionRequests::find($request->inspection_id);
        if($inspection == null){
            return $this->error("Inspection Request Not Found");
        }
        $report =  Reports::Create($request->all());
        $report->user_id = $request->user()->id;
        $report->images = $request->images == null ?  $this->cloudinaryService->uploadImage($request) : null;
        $report->save();
        $inspection->status = InspectionRequestStatusEnum::COMPLETED->value;
        $inspection->save();
        return $this->success("Report Created Successfully", $report);
    }

    public function GetReports(Request $request){
        $reports = Reports::where('user_id', $request->user()->id)->get();
        return $this->success("Reports Retrieved Successfully", $reports);
    }




    public function GetReport(Request $request, $id){
        $report = Reports::where('user_id', $request->user()->id)->where('id', $id)->first();
        if($report == null){
            return $this->error("Report Not Found");
        }
        return $this->success("Report Retrieved Successfully", $report);
    }

    public function ChangeInspectionRequestStatus(Request $request, $id){
        $request->validate([
            'status' => 'required|integer|in:2,3'
        ]);
        $inspection = InspectionRequests::find($id);
        if($inspection == null){
            return $this->error("Inspection Request Not Found");
        }
        $inspection->status = $request->status;
        $inspection->save();
        return $this->success("Inspection Request Status Changed Successfully", $inspection);
    }

    public function DashboardRequests(Request $request){
        $inspectionRequests = InspectionRequests::where([
            'user_id' => $request->user()->id,
        ])->get();
        $completed = $inspectionRequests->where('status', InspectionRequestStatusEnum::COMPLETED->value)->count();
        $rejected = $inspectionRequests->where('status', InspectionRequestStatusEnum::REJECTED->value)->count();
        $pending = $inspectionRequests->where('status', InspectionRequestStatusEnum::PENDING->value)->count();
        return $this->success("Dashboard Data Retrieved Successfully", [
            'total' => $inspectionRequests->count(),
            'completed' => $completed,
            'rejected' => $rejected,
            'pending' => $pending,
            "upcoming_inspection" => $inspectionRequests->where('status', InspectionRequestStatusEnum::PENDING->value)
        ]);
    }
}
