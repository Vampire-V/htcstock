<?php

namespace App\Models\KPI;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class EvaluatesHistory extends Model
{
    protected $table = 'kpi_evaluates_history';
    protected $guarded = [];
    protected $dates = ['updated_at', 'created_at'];


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
}
