<?php

namespace App\Models\KPI;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class EvaluatesHistory extends Model
{
    protected $table = 'kpi_evaluates_history';
    protected $guarded = [];


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
}
