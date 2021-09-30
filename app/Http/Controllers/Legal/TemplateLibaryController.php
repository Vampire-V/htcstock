<?php

namespace App\Http\Controllers\Legal;

use App\Http\Controllers\Controller;
use App\Models\Legal\TemplateLibary;
use App\Services\Legal\Interfaces\AgreementServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class TemplateLibaryController extends Controller
{
    protected $agreementService;
    public function __construct(AgreementServiceInterface $agreementService)
    {
        $this->agreementService = $agreementService;
    }
    public function index()
    {
        $agreements = $this->agreementService->with_template_libary();
        return \view('Legal.TemplateLibary.index', \compact('agreements'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->only('agreement_id', 'file_template'), [
            'agreement_id' => 'required|exists:App\Models\Legal\LegalAgreement,id',
            'file_template' => 'required|file|max:25|mimes:pdf',
        ]);

        if ($validator->fails()) {
            return \back()->withErrors($validator);
        }
        DB::beginTransaction();
        try {
            $folder = 'legal/template';
            $uri = $request->file('file_template')->store($folder, 'public');
            $template = new TemplateLibary();
            
            $template->name = $request->file('file_template')->getClientOriginalName();
            $template->name_encryp = \substr($uri, \strlen($folder) + 1);
            $template->uri = $uri;
            $template->url = \config('app.url');
            $template->agreement_id = $request->agreement_id;
            $template->version = $this->control_version($template);
            $template->save();
            DB::commit();
            return \back();
        } catch (\Exception $e) {
            DB::rollBack();
            return \back()->withErrors($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $template = TemplateLibary::find($id);
            return (new Response(Storage::disk('public')->get($template->uri), HttpFoundationResponse::HTTP_OK))->header('Content-Type', 'application/pdf');
        } catch (\Exception $e) {
            return \back()->withErrors($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $template = TemplateLibary::find($id);
            if (!Storage::disk('public')->exists($template->uri)) {
                return \back()->withErrors(['msg' => 'file not fount.']);
            }
            
            Storage::disk('public')->delete($template->uri);
            $template->delete();
            return \back();
        } catch (\Exception $e) {
            return \back()->withErrors($e->getMessage());
        }
    }

    private function control_version(TemplateLibary $model)
    {
        $version = "1.0.0";
        $last = TemplateLibary::where('agreement_id', $model->agreement_id)->orderBy('created_at', 'DESC')->first();
        if (!$last) {
            return $version;
        }
        $arr_version = explode(".", $last->version);
        if (\intval($arr_version[2]) < 9) {
            $arr_version[2] = \intval($arr_version[2]) + 1;
        }else{
            $arr_version[2] = 0;
            if (\intval($arr_version[1]) < 9){
                $arr_version[1] = \intval($arr_version[1]) + 1;
            }else{
                $arr_version[1] = 0;
                $arr_version[0] = \intval($arr_version[0]) + 1;
            }
        }
        $version = \implode(".",$arr_version);
        return $version;
    }
}
