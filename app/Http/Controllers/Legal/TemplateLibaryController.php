<?php

namespace App\Http\Controllers\Legal;

use App\Http\Controllers\Controller;
use App\Services\Legal\Interfaces\AgreementServiceInterface;
use Illuminate\Http\Request;

class TemplateLibaryController extends Controller
{
    protected $agreementService;
    public function __construct(AgreementServiceInterface $agreementService) {
        $this->agreementService = $agreementService;
    }
    public function index()
    {
        $agreements = $this->agreementService->dropdown();
        return \view('Legal.TemplateLibary.index',\compact('agreements'));
    }
}
