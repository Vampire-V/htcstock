<?php

namespace App\Http\Requests\Legal;

use App\Enum\ContractEnum;
use App\Services\Legal\Interfaces\ApprovalServiceInterface;
use App\Services\Legal\Interfaces\ContractRequestServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreApprovalContract extends FormRequest
{
    protected $contractRequestService;
    protected $approvalService;
    public function __construct(
        ContractRequestServiceInterface $contractRequestServiceInterface,
        ApprovalServiceInterface $approvalServiceInterface
    ) {
        $this->contractRequestService = $contractRequestServiceInterface;
        $this->approvalService = $approvalServiceInterface;
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        $contract = $this->contractRequestService->find($request->route()->parameters['id']);
        // $levelApproval = $this->approvalService->approvalByDepartment($contract->createdBy->department);
        $deptOfUser = auth()->user()->department()->get();
        if ($contract->status === ContractEnum::D && $contract->created_by === \auth()->id()) {
            return true;
        }
        if ($contract->status === ContractEnum::RQ || $contract->status === ContractEnum::CK) {
            // $userApproval = $levelApproval->where('levels', 1)->first()->user;
            if ($deptOfUser->where('id',240)->count() > 0) {
                return true;
            }
        }
        if ($contract->status === ContractEnum::P) {
            // $userApproval = $levelApproval->where('levels', 2)->first()->user;
            if ($deptOfUser->where('id',236)->count() > 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
