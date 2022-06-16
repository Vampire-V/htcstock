<?php

namespace App\Models\KPI;

use App\Http\Filters\KPI\EvaluationFilter;
use App\Relations\EvaluateTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Evaluate extends Model
{
    use EvaluateTrait;
    protected $table = 'kpi_evaluates';
    protected $dates = ['updated_at', 'created_at'];
    protected $casts = [
        'user_id' => 'int',
        'period_id' => 'int',
        'head_id' => 'int',
        'template_id' => 'int',
        'current_level' => 'int',
        'next_level' => 'int',
        'total_weight_kpi' => 'float',
        'total_weight_key_task' => 'float',
        'total_weight_omg' => 'float',
        'cal_kpi' => 'float',
        'cal_key_task' => 'float',
        'cal_omg' => 'float',
        'kpi_reduce' => 'float',
        'key_task_reduce' => 'float',
        'omg_reduce' => 'float',
        'kpi_reduce_hod' => 'float',
        'key_task_reduce_hod' => 'float',
        'omg_reduce_hod' => 'float'
    ];

    protected $guarded = [];
    // protected $with = ['nextlevel','currentlevel'];

    // service เรียกใช้ Filter
    public function scopeFilter(Builder $builder, $request)
    {
        return (new EvaluationFilter($request))->filter($builder);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            // $model->created_by = \auth()->id();
            $history = new EvaluatesHistory();
            $history->evaluate_id = $model->id;
            $history->status = $model->status;
            $history->comment = $model->comment;
            $history->current_level = $model->current_level;
            $history->next_level = $model->next_level;
            $history->created_by = \auth()->id();
            $history->ip = "(เปลี่ยนทุกวันใช้อ้างอิงไม่ได้)";
            // $history->ip = \request()->ip();
            // $history->device = substr(exec('getmac'),0,17);
            $history->save();
        });

        static::updated(function ($model) {
            // $model->updated_by = \auth()->id();
            // $user_app = $model->userApprove->where('level',$model->current_level)->first();
            $history = new EvaluatesHistory();
            $history->evaluate_id = $model->id;
            $history->status = $model->status;
            $history->comment = $model->comment;
            $history->current_level = $model->current_level;
            $history->next_level = $model->next_level;
            $history->created_by =  \auth()->id();
            $history->ip = "(เปลี่ยนทุกวันใช้อ้างอิงไม่ได้)";
            // $history->device = substr(exec('getmac'),0,17);
            $history->save();
        });
    }
}
