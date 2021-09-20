<?php

namespace App\Http\Controllers\Legal;

use App\Enum\ContractEnum;
use App\Enum\UserEnum;
use App\Http\Controllers\Controller;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\Legal\Interfaces\AgreementServiceInterface;
use App\Services\Legal\Interfaces\ContractRequestServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{
    protected $contractRequestService;
    protected $userService;
    protected $agreementService;
    public function __construct(
        ContractRequestServiceInterface $contractRequestServiceInterface,
        UserServiceInterface $userServiceInterface,
        AgreementServiceInterface $agreementServiceInterface
    ) {
        $this->contractRequestService = $contractRequestServiceInterface;
        $this->userService = $userServiceInterface;
        $this->agreementService = $agreementServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $requestCal = 0;
        $checking = 0;
        $providing = 0;
        $complete = 0;
        $selectedCreated = collect($request->created_by);
        $selectedAgree = collect($request->agreement);
        $status = [ContractEnum::R, ContractEnum::CK, ContractEnum::P, ContractEnum::CP];
        // $query = $request->all();
        try {
            $requestor = $this->contractRequestService->requestorInSystem();
            if (Gate::allows(UserEnum::ADMINLEGAL) || Gate::allows(UserEnum::SUPERADMIN)) {
                $contracts = $this->contractRequestService->filterForAdmin($request);
            } else {
                $contracts = null;
            }
            $agreements = $this->agreementService->dropdown();
            $allPromised = $this->contractRequestService->totalpromised();
            $ownPromise = $this->contractRequestService->ownpromised(\auth()->user());
            $requestSum = $this->contractRequestService->countStatus(ContractEnum::R);
            $checking = $this->contractRequestService->countStatus(ContractEnum::CK);
            $providing = $this->contractRequestService->countStatus(ContractEnum::P);
            $complete = $this->contractRequestService->countStatus(ContractEnum::CP);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }

        // if ($allPromised > 0) {
        //     $requestCal = round(($requestSum / $allPromised) * 100, 1);
        //     $checking = round(($checking / $allPromised) * 100, 1);
        //     $providing = round(($providing / $allPromised) * 100, 1);
        //     $complete = round(($complete / $allPromised) * 100, 1);
        // }

        return \view('legal.home', \compact(
            'allPromised',
            'ownPromise',
            'requestSum',
            'checking',
            'providing',
            'complete',
            'contracts',
            'status',
            'selectedCreated',
            'selectedAgree',
            'agreements',
            'requestor'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
