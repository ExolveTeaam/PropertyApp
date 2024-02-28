<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\InspectionRequests;
use App\Core\Enums\TransactionStatusEnum;
use App\Core\Enums\InspectionRequestStatusEnum;
use App\Core\Enums\RoleEnum;
use App\Http\Resources\InspectionRequestResource;
use App\Http\Resources\PaymentTransactionResource;

class AdminController extends Controller
{
    use HttpResponses;
    //

    public function AdminDashboard(Request $request){
        $revenue = Transactions::sum('amount');
        $properties = InspectionRequests::all();
        $pending = InspectionRequests::where('status', InspectionRequestStatusEnum::PENDING->value ||InspectionRequestStatusEnum::APPROVED->value )->count();
        $completed = InspectionRequests::where('status', InspectionRequestStatusEnum::COMPLETED->value)->count();
        $inspections = InspectionRequests::with('user')->select('id','location','first_date','status','user_id')->paginate(6);
        $inspections = InspectionRequestResource::collection($inspections);
        return $this->success("Dashboard Data Retrieved Successfully", [
            'revenue' => $revenue,
            'properties' => $properties,
            'upcoming' => $pending,
            'completed' => $completed,
            'inspections' => $inspections
        ]);

    }

    public function AssignInspector(Request $request, $id){
        $inspection = InspectionRequests::find($id);
        $inspection->inspector_id = $request->inspector_id;
        $inspection->save();
        return $this->success("Inspector Assigned Successfully", $inspection);
    }
    public function AdminPaymentDashboard(Request $request){
        $transactions = Transactions::all()->pluck('status');
        $revenue = $transactions->sum('amount');
        $completed = $transactions->where('status',TransactionStatusEnum::SUCCESSFUL->value)->count();
        $outstanding = $transactions->where('status',TransactionStatusEnum::PENDING->value)->count();
        $payments = InspectionRequests::with(['user','transactions','inspectionrequests'])->paginate(6);
        $payments = PaymentTransactionResource::collection($payments);
        return $this->success("Payment Dashboard Data Retrieved Successfully", [
            'revenue' => $revenue,
            'completed' => $completed,
            'outstanding' => $outstanding,
            'payments' => $payments
        ]);

    }

    public function Inspections(Request $request){
        $inspections = InspectionRequests::all()->pluck('status');
        $pending = $inspections->where('status',InspectionRequestStatusEnum::PENDING->value || InspectionRequestStatusEnum::APPROVED->value )->count();
        $completed =  $inspections->where('status',InspectionRequestStatusEnum::COMPLETED->value)->count();
        $rejected =  $inspections->where('status',InspectionRequestStatusEnum::REJECTED->value)->count();
        $total = $inspections->count();
        $inspections  = InspectionRequests::with(['user','transactions','inspectionrequests'])->paginate(6);
        $inspections = InspectionRequestResource::collection($inspections);

        return $this->success("Inspections Data Retrived Successfully",[
            "upcoming" => $pending,
            "completed" => $completed,
            "total" => $total,
            "rejected" => $rejected,
            "inspections" => $inspections
        ]);

    }

    public function Users(Request $request){
        $users = User::all();
        return $this->success("Users Data Retrieved Successfully",

        [ "users" => $users ,
            "active" => $users->count()
        ]);
    }

    public function Inspectors(Request $request){
        $inspectors = User::where('role',RoleEnum::PROPERTYINSPECTOR->value)->get();
        return $this->success("Inspectors Data Retrieved Successfully",[
            "inspectors" => $inspectors,
            "active" => $inspectors->count()
        ]);
    }

}
