<?php

namespace App\Http\Controllers\Legal;

use App\Enum\ApprovalEnum;
use App\Enum\ContractEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Legal\StoreApprovalContract;
use App\Http\Requests\Legal\StoreContractRequest;
use App\Mail\Legal\ContractApproval;
use App\Models\Legal\LegalContract;
use App\Services\Legal\Interfaces\ActionServiceInterface;
use App\Services\Legal\Interfaces\AgreementServiceInterface;
use App\Services\Legal\Interfaces\ApprovalDetailServiceInterface;
use App\Services\Legal\Interfaces\ApprovalServiceInterface;
use App\Services\Legal\Interfaces\ContractDescServiceInterface;
use App\Services\Legal\Interfaces\ContractRequestServiceInterface;
use App\Services\Legal\Interfaces\PaymentTypeServiceInterface;
use App\Services\Legal\Interfaces\SubtypeContractServiceInterface;
use App\Services\Utils\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class ContractRequestController extends Controller
{
    protected $actionService;
    protected $agreementService;
    protected $contractRequestService;
    protected $fileService;
    protected $contractDescService;
    protected $paymentTypeService;
    protected $subtypeContractService;
    protected $approvalService;
    protected $approvalDetailService;
    public function __construct(
        ActionServiceInterface $actionServiceInterface,
        AgreementServiceInterface $agreementServiceInterface,
        ContractRequestServiceInterface $contractRequestServiceInterface,
        FileService $fileService,
        ContractDescServiceInterface $contractDescServiceInterface,
        PaymentTypeServiceInterface $paymentTypeServiceInterface,
        SubtypeContractServiceInterface $subtypeContractServiceInterface,
        ApprovalServiceInterface $approvalServiceInterface,
        ApprovalDetailServiceInterface $approvalDetailServiceInterface
    ) {
        $this->actionService = $actionServiceInterface;
        $this->agreementService = $agreementServiceInterface;
        $this->contractRequestService = $contractRequestServiceInterface;
        $this->fileService = $fileService;
        $this->contractDescService = $contractDescServiceInterface;
        $this->paymentTypeService = $paymentTypeServiceInterface;
        $this->subtypeContractService = $subtypeContractServiceInterface;
        $this->approvalService = $approvalServiceInterface;
        $this->approvalDetailService = $approvalDetailServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->all();
        $status = ContractEnum::$types;
        // $selectedStatus = collect($request->status);
        $selectedAgree = collect($request->agreement);
        try {
            $agreements = $this->agreementService->dropdown();
            $contractsD = $this->contractRequestService->filterDraft($request);
            $contractsRQ = $this->contractRequestService->filterRequest($request);
            $contractsCK = $this->contractRequestService->filterChecking($request);
            $contractsP = $this->contractRequestService->filterProviding($request);
            $contractsCP = $this->contractRequestService->filterComplete($request);
            // dd($contractsRQ);

            return \view('legal.ContractRequestForm.index', \compact('contractsD','contractsRQ', 'contractsCK', 'contractsP', 'contractsCP', 'agreements', 'selectedAgree', 'query'));
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $actions = $this->actionService->dropdown();
            $agreements = $this->agreementService->dropdown();
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('legal.ContractRequestForm.create')->with(['actions' => $actions, 'agreements' => $agreements]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreContractRequest $request)
    {
        $attributes = $request->except(['_token']);
        DB::beginTransaction();
        try {
            $contractRequest = $this->contractRequestService->create($attributes);

            if (!$contractRequest) {
                $request->session()->flash('error', 'error create!');
            } else {
                $request->session()->flash('success',  ' has been create');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return $this->redirectContractByAgreement($contractRequest);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $agreements = $this->agreementService->dropdown();
            $legalContract = $this->contractRequestService->find($id);
            $subtypeContract = $this->subtypeContractService->dropdown($legalContract->agreement_id);

            $paymentType = $this->paymentTypeService->dropdown($legalContract->agreement_id);
            if ($legalContract->legalContractDest) {
                $legalContract->legalContractDest->value_of_contract = explode(",", $legalContract->legalContractDest->value_of_contract);
            }

            $permission = $legalContract->createdBy->department->legalApprove->search(function ($item) {
                return $item->user_id === \auth()->id();
            }, \true) === false ? 'Read' : 'Write';
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        switch ($legalContract->agreement_id) {
            case $agreements[0]->id:
                return \view('legal.ContractRequestForm.WorkServiceContract.view')
                    ->with(\compact('legalContract', 'paymentType', 'permission'));
                break;
            case $agreements[1]->id:
                return \view('legal.ContractRequestForm.PurchaseEquipment.view')
                    ->with(\compact('legalContract', 'paymentType', 'permission'));
                break;
            case $agreements[2]->id:
                return \view('legal.ContractRequestForm.PurchaseEquipmentInstall.view')
                    ->with(\compact('legalContract', 'paymentType', 'permission'));
                break;
            case $agreements[3]->id:
                return \view('legal.ContractRequestForm.Mould.view')
                    ->with(\compact('legalContract', 'paymentType', 'permission'));
                break;
            case $agreements[4]->id:
                return \view('legal.ContractRequestForm.Scrap.view')
                    ->with(\compact('legalContract', 'paymentType', 'permission'));
                break;
            case $agreements[5]->id:
                return \view('legal.ContractRequestForm.VendorServiceContract.view')
                    ->with(\compact('legalContract', 'subtypeContract', 'paymentType', 'permission'));
                break;
            case $agreements[6]->id:
                return \view('legal.ContractRequestForm.LeaseContract.view')
                    ->with(\compact('legalContract', 'subtypeContract', 'paymentType', 'permission'));
                break;
            case $agreements[7]->id:
                return \view('legal.ContractRequestForm.ProjectBasedAgreement.view')
                    ->with(\compact('legalContract', 'paymentType', 'permission'));
            case $agreements[8]->id:
                return \view('legal.ContractRequestForm.MarketingAgreement.view')
                    ->with(\compact('legalContract', 'paymentType', 'permission'));
                break;
            default:
                return \redirect()->back()->with('error', "Error : type not folud.");
                break;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $contract = $this->contractRequestService->find($id);
            $actions = $this->actionService->dropdown();
            $agreements = $this->agreementService->dropdown();
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('legal.ContractRequestForm.edit')->with(['contract' => $contract, 'actions' => $actions, 'agreements' => $agreements]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreContractRequest $request, $id)
    {
        $attributes = $request->except(['_token', '_method']);
        // dd($id);
        DB::beginTransaction();
        try {
            $contractRequest = $this->contractRequestService->find($id);
            $this->contractRequestService->update($attributes, $id);
            if ($contractRequest->company_cer !== $attributes['company_cer']) {
                Storage::delete($contractRequest->company_cer);
            }
            if ($contractRequest->representative_cer !== $attributes['representative_cer']) {
                Storage::delete($contractRequest->representative_cer);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return $this->redirectContractByAgreement($contractRequest);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $contract = $this->contractRequestService->find($id);
            if (!\hash_equals($contract->status, ContractEnum::RQ)) {
                Session::flash('error',  ' Not in a state of deletion.');
                return \redirect()->back();
            }
            if (!\hash_equals((string) $contract->created_by, (string) \auth()->id())) {
                Session::flash('error',  ' Cannot delete another user`s contract.');
                return \redirect()->back();
            }
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }

        DB::beginTransaction();
        try {
            $this->contractRequestService->update(['trash' => true], $id);
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        Session::flash('success',  ' has been delete');
        DB::commit();
        return \redirect()->back();
    }

    /**
     * Approval contract the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approvalContract(StoreApprovalContract $request, $id)
    {
        // Permission
        $attributes = $request->except(['_token', '_method']);
        if (!isset($request->comment)) {
            $attributes['comment'] = null;
        }
        DB::beginTransaction();
        try {
            $contractRequest = $this->contractRequestService->find($id);
            $levelApproval = $this->approvalService->approvalByDepartment($contractRequest->createdBy->department);
            if (\hash_equals($contractRequest->status, ContractEnum::D)) {
                $userApproval = $this->processDraft($contractRequest, $levelApproval);
            } else if (\hash_equals($contractRequest->status, ContractEnum::RQ)) {
                $userApproval = $this->processRequest($contractRequest, $levelApproval);
            } else if (\hash_equals($contractRequest->status, ContractEnum::CK)) {
                $request->validate([
                    'status' => Rule::in(ApprovalEnum::$types),
                    // 'comment' => 'required',
                ]);
                $userApproval = $this->processChecking($attributes, $contractRequest, $levelApproval);
            } else if (\hash_equals($contractRequest->status, ContractEnum::P)) {
                $request->validate([
                    'status' => Rule::in(ApprovalEnum::$types),
                    // 'comment' => 'required',
                ]);
                $userApproval = $this->processProviding($attributes, $contractRequest, $levelApproval);

                $alertReturn = $levelApproval->where('levels', 1)->first()->user;
                Mail::to($alertReturn->email)->send(new ContractApproval($contractRequest, $alertReturn));
            }
            Mail::to($userApproval->email)->send(new ContractApproval($contractRequest, $userApproval));
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        Session::flash('success',  'Send email approval');
        return \redirect()->back();
    }

    private  function processDraft(LegalContract $contract, Collection $levelApproval)
    {
        $approvalDetail = $this->approvalDetailService->create(['user_id' => \auth()->id(), 'contract_id' => $contract->id, 'levels' => 0]);
        $contract->status = ContractEnum::RQ;
        $approvalDetail->save();
        $contract->save();
        return $levelApproval->where('levels', 1)->first()->user;
    }

    private  function processRequest(LegalContract $contract, Collection $levelApproval)
    {
        $approvalDetail = $this->approvalDetailService->create(['user_id' => \auth()->id(), 'contract_id' => $contract->id, 'levels' => 0]);
        $contract->status = ContractEnum::CK;
        $approvalDetail->save();
        $contract->save();
        return $levelApproval->where('levels', 1)->first()->user;
    }

    private function processChecking(array $attributes, LegalContract $contract, Collection $levelApproval)
    {
        $approvalDetail = $this->approvalDetailService->create(['user_id' => \auth()->id(), 'contract_id' => $contract->id, 'levels' => 1]);
        if (\hash_equals($attributes['status'], ApprovalEnum::R)) {
            $approvalDetail->status = ApprovalEnum::R;
            $approvalDetail->comment = $attributes['comment'];
            $contract->status = ContractEnum::RQ;
            $userApproval = $contract->createdBy;

            // ส่งแจ้ง Eddy แจ้งเฉยๆไม่ต้องกดเข้ามาดู
        }
        if (\hash_equals($attributes['status'], ApprovalEnum::A)) {
            $approvalDetail->status = ApprovalEnum::A;
            $approvalDetail->comment = $attributes['comment'];
            $contract->status = ContractEnum::P;
            $userApproval =  $levelApproval->where('levels', 2)->first()->user;
        }
        $approvalDetail->save();
        $contract->save();

        return $userApproval;
    }

    private function processProviding(array $attributes, LegalContract $contract, Collection $levelApproval)
    {
        $approvalDetail = $this->approvalDetailService->create(['user_id' => \auth()->id(), 'contract_id' => $contract->id, 'levels' => 2]);
        if (\hash_equals($attributes['status'], ApprovalEnum::R)) {
            $approvalDetail->status = ApprovalEnum::R;
            $approvalDetail->comment = $attributes['comment'];
            $contract->status = ContractEnum::CK;
            $userApproval = $levelApproval->where('levels', 1)->first()->user;
        }
        if (\hash_equals($attributes['status'], ApprovalEnum::A)) {
            $approvalDetail->status = ApprovalEnum::A;
            $approvalDetail->comment = $attributes['comment'];
            $contract->status = ContractEnum::CP;
            $userApproval = $contract->createdBy;
        }
        $approvalDetail->save();
        $contract->save();
        return $userApproval;
    }


    public function redirectContractByAgreement(LegalContract $contractRequest)
    {
        try {
            $agreements = $this->agreementService->dropdown();
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }

        switch ($contractRequest->agreement_id) {
            case $agreements[0]->id:
                return \redirect()->route('legal.contract-request.workservicecontract.edit', $contractRequest->id);
                break;
            case $agreements[1]->id:
                return \redirect()->route('legal.contract-request.purchaseequipment.edit', $contractRequest->id);
                break;
            case $agreements[2]->id:
                return \redirect()->route('legal.contract-request.purchaseequipmentinstall.edit', $contractRequest->id);
                break;
            case $agreements[3]->id:
                return \redirect()->route('legal.contract-request.mould.edit', $contractRequest->id);
                break;
            case $agreements[4]->id:
                return \redirect()->route('legal.contract-request.scrap.edit', $contractRequest->id);
                break;
            case $agreements[5]->id:
                return \redirect()->route('legal.contract-request.vendorservicecontract.edit', $contractRequest->id);
                break;
            case $agreements[6]->id:
                return \redirect()->route('legal.contract-request.leasecontract.edit', $contractRequest->id);
                break;
            case $agreements[7]->id:
                return \redirect()->route('legal.contract-request.projectbasedagreement.edit', $contractRequest->id);
                break;
            case $agreements[8]->id:
                return \redirect()->route('legal.contract-request.marketingagreement.edit', $contractRequest->id);
                break;
            default:
                return \abort(404);
                break;
        }
    }

    /**
     * loadView by agreement pdf stream.
     * @param  LegalContract  $contractRequest
     * @return \Illuminate\Http\Response
     */
    public function loadViewContractByAgreement(LegalContract $contractRequest)
    {
        try {
            $agreements = $this->agreementService->dropdown();
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }

        switch ($contractRequest->agreement_id) {
            case $agreements[0]->id:
                return PDF::loadView('legal.ContractRequestForm.WorkServiceContract.pdf', ['contract' => $contractRequest]);
                break;
            case $agreements[1]->id:
                return PDF::loadView('legal.ContractRequestForm.PurchaseEquipment.pdf', ['contract' => $contractRequest]);
                break;
            case $agreements[2]->id:
                return PDF::loadView('legal.ContractRequestForm.PurchaseEquipmentInstall.pdf', ['contract' => $contractRequest]);
                break;
            case $agreements[3]->id:
                return PDF::loadView('legal.ContractRequestForm.Mould.pdf', ['contract' => $contractRequest]);
                break;
            case $agreements[4]->id:
                return PDF::loadView('legal.ContractRequestForm.Scrap.pdf', ['contract' => $contractRequest]);
                break;
            case $agreements[5]->id:
                return $this->pdfSubtypeContract($contractRequest);
                break;
            case $agreements[6]->id:
                return PDF::loadView('legal.ContractRequestForm.LeaseContract.pdf', ['contract' => $contractRequest]);
                break;
            case $agreements[7]->id:
                return PDF::loadView('legal.ContractRequestForm.ProjectBasedAgreement.pdf', ['contract' => $contractRequest]);
                break;
            case $agreements[8]->id:
                return PDF::loadView('legal.ContractRequestForm.MarketingAgreement.pdf', ['contract' => $contractRequest]);
                break;
            default:
                return \abort(404);
                break;
        }
    }

    /**
     * loadView by SubtypeContract pdf stream.
     * @param  LegalContract  $contractRequest
     * @return \Illuminate\Http\Response
     */
    public function pdfSubtypeContract(LegalContract $contractRequest)
    {
        switch ($contractRequest->legalContractDest->legalSubtypeContract->slug) {
            case 'bus-contract':
                return PDF::loadView('legal.ContractRequestForm.VendorServiceContract.pdf-bus-contract', ['contract' => $contractRequest]);
                break;
            case 'cleaning-contract':
                // \dd();
                $attribs = $contractRequest->legalContractDest->legalComercialTerm->attributesToArray();
                $total = array_reduce(array_keys($attribs), function ($accumulator, $key) use ($attribs) {
                    if (
                        $key === 'road' || $key === 'building' || $key === 'toilet' || $key === 'canteen'
                        || $key === 'washing' || $key === 'water' || $key === 'mowing' || $key === 'general'
                    ) {
                        return $accumulator += $attribs[$key];
                    }
                    return $accumulator;
                }, 0);
                return PDF::loadView('legal.ContractRequestForm.VendorServiceContract.pdf-cleaning-contract', ['contract' => $contractRequest, 'total' => $total]);
                break;
            case 'cook-contract':
                return PDF::loadView('legal.ContractRequestForm.VendorServiceContract.pdf-cook-contract', ['contract' => $contractRequest]);
                break;
            case 'doctor-contract':
                return PDF::loadView('legal.ContractRequestForm.VendorServiceContract.pdf-doctor-contract', ['contract' => $contractRequest]);
                break;
            case 'nurse-contract':
                return PDF::loadView('legal.ContractRequestForm.VendorServiceContract.pdf-nurse-contract', ['contract' => $contractRequest]);
                break;
            case 'security-contract':
                return PDF::loadView('legal.ContractRequestForm.VendorServiceContract.pdf-security-contract', ['contract' => $contractRequest]);
                break;
            case 'subcontractor-contract':
                return PDF::loadView('legal.ContractRequestForm.VendorServiceContract.pdf-subcontractor-contract', ['contract' => $contractRequest]);
                break;
            case 'transportation-contract':
                return PDF::loadView('legal.ContractRequestForm.VendorServiceContract.pdf-transportation-contract', ['contract' => $contractRequest]);
                break;
            case 'it-contract':
                return PDF::loadView('legal.ContractRequestForm.VendorServiceContract.pdf-it-contract', ['contract' => $contractRequest]);
                break;
            default:
                return \abort(404);
                break;
        }
    }


    public function uploadfilecontract(Request $request)
    {
        // max 20 MB.
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:pdf|max:30500',
        ]);
        if ($validator->fails()) {
            return \response()->json($validator->errors(), 500);
        }
        $date =  new Carbon();
        $segments = explode('/', \substr(url()->previous(), strlen($request->root())));
        try {
            $path = Storage::disk('public')->put(
                $segments[1] . '/' . $segments[2] . '/' . Auth::user()->username . '/' . $date->isoFormat('OYMMDD') ,
                new File($request->file('file'))
            );
            return \response()->json(['path' => $path]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    /**
     * Create pdf stream.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generatePDF($id)
    {
        try {
            $contract = $this->contractRequestService->find($id);
            if ($contract->legalContractDest->value_of_contract) {
                $contract->legalContractDest->value_of_contract = explode(",", $contract->legalContractDest->value_of_contract);
            }
            $pdf = $this->loadViewContractByAgreement($contract);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }

        return $pdf->stream();
    }
}
