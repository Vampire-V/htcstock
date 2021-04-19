<?php

namespace App\Http\Controllers\KPI\Rule;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\KPI\StoreRulePost;
use App\Http\Requests\KPI\StoreRulePut;
use App\Http\Resources\KPI\RuleResource;
use App\Imports\KPI\RulesImport;
use App\Models\TemporaryFile;
use App\Services\KPI\Interfaces\RuleCategoryServiceInterface;
use App\Services\KPI\Interfaces\RuleServiceInterface;
use App\Services\KPI\Interfaces\RuleTypeServiceInterface;
use App\Services\KPI\Interfaces\TargetUnitServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use stdClass;

class RuleController extends Controller
{

    protected $ruleCategoryService, $targetUnitService, $ruleService, $ruleTypeService, $excel_errors = [], $rule_attrs = [];
    public function __construct(
        RuleCategoryServiceInterface $ruleCategoryServiceInterface,
        TargetUnitServiceInterface $targetUnitServiceInterface,
        RuleServiceInterface $ruleServiceInterface,
        RuleTypeServiceInterface $ruleTypeServiceInterface
    ) {
        $this->ruleCategoryService = $ruleCategoryServiceInterface;
        $this->targetUnitService = $targetUnitServiceInterface;
        $this->ruleService = $ruleServiceInterface;
        $this->ruleTypeService = $ruleTypeServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->all();
        $searchRuleName = $request->ruleName;
        $selectedCategory = collect($request->category_id);
        $template = Storage::url('kpi/template/template-rule.xlsx');
        try {
            $category = $this->ruleCategoryService->dropdown();
            $rules = $this->ruleService->filter($request);
        } catch (\Throwable $th) {
            throw $th;
        }
        return \view('kpi.RuleList.index', \compact('rules', 'category', 'query', 'searchRuleName', 'selectedCategory', 'template'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = $this->ruleCategoryService->dropdown();
        $unit = $this->targetUnitService->dropdown();
        $calcuTypes = \collect([KPIEnum::positive, KPIEnum::negative, KPIEnum::zero_oriented_kpi]);
        return \view('kpi.RuleList.create', \compact('category', 'unit', 'calcuTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRulePost $request)
    {
        DB::beginTransaction();
        $fromValue = $request->except(['_token']);
        try {
            if (!$this->ruleService->create($fromValue)) {
                $request->session()->flash('error', ' has been create fail');
                return \back();
            }
            $request->session()->flash('success', ' has been create success');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
        return \back();
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
            $rule = $this->ruleService->find($id);
        } catch (\Throwable $th) {
            throw $th;
        }
        return new RuleResource($rule);
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
            $rule = $this->ruleService->find($id);
            $category = $this->ruleCategoryService->dropdown();
            $unit = $this->targetUnitService->dropdown();
            $calcuTypes = \collect([KPIEnum::positive, KPIEnum::negative, KPIEnum::zero_oriented_kpi]);
        } catch (\Throwable $th) {
            throw $th;
        }
        return \view('kpi.RuleList.edit', \compact('rule', 'category', 'unit', 'calcuTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRulePut $request, $id)
    {
        DB::beginTransaction();
        $validated = $request->validated();
        try {
            // \dd($validated);
            $rule = $this->ruleService->update($validated, $id);
            // \dd($rule);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
        return \back();
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

    /**
     * Get the Data for dropdown.
     *
     * @return \Illuminate\Http\Response
     */
    public function dropdown($group)
    {
        try {
            $rule = $this->ruleService->dropdown($group);
        } catch (\Throwable $th) {
            throw $th;
        }

        return RuleResource::collection($rule);
    }

    public function upload(Request $request)
    {
        $status = \false;

        $temporaryFile = TemporaryFile::where('folder', $request->file)->first();
        if (!$temporaryFile) {
            return \response()->json(["message" => "file not found!"],422);
        }

        $file = storage_path('app\\kpi\\' . $temporaryFile->folder . '\\' . $temporaryFile->filename);
        $read_data = Excel::toCollection(new RulesImport(), $file, null, \Maatwebsite\Excel\Excel::XLSX);
        $datas = $read_data[0]->filter(fn ($value) => $value[1] !== null);
        $category = $this->ruleCategoryService->dropdown();
        $rule_type = $this->ruleTypeService->dropdown();

        $datas->each(function ($value, $key) use ($category, $rule_type) {

            $c = $category->filter(fn ($obj) => $obj->name === $value[2]);
            $f = $rule_type->filter(fn ($obj) => $obj->name === $value[5]);
            $checkName = $this->ruleService->isName($value[1]);

            if ($checkName) {
                $error = new stdClass;
                $error->row = $key + 6;
                $error->col = 'B';
                $error->message = 'มีอยู่แล้ว';
                array_push($this->excel_errors, $error);
            }
            if ($c->count() < 1) {
                $error = new stdClass;
                $error->row = $key + 6;
                $error->col = 'C';
                $error->message = 'ไม่มี';
                array_push($this->excel_errors, $error);
            }
            if (is_null($value[4])) {
                $error = new stdClass;
                $error->row = $key + 6;
                $error->col = 'E';
                $error->message = 'ไม่มี';
                array_push($this->excel_errors, $error);
            }
            if ($f->count() < 1) {
                $error = new stdClass;
                $error->row = $key + 6;
                $error->col = 'F';
                $error->message = 'ไม่มี';
                array_push($this->excel_errors, $error);
            }
            
            if ($c->count() > 0 && !is_null($value[4]) && $f->count() > 0 && !$checkName) {
                \array_push(
                    $this->rule_attrs,
                    [
                        'name' => $value[1],
                        'category_id' => $c->first()->id,
                        'description' => $value[3],
                        'calculate_type' => $value[4],
                        'kpi_rule_types_id' => $f->first()->id,
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),
                    ]
                );
            }
        });
        DB::beginTransaction();
        try {
            
            if ($this->rule_attrs) {
                $status = $this->ruleService->insert($this->rule_attrs);
            }
            
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
        return \response()->json(['errors' => $this->excel_errors,'status' => $status]);
    }
}
